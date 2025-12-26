<?php

namespace App\Filament\Resources\DonationResource\Pages;

use App\Filament\Resources\DonationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateDonation extends CreateRecord
{
    protected static string $resource = DonationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['transaction_id'])) {
            $data['transaction_id'] = 'MANUAL-' . strtoupper(Str::random(12));
        }
        if (empty($data['payment_gateway'])) {
            $data['payment_gateway'] = 'manual';
        }
        return $data;
    }
}
