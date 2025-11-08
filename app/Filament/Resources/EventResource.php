<?php

namespace App\Filament\Resources;

use App\Models\Event;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $label = 'Acara & Kegiatan';

    protected static ?string $navigationLabel = 'Acara & Kegiatan';

    protected static string|UnitEnum|null $navigationGroup = 'Komunikasi';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Detail Kegiatan')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kegiatan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\MarkdownEditor::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('event_date')
                            ->label('Tanggal & Waktu')
                            ->required(),

                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('organizer')
                            ->label('Penyelenggara')
                            ->maxLength(255),

                        Forms\Components\Toggle::make('send_notification')
                            ->label('Kirim Notifikasi ke Warga')
                            ->default(true)
                            ->inline(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('event_date')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->limit(30),

                Tables\Columns\TextColumn::make('organizer')
                    ->label('Penyelenggara')
                    ->limit(30),

                Tables\Columns\IconColumn::make('send_notification')
                    ->label('Notifikasi')
                    ->boolean(),

                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Oleh')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('event_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Hingga'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('event_date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('event_date', '<=', $data['until']));
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
            'index' => \App\Filament\Resources\EventResource\Pages\ListEvents::route('/'),
            'create' => \App\Filament\Resources\EventResource\Pages\CreateEvent::route('/create'),
            'edit' => \App\Filament\Resources\EventResource\Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
