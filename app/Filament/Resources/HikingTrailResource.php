<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HikingTrailResource\Pages;
use App\Models\HikingTrail;
use App\Services\GpxParserService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use RuntimeException;
use Throwable;

class HikingTrailResource extends Resource
{
    protected static ?string $model =
        HikingTrail::class;

    protected static ?string $navigationIcon =
        'lucide-footprints';

    protected static ?string $navigationLabel =
        'Jalur Pendakian';

    protected static ?string $modelLabel =
        'Jalur Pendakian';

    protected static ?string $pluralModelLabel =
        'Jalur Pendakian';

    protected static ?int $navigationSort =
        2;


    /*
    |--------------------------------------------------------------------------
    | PERMISSIONS
    |--------------------------------------------------------------------------
    */

    public static function canViewAny(): bool
    {
        return auth()
            ->user()
            ?->can(
                'trails.view'
            )
            ??
            false;
    }


    public static function canCreate(): bool
    {
        return auth()
            ->user()
            ?->can(
                'trails.create'
            )
            ??
            false;
    }


    public static function canView(
        $record
    ): bool {
        return static::canAccessRecord(
            $record,
            'trails.view'
        );
    }


    public static function canEdit(
        $record
    ): bool {
        return static::canAccessRecord(
            $record,
            'trails.update'
        );
    }


