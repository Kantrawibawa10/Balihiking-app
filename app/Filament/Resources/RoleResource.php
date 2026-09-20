<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model =
        Role::class;

    protected static ?string $navigationIcon =
        'heroicon-o-key';

    protected static ?string $navigationLabel =
        'Role & Permission';

    protected static ?string $modelLabel =
        'Role';

    protected static ?string $pluralModelLabel =
        'Role & Permission';

    protected static ?string $navigationGroup =
        'Manajemen Sistem';

    protected static ?int $navigationSort =
        2;


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
                | ROLE INFORMATION
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make(
                    'Informasi Role'
                )
                    ->schema([

                        Forms\Components\TextInput::make(
                            'name'
                        )
                            ->label(
                                'Nama Role'
                            )
                            ->placeholder(
                                'contoh: petugas_pos'
                            )
                            ->required()
                            ->maxLength(
                                255
                            )
                            ->unique(
                                table:
                                    Role::class,

                                column:
                                    'name',

                                ignoreRecord:
                                    true
                            )
                            ->disabled(
                                fn (
                                    ?Role $record
                                ): bool =>
                                    $record
                                    &&
                                    in_array(
                                        $record->name,
                                        [
                                            'admin',
                                            'pengelola_lokasi',
                                            'pendaki',
                                        ],
                                        true
                                    )
                            )
                            ->helperText(
                                'Role bawaan sistem tidak dapat diganti namanya.'
                            ),


                        Forms\Components\Hidden::make(
                            'guard_name'
                        )
                            ->default(
                                'web'
                            ),

                    ]),


                /*
                |--------------------------------------------------------------------------
                | PERMISSIONS
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make(
                    'Permission Role'
                )
                    ->description(
                        'Centang menu atau tindakan yang dapat dilakukan oleh role ini.'
                    )
                    ->schema([

                        Forms\Components\CheckboxList::make(
                            'permissions'
                        )
                            ->label(
                                'Daftar Permission'
                            )
                            ->relationship(
                                'permissions',
                                'name',
                                modifyQueryUsing:
                                    fn (
                                        Builder $query
                                    ): Builder =>
                                        $query
                                            ->where(
                                                'guard_name',
                                                'web'
                                            )
                                            ->orderBy(
                                                'name'
                                            )
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (
                                    Permission $record
                                ): string =>
                                    static::permissionLabel(
                                        $record->name
                                    )
                            )
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(
                                3
                            )
                            ->disabled(
                                fn (
                                    ?Role $record
                                ): bool =>
                                    $record
                                        ?->name
                                    ===
                                    'admin'
                            )
                            ->helperText(
                                fn (
                                    ?Role $record
                                ): string =>
                                    $record
                                        ?->name
                                    ===
                                    'admin'

                                        ?

                                        'Role Admin otomatis memiliki seluruh permission.'

                                        :

                                        'Centang permission yang ingin diberikan.'
                            ),

                    ]),

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

                Tables\Columns\TextColumn::make(
                    'name'
                )
                    ->label(
                        'Role'
                    )
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            string $state
                        ): string =>
                            UserResource::roleLabel(
                                $state
                            )
                    )
                    ->color(
                        fn (
                            string $state
                        ): string =>
                            match (
                                $state
                            ) {

                                'admin' =>
                                    'danger',

                                'pengelola_lokasi' =>
                                    'warning',

                                'pendaki' =>
                                    'success',

                                default =>
                                    'info',
                            }
                    )
                    ->searchable()
                    ->sortable(),


                Tables\Columns\TextColumn::make(
                    'permissions_count'
                )
                    ->label(
                        'Jumlah Permission'
                    )
                    ->counts(
                        'permissions'
                    )
                    ->badge(),


                Tables\Columns\TextColumn::make(
                    'users_count'
                )
                    ->label(
                        'Jumlah User'
                    )
                    ->counts(
                        'users'
                    )
                    ->badge(),


                Tables\Columns\TextColumn::make(
                    'guard_name'
                )
                    ->label(
                        'Guard'
                    )
                    ->badge()
                    ->toggleable(
                        isToggledHiddenByDefault:
                            true
                    ),

            ])


            ->actions([

                Tables\Actions\EditAction::make()
                    ->label(
                        'Kelola'
                    ),

            ])


            ->bulkActions([])


            ->defaultSort(
                'name'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PERMISSION LABEL
    |--------------------------------------------------------------------------
    */

    public static function permissionLabel(
        string $permission
    ): string {
        return match (
            $permission
        ) {

            'admin_panel.access' =>
                'Admin Panel - Akses',

            'dashboard.view' =>
                'Dashboard - Lihat',

            'mountains.view' =>
                'Gunung - Lihat',

            'mountains.create' =>
                'Gunung - Tambah',

            'mountains.update' =>
                'Gunung - Edit',

            'mountains.delete' =>
                'Gunung - Hapus',

            'trails.view' =>
                'Jalur - Lihat',

            'trails.create' =>
                'Jalur - Tambah',

            'trails.update' =>
                'Jalur - Edit',

            'trails.delete' =>
                'Jalur - Hapus',

            'checkpoints.view' =>
                'Checkpoint - Lihat',

            'checkpoints.create' =>
                'Checkpoint - Tambah',

            'checkpoints.update' =>
                'Checkpoint - Edit',

            'checkpoints.delete' =>
                'Checkpoint - Hapus',

            'trail_reports.view' =>
                'Laporan Jalur - Lihat',

            'trail_reports.create' =>
                'Laporan Jalur - Tambah',

            'trail_reports.update' =>
                'Laporan Jalur - Edit',

            'trail_reports.delete' =>
                'Laporan Jalur - Hapus',

            'user_routes.view' =>
                'Aktivitas Pendaki - Lihat',

            'user_routes.complete' =>
                'Aktivitas Pendaki - Selesaikan',

            'live_tracking.view' =>
                'Live Tracking - Lihat',

            'trail_guides.view' =>
                'Panduan - Lihat',

            'trail_guides.create' =>
                'Panduan - Tambah',

            'trail_guides.update' =>
                'Panduan - Edit',

            'trail_guides.delete' =>
                'Panduan - Hapus',

            'users.view' =>
                'Users - Lihat',

            'users.create' =>
                'Users - Tambah',

            'users.update' =>
                'Users - Edit',

            'users.delete' =>
                'Users - Hapus',

            'roles.manage' =>
                'Role & Permission - Kelola',

            'location_assignments.manage' =>
                'Lokasi Pengelola - Kelola',

            'pendaki.dashboard.view' =>
                'Pendaki - Dashboard',

            'pendaki.mountains.view' =>
                'Pendaki - Gunung',

            'pendaki.trails.view' =>
                'Pendaki - Jalur',

            'pendaki.simaksi.view' =>
                'Pendaki - Lihat SIMAKSI',

            'pendaki.simaksi.create' =>
                'Pendaki - Buat SIMAKSI',

            'pendaki.live_tracking.view' =>
                'Pendaki - Live Tracking',

            'pendaki.live_tracking.start' =>
                'Pendaki - Mulai Tracking',

            'pendaki.live_tracking.complete' =>
                'Pendaki - Selesaikan Tracking',

            'pendaki.sos.send' =>
                'Pendaki - Kirim SOS',

            'pendaki.history.view' =>
                'Pendaki - Riwayat',

            'pendaki.profile.view' =>
                'Pendaki - Lihat Profil',

            'pendaki.profile.update' =>
                'Pendaki - Edit Profil',

            default =>
                str(
                    $permission
                )
                    ->replace(
                        [
                            '.',
                            '_',
                        ],
                        ' '
                    )
                    ->title()
                    ->toString(),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | AUTH
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


        return
            $user->hasRole(
                'admin'
            )
            ||
            $user->can(
                'roles.manage'
            );
    }


    public static function canCreate(): bool
    {
        return static::canViewAny();
    }


    public static function canEdit(
        $record
    ): bool {
        return static::canViewAny();
    }


    public static function canDelete(
        $record
    ): bool {
        if (
            in_array(
                $record->name,
                [
                    'admin',
                    'pengelola_lokasi',
                    'pendaki',
                ],
                true
            )
        ) {
            return false;
        }


        return static::canViewAny();
    }


    public static function canDeleteAny(): bool
    {
        return false;
    }


    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }


    /*
    |--------------------------------------------------------------------------
    | QUERY
    |--------------------------------------------------------------------------
    */

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(
                'guard_name',
                'web'
            )
            ->withCount([
                'permissions',
                'users',
            ]);
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
                Pages\ListRoles::route(
                    '/'
                ),

            'create' =>
                Pages\CreateRole::route(
                    '/create'
                ),

            'edit' =>
                Pages\EditRole::route(
                    '/{record}/edit'
                ),

        ];
    }
}