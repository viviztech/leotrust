<?php

namespace App\Filament\Widgets;

use App\Models\Beneficiary;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BeneficiaryStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $totalActive = Beneficiary::active()->count();
        $orphans = Beneficiary::ofType('orphan')->active()->count();
        $patients = Beneficiary::ofType('patient')->active()->count();
        $welfare = Beneficiary::ofType('welfare_recipient')->active()->count();

        return [
            Stat::make('Active Beneficiaries', $totalActive)
                ->description('Currently supported')
                ->descriptionIcon('heroicon-m-heart')
                ->color('success'),
            Stat::make('Orphans', $orphans)
                ->description('Children in care')
                ->descriptionIcon('heroicon-m-home')
                ->color('primary'),
            Stat::make('De-addiction Patients', $patients)
                ->description('In treatment')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('warning'),
            Stat::make('Welfare Recipients', $welfare)
                ->description('Receiving support')
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color('info'),
        ];
    }
}
