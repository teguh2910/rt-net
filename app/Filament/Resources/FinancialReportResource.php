<?php

namespace App\Filament\Resources;

use App\Models\FinancialReport;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class FinancialReportResource extends Resource
{
    protected static ?string $model = FinancialReport::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $label = 'Laporan Keuangan';

    protected static ?string $navigationLabel = 'Laporan Bulanan';

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Laporan Keuangan Bulanan')
                    ->description('Data keuangan bulan dan tahun tertentu')
                    ->schema([
                        Forms\Components\Select::make('month')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('year')
                            ->label('Tahun')
                            ->numeric()
                            ->required()
                            ->default(now()->year),

                        Forms\Components\TextInput::make('opening_balance')
                            ->label('Saldo Awal (Rp)')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('total_income')
                            ->label('Total Pemasukan (Rp)')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('total_expense')
                            ->label('Total Pengeluaran (Rp)')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('closing_balance')
                            ->label('Saldo Akhir (Rp)')
                            ->numeric()
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Periode')
                    ->getStateUsing(function ($record) {
                        $months = [
                            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ags',
                            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
                        ];

                        return $months[$record->month].' '.$record->year;
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('opening_balance')
                    ->label('Saldo Awal')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_income')
                    ->label('Pemasukan')
                    ->money('IDR', locale: 'id')
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_expense')
                    ->label('Pengeluaran')
                    ->money('IDR', locale: 'id')
                    ->color('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('closing_balance')
                    ->label('Saldo Akhir')
                    ->money('IDR', locale: 'id')
                    ->weight('bold')
                    ->sortable(),

                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Dibuat Oleh')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('month')
                    ->label('Bulan')
                    ->options([
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                        4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                        10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ]),

                Tables\Filters\SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(fn () => [
                        now()->year => now()->year,
                        now()->year - 1 => now()->year - 1,
                        now()->year - 2 => now()->year - 2,
                    ]),
            ])
            ->actions([
                ViewAction::make(),

                EditAction::make(),

                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\FinancialReportResource\Pages\ListFinancialReports::route('/'),
            'create' => \App\Filament\Resources\FinancialReportResource\Pages\CreateFinancialReport::route('/create'),
            'edit' => \App\Filament\Resources\FinancialReportResource\Pages\EditFinancialReport::route('/{record}/edit'),
            'view' => \App\Filament\Resources\FinancialReportResource\Pages\ViewFinancialReport::route('/{record}'),
        ];
    }
}
