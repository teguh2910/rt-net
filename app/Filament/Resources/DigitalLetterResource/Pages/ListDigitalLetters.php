<?php

namespace App\Filament\Resources\DigitalLetterResource\Pages;

use App\Filament\Resources\DigitalLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDigitalLetters extends ListRecords
{
    protected static string $resource = DigitalLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
