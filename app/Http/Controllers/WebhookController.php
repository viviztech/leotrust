<?php

namespace App\Http\Controllers;

use App\Services\DonationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        protected DonationService $donationService
    ) {
    }

    /**
     * Handle Stripe webhook.
     */
    public function handleStripe(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            Log::error('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        Log::info('Stripe webhook received', ['type' => $event->type]);

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleStripeCheckoutCompleted($event->data->object);
                break;
            case 'payment_intent.succeeded':
                $this->handleStripePaymentSucceeded($event->data->object);
                break;
            case 'invoice.payment_succeeded':
                $this->handleStripeInvoicePaid($event->data->object);
                break;
            case 'customer.subscription.created':
            case 'customer.subscription.updated':
                $this->handleStripeSubscriptionUpdated($event->data->object);
                break;
            case 'customer.subscription.deleted':
                $this->handleStripeSubscriptionCancelled($event->data->object);
                break;
        }

        return response('OK', 200);
    }

    /**
     * Handle Razorpay webhook.
     */
    public function handleRazorpay(Request $request): Response
    {
        $payload = $request->all();
        $signature = $request->header('X-Razorpay-Signature');
        $secret = config('services.razorpay.webhook_secret');

        // Verify signature
        $expectedSignature = hash_hmac('sha256', $request->getContent(), $secret);
        if (!hash_equals($expectedSignature, $signature ?? '')) {
            Log::error('Razorpay webhook signature verification failed');
            return response('Invalid signature', 400);
        }

        $event = $payload['event'] ?? null;

        Log::info('Razorpay webhook received', ['event' => $event]);

        switch ($event) {
            case 'payment.captured':
                $this->handleRazorpayPaymentCaptured($payload['payload']['payment']['entity']);
                break;
            case 'subscription.charged':
                $this->handleRazorpaySubscriptionCharged($payload['payload']['subscription']['entity']);
                break;
            case 'subscription.cancelled':
                $this->handleRazorpaySubscriptionCancelled($payload['payload']['subscription']['entity']);
                break;
        }

        return response('OK', 200);
    }

    /**
     * Handle Stripe checkout completed.
     */
    protected function handleStripeCheckoutCompleted($session): void
    {
        $metadata = $session->metadata ?? new \stdClass();

        $this->donationService->processDonation([
            'amount' => $session->amount_total / 100,
            'currency' => strtoupper($session->currency),
            'donor_email' => $session->customer_email ?? $session->customer_details->email ?? 'unknown@example.com',
            'donor_name' => $session->customer_details->name ?? null,
            'transaction_id' => $session->payment_intent,
            'payment_gateway' => 'stripe',
            'status' => 'completed',
            'is_recurring' => $session->mode === 'subscription',
            'subscription_id' => $session->subscription ?? null,
            'campaign_id' => $metadata->campaign_id ?? null,
            'donor_message' => $metadata->message ?? null,
            'is_anonymous' => $metadata->anonymous === 'true',
            'metadata' => (array) $metadata,
        ]);
    }

    /**
     * Handle Stripe payment succeeded.
     */
    protected function handleStripePaymentSucceeded($paymentIntent): void
    {
        // Check if we already processed this via checkout.session.completed
        if (\App\Models\Donation::where('transaction_id', $paymentIntent->id)->exists()) {
            return;
        }

        $metadata = $paymentIntent->metadata ?? new \stdClass();

        $this->donationService->processDonation([
            'amount' => $paymentIntent->amount / 100,
            'currency' => strtoupper($paymentIntent->currency),
            'donor_email' => $metadata->email ?? 'unknown@example.com',
            'donor_name' => $metadata->name ?? null,
            'transaction_id' => $paymentIntent->id,
            'payment_gateway' => 'stripe',
            'status' => 'completed',
            'campaign_id' => $metadata->campaign_id ?? null,
            'metadata' => (array) $metadata,
        ]);
    }

    /**
     * Handle Stripe invoice paid (for recurring).
     */
    protected function handleStripeInvoicePaid($invoice): void
    {
        if (!$invoice->subscription) {
            return;
        }

        $this->donationService->processDonation([
            'amount' => $invoice->amount_paid / 100,
            'currency' => strtoupper($invoice->currency),
            'donor_email' => $invoice->customer_email ?? 'unknown@example.com',
            'donor_name' => $invoice->customer_name ?? null,
            'transaction_id' => $invoice->payment_intent,
            'payment_gateway' => 'stripe',
            'status' => 'completed',
            'is_recurring' => true,
            'subscription_id' => $invoice->subscription,
            'metadata' => ['invoice_id' => $invoice->id],
        ]);
    }

    /**
     * Handle Stripe subscription updated.
     */
    protected function handleStripeSubscriptionUpdated($subscription): void
    {
        Log::info('Stripe subscription updated', ['subscription_id' => $subscription->id]);
    }

    /**
     * Handle Stripe subscription cancelled.
     */
    protected function handleStripeSubscriptionCancelled($subscription): void
    {
        Log::info('Stripe subscription cancelled', ['subscription_id' => $subscription->id]);
    }

    /**
     * Handle Razorpay payment captured.
     */
    protected function handleRazorpayPaymentCaptured(array $payment): void
    {
        $notes = $payment['notes'] ?? [];

        $this->donationService->processDonation([
            'amount' => $payment['amount'] / 100,
            'currency' => strtoupper($payment['currency'] ?? 'INR'),
            'donor_email' => $payment['email'] ?? 'unknown@example.com',
            'donor_name' => $notes['donor_name'] ?? null,
            'donor_phone' => $payment['contact'] ?? null,
            'transaction_id' => $payment['id'],
            'payment_gateway' => 'razorpay',
            'status' => 'completed',
            'campaign_id' => $notes['campaign_id'] ?? null,
            'donor_message' => $notes['message'] ?? null,
            'is_anonymous' => ($notes['anonymous'] ?? false) === 'true',
            'metadata' => $notes,
        ]);
    }

    /**
     * Handle Razorpay subscription charged.
     */
    protected function handleRazorpaySubscriptionCharged(array $subscription): void
    {
        $notes = $subscription['notes'] ?? [];

        $this->donationService->processDonation([
            'amount' => $subscription['current_start'] ?? 0,
            'currency' => 'INR',
            'donor_email' => $notes['email'] ?? 'unknown@example.com',
            'transaction_id' => $subscription['id'] . '-' . time(),
            'payment_gateway' => 'razorpay',
            'status' => 'completed',
            'is_recurring' => true,
            'subscription_id' => $subscription['id'],
            'metadata' => $notes,
        ]);
    }

    /**
     * Handle Razorpay subscription cancelled.
     */
    protected function handleRazorpaySubscriptionCancelled(array $subscription): void
    {
        Log::info('Razorpay subscription cancelled', ['subscription_id' => $subscription['id']]);
    }
}
