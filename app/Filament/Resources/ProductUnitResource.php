<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductUnitResource\Pages;
use App\Filament\Resources\ProductUnitResource\RelationManagers;
use App\Models\ProductUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductUnitResource extends Resource
{
    protected static ?string $model = ProductUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'وحدات قياس المنتج';
    protected static ?string $pluralLabel = 'وحدات قياس المنتجات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', titleAttribute: 'product_name')
                    ->label('المنتج')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                Forms\Components\Select::make('unit_id')
                    ->relationship('unit', titleAttribute: 'unit_name')
                    ->label('وحده القياس')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('product_price')
                    ->label('السعر')
                    ->required()
                    ->numeric(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.product_name')
                    ->label('المنتج')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit.unit_name')
                ->label('وحده القياس')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_price')
                    ->label('السعر')
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
                Tables\Actions\EditAction::make(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductUnits::route('/'),
            'create' => Pages\CreateProductUnit::route('/create'),
            'view' => Pages\ViewProductUnit::route('/{record}'),
            'edit' => Pages\EditProductUnit::route('/{record}/edit'),
        ];
    }
}
