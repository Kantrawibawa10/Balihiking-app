<?php

namespace App\Filament\Resources\UserRouteResource\Pages;

use App\Filament\Resources\UserRouteResource;
use Filament\Resources\Pages\ListRecords;

class ListUserRoutes extends ListRecords
{
    protected static string $resource =
        UserRouteResource::class;

    public function getTitle(): string
    {
        return 'Aktivitas Pendaki';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}