<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
use App\Models\Inventory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Item Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),
                        Forms\Components\Select::make('category')
                            ->options([
                                'food' => 'Food & Groceries',
                                'medicine' => 'Medicine & Healthcare',
                                'clothing' => 'Clothing',
                                'education' => 'Education Materials',
                                'household' => 'Household Items',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Stock Information')
                    ->schema([
                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        Forms\Components\TextInput::make('unit')
                            ->required()
                            ->default('pieces')
                            ->datalist([
                                'pieces',
                                'kg',
                                'liters',
                                'boxes',
                                'packets',
                                'bottles',
                            ]),
                        Forms\Components\TextInput::make('minimum_threshold')
                            ->required()
                            ->numeric()
                            ->default(10)
                            ->minValue(0)
                            ->helperText('Alert when stock falls below this level'),
                        Forms\Components\TextInput::make('unit_cost')
                            ->numeric()
                            ->prefix('₹'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Supplier & Storage')
                    ->schema([
                        Forms\Components\TextInput::make('supplier_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('supplier_contact')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('storage_location')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('expiry_date'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => config("leofoundation.inventory_categories.{$state}.label", ucfirst($state))),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'in_stock' => 'success',
                        'low_stock' => 'warning',
                        'out_of_stock' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->date()
                    ->sortable()
                    ->color(fn($record): ?string => $record->is_expired ? 'danger' : ($record->is_expiring_soon ? 'warning' : null)),
                Tables\Columns\TextColumn::make('storage_location')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'food' => 'Food & Groceries',
                        'medicine' => 'Medicine & Healthcare',
                        'clothing' => 'Clothing',
                        'education' => 'Education Materials',
                        'household' => 'Household Items',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'in_stock' => 'In Stock',
                        'low_stock' => 'Low Stock',
                        'out_of_stock' => 'Out of Stock',
                    ]),
                Tables\Filters\Filter::make('expiring_soon')
                    ->query(fn(Builder $query): Builder => $query->expiringSoon()),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('adjust_stock')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->options([
                                'in' => 'Stock In',
                                'out' => 'Stock Out',
                                'adjustment' => 'Adjustment',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->minValue(0.01),
                        Forms\Components\TextInput::make('reason')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('notes')
                            ->rows(2),
                    ])
                    ->action(function (Inventory $record, array $data): void {
                        $quantityBefore = $record->quantity;

                        if ($data['type'] === 'in') {
                            $record->quantity += $data['quantity'];
                        } elseif ($data['type'] === 'out') {
                            $record->quantity = max(0, $record->quantity - $data['quantity']);
                        } else {
                            $record->quantity = $data['quantity'];
                        }

                        $record->last_updated_by = auth()->id();
                        $record->save();

                        $record->transactions()->create([
                            'type' => $data['type'],
                            'quantity' => $data['quantity'],
                            'quantity_before' => $quantityBefore,
                            'quantity_after' => $record->quantity,
                            'reason' => $data['reason'] ?? null,
                            'notes' => $data['notes'] ?? null,
                            'performed_by' => auth()->id(),
                        ]);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
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
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
