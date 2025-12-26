<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DonationService
{
    /**
     * Process a new donation from a webhook.
     */
    public function processDonation(array $data): Donation
    {
        $donation = Donation::create([
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'INR',
            'donor_email' => $data['donor_email'],
            'donor_name' => $data['donor_name'] ?? null,
            'donor_phone' => $data['donor_phone'] ?? null,
            'transaction_id' => $data['transaction_id'],
            'payment_gateway' => $data['payment_gateway'],
            'status' => $data['status'] ?? 'completed',
            'is_recurring' => $data['is_recurring'] ?? false,
            'recurring_interval' => $data['recurring_interval'] ?? null,
            'subscription_id' => $data['subscription_id'] ?? null,
            'user_id' => $this->findUserByEmail($data['donor_email']),
            'campaign_id' => $data['campaign_id'] ?? null,
            'donor_message' => $data['donor_message'] ?? null,
            'is_anonymous' => $data['is_anonymous'] ?? false,
            'metadata' => $data['metadata'] ?? null,
        ]);

        // Update campaign current_amount if linked to a campaign
        if ($donation->campaign_id && $donation->status === 'completed') {
            $this->updateCampaignAmount($donation->campaign_id);
        }

        return $donation;
    }

    /**
     * Generate a PDF receipt for a donation.
     */
    public function generateReceipt(Donation $donation): string
    {
        $pdf = Pdf::loadView('receipts.donation', [
            'donation' => $donation,
            'foundation' => [
                'name' => config('leofoundation.name'),
                'tagline' => config('leofoundation.tagline'),
            ],
        ]);

        $filename = 'receipts/' . $donation->receipt_number . '.pdf';
        Storage::put('public/' . $filename, $pdf->output());

        return Storage::url($filename);
    }

    /**
     * Mark receipt as sent.
     */
    public function markReceiptSent(Donation $donation): void
    {
        $donation->update([
            'receipt_sent' => true,
            'receipt_sent_at' => now(),
        ]);
    }

    /**
     * Find user by email.
     */
    protected function findUserByEmail(string $email): ?int
    {
        $user = User::where('email', $email)->first();
        return $user?->id;
    }

    /**
     * Update campaign current amount.
     */
    protected function updateCampaignAmount(int $campaignId): void
    {
        $total = Donation::where('campaign_id', $campaignId)
            ->completed()
            ->sum('amount');

        Campaign::where('id', $campaignId)->update([
            'current_amount' => $total,
        ]);
    }

    /**
     * Get donor statistics for a user.
     */
    public function getDonorStats(User $user): array
    {
        $donations = $user->donations()->completed();

        return [
            'total_amount' => $donations->sum('amount'),
            'donation_count' => $donations->count(),
            'first_donation' => $donations->oldest()->first()?->created_at,
            'last_donation' => $donations->latest()->first()?->created_at,
            'recurring_active' => $donations->recurring()->exists(),
            'campaigns_supported' => $donations->distinct('campaign_id')->count('campaign_id'),
        ];
    }
}
