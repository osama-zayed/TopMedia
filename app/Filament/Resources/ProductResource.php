<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\UnitsRelationManager;
use App\Models\Product;
use Doctrine\DBAL\Schema\Column;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Laravel\Prompts\select;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'منتج';
    protected static ?string $pluralLabel = 'المنتجات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_name')
                    ->label('اسم المنتج')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_code')
                    ->label('كود المنتج')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Category.categorie_name')
                    ->label('الصنف'),
                Tables\Columns\IconColumn::make('product_status')
                    ->label('حالة المنتج')
                    ->boolean(),
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
                SelectFilter::make('categorie_id')
                    ->label('الصنف')
                    ->relationship('Category', 'categorie_name')
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
            UnitsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
