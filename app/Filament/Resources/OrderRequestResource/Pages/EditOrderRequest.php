<?php

namespace App\Filament\Resources\OrderRequestResource\Pages;

use App\Filament\Resources\OrderRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderRequest extends EditRecord
{
    protected static string $resource = OrderRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}