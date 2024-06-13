<?php

namespace App\Filament\Resources\ExchangeResource\Pages;

use App\Filament\Resources\ExchangeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExchange extends ViewRecord
{
    protected static string $resource = ExchangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
