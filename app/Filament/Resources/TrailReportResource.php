<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrailReportResource\Pages;
use App\Models\TrailReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TrailReportResource extends Resource
{
    protected static ?string $model = TrailReport::class;

    /*
    |--------------------------------------------------------------------------
    | NAVIGATION
    |--------------------------------------------------------------------------
    */

    protected static ?string $navigationIcon =
        'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel =
        'Laporan Kondisi Jalur';

    protected static ?string $modelLabel =
        'Laporan Kondisi Jalur';

    protected static ?string $pluralModelLabel =
        'Laporan Kondisi Jalur';

    protected static ?string $navigationGroup =
        'Laporan';

    protected static ?int $navigationSort = 1;

    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    |
    | Data laporan berasal dari pendaki.
    | Admin hanya membaca informasi laporan.
    |
    */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make(
                    'Informasi Laporan'
                )
                    ->description(
                        'Laporan kondisi jalur yang dikirim oleh pendaki.'
                    )
                    ->schema([

                        /*
                        |--------------------------------------------------------------------------
                        | PENDAKI
                        |--------------------------------------------------------------------------
                        */

                        Forms\Components\Select::make('user_id')
                            ->label('Pendaki')
                            ->relationship(
                                'user',
                                'name'
                            )
                            ->disabled()
                            ->dehydrated(false),

                        /*
                        |--------------------------------------------------------------------------
                        | JALUR
                        |--------------------------------------------------------------------------
                        */

                        Forms\Components\Select::make('hiking_trail_id')
                            ->label('Jalur Pendakian')
                            ->relationship(
                                'hikingTrail',
                                'name'
                            )
                            ->disabled()
                            ->dehydrated(false),

                        /*
                        |--------------------------------------------------------------------------
                        | STATUS
                        |--------------------------------------------------------------------------
                        */

                        Forms\Components\TextInput::make('status')
                            ->label('Kondisi Jalur')
                            ->formatStateUsing(
                                fn (?string $state): string =>
                                    self::statusLabel($state)
                            )
                            ->disabled()
                            ->dehydrated(false),

                        /*
                        |--------------------------------------------------------------------------
                        | TANGGAL
                        |--------------------------------------------------------------------------
                        */

                        Forms\Components\DateTimePicker::make(
                            'report_date'
                        )
                            ->label('Tanggal Laporan')
                            ->seconds(false)
                            ->displayFormat(
                                'd M Y H:i'
                            )
                            ->disabled()
                            ->dehydrated(false),

                        /*
                        |--------------------------------------------------------------------------
                        | KETERANGAN
                        |--------------------------------------------------------------------------
                        */

                        Forms\Components\Textarea::make(
                            'condition_note'
                        )
                            ->label('Keterangan Pendaki')
                            ->rows(6)
                            ->columnSpanFull()
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

            /*
            |--------------------------------------------------------------------------
            | EAGER LOAD
            |--------------------------------------------------------------------------
            */

            ->modifyQueryUsing(
                fn (Builder $query): Builder =>
                    $query->with([
                        'user',
                        'hikingTrail.mountain',
                    ])
            )

            /*
            |--------------------------------------------------------------------------
            | COLUMNS
            |--------------------------------------------------------------------------
            */

            ->columns([

                Tables\Columns\TextColumn::make(
                    'user.name'
                )
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
                    ->label('Jalur Pendakian')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make(
                    'status'
                )
                    ->label('Kondisi')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            self::statusLabel($state)
                    )
                    ->color(
                        fn (?string $state): string =>
                            self::statusColor($state)
                    ),

                Tables\Columns\TextColumn::make(
                    'condition_note'
                )
                    ->label('Keterangan')
                    ->limit(45)
                    ->wrap()
                    ->tooltip(
                        fn (
                            TrailReport $record
                        ): ?string =>
                            $record->condition_note
                    ),

                Tables\Columns\TextColumn::make(
                    'report_date'
                )
                    ->label('Tanggal Laporan')
                    ->dateTime(
                        'd M Y H:i'
                    )
                    ->sortable()
                    ->placeholder('-'),
            ])

            /*
            |--------------------------------------------------------------------------
            | DEFAULT SORT
            |--------------------------------------------------------------------------
            */

            ->defaultSort(
                'report_date',
                'desc'
            )

            /*
            |--------------------------------------------------------------------------
            | FILTER
            |--------------------------------------------------------------------------
            */

            ->filters([

                Tables\Filters\SelectFilter::make(
                    'status'
                )
                    ->label('Kondisi Jalur')
                    ->options([
                        'aman' =>
                            'Aman',

                        'licin' =>
                            'Licin',

                        'berlumpur' =>
                            'Berlumpur',

                        'longsor' =>
                            'Longsor',

                        'pohon_tumbang' =>
                            'Pohon Tumbang',

                        'jalur_tertutup' =>
                            'Jalur Tertutup',

                        'jembatan_rusak' =>
                            'Jembatan Rusak',

                        'lainnya' =>
                            'Lainnya',
                    ]),

                Tables\Filters\SelectFilter::make(
                    'hiking_trail_id'
                )
                    ->label('Jalur Pendakian')
                    ->relationship(
                        'hikingTrail',
                        'name'
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
                    ->label('Lihat')
                    ->icon(
                        'heroicon-o-eye'
                    ),

            ])

            /*
            |--------------------------------------------------------------------------
            | BULK
            |--------------------------------------------------------------------------
            */

            ->bulkActions([]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS LABEL
    |--------------------------------------------------------------------------
    */

    public static function statusLabel(
        ?string $status
    ): string {
        return match ($status) {
            'aman' =>
                'Aman',

            'licin' =>
                'Licin',

            'berlumpur' =>
                'Berlumpur',

            'longsor' =>
                'Longsor',

            'pohon_tumbang' =>
                'Pohon Tumbang',

            'jalur_tertutup' =>
                'Jalur Tertutup',

            'jembatan_rusak' =>
                'Jembatan Rusak',

            'lainnya' =>
                'Lainnya',

            default =>
                $status
                    ? ucfirst(
                        str_replace(
                            '_',
                            ' ',
                            $status
                        )
                    )
                    : '-',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS COLOR
    |--------------------------------------------------------------------------
    */

    public static function statusColor(
        ?string $status
    ): string {
        return match ($status) {
            'aman' =>
                'success',

            'licin',
            'berlumpur',
            'pohon_tumbang' =>
                'warning',

            'longsor',
            'jalur_tertutup',
            'jembatan_rusak' =>
                'danger',

            default =>
                'gray',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | PERMISSION
    |--------------------------------------------------------------------------
    |
    | Laporan berasal dari pendaki.
    |
    | Tidak boleh dibuat, diedit atau dihapus sembarangan melalui admin.
    |
    */

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(
        $record
    ): bool {
        return false;
    }

    public static function canDelete(
        $record
    ): bool {
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
                Pages\ListTrailReports::route(
                    '/'
                ),

            'view' =>
                Pages\ViewTrailReport::route(
                    '/{record}'
                ),
        ];
    }
}