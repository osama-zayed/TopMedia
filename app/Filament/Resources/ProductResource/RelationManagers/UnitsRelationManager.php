<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';
    protected static ?string $title = 'وحدات القياس للمنتج';
    protected static ?string $modelLabel = 'وحدة قياس';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('unit_name')
                    ->required()
                    ->label("اسم الوحدة")
                    ->maxLength(255),

                Forms\Components\TextInput::make('product_price')
                    ->required()
                    ->label("سعر الوحدة")
                    ->maxLength(255),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('unit_name')
            ->columns([
                Tables\Columns\TextColumn::make('unit_name')->label("اسم الوحدة"),
                Tables\Columns\TextColumn::make('product_price')->label("السعر"),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(
                        fn (AttachAction $action): array => [
                            $action->getRecordSelect(),
                            Forms\Components\TextInput::make('product_price')->required()->numeric(),
                        ]
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->emptyStateActions([
                Tables\Actions\AttachAction::make(),
            ]);
    }
}
