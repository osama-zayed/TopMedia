<?php

namespace App\Filament\Resources\SupplyResource\Pages;

use App\Filament\Resources\SupplyResource;
use App\Models\Inventory;
use Exception;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class CreateSupply extends CreateRecord
{
    protected static string $resource = SupplyResource::class;

    public static function afterSave(Form $form, $record): void
    {

        $supplyDetails = $record->supplyDetails;

        foreach ($supplyDetails as $detail) {
            $inventory = Inventory::where([
                'product_id' => $detail->product_id,
                'unit_id' => $detail->unit_id
            ])->first();

            if ($inventory) {
                $inventory->quantity += $detail->quantity;
                $inventory->save();
            } else {
                Inventory::create([
                    'product_id' => $detail->product_id,
                    'unit_id' => $detail->unit_id,
                    'quantity' => $detail->quantity
                ]);
            }
        }
    }

    protected function afterCreate(): void
    {
        try {
            $this->record->total_amount = 0;
            $supply = $this->record;
            $total_amount = 0;
            foreach ($supply->supplyDetails as $detail) {
                $inventory = Inventory::where([
                    'product_id' => $detail->product_id,
                    'unit_id' => $detail->unit_id
                ])->with('product', 'unit')->first();
                // $total_amount += $detail->total_price;
            }
            // $this->record = $total_amount;

            static::afterSave($this->form, $this->record);
        } catch (\Throwable $th) {
            Notification::make()
                ->danger()
                ->title('خطاء')
                ->body($th->getMessage())
                ->send();
        }
    }
}
