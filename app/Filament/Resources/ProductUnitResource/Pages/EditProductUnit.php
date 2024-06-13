<?php

namespace App\Filament\Resources\ProductUnitResource\Pages;

use App\Filament\Resources\ProductUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductUnit extends EditRecord
{
    protected static string $resource = ProductUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
