<?php

namespace App\Filament\Resources\SocialPostResource\Pages;

use App\Filament\Resources\SocialPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSocialPost extends CreateRecord
{
    protected static string $resource = SocialPostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        // Set status based on scheduled_at
        if (!empty($data['scheduled_at'])) {
            $data['status'] = 'scheduled';
        } else {
            $data['status'] = 'draft';
        }

        return $data;
    }
}
