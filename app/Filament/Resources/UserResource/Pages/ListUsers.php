<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
    
        // Exclude the currently logged-in user from the table
        if ($user = auth()->user()) {
            $query->where('id', '!=', $user->id);
        }
    
        return $query;
    }
}
