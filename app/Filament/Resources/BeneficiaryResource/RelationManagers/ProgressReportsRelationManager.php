<?php

namespace App\Filament\Resources\BeneficiaryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProgressReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'progressReports';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('report_date')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('overall_status')
                    ->options([
                        'excellent' => 'Excellent',
                        'good' => 'Good',
                        'satisfactory' => 'Satisfactory',
                        'needs_attention' => 'Needs Attention',
                        'critical' => 'Critical',
                    ])
                    ->required()
                    ->default('good'),
                Forms\Components\Textarea::make('summary')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('observations')
                    ->rows(3),
                Forms\Components\Textarea::make('recommendations')
                    ->rows(3),
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('health_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10)
                            ->step(0.5),
                        Forms\Components\TextInput::make('behavior_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10)
                            ->step(0.5),
                        Forms\Components\TextInput::make('progress_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10)
                            ->step(0.5),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('report_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('overall_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'excellent' => 'success',
                        'good' => 'info',
                        'satisfactory' => 'warning',
                        'needs_attention' => 'danger',
                        'critical' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('average_score')
                    ->label('Avg Score'),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('By'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = auth()->id();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('report_date', 'desc');
    }
}
