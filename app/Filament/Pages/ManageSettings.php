<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\SettingsService;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.manage-settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(SettingsService::class)->getAll();
        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->schema([
                                TextInput::make('site_name')
                                    ->label('Site Name')
                                    ->required(),
                                TextInput::make('site_tagline')
                                    ->label('Tagline')
                                    ->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('Contact')
                            ->schema([
                                TextInput::make('contact_phone')
                                    ->label('Phone Number')
                                    ->tel(),
                                TextInput::make('contact_email')
                                    ->label('Email Address')
                                    ->email(),
                                Textarea::make('contact_address')
                                    ->label('Physical Address')
                                    ->rows(3),
                                TextInput::make('contact_map_link')
                                    ->label('Google Maps Embed Link')
                                    ->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('Social Media')
                            ->schema([
                                TextInput::make('social_facebook')
                                    ->label('Facebook URL')
                                    ->url(),
                                TextInput::make('social_twitter')
                                    ->label('Twitter URL')
                                    ->url(),
                                TextInput::make('social_linkedin')
                                    ->label('LinkedIn URL')
                                    ->url(),
                                TextInput::make('social_instagram')
                                    ->label('Instagram URL')
                                    ->url(),
                            ]),
                        Tabs\Tab::make('SEO')
                            ->schema([
                                TextInput::make('seo_default_title')
                                    ->label('Default Meta Title'),
                                Textarea::make('seo_default_description')
                                    ->label('Default Meta Description')
                                    ->rows(3),
                            ]),
                        Tabs\Tab::make('Analytics')
                            ->schema([
                                TextInput::make('analytics_ga_id')
                                    ->label('Google Analytics Measurement ID')
                                    ->placeholder('G-XXXXXXXXXX')
                                    ->helperText('Enter your GA4 Measurement ID.'),
                                TextInput::make('analytics_gtm_id')
                                    ->label('Google Tag Manager ID')
                                    ->placeholder('GTM-XXXXXXX')
                                    ->helperText('Enter your GTM Container ID.'),
                                Textarea::make('analytics_custom_head')
                                    ->label('Custom Header Scripts')
                                    ->rows(5)
                                    ->helperText('Scripts to be added to the <head> section (e.g., Pixel).'),
                                Textarea::make('analytics_custom_body')
                                    ->label('Custom Body Scripts')
                                    ->rows(5)
                                    ->helperText('Scripts to be added to the start of the <body> section.'),
                            ]),
                    ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $service = app(SettingsService::class);

        foreach ($data as $key => $value) {
            $service->set($key, $value);
        }

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
