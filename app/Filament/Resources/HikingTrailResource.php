<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HikingTrailResource\Pages;
use App\Models\HikingTrail;
use App\Services\GpxParserService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HikingTrailResource extends Resource
{
    protected static ?string $model = HikingTrail::class;

    protected static ?string $navigationIcon = 'lucide-footprints';

    protected static ?string $navigationLabel = 'Jalur Pendakian';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Utama')
                            ->schema([
                                Forms\Components\Select::make('mountain_id')
                                    ->relationship('mountain', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Jalur')
                                    ->required(),

                                Forms\Components\Select::make('difficulty')
                                    ->label('Tingkat Kesulitan')
                                    ->options([
                                        'easy' => 'Mudah (Easy)',
                                        'medium' => 'Sedang (Medium)',
                                        'hard' => 'Sulit (Hard)',
                                    ])
                                    ->required(),

                                Forms\Components\Select::make('status')
                                    ->options([
                                        'open' => 'Buka (Open)',
                                        'closed' => 'Tutup (Closed)',
                                        'warning' => 'Peringatan (Warning)',
                                    ])
                                    ->default('open')
                                    ->required(),
                            ])->columns(2),

                        Forms\Components\Section::make('Unggah GPX & Peta Jalur')
                            ->schema([
                                Forms\Components\FileUpload::make('gpx_file_path')
                                    ->label('File GPX Trail')
                                    ->disk('public')
                                    ->directory('gpx-files')
                                    ->acceptedFileTypes(['application/gpx+xml', 'text/xml', '.gpx'])
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if (! $state) {
                                            return;
                                        }

                                        $path = is_string($state) ? $state : $state->getRealPath();

                                        try {
                                            $parsed = GpxParserService::parseFromContent(
                                                is_string($state)
                                                    ? \Storage::disk('public')->get($state)
                                                    : file_get_contents($path)
                                            );

                                            $set('coordinates', $parsed['coordinates']);
                                            $set('distance_km', $parsed['distance_km']);
                                            $set('max_elevation', $parsed['max_elevation']);
                                            $set('min_elevation', $parsed['min_elevation']);

                                            if (empty($get('name'))) {
                                                $set('name', $parsed['name']);
                                            }
                                        } catch (\Exception $e) {
                                            // Handling error parser
                                        }
                                    }),

                                Forms\Components\Hidden::make('coordinates')->live(),

                                Forms\Components\ViewField::make('map_preview')
                                    ->view('filament.forms.components.gpx-map')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Statistik Fisik')
                            ->schema([
                                Forms\Components\TextInput::make('distance_km')
                                    ->label('Jarak (km)')
                                    ->numeric()
                                    ->suffix('km'),

                                Forms\Components\TextInput::make('estimated_time_hours')
                                    ->label('Estimasi Waktu')
                                    ->numeric()
                                    ->suffix('Jam'),

                                Forms\Components\TextInput::make('max_elevation')
                                    ->label('Elevasi Max')
                                    ->numeric()
                                    ->suffix('m'),

                                Forms\Components\TextInput::make('min_elevation')
                                    ->label('Elevasi Min')
                                    ->numeric()
                                    ->suffix('m'),
                            ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Gunung + Lokasi
                Tables\Columns\TextColumn::make('mountain.name')
                    ->label('Gunung')
                    ->icon('lucide-mountain-snow')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                // 2. Nama Jalur Pendakian
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Jalur')
                    ->searchable()
                    ->sortable()
                    ->description(fn (HikingTrail $record): string => "Est. Waktu: {$record->estimated_time_hours} Jam"),

                // 3. Jarak Total (km)
                Tables\Columns\TextColumn::make('distance_km')
                    ->label('Jarak Total')
                    ->numeric(decimalPlaces: 1)
                    ->suffix(' km')
                    ->sortable()
                    ->alignCenter(),

                // 4. Kesulitan Jalur (Badge Warna-warni)
                Tables\Columns\TextColumn::make('difficulty')
                    ->label('Kesulitan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'easy' => 'success',
                        'medium' => 'warning',
                        'hard' => 'danger',
                        'extreme' => 'gray',
                        default => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),

                // 5. Total Checkpoint / Pos Pendakian
                Tables\Columns\TextColumn::make('checkpoints_count')
                    ->counts('checkpoints')
                    ->label('Total Pos')
                    ->badge()
                    ->color('purple')
                    ->alignCenter(),

                // 6. Status Jalur (Buka / Tutup) - Format Filament v3
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'success',
                        'closed' => 'danger',
                        'maintenance' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'Buka',
                        'closed' => 'Tutup',
                        'maintenance' => 'Perbaikan',
                        default => ucfirst($state),
                    })
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')

            // Filter Data Berdasarkan Status & Gunung
            ->filters([
            Tables\Filters\SelectFilter::make('status')
                    ->label('Status Jalur')
                    ->options([
                        'open' => 'Buka (Open)',
                        'closed' => 'Tutup (Closed)',
                        'maintenance' => 'Perbaikan',
                    ]),
            Tables\Filters\SelectFilter::make('mountain_id')
                    ->label('Berdasarkan Gunung')
                    ->relationship('mountain', 'name')
                    ->searchable()
                    ->preload(),
        ])

            // Aksi Baris Tabel
            ->actions([
            // Action Pratinjau Pendaki (Modal Preview)
            Tables\Actions\Action::make('preview')
                    ->label('Pratinjau')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading('Pratinjau Tampilan Pendaki')
                    ->modalWidth('4xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (HikingTrail $record) => view(
                        'filament.resources.hiking-trail.preview',
                        ['record' => $record->load(['mountain', 'checkpoints'])]
                    )),

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
            'index' => Pages\ListHikingTrails::route('/'),
            'create' => Pages\CreateHikingTrail::route('/create'),
            'edit' => Pages\EditHikingTrail::route('/{record}/edit'),
        ];
    }
}
