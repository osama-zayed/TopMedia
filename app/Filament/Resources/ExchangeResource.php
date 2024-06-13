<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExchangeResource\Pages;
use App\Filament\Resources\ExchangeResource\RelationManagers\ExchangeDetailsRelationManager;
use App\Models\Exchange;
use App\Models\Inventory;
use App\Models\ProductUnit;
use App\Models\Supply;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;


class ExchangeResource extends Resource
{
    protected static ?string $model = Exchange::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'حركة مخزنية';
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'صرف';
    protected static ?string $pluralLabel = 'المبيعات';




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('invoice_number')
                    ->label('رقم الفاتورة')
                    ->numeric()
                    ->required(),
                Forms\Components\DatePicker::make('exchange_date')
                    ->label('تاريخ المبيعات')
                    ->default(today())
                    ->required(),
                Select::make('inventory_id')
                    ->relationship('Inventory', 'name')
                    ->label('المخزن')
                    ->columnSpanFull()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم المخزن')
                            ->required()
                            ->reactive(),
                        Forms\Components\TextInput::make('location')
                            ->label('موقع المخزن')
                            ->required()
                            ->reactive(),
                        Forms\Components\TextInput::make('description')
                            ->label('وصف المخزن')
                            ->reactive(),
                    ])
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set('inventory_id', $state);
                    }),

                TextInput::make('exchange_name')
                    ->label('اسم العميل')
                    ->required()
                    ->maxLength(255),
                TextInput::make('total_amount')
                    ->required()
                    ->label('اجمالي الفاتوره')
                    ->numeric()
                    ->default(0.0)
                    ->live()
                    ->reactive(),

                Textarea::make('notes')
                    ->maxLength(65535)
                    ->label('الملاحظات')
                    ->columnSpanFull(),
                Repeater::make('exchangeDetails')
                    ->relationship('exchangeDetails')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'product_name')
                            ->label('المنتج')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $Inventory = $get('inventory_id');
                                $unit_id = $get('unit_id');
                                $quantity = $get('quantity') ?? 0;
                                dd($Inventory);

                                $productUnit = ProductUnit::where('product_id', $state)
                                    ->where('unit_id', $unit_id)
                                    ->first();
                                if ($productUnit) {
                                    $inventory = Supply::where('inventory_id', $Inventory)
                                        ->whereHas('SupplyDetails', function ($query) use ($state) {
                                            $query->where('product_id', $state);
                                        })
                                        ->whereHas('SupplyDetails', function ($query) use ($unit_id) {
                                            $query->where('unit_id', $unit_id);
                                        })
                                        ->first();
                                    if (!$inventory) {
                                        Notification::make()
                                            ->danger()
                                            ->title('خطاء')
                                            ->body('هذا المنتج غير موجود في المخزن')
                                            ->send();
                                        $set('max_quantity', 0);
                                    }
                                    if ($quantity > $inventory->quantity) {
                                        Notification::make()
                                            ->danger()
                                            ->title('خطاء')
                                            ->body('الكمية المتبقية في المخزن من هذا المنتج هي ' . $inventory->quantity)
                                            ->send();
                                        $set('quantity', $inventory->quantity);
                                    }
                                    if ($inventory) {
                                        $set('unit_price', $productUnit->product_price);
                                        $set('total_price', $get('quantity') * $productUnit->product_price);
                                        $set('max_quantity', $inventory->quantity);
                                    }
                                    self::updateTotalAmount($get, $set);
                                } else {
                                    $set('quantity', 0);
                                    $set('max_quantity', 0);
                                }
                            })
                            ,
                        Select::make('unit_id')
                            ->relationship('unit', 'unit_name')
                            ->label('وحده القياس')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        // ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        //     $product_id = $get('product_id');
                        //     $quantity = $get('quantity') ?? 0;
                        //     $productUnit = ProductUnit::where('product_id', $product_id)
                        //         ->where('unit_id', $state)
                        //         ->first();
                        //     if ($productUnit) {
                        //         $inventory = Inventory::where('product_id', $product_id)
                        //             ->where('unit_id', $state)
                        //             ->first();
                        //         if (!$inventory) {
                        //             Notification::make()
                        //                 ->danger()
                        //                 ->title('خطاء')
                        //                 ->body('هذا المنتج غير موجود في المخزن')
                        //                 ->send();
                        //             $set('total_price', $get('quantity') * $productUnit->product_price);
                        //             $set('max_quantity', $inventory->quantity);
                        //         }
                        //         if ($quantity > $inventory->quantity) {
                        //             Notification::make()
                        //                 ->danger()
                        //                 ->title('خطاء')
                        //                 ->body('الكمية المتبقية في المخزن من هذا المنتج هي ' . $inventory->quantity)
                        //                 ->send();
                        //             $set('total_price', $get('quantity') * $productUnit->product_price);
                        //             $set('max_quantity', $inventory->quantity);
                        //         }
                        //         if ($inventory) {
                        //             $set('unit_price', $productUnit->product_price);
                        //             $set('total_price', $get('quantity') * $productUnit->product_price);
                        //             $set('max_quantity', $inventory->quantity);
                        //         }
                        //     } else {
                        //         $set('quantity', 0);
                        //         $set('max_quantity', 0);
                        //     }
                        // }),
                        TextInput::make('quantity')
                            ->label('الكمية')
                            ->required()
                            ->live()
                            ->numeric()
                            ->minValue(1)
                            ->reactive(),
                        // ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        //     $product_id = $get('product_id');
                        //     $unit_id = $get('unit_id');
                        //     $productUnit = ProductUnit::where('product_id', $product_id)
                        //         ->where('unit_id', $unit_id)
                        //         ->first();
                        //     if ($productUnit) {
                        //         $inventory = Inventory::where('product_id', $product_id)
                        //             ->where('unit_id', $unit_id)
                        //             ->first();
                        //         if ($inventory) {
                        //             if ($state > $inventory->quantity) {
                        //                 Notification::make()
                        //                     ->danger()
                        //                     ->title('خطاء')
                        //                     ->body('الكمية المتبقية في المخزن من هذا المنتج هي ' . $inventory->quantity)
                        //                     ->send();
                        //                 $set('quantity', $inventory->quantity);
                        //             }
                        //             $set('unit_price', $productUnit->product_price);
                        //             $set('total_price', $state * $productUnit->product_price);
                        //         } else {
                        //             Notification::make()
                        //                 ->danger()
                        //                 ->title('خطاء')->body('هذا المنتج غير موجود في المخزن')
                        //                 ->send();
                        //             $set('quantity', 0);
                        //             $set('max_quantity', 0);
                        //         }
                        //     } else {
                        //         Notification::make()
                        //             ->danger()
                        //             ->title('خطاء')
                        //             ->body('المنتج لا يحتوي على وحدة القياس')
                        //             ->send();
                        //         $set('quantity', 0);
                        //         $set('max_quantity', 0);
                        //     }
                        // })
                        // ->extraAttributes(function (callable $get) {
                        //     $product_id = $get('product_id');
                        //     $unit_id = $get('unit_id');
                        //     $inventory = Inventory::where('product_id', $product_id)
                        //         ->where('unit_id', $unit_id)
                        //         ->first();
                        //     return [
                        //         'max' => $inventory ? $inventory->quantity : 0
                        //     ];
                        // }),
                        TextInput::make('unit_price')
                            ->label('سعر الوحدة')
                            ->required()
                            ->numeric()
                            ->live()
                            ->reactive()
                            ->default(0.0)
                            ->readonly(),
                        TextInput::make('total_price')
                            ->label('السعر الاجمالي')
                            ->required()
                            ->numeric()
                            ->live()
                            ->reactive()
                            ->default(0.0)
                            ->readonly(),
                    ])
                    ->label('تفاصيل الصرف')
                    ->columns(3)
                    ->columnSpanFull()
                    ->live()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        self::updateTotalAmount($get, $set);
                    }),
            ])
            ->columns(2);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_number')
                    ->numeric()
                    ->label('رقم الفاتوره')
                    ->sortable(),
                Tables\Columns\TextColumn::make('exchange_date')
                    ->label('تاريخ المبيعات')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('exchange_name')
                    ->label('اسم العميل')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('اجمالي الفاتوره')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('وقت الاضافة')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('وقت التعديل')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ExchangeDetailsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExchanges::route('/'),
            'create' => Pages\CreateExchange::route('/create'),
            'view' => Pages\ViewExchange::route('/{record}'),
            // 'edit' => Pages\EditExchange::route('/{record}/edit'),
        ];
    }
}
