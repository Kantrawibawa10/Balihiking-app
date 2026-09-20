<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MountainResource\Pages;
use App\Models\Mountain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MountainResource extends Resource
{
    protected static ?string $model = Mountain::class;

    protected static ?string $navigationIcon = 'lucide-mountain';

    protected static ?string $navigationLabel = 'Data Gunung';

    protected static ?int $navigationSort = 1;

    /*
|--------------------------------------------------------------------------
| PERMISSIONS
|--------------------------------------------------------------------------
*/

    public static function canViewAny(): bool
    {
        return auth()
            ->user()
            ?->can('mountains.view')
            ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()
            ->user()
            ?->can('mountains.create')
            ?? false;
    }

    public static function canView($record): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if (! $user->can('mountains.view')) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        return $user
            ->mountains()
            ->whereKey($record->id)
            ->exists();
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if (! $user->can('mountains.update')) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        return $user
            ->mountains()
            ->whereKey($record->id)
            ->exists();
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Saya sarankan hapus Gunung hanya Admin.
        |--------------------------------------------------------------------------
        */

        return $user->hasRole('admin')
            && $user->can('mountains.delete');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    /*
    |--------------------------------------------------------------------------
    | LOCATION SCOPE
    |--------------------------------------------------------------------------
    */

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole('admin')) {
            return $query;
        }

        return $query->whereHas(
            'managers',
            function (Builder $managerQuery) use ($user) {
                $managerQuery->where(
                    'users.id',
                    $user->id
                );
            }
        );
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Kolom Kiri (Utama): Informasi Teks & Deskripsi (Lebar: 2 dari 3 kolom)
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Gunung')
                            ->description('Masukkan data dasar mengenai gunung.')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Gunung')
                                            ->placeholder('Contoh: Gunung Agung')
                                            ->required()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('location')
                                            ->label('Lokasi / Wilayah')
                                            ->placeholder('Contoh: Karangasem, Bali')
                                            ->prefixIcon('heroicon-o-map-pin')
                                            ->required()
                                            ->maxLength(255),
                                    ]),

                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi Singkat')
                                    ->placeholder('Tuliskan gambaran umum, daya tarik, atau sejarah singkat tentang gunung ini...')
                                    ->rows(5)
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('elevation_m')
                                    ->label('Ketinggian (MDPL)')
                                    ->numeric()
                                    ->nullable()
                                    ->suffix('mdpl')
                                    ->placeholder('Contoh: 3142')
                                    ->minValue(0)
                                    ->maxValue(10000),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                // Kolom Kanan (Samping): Media & Upload Gambar (Lebar: 1 dari 3 kolom)
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Gambar Sampul')
                            ->description('Unggah foto terbaik untuk gunung ini.')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\FileUpload::make('cover_image')
                                    ->label('Cover Image')
                                    ->image()
                                    ->imageEditor() // Fitur crop/rotate bawaan Filament v3
                                    ->directory('mountains/covers')
                                    ->disk('public')
                                    ->maxSize(5120) // Batas 5MB
                                    ->helperText('Format: JPG, PNG, WEBP. Maksimal 5MB.'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3); // Membagi seluruh form menjadi grid 3 kolom
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Cover Image dengan Tampilan Bulat/Square Bersetting Baik
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Foto')
                    ->circular() // Lebih estetis berbentuk lingkaran
                    ->size(45)
                    ->defaultImageUrl(url('/images/placeholder-mountain.png')),

                // 2. Nama Gunung + Subtitle Ketinggian (MDPL)
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Gunung')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Mountain $record): string => $record->elevation_m ? "⛰️ {$record->elevation_m} mdpl" : 'Ketinggian belum diset'),

                // 3. Lokasi / Wilayah
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi / Wilayah')
                    ->searchable()
                    ->icon('lucide-map-pin') // Pakai ikon Lucide yang aman dari error
                    ->iconColor('primary')
                    ->sortable(),

                // 4. Jumlah Jalur Pendakian Terhubung (Relasi count)
                Tables\Columns\TextColumn::make('hiking_trails_count')
                    ->counts('hikingTrails')
                    ->label('Total Jalur')
                    ->badge()
                    ->color('success')
                    ->alignCenter()
                    ->sortable(),

                // 5. Tanggal Pembuatan & Diperbarui
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name', 'asc')

            // Filter Pencarian di Atas Tabel
            ->filters([
                // Filter cepat gunung berdasarkan ketersediaan jalur
                Tables\Filters\Filter::make('has_trails')
                    ->label('Hanya Gunung dengan Jalur')
                    ->query(fn ($query) => $query->has('hikingTrails')),
            ])

            // Aksi Baris Tabel
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])

            // Aksi Masal
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListMountains::route('/'),
            'create' => Pages\CreateMountain::route('/create'),
            'edit' => Pages\EditMountain::route('/{record}/edit'),
        ];
    }
}
