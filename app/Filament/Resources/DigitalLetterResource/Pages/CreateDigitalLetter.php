<?php

namespace App\Filament\Resources\DigitalLetterResource\Pages;

use App\Filament\Resources\DigitalLetterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDigitalLetter extends CreateRecord
{
    protected static string $resource = DigitalLetterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['issued_by'] = auth()->id();

        return $data;
    }
}
