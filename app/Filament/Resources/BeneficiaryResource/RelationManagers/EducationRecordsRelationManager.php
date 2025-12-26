<?php

namespace App\Filament\Resources\BeneficiaryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EducationRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'educationRecords';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('school_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('academic_year')
                    ->maxLength(20)
                    ->placeholder('2024-25'),
                Forms\Components\TextInput::make('grade')
                    ->maxLength(50),
                Forms\Components\TextInput::make('section')
                    ->maxLength(10),
                Forms\Components\Select::make('performance')
                    ->options([
                        'excellent' => 'Excellent',
                        'good' => 'Good',
                        'average' => 'Average',
                        'below_average' => 'Below Average',
                        'poor' => 'Poor',
                    ]),
                Forms\Components\TextInput::make('attendance_percentage')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%'),
                Forms\Components\Textarea::make('subjects')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('achievements')
                    ->rows(2),
                Forms\Components\Textarea::make('areas_of_improvement')
                    ->rows(2),
                Forms\Components\TextInput::make('teacher_contact')
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('academic_year')
            ->columns([
                Tables\Columns\TextColumn::make('academic_year')
                    ->sortable(),
                Tables\Columns\TextColumn::make('school_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grade'),
                Tables\Columns\TextColumn::make('performance')
                    ->badge()
                    ->color(fn(?string $state): string => match ($state) {
                        'excellent' => 'success',
                        'good' => 'info',
                        'average' => 'warning',
                        'below_average' => 'danger',
                        'poor' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('attendance_percentage')
                    ->suffix('%')
                    ->color(fn($state): string => match (true) {
                        $state >= 90 => 'success',
                        $state >= 75 => 'info',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
            ->defaultSort('created_at', 'desc');
    }
}
