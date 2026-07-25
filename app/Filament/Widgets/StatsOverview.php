<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // ১. মোট বিক্রির হিসাব
            Stat::make('Total Revenue', '$' . number_format(Order::sum('total_amount'), 2))
                ->description('Total earnings from orders')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            // ২. মোট কতগুলো অর্ডার এসেছে
            Stat::make('Total Orders', Order::count())
                ->description('Total orders placed')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            // ৩. যেসব প্রোডাক্টের স্টক কম (৫ বা তার নিচে)
            Stat::make('Low Stock Products', Product::where('stock', '<=', 5)->count())
                ->description('Products with 5 or less stock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),
        ];
    }
}