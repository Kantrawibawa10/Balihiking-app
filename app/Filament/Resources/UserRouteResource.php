<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserRouteResource\Pages;
use App\Models\UserRoute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UserRouteResource extends Resource
{
    protected static ?string $model = UserRoute::class;

    protected static ?string $navigationIcon =
        'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel =
        'Aktivitas Pendaki';

    protected static ?string $modelLabel =
        'Aktivitas Pendaki';

    protected static ?string $pluralModelLabel =
        'Aktivitas Pendaki';

    protected static ?string $navigationGroup =
        'Monitoring Pendakian';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()
            ->user()
            ?->can('user_routes.view')
            ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
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

    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    |
    | Form hanya digunakan untuk melihat detail aktivitas.
    | Data aktivitas tidak diedit manual melalui form.
    |
    */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                /*
                |--------------------------------------------------------------------------
                | INFORMASI PENDAKI
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make(
                    'Informasi Pendaki'
                )
                    ->schema([

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
                            ->disabled()
                            ->dehydrated(
                                false
                            ),

                        Forms\Components\Select::make(
                            'hiking_trail_id'
                        )
                            ->label(
                                'Jalur Pendakian'
                            )
                            ->relationship(
                                'hikingTrail',
                                'name'
                            )
                            ->disabled()
                            ->dehydrated(
                                false
                            ),

                    ])
                    ->columns(
                        2
                    ),

                /*
                |--------------------------------------------------------------------------
                | AKTIVITAS PENDAKIAN
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make(
                    'Aktivitas Pendakian'
                )
                    ->schema([

                        Forms\Components\DateTimePicker::make(
                            'created_at'
                        )
                            ->label(
                                'Mulai Pendakian'
                            )
                            ->seconds(
                                false
                            )
                            ->displayFormat(
                                'd M Y H:i'
                            )
                            ->disabled()
                            ->dehydrated(
                                false
                            ),

                        Forms\Components\DateTimePicker::make(
                            'completed_at'
                        )
                            ->label(
                                'Selesai Pendakian'
                            )
                            ->seconds(
                                false
                            )
                            ->displayFormat(
                                'd M Y H:i'
                            )
                            ->disabled()
                            ->dehydrated(
                                false
                            ),

                        Forms\Components\TextInput::make(
                            'gpx_file_path'
                        )
                            ->label(
                                'Rekaman GPX'
                            )
                            ->placeholder(
                                'Belum tersedia'
                            )
                            ->disabled()
                            ->dehydrated(
                                false
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

    public static function table(Table $table): Table
    {
        return $table

            /*
            |--------------------------------------------------------------------------
            | COLUMNS
            |--------------------------------------------------------------------------
            */

            ->columns([

                /*
                |--------------------------------------------------------------------------
                | PENDAKI
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make(
                    'user.name'
                )
                    ->label(
                        'Pendaki'
                    )
                    ->searchable()
                    ->sortable()
                    ->weight(
                        'bold'
                    )
                    ->placeholder(
                        '-'
                    ),

                /*
                |--------------------------------------------------------------------------
                | GUNUNG
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
                | JALUR
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
                | MULAI
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make(
                    'created_at'
                )
                    ->label(
                        'Mulai'
                    )
                    ->dateTime(
                        'd M Y H:i'
                    )
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | SELESAI
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make(
                    'completed_at'
                )
                    ->label(
                        'Selesai'
                    )
                    ->dateTime(
                        'd M Y H:i'
                    )
                    ->sortable()
                    ->placeholder(
                        'Belum selesai'
                    ),

                /*
                |--------------------------------------------------------------------------
                | DURASI AKTUAL
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make(
                    'duration_display'
                )
                    ->label(
                        'Durasi'
                    )
                    ->getStateUsing(
                        function (
                            UserRoute $record
                        ): string {

                            if (
                                ! $record->created_at
                            ) {
                                return '-';
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Jika belum selesai:
                            | hitung sampai waktu sekarang.
                            |--------------------------------------------------------------------------
                            */

                            $end =
                                $record->completed_at
                                ??
                                now();

                            $minutes =
                                (int)
                                $record
                                    ->created_at
                                    ->diffInMinutes(
                                        $end,
                                        true
                                    );

                            $hours =
                                intdiv(
                                    $minutes,
                                    60
                                );

                            $remainingMinutes =
                                $minutes
                                %
                                60;

                            if (
                                $hours <= 0
                            ) {
                                return
                                    $remainingMinutes
                                    .
                                    ' menit';
                            }

                            if (
                                $remainingMinutes <= 0
                            ) {
                                return
                                    $hours
                                    .
                                    ' jam';
                            }

                            return
                                $hours
                                .
                                ' jam '
                                .
                                $remainingMinutes
                                .
                                ' menit';
                        }
                    ),

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make(
                    'activity_status'
                )
                    ->label(
                        'Status'
                    )
                    ->badge()
                    ->getStateUsing(
                        fn (
                            UserRoute $record
                        ): string => $record->completed_at
                                ?
                                'Selesai'
                                :
                                'Berlangsung'
                    )
                    ->color(
                        fn (
                            UserRoute $record
                        ): string => $record->completed_at
                                ?
                                'success'
                                :
                                'warning'
                    ),

            ])

            /*
            |--------------------------------------------------------------------------
            | DEFAULT SORT
            |--------------------------------------------------------------------------
            */

            ->defaultSort(
                'created_at',
                'desc'
            )

            /*
            |--------------------------------------------------------------------------
            | AUTO REFRESH
            |--------------------------------------------------------------------------
            |
            | Agar status aktivitas dari pendaki maupun petugas
            | dapat ter-update pada halaman monitoring.
            |
            */

            ->poll(
                '10s'
            )

            /*
            |--------------------------------------------------------------------------
            | FILTER
            |--------------------------------------------------------------------------
            */

            ->filters([

                /*
                |--------------------------------------------------------------------------
                | SEDANG BERLANGSUNG
                |--------------------------------------------------------------------------
                */

                Tables\Filters\Filter::make(
                    'ongoing'
                )
                    ->label(
                        'Sedang Berlangsung'
                    )
                    ->query(
                        fn (
                            Builder $query
                        ): Builder => $query->whereNull(
                            'completed_at'
                        )
                    ),

                /*
                |--------------------------------------------------------------------------
                | SUDAH SELESAI
                |--------------------------------------------------------------------------
                */

                Tables\Filters\Filter::make(
                    'completed'
                )
                    ->label(
                        'Sudah Selesai'
                    )
                    ->query(
                        fn (
                            Builder $query
                        ): Builder => $query->whereNotNull(
                            'completed_at'
                        )
                    ),

                /*
                |--------------------------------------------------------------------------
                | JALUR
                |--------------------------------------------------------------------------
                */

                Tables\Filters\SelectFilter::make(
                    'hiking_trail_id'
                )
                    ->label(
                        'Jalur'
                    )
                    ->relationship(
                        'hikingTrail',
                        'name'
                    )
                    ->searchable()
                    ->preload(),

            ])

            /*
            |--------------------------------------------------------------------------
            | ROW ACTIONS
            |--------------------------------------------------------------------------
            */

            ->actions([

                /*
                |--------------------------------------------------------------------------
                | SELESAIKAN PENDAKIAN
                |--------------------------------------------------------------------------
                |
                | Hanya muncul jika completed_at masih NULL.
                |
                */

                Tables\Actions\Action::make(
                    'complete'
                )
                    ->label(
                        'Selesaikan'
                    )
                    ->icon(
                        'heroicon-o-check-circle'
                    )
                    ->color(
                        'success'
                    )
                    ->visible(
                        fn (
                            UserRoute $record
                        ): bool => $record->completed_at === null
                    )
                    ->requiresConfirmation()
                    ->modalIcon(
                        'heroicon-o-check-circle'
                    )
                    ->modalIconColor(
                        'success'
                    )
                    ->modalHeading(
                        'Selesaikan Aktivitas Pendakian?'
                    )
                    ->modalDescription(
                        function (
                            UserRoute $record
                        ): string {

                            $pendaki =
                                $record
                                    ->user
                                    ?->name
                                ??
                                'Pendaki';

                            $jalur =
                                $record
                                    ->hikingTrail
                                    ?->name
                                ??
                                'jalur pendakian';

                            return
                                'Aktivitas '
                                .
                                $pendaki
                                .
                                ' pada '
                                .
                                $jalur
                                .
                                ' akan ditandai selesai pada waktu sekarang. '
                                .
                                'Data perjalanan dan rekaman lokasi tidak akan dihapus.';
                        }
                    )
                    ->modalSubmitActionLabel(
                        'Ya, Selesaikan'
                    )
                    ->modalCancelActionLabel(
                        'Batal'
                    )
                    ->action(
                        function (
                            UserRoute $record
                        ): void {

                            $completed =
                                false;

                            /*
                            |--------------------------------------------------------------------------
                            | DATABASE TRANSACTION
                            |--------------------------------------------------------------------------
                            |
                            | lockForUpdate mencegah admin dan pendaki
                            | menyelesaikan record yang sama pada saat bersamaan.
                            |
                            */

                            DB::transaction(
                                function () use (
                                    $record,
                                    &$completed
                                ): void {

                                    $activity =
                                        UserRoute::query()
                                            ->lockForUpdate()
                                            ->find(
                                                $record->getKey()
                                            );

                                    /*
                                    |--------------------------------------------------------------------------
                                    | RECORD TIDAK DITEMUKAN
                                    |--------------------------------------------------------------------------
                                    */

                                    if (
                                        ! $activity
                                    ) {
                                        return;
                                    }

                                    /*
                                    |--------------------------------------------------------------------------
                                    | SUDAH SELESAI
                                    |--------------------------------------------------------------------------
                                    */

                                    if (
                                        $activity->completed_at
                                        !==
                                        null
                                    ) {
                                        return;
                                    }

                                    /*
                                    |--------------------------------------------------------------------------
                                    | COMPLETE
                                    |--------------------------------------------------------------------------
                                    */

                                    $activity->update([
                                        'completed_at' => now(),
                                    ]);

                                    $completed =
                                        true;
                                }
                            );

                            /*
                            |--------------------------------------------------------------------------
                            | NOTIFICATION
                            |--------------------------------------------------------------------------
                            */

                            if (
                                $completed
                            ) {

                                Notification::make()
                                    ->title(
                                        'Aktivitas pendakian berhasil diselesaikan'
                                    )
                                    ->body(
                                        'Status pendakian telah diubah menjadi Selesai.'
                                    )
                                    ->success()
                                    ->send();

                                return;
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | RECORD SUDAH SELESAI SEBELUM ACTION DIEKSEKUSI
                            |--------------------------------------------------------------------------
                            */

                            Notification::make()
                                ->title(
                                    'Aktivitas sudah selesai'
                                )
                                ->body(
                                    'Aktivitas ini sudah diselesaikan sebelumnya.'
                                )
                                ->info()
                                ->send();
                        }
                    ),

                /*
                |--------------------------------------------------------------------------
                | LIHAT DETAIL
                |--------------------------------------------------------------------------
                */

                Tables\Actions\ViewAction::make()
                    ->label(
                        'Lihat'
                    )
                    ->icon(
                        'heroicon-o-eye'
                    ),

            ])

            /*
            |--------------------------------------------------------------------------
            | BULK ACTION
            |--------------------------------------------------------------------------
            */

            ->bulkActions(
                []
            );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY
    |--------------------------------------------------------------------------
    */

   

    /*
    |--------------------------------------------------------------------------
    | PAGES
    |--------------------------------------------------------------------------
    */

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListUserRoutes::route(
                '/'
            ),

            'view' => Pages\ViewUserRoute::route(
                '/{record}'
            ),

        ];
    }
}
