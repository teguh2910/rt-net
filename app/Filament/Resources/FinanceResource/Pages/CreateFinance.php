<?php

namespace App\Filament\Resources\FinanceResource\Pages;

use App\Filament\Resources\FinanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFinance extends CreateRecord
{
    protected static string $resource = FinanceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
