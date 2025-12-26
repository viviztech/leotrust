<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialAccountResource\Pages;
use App\Models\SocialAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SocialAccountResource extends Resource
{
    protected static ?string $model = SocialAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Social Media';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Connected Accounts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Account Information')
                    ->schema([
                        Forms\Components\Select::make('provider_name')
                            ->options([
                                'facebook' => 'Facebook',
                                'twitter' => 'Twitter/X',
                                'linkedin' => 'LinkedIn',
                            ])
                            ->required()
                            ->disabled(),
                        Forms\Components\TextInput::make('account_name')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('account_username')
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Token Status')
                    ->schema([
                        Forms\Components\DateTimePicker::make('token_expires_at')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('last_used_at')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('account_avatar')
                    ->circular()
                    ->label(''),
                Tables\Columns\TextColumn::make('provider_name')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'facebook' => 'info',
                        'twitter' => 'gray',
                        'linkedin' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('account_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_username')
                    ->searchable()
                    ->prefix('@'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\IconColumn::make('is_token_expired')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->label('Token Valid'),
                Tables\Columns\TextColumn::make('last_used_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Connected By'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('provider_name')
                    ->options([
                        'facebook' => 'Facebook',
                        'twitter' => 'Twitter/X',
                        'linkedin' => 'LinkedIn',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\Action::make('refresh_token')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn(SocialAccount $record): bool => $record->is_token_expired)
                    ->url(fn(SocialAccount $record): string => route('social.auth.redirect', $record->provider_name)),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Disconnect'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Disconnect Selected'),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('connect_facebook')
                    ->label('Connect Facebook')
                    ->icon('heroicon-o-plus')
                    ->color('info')
                    ->url(route('social.auth.redirect', 'facebook')),
                Tables\Actions\Action::make('connect_twitter')
                    ->label('Connect Twitter/X')
                    ->icon('heroicon-o-plus')
                    ->color('gray')
                    ->url(route('social.auth.redirect', 'twitter')),
                Tables\Actions\Action::make('connect_linkedin')
                    ->label('Connect LinkedIn')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->url(route('social.auth.redirect', 'linkedin')),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocialAccounts::route('/'),
            'edit' => Pages\EditSocialAccount::route('/{record}/edit'),
        ];
    }
}
