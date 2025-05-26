<?php

namespace App\Filament\Resources\AsetCategoryResource\Pages;

use App\Filament\Resources\AsetCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsetCategories extends ListRecords
{
    protected static string $resource = AsetCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
