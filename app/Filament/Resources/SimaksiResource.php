<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SimaksiResource\Pages;
use App\Models\Mountain;
use App\Models\Simaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SimaksiResource extends Resource
{
    protected static ?string $model =
        Simaksi::class;


    /*
    |--------------------------------------------------------------------------
    | NAVIGATION
    |--------------------------------------------------------------------------
    */

    protected static ?string $navigationIcon =
        'heroicon-o-document-check';

    protected static ?string $navigationLabel =
        'SIMAKSI';

    protected static ?string $modelLabel =
        'SIMAKSI';

    protected static ?string $pluralModelLabel =
        'SIMAKSI';

    protected static ?string $navigationGroup =
        'Manajemen Pendakian';

    protected static ?int $navigationSort =
        3;


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
                    'Informasi Permohonan'
                )
                    ->schema([

                        Forms\Components\TextInput::make(
                            'code'
                        )
                            ->label(
                                'Kode SIMAKSI'
                            )
                            ->disabled(),


                        Forms\Components\Select::make(
                            'user_id'
                        )
                            ->label(
                                'Pendaki'
                            )
                            ->relationship(
                                'user',
                                'name'
                            )
                            ->disabled(),


                        Forms\Components\Select::make(
                            'mountain_id'
                        )
                            ->label(
                                'Gunung'
                            )
                            ->relationship(
                                'mountain',
                                'name'
                            )
                            ->searchable()
                            ->preload()
                            ->disabled(),


                        Forms\Components\TextInput::make(
                            'gunung'
                        )
                            ->label(
                                'Nama Gunung'
                            )
                            ->disabled(),

                    ])
                    ->columns(
                        2
                    ),


                Forms\Components\Section::make(
                    'Data Perjalanan'
                )
                    ->schema([

                        Forms\Components\DatePicker::make(
                            'tanggal_naik'
                        )
                            ->label(
                                'Tanggal Naik'
                            )
                            ->required(),


                        Forms\Components\DatePicker::make(
                            'tanggal_turun'
                        )
                            ->label(
                                'Tanggal Turun'
                            )
                            ->required()
                            ->afterOrEqual(
                                'tanggal_naik'
                            ),


                        Forms\Components\TextInput::make(
                            'jumlah_anggota'
                        )
                            ->label(
                                'Jumlah Anggota'
                            )
                            ->numeric()
                            ->minValue(
                                1
                            )
                            ->required(),


                        Forms\Components\TextInput::make(
                            'nomor_darurat'
                        )
                            ->label(
                                'Nomor Darurat'
                            )
                            ->tel()
                            ->required(),

                    ])
                    ->columns(
                        2
                    ),


                Forms\Components\Section::make(
                    'Status & Catatan'
                )
                    ->schema([

                        Forms\Components\Select::make(
                            'status'
                        )
                            ->label(
                                'Status'
                            )
                            ->options([
                                'pending' =>
                                    'Menunggu',

                                'approved' =>
                                    'Disetujui',

                                'rejected' =>
                                    'Ditolak',

                                'completed' =>
                                    'Selesai',

                                'cancelled' =>
                                    'Dibatalkan',
                            ])
                            ->disabled(),


                        Forms\Components\Textarea::make(
                            'admin_notes'
                        )
                            ->label(
                                'Catatan Petugas'
                            )
                            ->rows(
                                4
                            )
                            ->columnSpanFull(),


                        Forms\Components\Textarea::make(
                            'rejection_reason'
                        )
                            ->label(
                                'Alasan Penolakan'
                            )
                            ->disabled()
                            ->rows(
                                4
                            )
                            ->columnSpanFull(),

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

                Tables\Columns\TextColumn::make(
                    'code'
                )
                    ->label(
                        'Kode'
                    )
                    ->searchable()
                    ->copyable()
                    ->weight(
                        'bold'
                    ),


                Tables\Columns\TextColumn::make(
                    'user.name'
                )
                    ->label(
                        'Pendaki'
                    )
                    ->searchable()
                    ->sortable()
                    ->description(
                        fn (
                            Simaksi $record
                        ): string =>
                            $record
                                ->user
                                ?->email
                            ??
                            '-'
                    ),


                Tables\Columns\TextColumn::make(
                    'mountain_name'
                )
                    ->label(
                        'Gunung'
                    )
                    ->state(
                        fn (
                            Simaksi $record
                        ): string =>
                            $record
                                ->mountain_name
                    )
                    ->weight(
                        'bold'
                    ),


                Tables\Columns\TextColumn::make(
                    'tanggal_naik'
                )
                    ->label(
                        'Naik'
                    )
                    ->date(
                        'd M Y'
                    )
                    ->sortable(),


                Tables\Columns\TextColumn::make(
                    'tanggal_turun'
                )
                    ->label(
                        'Turun'
                    )
                    ->date(
                        'd M Y'
                    )
                    ->sortable(),


                Tables\Columns\TextColumn::make(
                    'jumlah_anggota'
                )
                    ->label(
                        'Anggota'
                    )
                    ->suffix(
                        ' Orang'
                    )
                    ->alignCenter(),


                Tables\Columns\TextColumn::make(
                    'nomor_darurat'
                )
                    ->label(
                        'Kontak Darurat'
                    )
                    ->copyable(),


                Tables\Columns\TextColumn::make(
                    'status'
                )
                    ->label(
                        'Status'
                    )
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            string $state
                        ): string =>
                            match (
                                $state
                            ) {
                                'pending' =>
                                    'Menunggu',

                                'approved' =>
                                    'Disetujui',

                                'rejected' =>
                                    'Ditolak',

                                'completed' =>
                                    'Selesai',

                                'cancelled' =>
                                    'Dibatalkan',

                                default =>
                                    ucfirst(
                                        $state
                                    ),
                            }
                    )
                    ->color(
                        fn (
                            string $state
                        ): string =>
                            match (
                                $state
                            ) {
                                'pending' =>
                                    'warning',

                                'approved' =>
                                    'success',

                                'rejected' =>
                                    'danger',

                                'completed' =>
                                    'info',

                                'cancelled' =>
                                    'gray',

                                default =>
                                    'gray',
                            }
                    ),


                Tables\Columns\TextColumn::make(
                    'created_at'
                )
                    ->label(
                        'Diajukan'
                    )
                    ->dateTime(
                        'd M Y H:i'
                    )
                    ->sortable()
                    ->toggleable(),

            ])


            /*
            |--------------------------------------------------------------------------
            | FILTERS
            |--------------------------------------------------------------------------
            */

            ->filters([

                Tables\Filters\SelectFilter::make(
                    'status'
                )
                    ->label(
                        'Status'
                    )
                    ->options([
                        'pending' =>
                            'Menunggu',

                        'approved' =>
                            'Disetujui',

                        'rejected' =>
                            'Ditolak',

                        'completed' =>
                            'Selesai',

                        'cancelled' =>
                            'Dibatalkan',
                    ]),


                Tables\Filters\SelectFilter::make(
                    'mountain_id'
                )
                    ->label(
                        'Gunung'
                    )
                    ->options(
                        fn (): array =>
                            static::mountainOptions()
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

                /*
                |--------------------------------------------------------------------------
                | VIEW
                |--------------------------------------------------------------------------
                */

                Tables\Actions\ViewAction::make()
                    ->label(
                        'Lihat'
                    ),


                /*
                |--------------------------------------------------------------------------
                | EDIT
                |--------------------------------------------------------------------------
                */

                Tables\Actions\EditAction::make()
                    ->label(
                        'Edit'
                    )
                    ->visible(
                        fn (
                            Simaksi $record
                        ): bool =>
                            static::canEdit(
                                $record
                            )
                    ),


                /*
                |--------------------------------------------------------------------------
                | APPROVE
                |--------------------------------------------------------------------------
                */

                Tables\Actions\Action::make(
                    'approve'
                )
                    ->label(
                        'Setujui'
                    )
                    ->icon(
                        'heroicon-o-check-circle'
                    )
                    ->color(
                        'success'
                    )
                    ->requiresConfirmation()
                    ->modalHeading(
                        'Setujui SIMAKSI'
                    )
                    ->modalDescription(
                        'Permohonan akan ditandai sebagai disetujui.'
                    )
                    ->visible(
                        function (
                            Simaksi $record
                        ): bool {
                            $user =
                                auth()->user();


                            return
                                in_array(
                                    $record->status,
                                    [
                                        'pending',
                                        'rejected',
                                    ],
                                    true
                                )
                                &&
                                (
                                    $user
                                        ?->hasRole(
                                            'admin'
                                        )
                                    ||
                                    $user
                                        ?->can(
                                            'simaksi.approve'
                                        )
                                );
                        }
                    )
                    ->action(
                        function (
                            Simaksi $record
                        ): void {
                            $record->update([

                                'status' =>
                                    'approved',

                                'approved_by' =>
                                    auth()->id(),

                                'approved_at' =>
                                    now(),

                                'rejected_by' =>
                                    null,

                                'rejected_at' =>
                                    null,

                                'rejection_reason' =>
                                    null,

                            ]);


                            Notification::make()
                                ->title(
                                    'SIMAKSI disetujui'
                                )
                                ->success()
                                ->send();
                        }
                    ),


                /*
                |--------------------------------------------------------------------------
                | REJECT
                |--------------------------------------------------------------------------
                */

                Tables\Actions\Action::make(
                    'reject'
                )
                    ->label(
                        'Tolak'
                    )
                    ->icon(
                        'heroicon-o-x-circle'
                    )
                    ->color(
                        'danger'
                    )
                    ->form([

                        Forms\Components\Textarea::make(
                            'reason'
                        )
                            ->label(
                                'Alasan Penolakan'
                            )
                            ->required()
                            ->rows(
                                4
                            ),

                    ])
                    ->visible(
                        function (
                            Simaksi $record
                        ): bool {
                            $user =
                                auth()->user();


                            return
                                ! in_array(
                                    $record->status,
                                    [
                                        'completed',
                                        'cancelled',
                                    ],
                                    true
                                )
                                &&
                                (
                                    $user
                                        ?->hasRole(
                                            'admin'
                                        )
                                    ||
                                    $user
                                        ?->can(
                                            'simaksi.reject'
                                        )
                                );
                        }
                    )
                    ->action(
                        function (
                            Simaksi $record,
                            array $data
                        ): void {
                            $record->update([

                                'status' =>
                                    'rejected',

                                'rejected_by' =>
                                    auth()->id(),

                                'rejected_at' =>
                                    now(),

                                'rejection_reason' =>
                                    $data[
                                        'reason'
                                    ],

                                'approved_by' =>
                                    null,

                                'approved_at' =>
                                    null,

                            ]);


                            Notification::make()
                                ->title(
                                    'SIMAKSI ditolak'
                                )
                                ->danger()
                                ->send();
                        }
                    ),


                /*
                |--------------------------------------------------------------------------
                | COMPLETE
                |--------------------------------------------------------------------------
                */

                Tables\Actions\Action::make(
                    'complete'
                )
                    ->label(
                        'Selesai'
                    )
                    ->icon(
                        'heroicon-o-flag'
                    )
                    ->color(
                        'info'
                    )
                    ->requiresConfirmation()
                    ->visible(
                        function (
                            Simaksi $record
                        ): bool {
                            $user =
                                auth()->user();


                            return
                                $record->status
                                ===
                                'approved'
                                &&
                                (
                                    $user
                                        ?->hasRole(
                                            'admin'
                                        )
                                    ||
                                    $user
                                        ?->can(
                                            'simaksi.complete'
                                        )
                                );
                        }
                    )
                    ->action(
                        function (
                            Simaksi $record
                        ): void {
                            $record->update([

                                'status' =>
                                    'completed',

                                'completed_by' =>
                                    auth()->id(),

                                'completed_at' =>
                                    now(),

                            ]);


                            Notification::make()
                                ->title(
                                    'SIMAKSI diselesaikan'
                                )
                                ->success()
                                ->send();
                        }
                    ),

            ])


            /*
            |--------------------------------------------------------------------------
            | BULK ACTION
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
                                ??
                                false
                        ),

                ]),

            ])


            ->defaultSort(
                'created_at',
                'desc'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | MOUNTAIN OPTIONS
    |--------------------------------------------------------------------------
    */

    protected static function mountainOptions(): array
    {
        $user =
            auth()->user();


        if (! $user) {
            return [];
        }


        if (
            $user->hasRole(
                'admin'
            )
        ) {
            return Mountain::query()
                ->orderBy(
                    'name'
                )
                ->pluck(
                    'name',
                    'id'
                )
                ->toArray();
        }


        return $user
            ->mountains()
            ->orderBy(
                'name'
            )
            ->pluck(
                'name',
                'mountains.id'
            )
            ->toArray();
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


        if (! $user) {
            return false;
        }


        return
            $user->hasRole(
                'admin'
            )
            ||
            $user->can(
                'simaksi.view'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMAKSI HANYA DIBUAT DARI FRONTEND PENDAKI
    |--------------------------------------------------------------------------
    */

    public static function canCreate(): bool
    {
        return false;
    }


    public static function canView(
        $record
    ): bool {
        return static::canAccessRecord(
            $record,
            'simaksi.view'
        );
    }


    public static function canEdit(
        $record
    ): bool {
        return static::canAccessRecord(
            $record,
            'simaksi.update'
        );
    }


    public static function canDelete(
        $record
    ): bool {
        return static::canAccessRecord(
            $record,
            'simaksi.delete'
        );
    }


    public static function canDeleteAny(): bool
    {
        return auth()
            ->user()
            ?->hasRole(
                'admin'
            )
            ??
            false;
    }


    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }


    /*
    |--------------------------------------------------------------------------
    | RECORD LOCATION AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    protected static function canAccessRecord(
        Simaksi $record,
        string $permission
    ): bool {
        $user =
            auth()->user();


        if (! $user) {
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
        | BY MOUNTAIN ID
        |--------------------------------------------------------------------------
        */

        if (
            $record->mountain_id
        ) {
            return $user
                ->mountains()
                ->whereKey(
                    $record->mountain_id
                )
                ->exists();
        }


        /*
        |--------------------------------------------------------------------------
        | LEGACY BY MOUNTAIN NAME
        |--------------------------------------------------------------------------
        */

        if (
            $record->gunung
        ) {
            return $user
                ->mountains()
                ->where(
                    'name',
                    $record->gunung
                )
                ->exists();
        }


        return false;
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
                    'user',
                    'mountain',
                    'approvedBy',
                    'rejectedBy',
                    'completedBy',
                ]);


        $user =
            auth()->user();


        if (! $user) {
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
        | ASSIGNED MOUNTAINS
        |--------------------------------------------------------------------------
        */

        $mountainIds =
            $user
                ->mountains()
                ->pluck(
                    'mountains.id'
                );


        $mountainNames =
            $user
                ->mountains()
                ->pluck(
                    'mountains.name'
                );


        return $query
            ->where(
                function (
                    Builder $scope
                ) use (
                    $mountainIds,
                    $mountainNames
                ) {
                    $scope
                        ->whereIn(
                            'mountain_id',
                            $mountainIds
                        )
                        ->orWhereIn(
                            'gunung',
                            $mountainNames
                        );
                }
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
                Pages\ListSimaksis::route(
                    '/'
                ),

            'view' =>
                Pages\ViewSimaksi::route(
                    '/{record}'
                ),

            'edit' =>
                Pages\EditSimaksi::route(
                    '/{record}/edit'
                ),

        ];
    }
}