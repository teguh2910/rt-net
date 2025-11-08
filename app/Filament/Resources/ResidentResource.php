<?php

namespace App\Filament\Resources;

use App\Models\Resident;
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
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ResidentResource extends Resource
{
    protected static ?string $model = Resident::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $label = 'Data Warga';

    protected static ?string $navigationLabel = 'Data Warga';

    protected static string|UnitEnum|null $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Data Diri Warga')
                    ->description('Informasi dasar warga')
                    ->schema([
                        Forms\Components\TextInput::make('nik')
                            ->label('NIK')
                            ->required()
                            ->unique(ignorable: fn ($record) => $record)
                            ->maxLength(16),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('no_kk')
                            ->label('Nomor Kartu Keluarga')
                            ->maxLength(16),

                        Forms\Components\TextInput::make('phone_number')
                            ->label('Nomor HP')
                            ->tel()
                            ->required(),

                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->required()
                            ->rows(3),
                    ])->columns(2),

                Section::make('Status & Kepemilikan')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status Tempat Tinggal')
                            ->options([
                                'tetap' => 'Tetap',
                                'kontrak' => 'Kontrak',
                            ])
                            ->default('tetap')
                            ->required(),

                        Forms\Components\Toggle::make('is_head_of_family')
                            ->label('Kepala Keluarga')
                            ->inline(false),

                        Forms\Components\FileUpload::make('photo_path')
                            ->label('Foto')
                            ->image()
                            ->directory('residents/photos')
                            ->maxSize(5120),
                    ])->columns(2),

                Section::make('Akun Pengguna')
                    ->description('Hubungkan dengan akun pengguna aplikasi (opsional)')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pengguna')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Nomor HP')
                    ->copyable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state === 'tetap' ? 'success' : 'warning'),

                Tables\Columns\IconColumn::make('is_head_of_family')
                    ->label('Kepala Keluarga')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'tetap' => 'Tetap',
                        'kontrak' => 'Kontrak',
                    ]),

                Tables\Filters\TernaryFilter::make('is_head_of_family')
                    ->label('Kepala Keluarga'),
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // If user is warga, only show their own data
        $user = auth()->user();
        if ($user && $user->isWarga() && $user->resident) {
            return $query->where('id', $user->resident->id);
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ResidentResource\Pages\ListResidents::route('/'),
            'create' => \App\Filament\Resources\ResidentResource\Pages\CreateResident::route('/create'),
            'edit' => \App\Filament\Resources\ResidentResource\Pages\EditResident::route('/{record}/edit'),
        ];
    }
}
