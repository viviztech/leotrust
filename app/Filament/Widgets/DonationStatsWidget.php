<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class DonationStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayDonations = Donation::completed()
            ->whereDate('created_at', today())
            ->sum('amount');

        $monthDonations = Donation::completed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $totalDonations = Donation::completed()->sum('amount');

        $totalDonors = Donation::completed()
            ->distinct('donor_email')
            ->count('donor_email');

        return [
            Stat::make('Today\'s Donations', '₹' . Number::abbreviate($todayDonations))
                ->description('Donations received today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('This Month', '₹' . Number::abbreviate($monthDonations))
                ->description(now()->format('F Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
            Stat::make('Total Raised', '₹' . Number::abbreviate($totalDonations))
                ->description('All time donations')
                ->descriptionIcon('heroicon-m-currency-rupee')
                ->color('primary'),
            Stat::make('Total Donors', Number::abbreviate($totalDonors))
                ->description('Unique donors')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
        ];
    }
}
