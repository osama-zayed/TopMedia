<?php

namespace App\Filament\Resources\ExchangeResource\RelationManagers;

use App\Models\ProductUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExchangeDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'exchangeDetails';

    protected static ?string $title = 'تفاصيل الصرف';
    protected static ?string $modelLabel = 'تفاصيل الصرف';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('product_id')
                ->relationship('product', 'product_name')
                ->label('المنتج')
                ->searchable()
                ->preload()
                ->live()
                ->required()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $unit_id = $get('unit_id');
                    $set('product_id', $state);
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
                ->required()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $product_id = $get('product_id');
                    $set('unit_id', $state);
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
                        $set('unit_price',  $productUnit->product_price);
                        $set('total_price', $state * $productUnit->product_price);
                    }
                }),
            Forms\Components\TextInput::make('unit_price')
                ->required()
                ->label('سعر الوحدة')
                ->numeric()
                ->live()
                ->reactive()
                ->readonly()
                ->default(0.0),
                Forms\Components\TextInput::make('total_price')
                ->required()
                ->label('السعر الاجمالي')
                ->numeric()
                ->live()
                ->reactive()
                ->readonly()
                ->default(0.0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('تفاصيل الصرف')
            ->columns([
                Tables\Columns\TextColumn::make('product.product_name')
                    ->label('المنتج')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit.unit_name')
                    ->label('وحده القياس')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('quantity')
                    ->label('الكمية')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->numeric()
                    ->label('سعر الحبة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->label('الاجمالي')
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                ])
                ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
