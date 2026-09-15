<?php

namespace App\Filament\Resources\UserRouteResource\Pages;

use App\Filament\Resources\UserRouteResource;
use Filament\Resources\Pages\ViewRecord;

class ViewUserRoute extends ViewRecord
{
    protected static string $resource =
        UserRouteResource::class;

    public function getTitle(): string
    {
        return 'Detail Aktivitas Pendaki';
    }

    public function getBreadcrumb(): string
    {
        return 'Detail';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}