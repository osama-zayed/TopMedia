<?php

namespace App\Filament\Resources\SupplyResource\Widgets;

use App\Models\Supply;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SupplyChart extends ChartWidget
{
    protected static ?string $heading = 'عمليات التوريد';
    protected static string $color = 'info';


     
    protected function getData(): array
    {
        $data = Trend::model(Supply::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();
     
        return [
            'datasets' => [
                [
                    'label' => 'عمليات التوريد',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
