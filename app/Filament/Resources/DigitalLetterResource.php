<?php

namespace App\Filament\Resources;

use App\Models\DigitalLetter;
use App\Services\DigitalLetterPdfService;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DigitalLetterResource extends Resource
{
    protected static ?string $model = DigitalLetter::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $label = 'Surat Digital';

    protected static ?string $navigationLabel = 'Surat Digital';

    protected static string|UnitEnum|null $navigationGroup = 'Administrasi';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Data Surat')
                    ->description('Buat surat keterangan digital baru')
                    ->schema([
                        Forms\Components\TextInput::make('letter_number')
                            ->label('Nomor Surat')
                            ->required()
                            ->unique(ignorable: fn ($record) => $record)
                            ->maxLength(50),

                        Forms\Components\Select::make('letter_type')
                            ->label('Jenis Surat')
                            ->options([
                                'domisili' => 'Surat Domisili',
                                'pengantar' => 'Surat Pengantar',
                                'usaha' => 'Surat Usaha',
                                'kelahiran' => 'Surat Keterangan Kelahiran',
                                'pernikahan' => 'Surat Keterangan Pernikahan',
                                'lainnya' => 'Lainnya',
                            ])
                            ->required(),

                        Forms\Components\Select::make('resident_id')
                            ->label('Data Warga')
                            ->relationship('resident', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DatePicker::make('issued_date')
                            ->label('Tanggal Diterbitkan')
                            ->required()
                            ->default(today()),

                        Forms\Components\DatePicker::make('valid_until')
                            ->label('Berlaku Hingga'),

                        Forms\Components\MarkdownEditor::make('letter_content')
                            ->label('Isi Surat')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('signature_path')
                            ->label('Upload Tanda Tangan (Ketua RT/RW)')
                            ->image()
                            ->directory('letters/signatures')
                            ->maxSize(2048),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('letter_number')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('letter_type')
                    ->label('Jenis Surat')
                    ->badge(),

                Tables\Columns\TextColumn::make('resident.name')
                    ->label('Warga')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('issued_date')
                    ->label('Diterbitkan')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Berlaku Hingga')
                    ->date('d/m/Y'),

                Tables\Columns\IconColumn::make('pdf_path')
                    ->label('PDF')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('letter_type')
                    ->options([
                        'domisili' => 'Domisili',
                        'pengantar' => 'Pengantar',
                        'usaha' => 'Usaha',
                        'kelahiran' => 'Kelahiran',
                        'pernikahan' => 'Pernikahan',
                        'lainnya' => 'Lainnya',
                    ]),
            ])
            ->actions([
                Action::make('preview_pdf')
                    ->label('Preview PDF')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (DigitalLetter $record): string => route('digital-letter.preview', $record))
                    ->openUrlInNewTab(),

                Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (DigitalLetter $record): string => route('digital-letter.download', $record))
                    ->openUrlInNewTab(),

                Action::make('generate_pdf')
                    ->label('Generate PDF')
                    ->icon('heroicon-o-document-plus')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (DigitalLetter $record, DigitalLetterPdfService $pdfService) {
                        try {
                            $pdfService->generate($record);

                            Notification::make()
                                ->title('PDF berhasil digenerate')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal generate PDF')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (DigitalLetter $record): bool => auth()->user()->canManage()),

                EditAction::make()
                    ->visible(fn (DigitalLetter $record): bool => auth()->user()->can('update', $record)),

                DeleteAction::make()
                    ->visible(fn (DigitalLetter $record): bool => auth()->user()->can('delete', $record)),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // If user is warga, only show their own letters
        $user = auth()->user();
        if ($user && $user->isWarga() && $user->resident) {
            return $query->where('resident_id', $user->resident->id);
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\DigitalLetterResource\Pages\ListDigitalLetters::route('/'),
            'create' => \App\Filament\Resources\DigitalLetterResource\Pages\CreateDigitalLetter::route('/create'),
            'edit' => \App\Filament\Resources\DigitalLetterResource\Pages\EditDigitalLetter::route('/{record}/edit'),
        ];
    }
}
