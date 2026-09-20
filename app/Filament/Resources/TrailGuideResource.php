<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrailGuideResource\Pages;
use App\Models\TrailGuide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TrailGuideResource extends Resource
{
    protected static ?string $model =
        TrailGuide::class;

    protected static ?string $navigationIcon =
        'heroicon-o-shield-check';

    protected static ?string $navigationLabel =
        'Panduan & Keamanan';

    protected static ?string $modelLabel =
        'Panduan';

    protected static ?string $pluralModelLabel =
        'Panduan & Keamanan';

    protected static ?string $navigationGroup =
        'Master Data';

    protected static ?int $navigationSort =
        4;


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

                Forms\Components\Section::make(
                    'Informasi Panduan'
                )
                    ->description(
                        'Kelola panduan, informasi keamanan, perlengkapan dan informasi darurat pada jalur pendakian.'
                    )
                    ->schema([

                        /*
                        |--------------------------------------------------------------------------
                        | JALUR PENDAKIAN
                        |--------------------------------------------------------------------------
                        */

                        Forms\Components\Select::make(
                            'hiking_trail_id'
                        )
                            ->label(
                                'Jalur Pendakian'
                            )
                            ->relationship(
                                name: 'hikingTrail',
                                titleAttribute: 'name',
                                modifyQueryUsing:
                                    function (
                                        Builder $query
                                    ): Builder {

                                        return static::filterTrailQueryByUser(
                                            $query
                                        );
                                    }
                            )
                            ->getOptionLabelFromRecordUsing(
                                function (
                                    $record
                                ): string {

                                    $mountainName =
                                        $record
                                            ->mountain
                                            ?->name
                                        ??
                                        'Gunung';

                                    return
                                        $mountainName
                                        .
                                        ' - '
                                        .
                                        $record->name;
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->required(),


                        /*
                        |--------------------------------------------------------------------------
                        | TYPE
                        |--------------------------------------------------------------------------
                        */

                        Forms\Components\Select::make(
                            'type'
                        )
                            ->label(
                                'Jenis Informasi'
                            )
                            ->options([

                                'guide' =>
                                    'Panduan Perjalanan',

                                'safety' =>
                                    'Informasi Keamanan',

                                'warning' =>
                                    'Peringatan',

                                'equipment' =>
                                    'Perlengkapan',

                                'emergency' =>
                                    'Informasi Darurat',

                            ])
                            ->required()
                            ->native(
                                false
                            ),


                        /*
                        |--------------------------------------------------------------------------
                        | TITLE
                        |--------------------------------------------------------------------------
                        */

                        Forms\Components\TextInput::make(
                            'title'
                        )
                            ->label(
                                'Judul'
                            )
                            ->placeholder(
                                'Contoh: Persiapan Sebelum Mendaki'
                            )
                            ->required()
                            ->maxLength(
                                255
                            )
                            ->columnSpanFull(),


                        /*
                        |--------------------------------------------------------------------------
                        | CONTENT
                        |--------------------------------------------------------------------------
                        */

                        Forms\Components\Textarea::make(
                            'content'
                        )
                            ->label(
                                'Isi Informasi'
                            )
                            ->placeholder(
                                'Masukkan isi panduan atau informasi keamanan...'
                            )
                            ->required()
                            ->rows(
                                8
                            )
                            ->columnSpanFull(),


                        /*
                        |--------------------------------------------------------------------------
                        | SORT ORDER
                        |--------------------------------------------------------------------------
                        */

                        Forms\Components\TextInput::make(
                            'sort_order'
                        )
                            ->label(
                                'Urutan'
                            )
                            ->numeric()
                            ->default(
                                0
                            )
                            ->minValue(
                                0
                            )
                            ->required(),


                        /*
                        |--------------------------------------------------------------------------
                        | ACTIVE
                        |--------------------------------------------------------------------------
                        */

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

            ]);
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

                /*
                |--------------------------------------------------------------------------
                | MOUNTAIN
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make(
                    'hikingTrail.mountain.name'
                )
                    ->label(
                        'Gunung'
                    )
                    ->searchable()
                    ->sortable()
                    ->placeholder(
                        '-'
                    ),


                /*
                |--------------------------------------------------------------------------
                | TRAIL
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make(
                    'hikingTrail.name'
                )
                    ->label(
                        'Jalur'
                    )
                    ->searchable()
                    ->sortable()
                    ->placeholder(
                        '-'
                    ),


                /*
                |--------------------------------------------------------------------------
                | TYPE
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make(
                    'type'
                )
                    ->label(
                        'Jenis'
                    )
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            ?string $state
                        ): string =>
                            match (
                                $state
                            ) {

                                'guide' =>
                                    'Panduan',

                                'safety' =>
                                    'Keamanan',

                                'warning' =>
                                    'Peringatan',

                                'equipment' =>
                                    'Perlengkapan',

                                'emergency' =>
                                    'Darurat',

                                default =>
                                    ucfirst(
                                        (string)
                                        $state
                                    ),
                            }
                    )
                    ->color(
                        fn (
                            ?string $state
                        ): string =>
                            match (
                                $state
                            ) {

                                'guide' =>
                                    'info',

                                'safety' =>
                                    'success',

                                'warning' =>
                                    'warning',

                                'equipment' =>
                                    'gray',

                                'emergency' =>
                                    'danger',

                                default =>
                                    'gray',
                            }
                    ),


                /*
                |--------------------------------------------------------------------------
                | TITLE
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make(
                    'title'
                )
                    ->label(
                        'Judul'
                    )
                    ->searchable()
                    ->weight(
                        'bold'
                    )
                    ->limit(
                        45
                    ),


                /*
                |--------------------------------------------------------------------------
                | CONTENT
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make(
                    'content'
                )
                    ->label(
                        'Isi'
                    )
                    ->limit(
                        55
                    )
                    ->tooltip(
                        fn (
                            TrailGuide $record
                        ): string =>
                            $record->content
                    ),


                /*
                |--------------------------------------------------------------------------
                | SORT
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make(
                    'sort_order'
                )
                    ->label(
                        'Urutan'
                    )
                    ->sortable(),


                /*
                |--------------------------------------------------------------------------
                | ACTIVE
                |--------------------------------------------------------------------------
                */

                Tables\Columns\IconColumn::make(
                    'is_active'
                )
                    ->label(
                        'Aktif'
                    )
                    ->boolean(),

            ])


            /*
            |--------------------------------------------------------------------------
            | DEFAULT SORT
            |--------------------------------------------------------------------------
            */

            ->defaultSort(
                'sort_order'
            )


            /*
            |--------------------------------------------------------------------------
            | FILTERS
            |--------------------------------------------------------------------------
            */

            ->filters([

                Tables\Filters\SelectFilter::make(
                    'type'
                )
                    ->label(
                        'Jenis'
                    )
                    ->options([

                        'guide' =>
                            'Panduan Perjalanan',

                        'safety' =>
                            'Informasi Keamanan',

                        'warning' =>
                            'Peringatan',

                        'equipment' =>
                            'Perlengkapan',

                        'emergency' =>
                            'Informasi Darurat',

                    ]),


                Tables\Filters\SelectFilter::make(
                    'hiking_trail_id'
                )
                    ->label(
                        'Jalur'
                    )
                    ->relationship(
                        name: 'hikingTrail',
                        titleAttribute: 'name',
                        modifyQueryUsing:
                            function (
                                Builder $query
                            ): Builder {

                                return static::filterTrailQueryByUser(
                                    $query
                                );
                            }
                    )
                    ->searchable()
                    ->preload(),

            ])


            /*
            |--------------------------------------------------------------------------
            | ACTIONS
            |--------------------------------------------------------------------------
            */

            ->actions([

                Tables\Actions\ViewAction::make()
                    ->label(
                        'Lihat'
                    )
                    ->visible(
                        fn (
                            TrailGuide $record
                        ): bool =>
                            static::canView(
                                $record
                            )
                    ),


                Tables\Actions\EditAction::make()
                    ->label(
                        'Edit'
                    )
                    ->visible(
                        fn (
                            TrailGuide $record
                        ): bool =>
                            static::canEdit(
                                $record
                            )
                    ),

            ])


            /*
            |--------------------------------------------------------------------------
            | BULK
            |--------------------------------------------------------------------------
            */

            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(
                            fn (): bool =>
                                auth()
                                    ->user()
                                    ?->hasRole(
                                        'admin'
                                    )
                                ?? false
                        ),

                ]),

            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | NAVIGATION PERMISSION
    |--------------------------------------------------------------------------
    */

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }


    /*
    |--------------------------------------------------------------------------
    | CAN VIEW ANY
    |--------------------------------------------------------------------------
    */

    public static function canViewAny(): bool
    {
        $user =
            auth()->user();


        if (
            ! $user
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $user->hasRole(
                'admin'
            )
        ) {
            return true;
        }


        return $user->can(
            'trail_guides.view'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CAN CREATE
    |--------------------------------------------------------------------------
    */

    public static function canCreate(): bool
    {
        $user =
            auth()->user();


        if (
            ! $user
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


        /*
        |--------------------------------------------------------------------------
        | Pengelola harus punya permission DAN setidaknya satu lokasi.
        |--------------------------------------------------------------------------
        */

        return
            $user->can(
                'trail_guides.create'
            )
            &&
            $user
                ->mountains()
                ->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | CAN VIEW RECORD
    |--------------------------------------------------------------------------
    */

    public static function canView(
        $record
    ): bool {
        return static::canAccessRecord(
            $record,
            'trail_guides.view'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CAN EDIT
    |--------------------------------------------------------------------------
    */

    public static function canEdit(
        $record
    ): bool {
        return static::canAccessRecord(
            $record,
            'trail_guides.update'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CAN DELETE
    |--------------------------------------------------------------------------
    */

    public static function canDelete(
        $record
    ): bool {
        return static::canAccessRecord(
            $record,
            'trail_guides.delete'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BULK DELETE
    |--------------------------------------------------------------------------
    */

    public static function canDeleteAny(): bool
    {
        $user =
            auth()->user();


        if (
            ! $user
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Untuk keamanan, bulk delete hanya Admin.
        |--------------------------------------------------------------------------
        */

        return $user->hasRole(
            'admin'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK RECORD LOCATION ACCESS
    |--------------------------------------------------------------------------
    */

    protected static function canAccessRecord(
        $record,
        string $permission
    ): bool {
        $user =
            auth()->user();


        if (
            ! $user
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $user->hasRole(
                'admin'
            )
        ) {
            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | PERMISSION
        |--------------------------------------------------------------------------
        */

        if (
            ! $user->can(
                $permission
            )
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | GET MOUNTAIN
        |--------------------------------------------------------------------------
        */

        $mountainId =
            $record
                ->hikingTrail
                ?->mountain_id;


        if (
            ! $mountainId
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | LOCATION ASSIGNMENT
        |--------------------------------------------------------------------------
        */

        return $user
            ->mountains()
            ->whereKey(
                $mountainId
            )
            ->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | RESOURCE QUERY
    |--------------------------------------------------------------------------
    |
    | Admin:
    | semua panduan.
    |
    | Pengelola:
    | hanya panduan pada gunung yang ditugaskan.
    |--------------------------------------------------------------------------
    */

    public static function getEloquentQuery(): Builder
    {
        $query =
            parent::getEloquentQuery()
                ->with([
                    'hikingTrail.mountain',
                ]);


        $user =
            auth()->user();


        /*
        |--------------------------------------------------------------------------
        | NO USER
        |--------------------------------------------------------------------------
        */

        if (
            ! $user
        ) {
            return $query
                ->whereRaw(
                    '1 = 0'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $user->hasRole(
                'admin'
            )
        ) {
            return $query;
        }


        /*
        |--------------------------------------------------------------------------
        | PENGELOLA LOKASI
        |--------------------------------------------------------------------------
        */

        return $query
            ->whereHas(
                'hikingTrail.mountain.managers',
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
    | FILTER TRAIL QUERY
    |--------------------------------------------------------------------------
    |
    | Dipakai dropdown form dan filter table.
    |--------------------------------------------------------------------------
    */

    protected static function filterTrailQueryByUser(
        Builder $query
    ): Builder {
        $user =
            auth()->user();


        if (
            ! $user
        ) {
            return $query
                ->whereRaw(
                    '1 = 0'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $user->hasRole(
                'admin'
            )
        ) {
            return $query
                ->with(
                    'mountain'
                )
                ->orderBy(
                    'name'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | PENGELOLA
        |--------------------------------------------------------------------------
        */

        return $query
            ->with(
                'mountain'
            )
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
            )
            ->orderBy(
                'name'
            );
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
                Pages\ListTrailGuides::route(
                    '/'
                ),

            'create' =>
                Pages\CreateTrailGuide::route(
                    '/create'
                ),

            'view' =>
                Pages\ViewTrailGuide::route(
                    '/{record}'
                ),

            'edit' =>
                Pages\EditTrailGuide::route(
                    '/{record}/edit'
                ),

        ];
    }
}