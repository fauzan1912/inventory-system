<?php

namespace App\Filament\Resources\AsetCategoryResource\Pages;

use App\Filament\Resources\AsetCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsetCategory extends EditRecord
{
    protected static string $resource = AsetCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
