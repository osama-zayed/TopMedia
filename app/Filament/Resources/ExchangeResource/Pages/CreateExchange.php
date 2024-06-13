<?php

namespace App\Filament\Resources\ExchangeResource\Pages;

use App\Filament\Resources\ExchangeResource;
use App\Models\Inventory;
use Exception;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
class CreateExchange extends CreateRecord
{
    protected static string $resource = ExchangeResource::class;
    public static function afterSave(Form $form, $record): void
    {
        $ExchangeDetails = $record->ExchangeDetails;

        foreach ($ExchangeDetails as $detail) {
            $inventory = Inventory::where([
                'product_id' => $detail->product_id,
                'unit_id' => $detail->unit_id
            ])->first();

            if ($inventory && $inventory->quantity >= $detail->quantity) {
                $inventory->quantity -= $detail->quantity;
                $inventory->save();
            } else {
                throw new Exception("الكمية المتوفرة في المخزون غير كافية للمنتج ذي الرقم: {$detail->product_id}، والوحدة ذات الرقم: {$detail->unit_id}");
            }
        }
    }


    protected function afterCreate(): void
    {
        try {
            $Exchange = $this->record;
            $total_amount = 0;

            foreach ($Exchange->ExchangeDetails as $detail) {
                $inventory = Inventory::where([
                    'product_id' => $detail->product_id,
                    'unit_id' => $detail->unit_id
                ])->with('product', 'unit')->first();
                if (!$inventory) {
                    throw new Exception('المنتج ' . $detail->product->product_name . ' لا يحتوي على الصنف ' . $detail->unit->unit_name);
                }
                $total_amount += $detail->total_price;
                if ($inventory && $inventory->quantity >= $detail->quantity) {
                    $inventory->quantity -= $detail->quantity;
                    $inventory->save();
                } else {
                    throw new Exception('الكمية غير كافية في المخزن من المنتج ' . $detail->product->product_name  . ' بوحده قياس ' . $detail->unit->unit_name);
                }
            }
            $this->record= $total_amount;
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
