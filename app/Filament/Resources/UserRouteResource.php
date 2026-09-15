<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserRouteResource\Pages;
use App\Models\UserRoute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    |
    | Digunakan pada halaman detail.
    | Seluruh field readonly karena aktivitas dibuat otomatis oleh pendaki.
    |
    */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Informasi Pendaki')
                    ->schema([

                        Forms\Components\Select::make('user_id')
                            ->label('Pendaki')
                            ->relationship(
                                'user',
                                'name'
                            )
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('hiking_trail_id')
                            ->label('Jalur Pendakian')
                            ->relationship(
                                'hikingTrail',
                                'name'
                            )
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Aktivitas Pendakian')
                    ->schema([

                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Mulai Pendakian')
                            ->seconds(false)
                            ->displayFormat('d M Y H:i')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Selesai Pendakian')
                            ->seconds(false)
                            ->displayFormat('d M Y H:i')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('gpx_file_path')
                            ->label('Rekaman GPX')
                            ->placeholder('Belum tersedia')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
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
            ->columns([

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pendaki')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make(
                    'hikingTrail.mountain.name'
                )
                    ->label('Gunung')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make(
                    'hikingTrail.name'
                )
                    ->label('Jalur')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Selesai')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('Belum selesai'),

                /*
                |--------------------------------------------------------------------------
                | DURASI AKTUAL
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('duration_display')
                    ->label('Durasi')
                    ->getStateUsing(
                        function (UserRoute $record): string {

                            if (! $record->created_at) {
                                return '-';
                            }

                            $end =
                                $record->completed_at
                                ?? now();

                            $minutes = (int) $record
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
                                $minutes % 60;

                            if ($hours <= 0) {
                                return $remainingMinutes
                                    . ' menit';
                            }

                            if ($remainingMinutes <= 0) {
                                return $hours
                                    . ' jam';
                            }

                            return $hours
                                . ' jam '
                                . $remainingMinutes
                                . ' menit';
                        }
                    ),

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('activity_status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(
                        fn (UserRoute $record): string =>
                            $record->completed_at
                                ? 'Selesai'
                                : 'Berlangsung'
                    )
                    ->color(
                        fn (UserRoute $record): string =>
                            $record->completed_at
                                ? 'success'
                                : 'warning'
                    ),
            ])

            ->defaultSort(
                'created_at',
                'desc'
            )

            ->filters([

                Tables\Filters\Filter::make('ongoing')
                    ->label('Sedang Berlangsung')
                    ->query(
                        fn (Builder $query): Builder =>
                            $query->whereNull(
                                'completed_at'
                            )
                    ),

                Tables\Filters\Filter::make('completed')
                    ->label('Sudah Selesai')
                    ->query(
                        fn (Builder $query): Builder =>
                            $query->whereNotNull(
                                'completed_at'
                            )
                    ),

                Tables\Filters\SelectFilter::make('hiking_trail_id')
                    ->label('Jalur')
                    ->relationship(
                        'hikingTrail',
                        'name'
                    )
                    ->searchable()
                    ->preload(),
            ])

            ->actions([

                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-o-eye'),
            ])

            ->bulkActions([]);
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
                'user',
                'hikingTrail.mountain',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | AKTIVITAS TIDAK BOLEH DIBUAT ADMIN
    |--------------------------------------------------------------------------
    */

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
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
                Pages\ListUserRoutes::route('/'),

            'view' =>
                Pages\ViewUserRoute::route(
                    '/{record}'
                ),
        ];
    }
}