<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Mountain;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model =
        User::class;

    protected static ?string $navigationIcon =
        'heroicon-o-users';

    protected static ?string $navigationLabel =
        'Users';

    protected static ?string $modelLabel =
        'User';

    protected static ?string $pluralModelLabel =
        'Users';

    protected static ?string $navigationGroup =
        'Manajemen Sistem';

    protected static ?int $navigationSort =
        1;


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
                | DATA AKUN
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make(
                    'Informasi User'
                )
                    ->description(
                        'Data login dan identitas pengguna BaliHiking.'
                    )
                    ->schema([

                        Forms\Components\TextInput::make(
                            'name'
                        )
                            ->label(
                                'Nama Lengkap'
                            )
                            ->placeholder(
                                'Masukkan nama user'
                            )
                            ->required()
                            ->maxLength(
                                255
                            ),


                        Forms\Components\TextInput::make(
                            'email'
                        )
                            ->label(
                                'Email'
                            )
                            ->email()
                            ->placeholder(
                                'nama@gmail.com'
                            )
                            ->required()
                            ->unique(
                                ignoreRecord: true
                            )
                            ->maxLength(
                                255
                            ),


                        Forms\Components\TextInput::make(
                            'password'
                        )
                            ->label(
                                'Password'
                            )
                            ->password()
                            ->revealable()
                            ->minLength(
                                8
                            )
                            ->required(
                                fn (
                                    string $operation
                                ): bool =>
                                    $operation
                                    ===
                                    'create'
                            )
                            ->dehydrated(
                                fn (
                                    ?string $state
                                ): bool =>
                                    filled(
                                        $state
                                    )
                            )
                            ->helperText(
                                'Minimal 8 karakter. Saat edit, kosongkan jika tidak ingin mengganti password.'
                            ),

                    ])
                    ->columns(
                        2
                    ),


                /*
                |--------------------------------------------------------------------------
                | ROLE
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make(
                    'Role & Hak Akses'
                )
                    ->description(
                        'Tentukan jenis user dan lokasi yang dapat dikelola.'
                    )
                    ->schema([

                        Forms\Components\Select::make(
                            'roles'
                        )
                            ->label(
                                'Role'
                            )
                            ->relationship(
                                'roles',
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
                            ->multiple()
                            ->maxItems(
                                1
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(
                                fn (
                                    Role $record
                                ): string =>
                                    static::roleLabel(
                                        $record->name
                                    )
                            )
                            ->helperText(
                                'Setiap user menggunakan satu role utama.'
                            ),


                        Forms\Components\CheckboxList::make(
                            'mountains'
                        )
                            ->label(
                                'Gunung / Lokasi yang Dikelola'
                            )
                            ->relationship(
                                'mountains',
                                'name',
                                modifyQueryUsing:
                                    fn (
                                        Builder $query
                                    ): Builder =>
                                        $query
                                            ->orderBy(
                                                'name'
                                            )
                            )
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(
                                2
                            )
                            ->helperText(
                                'Isi bagian ini untuk role Pengelola Pendakian. Admin tidak perlu assignment lokasi dan Pendaki tidak menggunakan akses ini.'
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
                        'Nama'
                    )
                    ->searchable()
                    ->sortable()
                    ->weight(
                        'bold'
                    ),


                Tables\Columns\TextColumn::make(
                    'email'
                )
                    ->label(
                        'Email'
                    )
                    ->searchable()
                    ->sortable(),


                Tables\Columns\TextColumn::make(
                    'roles.name'
                )
                    ->label(
                        'Role'
                    )
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            string $state
                        ): string =>
                            static::roleLabel(
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
                    ),


                Tables\Columns\TextColumn::make(
                    'mountains.name'
                )
                    ->label(
                        'Lokasi Dikelola'
                    )
                    ->badge()
                    ->placeholder(
                        '-'
                    )
                    ->limitList(
                        3
                    )
                    ->expandableLimitedList(),


                Tables\Columns\TextColumn::make(
                    'created_at'
                )
                    ->label(
                        'Dibuat'
                    )
                    ->dateTime(
                        'd M Y H:i'
                    )
                    ->sortable(),

            ])


            /*
            |--------------------------------------------------------------------------
            | FILTER ROLE
            |--------------------------------------------------------------------------
            */

            ->filters([

                Tables\Filters\SelectFilter::make(
                    'roles'
                )
                    ->label(
                        'Role'
                    )
                    ->relationship(
                        'roles',
                        'name',
                        fn (
                            Builder $query
                        ): Builder =>
                            $query
                                ->where(
                                    'guard_name',
                                    'web'
                                )
                    )
                    ->searchable()
                    ->preload(),

            ])


            /*
            |--------------------------------------------------------------------------
            | ACTION
            |--------------------------------------------------------------------------
            */

            ->actions([

                Tables\Actions\EditAction::make()
                    ->label(
                        'Edit'
                    ),

            ])


            ->bulkActions([])


            ->defaultSort(
                'created_at',
                'desc'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE LABEL
    |--------------------------------------------------------------------------
    */

    public static function roleLabel(
        string $role
    ): string {
        return match (
            $role
        ) {

            'admin' =>
                'Admin',

            'pengelola_lokasi' =>
                'Pengelola Pendakian',

            'pendaki' =>
                'Pendaki',

            default =>
                str(
                    $role
                )
                    ->replace(
                        '_',
                        ' '
                    )
                    ->title()
                    ->toString(),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
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
                'users.view'
            );
    }


    public static function canCreate(): bool
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
                'users.create'
            );
    }


    public static function canEdit(
        $record
    ): bool {
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
                'users.update'
            );
    }


    public static function canDelete(
        $record
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
        | Tidak boleh menghapus akun sendiri.
        |--------------------------------------------------------------------------
        */

        if (
            (int)
            $record->id
            ===
            (int)
            $user->id
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Lindungi admin utama.
        |--------------------------------------------------------------------------
        */

        if (
            $record->email
            ===
            'admin@gmail.com'
        ) {
            return false;
        }


        return
            $user->hasRole(
                'admin'
            )
            ||
            $user->can(
                'users.delete'
            );
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
            ->with([
                'roles',
                'mountains',
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
                Pages\ListUsers::route(
                    '/'
                ),

            'create' =>
                Pages\CreateUser::route(
                    '/create'
                ),

            'edit' =>
                Pages\EditUser::route(
                    '/{record}/edit'
                ),

        ];
    }
}