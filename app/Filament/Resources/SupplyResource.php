<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplyResource\Pages;
use App\Filament\Resources\SupplyResource\RelationManagers\SupplyDetailsRelationManager;
use App\Models\Inventory;
use App\Models\Supply;
use App\Models\ProductUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplyResource extends Resource
{
    protected static ?string $model = Supply::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'حركة مخزنية';

    protected static ?string $modelLabel = 'مشتريات';
    protected static ?string $pluralLabel = 'المشتريات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('invoice_number')
                    ->label('رقم الفاتورة')
                    ->numeric()
                    ->required(),
                    Forms\Components\DatePicker::make('supply_date')
                    ->default(today())
                    ->label('تاريخ التوريد')
                    ->required(),
                Forms\Components\Select::make('inventory_id')
                    ->relationship('Inventory', 'name')
                    ->label('المخزن')
                    ->searchable()
                    ->preload()->columnSpan(2)
                    ->live()
                    ->required()->createOptionForm([
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
                    ]),
              
                Forms\Components\TextInput::make('supplier_name')
                    ->required()
                    ->label('اسم المورد')
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->label('اجمالي السعر')
                    ->numeric()
                    ->reactive()
                    // ->hidden()
                    ->live()
                    ->readOnly()
                    ->default(0.0),

                Forms\Components\Textarea::make('notes')
                    ->label('الملاحظات')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('supplyDetails')
                    ->relationship('supplyDetails')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'product_name')
                            ->label('المنتج')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('product_name')
                                    ->required()
                                    ->label('اسم المنتج')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('product_code')
                                    ->required()
                                    ->label('كود المنتج')
                                    ->maxLength(255),
                                Forms\Components\Select::make('categorie_id')
                                    ->relationship('Category', titleAttribute: 'categorie_name')
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(2)
                                    ->label('الصنف')
                                    ->required()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('categorie_name')
                                        ->required()
                                        ->label('اسم الصنف')
                                        ->maxLength(255),
                                    ]),
                                Forms\Components\Textarea::make('product_description')
                                    ->required()
                                    ->maxLength(65535)
                                    ->columnSpanFull()
                                    ->label('وصف المنتج')
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('product_status')
                                    ->label('حالة المنتج')
                                    ->default(true)
                                    ->columnSpan(2)
                                    ->required(),
                                Forms\Components\Repeater::make('product_unit_prices')
                                    ->schema([
                                        Forms\Components\Select::make('unit_id')
                                            ->relationship('units', 'unit_name')
                                            ->label('الوحدة')
                                            ->searchable()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('unit_name')
                                                    ->required()
                                                    ->label('وحده القياس')
                                                    ->maxLength(255),
                                            ])
                                            ->preload()
                                            ->required(),
                                        Forms\Components\TextInput::make('product_price')
                                            ->label('السعر')
                                            ->required()
                                            ->numeric(),
                                    ])
                                    ->columnSpan(2)
                                    ->columns(2)
                                    ->label('أسعار الوحدات')
                            ])
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $unit_id = $get('unit_id');
                                $productUnit = ProductUnit::where('product_id', $state)
                                    ->where('unit_id', $unit_id)
                                    ->first();
                                if ($productUnit) {
                                    $set('unit_price', $productUnit->product_price);
                                    $set('total_price', $get('quantity') * $productUnit->product_price);
                                }
                            }),
                        Forms\Components\Select::make('unit_id')
                            ->relationship('unit', 'unit_name')
                            ->label('وحده القياس')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('unit_name')
                                    ->required()
                                    ->label('وحده القياس')
                                    ->maxLength(255),
                            ])
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $product_id = $get('product_id');
                                $productUnit = ProductUnit::where('product_id', $product_id)
                                    ->where('unit_id', $state)
                                    ->first();
                                if ($productUnit) {
                                    $set('unit_price', $productUnit->product_price);
                                    $set('total_price', $get('quantity') * $productUnit->product_price);
                                }
                            }),
                        Forms\Components\TextInput::make('quantity')
                            ->label('الكمية')
                            ->required()
                            ->live()
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $product_id = $get('product_id');
                                $unit_id = $get('unit_id');
                                $productUnit = ProductUnit::where('product_id', $product_id)
                                    ->where('unit_id', $unit_id)
                                    ->first();
                                if ($productUnit) {
                                    $set('unit_price', $productUnit->product_price);
                                    $set('total_price', $state * $productUnit->product_price);
                                }
                            }),
                        Forms\Components\TextInput::make('unit_price')
                            ->label('سعر الوحدة')
                            ->required()
                            ->numeric()
                            ->live()
                            ->reactive()
                            ->default(0.0)
                            ->readonly(),
                        Forms\Components\TextInput::make('total_price')
                            ->label('السعر الاجمالي')
                            ->required()
                            ->numeric()
                            ->live()
                            ->reactive()
                            ->default(0.0)
                            ->readonly()->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $totalAmount = 0;
                                dd("سس");
                                $supplyDetails = $get('supplyDetails') ?? [];
                                foreach ($supplyDetails as $detail) {
                                    $totalAmount += $detail['total_price'] ?? 0;
                                }
                                $set('total_amount', $totalAmount);
                            }),

                    ])
                    ->label('تفاصيل التوريد')
                    ->columns(3)
                    ->columnSpanFull()
                    ->live()
                    ->reactive(),
            ]);
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
                Tables\Columns\TextColumn::make('supply_date')
                    ->date()
                    ->label('تاريخ التوريد')
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier_name')
                    ->label('اسم المورد')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->label('اجمالي السعر')
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
                // Add filters here if needed
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
            SupplyDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupplies::route('/'),
            'create' => Pages\CreateSupply::route('/create'),
            'view' => Pages\ViewSupply::route('/{record}'),
            // 'edit' => Pages\EditSupply::route('/{record}/edit'),
        ];
    }
}
