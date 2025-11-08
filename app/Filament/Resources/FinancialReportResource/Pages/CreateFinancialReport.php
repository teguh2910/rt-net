<?php

namespace App\Filament\Resources\FinancialReportResource\Pages;

use App\Filament\Resources\FinancialReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFinancialReport extends CreateRecord
{
    protected static string $resource = FinancialReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
