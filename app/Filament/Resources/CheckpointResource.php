<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CheckpointResource\Pages;
use App\Models\Checkpoint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CheckpointResource extends Resource
{
    protected static ?string $model = Checkpoint::class;

    protected static ?string $navigationIcon = 'lucide-map-pin-check';

    protected static ?string $navigationLabel = 'Pos Checkpoint';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()
            ->user()
            ?->can('checkpoints.view')
            ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()
            ->user()
            ?->can('checkpoints.create')
            ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    public static function canView($record): bool
    {
        return static::canAccessRecord(
            $record,
            'checkpoints.view'
        );
    }

    public static function canEdit($record): bool
    {
        return static::canAccessRecord(
            $record,
            'checkpoints.update'
        );
    }

    public static function canDelete($record): bool
    {
        return static::canAccessRecord(
            $record,
            'checkpoints.delete'
        );
    }

    protected static function canAccessRecord(
        $record,
        string $permission
    ): bool {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if (! $user->can($permission)) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        $mountainId =
            $record
                ->hikingTrail
                ?->mountain_id;

        if (! $mountainId) {
            return false;
        }

        return $user
            ->mountains()
            ->whereKey($mountainId)
            ->exists();
    }

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
            'hikingTrail.mountain.managers',
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
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Checkpoint')
                            ->description('Pilih jalur pendakian dan tentukan detail titik pemberhentian.')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                // Relasi Dropdown ke Hiking Trail
                                Forms\Components\Select::make('hiking_trail_id')
                                    ->label('Jalur Pendakian (Hiking Trail)')
                                    ->relationship('hikingTrail', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->helperText('Pilih rute jalur tempat checkpoint ini berada.')
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Checkpoint / Pos')
                                    ->placeholder('Contoh: Pos 1 - Sumber Air / Pos Bayangan')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Select::make('type')
                                    ->label('Tipe / Jenis Checkpoint')
                                    ->options([
                                        'pos' => 'Pos Pendakian / Shelter',
                                        'water_source' => 'Mata Air (Water Source)',
                                        'camp_site' => 'Area Camping (Camp Site)',
                                        'peak' => 'Puncak (Peak / Summit)',
                                        'junction' => 'Persimpangan Jalur (Junction)',
                                        'danger' => 'Titik Bahaya / Rawan',
                                    ])
                                    ->required()
                                    ->native(false),
                            ])->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Lokasi & Geografis')
                            ->description('Koordinat posisi GPS checkpoint.')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->placeholder('-8.3672838')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->placeholder('115.4652620')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('elevation_m')
                                    ->label('Elevasi (Ketinggian)')
                                    ->placeholder('1500')
                                    ->numeric()
                                    ->suffix('mdpl')
                                    ->helperText('Meter di atas permukaan laut.'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Relasi Jalur Pendakian + Gunung
                Tables\Columns\TextColumn::make('hikingTrail.name')
                    ->label('Jalur Pendakian')
                    ->icon('lucide-footprints')
                    ->iconColor('primary')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Checkpoint $record): string => $record->hikingTrail?->mountain?->name ? "⛰️ {$record->hikingTrail->mountain->name}" : '-'),

                // 2. Nama Pos / Checkpoint
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pos')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                // 3. Tipe Pos (Badge Warna-warni)
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe Pos')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'basecamp', 'start_point' => 'info',
                        'shelter', 'pos' => 'success',
                        'water_source' => 'cyan',
                        'campsite' => 'warning',
                        'peak' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'basecamp' => 'Basecamp',
                        'start_point' => 'Titik Awal',
                        'water_source' => 'Mata Air',
                        'campsite' => 'Area Camp',
                        'shelter' => 'Shelter / Pos',
                        'peak' => 'Puncak',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->sortable(),

                // 4. Ketinggian / Elevasi (mdpl)
                Tables\Columns\TextColumn::make('elevation_m')
                    ->label('Ketinggian')
                    ->numeric()
                    ->suffix(' mdpl')
                    ->sortable()
                    ->alignCenter(),

                // 5. Koordinat Lokasi + Link Google Maps
                Tables\Columns\TextColumn::make('coordinates')
                    ->label('Koordinat (Lat, Lng)')
                    ->state(fn (Checkpoint $record): string => "{$record->latitude}, {$record->longitude}")
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('gray')
                    ->url(fn (Checkpoint $record): string => "https://www.google.com/maps/search/?api=1&query={$record->latitude},{$record->longitude}")
                    ->openUrlInNewTab()
                    ->toggleable(),

                // 6. Tanggal Pembuatan
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name', 'asc')

            // Filter Data Berdasarkan Jalur & Tipe Pos
            ->filters([
                Tables\Filters\SelectFilter::make('hiking_trail_id')
                    ->label('Berdasarkan Jalur')
                    ->relationship('hikingTrail', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Berdasarkan Tipe Pos')
                    ->options([
                        'basecamp' => 'Basecamp',
                        'start_point' => 'Titik Awal',
                        'shelter' => 'Shelter / Pos',
                        'water_source' => 'Mata Air',
                        'campsite' => 'Area Camp',
                        'peak' => 'Puncak',
                    ]),
            ])

            // Tombol Aksi Baris
            ->actions([
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
            'index' => Pages\ListCheckpoints::route('/'),
            'create' => Pages\CreateCheckpoint::route('/create'),
            'edit' => Pages\EditCheckpoint::route('/{record}/edit'),
        ];
    }
}
