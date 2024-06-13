<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Exchange;
use App\Models\Product;
use App\Models\Supply;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
       $productCount =Product::count();
       $supplyCount =Supply::count();
       $exchangeCount =Exchange::count();
        return [
            Stat::make('عدد المنتجات', $productCount),
            Stat::make('عمليات الشراء', $supplyCount),
            Stat::make('عمليات البيع', $exchangeCount),
        ];
    }
}
