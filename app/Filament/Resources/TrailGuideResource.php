<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrailGuideResource\Pages;
use App\Models\TrailGuide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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

    protected static ?int $navigationSort = 4;

    public static function form(
        Form $form
    ): Form {
        return $form
            ->schema([

                Forms\Components\Section::make(
                    'Informasi Panduan'
                )
                    ->schema([

                        Forms\Components\Select::make(
                            'hiking_trail_id'
                        )
                            ->label('Jalur Pendakian')
                            ->relationship(
                                'hikingTrail',
                                'name'
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make(
                            'type'
                        )
                            ->label('Jenis Informasi')
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
                            ->native(false),

                        Forms\Components\TextInput::make(
                            'title'
                        )
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make(
                            'content'
                        )
                            ->label('Isi Informasi')
                            ->required()
                            ->rows(7)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make(
                            'sort_order'
                        )
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Forms\Components\Toggle::make(
                            'is_active'
                        )
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(
        Table $table
    ): Table {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make(
                    'hikingTrail.mountain.name'
                )
                    ->label('Gunung')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make(
                    'hikingTrail.name'
                )
                    ->label('Jalur')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make(
                    'type'
                )
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            ?string $state
                        ): string =>
                            match ($state) {
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
                                        (string) $state
                                    ),
                            }
                    )
                    ->color(
                        fn (
                            ?string $state
                        ): string =>
                            match ($state) {
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

                Tables\Columns\TextColumn::make(
                    'title'
                )
                    ->label('Judul')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make(
                    'content'
                )
                    ->label('Isi')
                    ->limit(55),

                Tables\Columns\TextColumn::make(
                    'sort_order'
                )
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\IconColumn::make(
                    'is_active'
                )
                    ->label('Aktif')
                    ->boolean(),
            ])

            ->defaultSort(
                'sort_order'
            )

            ->filters([

                Tables\Filters\SelectFilter::make(
                    'type'
                )
                    ->label('Jenis')
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
                    ->label('Jalur')
                    ->relationship(
                        'hikingTrail',
                        'name'
                    )
                    ->searchable()
                    ->preload(),
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

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