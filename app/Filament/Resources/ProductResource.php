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
    protected static string | array $routeMiddleware = [
        'auth:web',
        'Permission:view products',
    ];

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'منتج';
    protected static ?string $pluralLabel = 'المنتجات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('product_name')
                        ->required()
                        ->label('اسم المنتج')
                        ->maxLength(255),
                    Forms\Components\Select::make('categorie_id')
                        ->relationship('Category', titleAttribute: 'categorie_name')
                        ->searchable()
                        ->preload()
                        ->label('الصنف')
                        ->required()
                        ->createOptionForm(CategoryResource::formCategory()),
                    Forms\Components\TextInput::make('product_price')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->label('سعر المنتج'),
                    Forms\Components\TextInput::make('discount_percentage')
                        ->required()
                        ->numeric()
                        ->maxValue(70)
                        ->minValue(0)
                        ->label('نسبة التخفيض'),
                    // Forms\Components\MarkdownEditor::make('product_description')
                    Forms\Components\Textarea::make('product_description')
                        ->required()
                        ->maxLength(65535)
                        ->label('وصف المنتج')
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('product_status')
                        ->label('حالة المنتج')
                        ->default(true)
                        ->required(),
                ])->columns(2),
                Forms\Components\Section::make([
                    Forms\Components\FileUpload::make('image')
                        ->label('صور للمنتج')
                        ->multiple()
                        // ->image()
                        ->required()
                        ->directory('Product')
                ]),
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
                Tables\Columns\TextColumn::make('Category.categorie_name')
                    ->label('الصنف'),
                Tables\Columns\TextColumn::make('product_price')
                    ->sortable()
                    ->label('سعر المنتج'),
                Tables\Columns\TextColumn::make('discount_percentage')
                    ->sortable()
                    ->label('نسبة التخفيض'),
                Tables\Columns\IconColumn::make('product_status')
                    ->label('حالة المنتج')
                    ->boolean(),
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->stacked()
                    ->label('صور المنتج')
                    ->limit(3)
                    ->limitedRemainingText(),
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
        return [];
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
