<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-rupee';

    protected static ?string $navigationGroup = 'Donations';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Donation Details')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('₹'),
                        Forms\Components\Select::make('currency')
                            ->options([
                                'INR' => 'INR (₹)',
                                'USD' => 'USD ($)',
                                'GBP' => 'GBP (£)',
                            ])
                            ->default('INR'),
                        Forms\Components\Select::make('payment_gateway')
                            ->options([
                                'stripe' => 'Stripe',
                                'razorpay' => 'Razorpay',
                                'manual' => 'Manual/Offline',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('transaction_id')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->required()
                            ->default('completed'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Donor Information')
                    ->schema([
                        Forms\Components\TextInput::make('donor_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('donor_email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('donor_phone')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Toggle::make('is_anonymous')
                            ->label('Anonymous Donation'),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Registered User'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Campaign & Recurring')
                    ->schema([
                        Forms\Components\Select::make('campaign_id')
                            ->relationship('campaign', 'title')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Toggle::make('is_recurring')
                            ->label('Recurring Donation'),
                        Forms\Components\Select::make('recurring_interval')
                            ->options([
                                'monthly' => 'Monthly',
                                'quarterly' => 'Quarterly',
                                'yearly' => 'Yearly',
                            ])
                            ->visible(fn(Forms\Get $get) => $get('is_recurring')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Message')
                    ->schema([
                        Forms\Components\Textarea::make('donor_message')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('receipt_number')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('donor_display_name')
                    ->label('Donor')
                    ->searchable(['donor_name', 'donor_email']),
                Tables\Columns\TextColumn::make('formatted_amount')
                    ->label('Amount')
                    ->sortable('amount'),
                Tables\Columns\TextColumn::make('payment_gateway')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'stripe' => 'info',
                        'razorpay' => 'primary',
                        'manual' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_recurring')
                    ->boolean()
                    ->label('Recurring'),
                Tables\Columns\TextColumn::make('campaign.title')
                    ->label('Campaign')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('payment_gateway')
                    ->options([
                        'stripe' => 'Stripe',
                        'razorpay' => 'Razorpay',
                        'manual' => 'Manual',
                    ]),
                Tables\Filters\TernaryFilter::make('is_recurring')
                    ->label('Recurring'),
                Tables\Filters\SelectFilter::make('campaign')
                    ->relationship('campaign', 'title'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download_receipt')
                    ->icon('heroicon-o-document-arrow-down')
                    ->label('Receipt')
                    ->color('success')
                    ->url(fn(Donation $record): string => route('donations.receipt', $record))
                    ->openUrlInNewTab()
                    ->visible(fn(Donation $record): bool => $record->status === 'completed'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
