<?php

namespace App\Filament\Resources;

use App\Models\Finance;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class FinanceResource extends Resource
{
    protected static ?string $model = Finance::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $label = 'Keuangan RT';

    protected static ?string $navigationLabel = 'Transaksi Keuangan';

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Transaksi')
                    ->schema([
                        Select::make('type')
                            ->label('Jenis Transaksi')
                            ->options([
                                'pemasukan' => 'Pemasukan',
                                'pengeluaran' => 'Pengeluaran',
                            ])
                            ->required()
                            ->reactive(),

                        Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'iuran' => 'Iuran Bulanan',
                                'donasi' => 'Donasi',
                                'kegiatan' => 'Kegiatan',
                                'perbaikan' => 'Perbaikan',
                                'kebersihan' => 'Kebersihan',
                                'lainnya' => 'Lainnya',
                            ])
                            ->required(),

                        TextInput::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('amount')
                            ->label('Jumlah (Rp)')
                            ->numeric()
                            ->required(),

                        DatePicker::make('transaction_date')
                            ->label('Tanggal Transaksi')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Jenis')
                    ->color(fn ($state) => $state === 'pemasukan' ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->color(fn ($record) => $record->type === 'pemasukan' ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Oleh')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'pemasukan' => 'Pemasukan',
                        'pengeluaran' => 'Pengeluaran',
                    ]),

                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'iuran' => 'Iuran',
                        'donasi' => 'Donasi',
                        'kegiatan' => 'Kegiatan',
                        'perbaikan' => 'Perbaikan',
                        'kebersihan' => 'Kebersihan',
                        'lainnya' => 'Lainnya',
                    ]),

                Tables\Filters\Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari'),
                        DatePicker::make('until')
                            ->label('Hingga'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('transaction_date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('transaction_date', '<=', $data['until']));
                    }),
            ])
            ->actions([
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
            'index' => \App\Filament\Resources\FinanceResource\Pages\ListFinances::route('/'),
            'create' => \App\Filament\Resources\FinanceResource\Pages\CreateFinance::route('/create'),
            'edit' => \App\Filament\Resources\FinanceResource\Pages\EditFinance::route('/{record}/edit'),
        ];
    }
}