    public static function canDelete(
        $record
    ): bool {
        return static::canAccessRecord(
            $record,
            'trails.delete'
        );
    }


    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }


    /*
    |--------------------------------------------------------------------------
    | RECORD ACCESS
    |--------------------------------------------------------------------------
    */

    protected static function canAccessRecord(
        $record,
        string $permission
    ): bool {
        $user =
            auth()->user();


        if (! $user) {
            return false;
        }


        if (
            ! $user->can(
                $permission
            )
        ) {
            return false;
        }


        if (
            $user->hasRole(
                'admin'
            )
        ) {
            return true;
        }


        return $user
            ->mountains()
            ->whereKey(
                $record->mountain_id
            )
            ->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | QUERY LOCATION SCOPE
    |--------------------------------------------------------------------------
    */

    public static function getEloquentQuery(): Builder
    {
        $query =
            parent::getEloquentQuery()
                ->with([
                    'mountain',
                ]);


        $user =
            auth()->user();


        if (! $user) {
            return $query
                ->whereRaw(
                    '1 = 0'
                );
        }


        if (
            $user->hasRole(
                'admin'
            )
        ) {
            return $query;
        }


        return $query
            ->whereHas(
                'mountain.managers',
                function (
                    Builder $managerQuery
                ) use (
                    $user
                ) {
                    $managerQuery
                        ->where(
                            'users.id',
                            $user->id
                        );
                }
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    public static function form(
        Form $form
    ): Form {
        return $form
            ->schema([

                /*
                |--------------------------------------------------------------------------
                | LEFT
                |--------------------------------------------------------------------------
                */

                Forms\Components\Group::make()
                    ->schema([

                        /*
                        |--------------------------------------------------------------------------
                        | MAIN INFORMATION
                        |--------------------------------------------------------------------------
                        */

                        Forms\Components\Section::make(
                            'Informasi Utama'
                        )
                            ->schema([

                                Forms\Components\Select::make(
                                    'mountain_id'
                                )
                                    ->label(
                                        'Gunung'
                                    )
                                    ->relationship(
                                        name: 'mountain',
                                        titleAttribute: 'name',
                                        modifyQueryUsing:
                                            function (
                                                Builder $query
                                            ): Builder {
                                                return static::filterMountainQuery(
                                                    $query
                                                );
                                            }
                                    )
                                    ->required()
                                    ->searchable()
                                    ->preload(),


                                Forms\Components\TextInput::make(
                                    'name'
                                )
                                    ->label(
                                        'Nama Jalur'
                                    )
                                    ->required()
                                    ->maxLength(
                                        255
                                    ),


                                Forms\Components\Select::make(
                                    'difficulty'
                                )
                                    ->label(
                                        'Tingkat Kesulitan'
                                    )
                                    ->options([
                                        'easy' =>
                                            'Mudah (Easy)',

                                        'medium' =>
                                            'Sedang (Medium)',

                                        'hard' =>
                                            'Sulit (Hard)',

                                        'extreme' =>
                                            'Ekstrem',
                                    ])
                                    ->required(),


                                Forms\Components\Select::make(
                                    'status'
                                )
                                    ->label(
                                        'Status'
                                    )
                                    ->options([
                                        'open' =>
                                            'Buka (Open)',

                                        'closed' =>
                                            'Tutup (Closed)',

                                        'maintenance' =>
                                            'Perbaikan',

                                        'warning' =>
                                            'Peringatan',
                                    ])
                                    ->default(
                                        'open'
                                    )
                                    ->required(),


                                Forms\Components\Toggle::make(
                                    'is_active'
                                )
                                    ->label(
                                        'Aktif'
                                    )
                                    ->default(
                                        true
                                    ),

                            ])
                            ->columns(
                                2
                            ),


                        /*
                        |--------------------------------------------------------------------------
                        | GPX
                        |--------------------------------------------------------------------------
                        */

                        Forms\Components\Section::make(
                            'Unggah GPX & Peta Jalur'
                        )
                            ->description(
                                'Upload file GPX. BaliHiking akan membaca jalur, jarak dan elevasi secara otomatis.'
                            )
                            ->schema([

                                Forms\Components\FileUpload::make(
                                    'gpx_file_path'
                                )
                                    ->label(
                                        'File GPX Trail'
                                    )
                                    ->disk(
                                        'public'
                                    )
                                    ->directory(
                                        'gpx-files'
                                    )
                                    ->acceptedFileTypes([
                                        'application/gpx+xml',
                                        'application/xml',
                                        'text/xml',
                                        'text/plain',
                                        'application/octet-stream',
                                    ])
                                    ->maxSize(
                                        10240
                                    )
                                    ->downloadable()
                                    ->openable()
                                    ->live()
                                    ->afterStateUpdated(
                                        function (
                                            $state,
                                            Get $get,
                                            Set $set
                                        ): void {
                                            if (! $state) {
                                                $set(
                                                    'coordinates',
                                                    null
                                                );

                                                $set(
                                                    'map_geojson',
                                                    null
                                                );

                                                return;
                                            }


                                            try {
                                                $content =
                                                    static::readGpxState(
                                                        $state
                                                    );


                                                $parsed =
                                                    GpxParserService::parseFromContent(
                                                        $content
                                                    );


                                                /*
                                                |--------------------------------------------------------------------------
                                                | COORDINATES
                                                |--------------------------------------------------------------------------
                                                */

                                                $set(
                                                    'coordinates',
                                                    $parsed[
                                                        'coordinates'
                                                    ]
                                                );


                                                /*
                                                |--------------------------------------------------------------------------
                                                | GEOJSON
                                                |--------------------------------------------------------------------------
                                                */

                                                $set(
                                                    'map_geojson',
                                                    $parsed[
                                                        'map_geojson'
                                                    ]
                                                );


                                                /*
                                                |--------------------------------------------------------------------------
                                                | STATISTICS
                                                |--------------------------------------------------------------------------
                                                */

                                                $set(
                                                    'distance_km',
                                                    $parsed[
                                                        'distance_km'
                                                    ]
                                                );


                                                $set(
                                                    'max_elevation',
                                                    $parsed[
                                                        'max_elevation'
                                                    ]
                                                );


                                                $set(
                                                    'min_elevation',
                                                    $parsed[
                                                        'min_elevation'
                                                    ]
                                                );


                                                /*
                                                |--------------------------------------------------------------------------
                                                | GPX NAME
                                                |--------------------------------------------------------------------------
                                                */

                                                if (
                                                    blank(
                                                        $get(
                                                            'name'
                                                        )
                                                    )
                                                    &&
                                                    filled(
                                                        $parsed[
                                                            'name'
                                                        ]
                                                    )
                                                ) {
                                                    $set(
                                                        'name',
                                                        $parsed[
                                                            'name'
                                                        ]
                                                    );
                                                }


                                                Notification::make()
                                                    ->title(
                                                        'GPX berhasil dibaca'
                                                    )
                                                    ->body(
                                                        number_format(
                                                            $parsed[
                                                                'distance_km'
                                                            ],
                                                            2
                                                        )
                                                        .
                                                        ' km • '
                                                        .
                                                        $parsed[
                                                            'points_count'
                                                        ]
                                                        .
                                                        ' titik GPS'
                                                    )
                                                    ->success()
                                                    ->send();

                                            } catch (
                                                Throwable $exception
                                            ) {
                                                Log::error(
                                                    'BaliHiking GPX parsing error',
                                                    [
                                                        'message' =>
                                                            $exception
                                                                ->getMessage(),

                                                        'class' =>
                                                            get_class(
                                                                $exception
                                                            ),
                                                    ]
                                                );


                                                Notification::make()
                                                    ->title(
                                                        'GPX gagal dibaca'
                                                    )
                                                    ->body(
                                                        $exception
                                                            ->getMessage()
                                                    )
                                                    ->danger()
                                                    ->persistent()
                                                    ->send();
                                            }
                                        }
                                    ),


                                /*
                                |--------------------------------------------------------------------------
                                | PERSISTED TRACK
                                |--------------------------------------------------------------------------
                                */

                                Forms\Components\Hidden::make(
                                    'coordinates'
                                )
                                    ->dehydrated()
                                    ->live(),


                                Forms\Components\Hidden::make(
                                    'map_geojson'
                                )
                                    ->dehydrated()
                                    ->live(),


                                /*
                                |--------------------------------------------------------------------------
                                | MAP
                                |--------------------------------------------------------------------------
                                */

                                Forms\Components\ViewField::make(
                                    'gpx_map_preview'
                                )
                                    ->label(
                                        ''
                                    )
                                    ->view(
                                        'filament.forms.components.gpx-map'
                                    )
                                    ->viewData(
                                        function (
                                            Get $get
                                        ): array {
                                            return [
                                                'coordinates' =>
                                                    $get(
                                                        'coordinates'
                                                    ),

                                                'mapGeoJson' =>
                                                    $get(
                                                        'map_geojson'
                                                    ),
                                            ];
                                        }
                                    )
                                    ->columnSpanFull(),

                            ]),

                    ])
                    ->columnSpan(
                        2
                    ),


                /*
                |--------------------------------------------------------------------------
                | RIGHT
                |--------------------------------------------------------------------------
                */

                Forms\Components\Group::make()
                    ->schema([

                        Forms\Components\Section::make(
                            'Statistik Fisik'
                        )
                            ->schema([

                                Forms\Components\TextInput::make(
                                    'distance_km'
                                )
                                    ->label(
                                        'Jarak'
                                    )
                                    ->numeric()
                                    ->suffix(
                                        'km'
                                    ),


                                Forms\Components\TextInput::make(
                                    'estimated_time_hours'
                                )
                                    ->label(
                                        'Estimasi Waktu'
                                    )
                                    ->numeric()
                                    ->suffix(
                                        'Jam'
                                    ),


                                Forms\Components\TextInput::make(
                                    'max_elevation'
                                )
                                    ->label(
                                        'Elevasi Max'
                                    )
                                    ->numeric()
                                    ->suffix(
                                        'm'
                                    ),


                                Forms\Components\TextInput::make(
                                    'min_elevation'
                                )
                                    ->label(
                                        'Elevasi Min'
                                    )
                                    ->numeric()
                                    ->suffix(
                                        'm'
                                    ),

                            ]),

                    ])
                    ->columnSpan(
                        1
                    ),

            ])
            ->columns(
                3
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    public static function table(
        Table $table
    ): Table {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make(
                    'mountain.name'
                )
                    ->label(
                        'Gunung'
                    )
                    ->icon(
                        'lucide-mountain-snow'
                    )
                    ->sortable()
                    ->searchable()
                    ->weight(
                        'bold'
                    ),


                Tables\Columns\TextColumn::make(
                    'name'
                )
                    ->label(
                        'Nama Jalur'
                    )
                    ->searchable()
                    ->sortable()
                    ->description(
                        fn (
                            HikingTrail $record
                        ): string =>
                            'Est. Waktu: '
                            .
                            (
                                $record
                                    ->estimated_time_hours
                                ??
                                '-'
                            )
                            .
                            ' Jam'
                    ),


                Tables\Columns\TextColumn::make(
                    'distance_km'
                )
                    ->label(
                        'Jarak Total'
                    )
                    ->numeric(
                        decimalPlaces: 2
                    )
                    ->suffix(
                        ' km'
                    )
                    ->sortable()
                    ->alignCenter(),


                Tables\Columns\TextColumn::make(
                    'difficulty'
                )
                    ->label(
                        'Kesulitan'
                    )
                    ->badge()
                    ->color(
                        fn (
                            string $state
                        ): string =>
                            match (
                                $state
                            ) {
                                'easy' =>
                                    'success',

                                'medium' =>
                                    'warning',

                                'hard' =>
                                    'danger',

                                'extreme' =>
                                    'gray',

                                default =>
                                    'info',
                            }
                    )
                    ->formatStateUsing(
                        fn (
                            string $state
                        ): string =>
                            ucfirst(
                                $state
                            )
                    )
                    ->sortable(),


                Tables\Columns\TextColumn::make(
                    'checkpoints_count'
                )
                    ->counts(
                        'checkpoints'
                    )
                    ->label(
                        'Total Pos'
                    )
                    ->badge()
                    ->color(
                        'purple'
                    )
                    ->alignCenter(),


                Tables\Columns\TextColumn::make(
                    'status'
                )
                    ->label(
                        'Status'
                    )
                    ->badge()
                    ->color(
                        fn (
                            string $state
                        ): string =>
                            match (
                                $state
                            ) {
                                'open' =>
                                    'success',

                                'closed' =>
                                    'danger',

                                'maintenance' =>
                                    'warning',

                                'warning' =>
                                    'warning',

                                default =>
                                    'gray',
                            }
                    )
                    ->formatStateUsing(
                        fn (
                            string $state
                        ): string =>
                            match (
                                $state
                            ) {
                                'open' =>
                                    'Buka',

                                'closed' =>
                                    'Tutup',

                                'maintenance' =>
                                    'Perbaikan',

                                'warning' =>
                                    'Peringatan',

                                default =>
                                    ucfirst(
                                        $state
                                    ),
                            }
                    )
                    ->sortable(),

            ])


            ->defaultSort(
                'created_at',
                'desc'
            )


            ->filters([

                Tables\Filters\SelectFilter::make(
                    'status'
                )
                    ->label(
                        'Status Jalur'
                    )
                    ->options([
                        'open' =>
                            'Buka',

                        'closed' =>
                            'Tutup',

                        'maintenance' =>
                            'Perbaikan',

                        'warning' =>
                            'Peringatan',
                    ]),


                Tables\Filters\SelectFilter::make(
                    'mountain_id'
                )
                    ->label(
                        'Berdasarkan Gunung'
                    )
                    ->relationship(
                        name: 'mountain',
                        titleAttribute: 'name',
                        modifyQueryUsing:
                            fn (
                                Builder $query
                            ): Builder =>
                                static::filterMountainQuery(
                                    $query
                                )
                    )
                    ->searchable()
                    ->preload(),

            ])


            ->actions([

                /*
                |--------------------------------------------------------------------------
                | PREVIEW
                |--------------------------------------------------------------------------
                */

                Tables\Actions\Action::make(
                    'preview'
                )
                    ->label(
                        'Pratinjau'
                    )
                    ->icon(
                        'heroicon-o-eye'
                    )
                    ->color(
                        'info'
                    )
                    ->modalHeading(
                        'Pratinjau Tampilan Pendaki'
                    )
                    ->modalWidth(
                        '5xl'
                    )
                    ->modalSubmitAction(
                        false
                    )
                    ->modalCancelActionLabel(
                        'Tutup'
                    )
                    ->modalContent(
                        function (
                            HikingTrail $record
                        ) {
                            /*
                            |--------------------------------------------------------------------------
                            | BACKFILL OLD GPX AUTOMATICALLY
                            |--------------------------------------------------------------------------
                            */

                            $record =
                                static::syncGpxFromStoredFile(
                                    $record,
                                    false
                                );


                            return view(
                                'filament.resources.hiking-trail.preview',
                                [
                                    'record' =>
                                        $record->load([
                                            'mountain',
                                            'checkpoints',
                                        ]),
                                ]
                            );
                        }
                    ),


                Tables\Actions\EditAction::make(),


                Tables\Actions\DeleteAction::make(),

            ])


            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make(),

                ]),

            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | MOUNTAIN FILTER
    |--------------------------------------------------------------------------
    */

    protected static function filterMountainQuery(
        Builder $query
    ): Builder {
        $user =
            auth()->user();


        if (! $user) {
            return $query
                ->whereRaw(
                    '1 = 0'
                );
        }


        if (
            $user->hasRole(
                'admin'
            )
        ) {
            return $query
                ->orderBy(
                    'name'
                );
        }


        return $query
            ->whereHas(
                'managers',
                function (
                    Builder $managerQuery
                ) use (
                    $user
                ) {
                    $managerQuery
                        ->where(
                            'users.id',
                            $user->id
                        );
                }
            )
            ->orderBy(
                'name'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | READ GPX UPLOAD STATE
    |--------------------------------------------------------------------------
    */

    protected static function readGpxState(
        mixed $state
    ): string {
        /*
        |--------------------------------------------------------------------------
        | ARRAY STATE
        |--------------------------------------------------------------------------
        */

        if (
            is_array(
                $state
            )
        ) {
            $state =
                collect(
                    $state
                )
                    ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | TEMP UPLOAD
        |--------------------------------------------------------------------------
        */

        if (
            $state instanceof
            TemporaryUploadedFile
        ) {
            $path =
                $state->getRealPath();


            if (
                ! $path
                ||
                ! is_file(
                    $path
                )
            ) {
                throw new RuntimeException(
                    'File GPX sementara tidak ditemukan.'
                );
            }


            $content =
                file_get_contents(
                    $path
                );


            if ($content === false) {
                throw new RuntimeException(
                    'File GPX tidak dapat dibaca.'
                );
            }


            return $content;
        }


        /*
        |--------------------------------------------------------------------------
        | STORED FILE
        |--------------------------------------------------------------------------
        */

        if (
            is_string(
                $state
            )
        ) {
            $disk =
                Storage::disk(
                    'public'
                );


            if (
                $disk->exists(
                    $state
                )
            ) {
                return $disk->get(
                    $state
                );
            }


            if (
                is_file(
                    $state
                )
            ) {
                $content =
                    file_get_contents(
                        $state
                    );


                if ($content !== false) {
                    return $content;
                }
            }
        }


        throw new RuntimeException(
            'File GPX tidak ditemukan atau belum selesai diupload.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SYNC STORED GPX
    |--------------------------------------------------------------------------
    |
    | Digunakan untuk:
    |
    | - GPX lama
    | - edit existing trail
    | - preview existing trail
    |--------------------------------------------------------------------------
    */

    public static function syncGpxFromStoredFile(
        HikingTrail $record,
        bool $force = false
    ): HikingTrail {
        if (
            ! $record->gpx_file_path
        ) {
            return $record;
        }


        /*
        |--------------------------------------------------------------------------
        | Kalau data sudah lengkap, tidak perlu parse ulang.
        |--------------------------------------------------------------------------
        */

        if (
            ! $force
            &&
            ! empty(
                $record->coordinates
            )
            &&
            ! empty(
                $record->map_geojson
            )
        ) {
            return $record;
        }


        $disk =
            Storage::disk(
                'public'
            );


        if (
            ! $disk->exists(
                $record->gpx_file_path
            )
        ) {
            Log::warning(
                'GPX stored file not found',
                [
                    'trail_id' =>
                        $record->id,

                    'path' =>
                        $record
                            ->gpx_file_path,
                ]
            );


            return $record;
        }


        try {
            $parsed =
                GpxParserService::parseFromContent(
                    $disk->get(
                        $record->gpx_file_path
                    )
                );


            $record->forceFill([
                'coordinates' =>
                    $parsed[
                        'coordinates'
                    ],

                'map_geojson' =>
                    $parsed[
                        'map_geojson'
                    ],

                'distance_km' =>
                    $parsed[
                        'distance_km'
                    ],

                'max_elevation' =>
                    $parsed[
                        'max_elevation'
                    ],

                'min_elevation' =>
                    $parsed[
                        'min_elevation'
                    ],
            ]);


            $record->saveQuietly();


            return $record
                ->refresh();

        } catch (
            Throwable $exception
        ) {
            Log::error(
                'Failed syncing GPX',
                [
                    'trail_id' =>
                        $record->id,

                    'path' =>
                        $record
                            ->gpx_file_path,

                    'message' =>
                        $exception
                            ->getMessage(),
                ]
            );


            return $record;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public static function getRelations(): array
    {
        return [];
    }


    /*
    |--------------------------------------------------------------------------
    | PAGES
    |--------------------------------------------------------------------------
    */

    public static function getPages(): array
    {
        return [

            'index' =>
                Pages\ListHikingTrails::route(
                    '/'
                ),

            'create' =>
                Pages\CreateHikingTrail::route(
                    '/create'
                ),

            'edit' =>
                Pages\EditHikingTrail::route(
                    '/{record}/edit'
                ),

        ];
    }
}