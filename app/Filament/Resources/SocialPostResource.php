<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialPostResource\Pages;
use App\Jobs\PublishSocialPost;
use App\Models\SocialPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SocialPostResource extends Resource
{
    protected static ?string $model = SocialPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Social Media';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Posts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Post Content')
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->rows(5)
                            ->maxLength(63206)
                            ->helperText(fn(Forms\Get $get) => 'Characters: ' . mb_strlen($get('content') ?? '')),
                        Forms\Components\FileUpload::make('image_path')
                            ->image()
                            ->directory('social-posts')
                            ->maxSize(5120)
                            ->label('Main Image'),
                        Forms\Components\FileUpload::make('additional_images')
                            ->image()
                            ->multiple()
                            ->directory('social-posts')
                            ->maxFiles(4)
                            ->maxSize(5120),
                    ]),

                Forms\Components\Section::make('Publishing')
                    ->schema([
                        Forms\Components\Select::make('socialAccounts')
                            ->relationship('socialAccounts', 'account_name')
                            ->multiple()
                            ->preload()
                            ->required()
                            ->label('Publish To'),
                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label('Schedule For')
                            ->minDate(now())
                            ->helperText('Leave empty to save as draft'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'scheduled' => 'Scheduled',
                            ])
                            ->default('draft')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->circular(),
                Tables\Columns\TextColumn::make('content_preview')
                    ->label('Content')
                    ->limit(50)
                    ->searchable('content'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'scheduled' => 'info',
                        'publishing' => 'warning',
                        'published' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('socialAccounts.provider_name')
                    ->label('Platforms')
                    ->badge(),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'publishing' => 'Publishing',
                        'published' => 'Published',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('publish_now')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn(SocialPost $record): bool => in_array($record->status, ['draft', 'scheduled']))
                    ->requiresConfirmation()
                    ->action(function (SocialPost $record): void {
                        $record->update(['status' => 'publishing']);
                        PublishSocialPost::dispatch($record);
                    }),
                Tables\Actions\Action::make('retry')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn(SocialPost $record): bool => $record->status === 'failed')
                    ->action(function (SocialPost $record): void {
                        $record->update([
                            'status' => 'publishing',
                            'error_message' => null,
                            'retry_count' => $record->retry_count + 1,
                        ]);
                        PublishSocialPost::dispatch($record);
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(fn(SocialPost $record): bool => in_array($record->status, ['draft', 'scheduled'])),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(SocialPost $record): bool => in_array($record->status, ['draft', 'scheduled', 'failed'])),
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
            'index' => Pages\ListSocialPosts::route('/'),
            'create' => Pages\CreateSocialPost::route('/create'),
            'edit' => Pages\EditSocialPost::route('/{record}/edit'),
        ];
    }
}
