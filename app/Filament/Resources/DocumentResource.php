<?php

namespace App\Filament\Resources;

use App\Models\Document;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document';

    protected static ?string $label = 'Dokumen';

    protected static ?string $navigationLabel = 'Dokumen RT';

    protected static string|UnitEnum|null $navigationGroup = 'Administrasi';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Informasi Dokumen')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Dokumen')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('file_type')
                            ->label('Tipe Dokumen')
                            ->default('laporan')
                            ->maxLength(50),

                        Forms\Components\FileUpload::make('file_path')
                            ->label('File')
                            ->required()
                            ->directory('documents')
                            ->maxSize(10240),

                        Forms\Components\DatePicker::make('document_date')
                            ->label('Tanggal Dokumen')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3),
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
                    ->limit(40),

                Tables\Columns\TextColumn::make('document_date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('file_type')
                    ->label('Tipe'),

                Tables\Columns\TextColumn::make('uploadedBy.name')
                    ->label('Upload Oleh')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diupload')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('file_type')
                    ->options([
                        'notulen' => 'Notulen',
                        'foto' => 'Foto Kegiatan',
                        'laporan' => 'Laporan',
                        'kontrak' => 'Kontrak',
                        'lainnya' => 'Lainnya',
                    ]),
            ])
            ->actions([
                Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => asset('storage/'.$record->file_path))
                    ->openUrlInNewTab(),

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
            'index' => \App\Filament\Resources\DocumentResource\Pages\ListDocuments::route('/'),
            'create' => \App\Filament\Resources\DocumentResource\Pages\CreateDocument::route('/create'),
        ];
    }
}
