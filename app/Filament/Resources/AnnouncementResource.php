<?php

namespace App\Filament\Resources;

use App\Models\Announcement;
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

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $label = 'Pengumuman';

    protected static ?string $navigationLabel = 'Pengumuman';

    protected static string|UnitEnum|null $navigationGroup = 'Komunikasi';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Konten Pengumuman')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\MarkdownEditor::make('content')
                            ->label('Isi Pengumuman')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Waktu Publikasi')
                            ->required()
                            ->default(now()),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Waktu Kadaluarsa')
                            ->afterOrEqual('published_at'),
                    ])->columns(2),

                Section::make('Pengaturan Notifikasi')
                    ->description('Pilih saluran notifikasi untuk pengumuman ini')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(true)
                            ->inline(false),

                        Forms\Components\Toggle::make('send_email')
                            ->label('Kirim Email ke Warga')
                            ->inline(false),

                        Forms\Components\Toggle::make('send_whatsapp')
                            ->label('Kirim WhatsApp ke Warga')
                            ->inline(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publikasi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('is_published')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->is_published ? 'Dipublikasikan' : 'Draft')
                    ->color(fn ($state) => $state === 'Dipublikasikan' ? 'success' : 'warning'),

                Tables\Columns\IconColumn::make('send_email')
                    ->label('Email')
                    ->boolean(),

                Tables\Columns\IconColumn::make('send_whatsapp')
                    ->label('WhatsApp')
                    ->boolean(),

                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Oleh')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status Publikasi'),

                Tables\Filters\TernaryFilter::make('send_email')
                    ->label('Kirim Email'),

                Tables\Filters\TernaryFilter::make('send_whatsapp')
                    ->label('Kirim WhatsApp'),
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
            'index' => \App\Filament\Resources\AnnouncementResource\Pages\ListAnnouncements::route('/'),
            'create' => \App\Filament\Resources\AnnouncementResource\Pages\CreateAnnouncement::route('/create'),
            'edit' => \App\Filament\Resources\AnnouncementResource\Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
