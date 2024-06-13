<?php

namespace App\Filament\Resources\ExchangeResource\Pages;

use App\Filament\Resources\ExchangeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExchange extends EditRecord
{
    protected static string $resource = ExchangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
