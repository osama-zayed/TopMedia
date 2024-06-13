<?php

namespace App\Filament\Resources\ExchangeResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Exchange;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
class ExchangeChart extends ChartWidget
{
    protected static ?string $heading = 'عمليات الصرف';

    protected function getData(): array
    {
            $data = Trend::model(Exchange::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();
     
        return [
            'datasets' => [
                [
                    'label' => 'عمليات الصرف',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
