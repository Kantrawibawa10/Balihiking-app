<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class LiveTrackingMapWidget extends Widget
{
    protected static string $view = 'filament.widgets.live-tracking-map-widget';

    protected int|string|array $columnSpan = 'full';

    public function refreshMap(): void
    {
        // Dummy handler agar polling/button dari widget tidak memicu error MethodNotFound
        $this->dispatch('refresh-iframe');
    }

    // HAPUS ATAU HILANGKAN BAGIAN INI DARI LiveTracking.php:
    protected function getHeaderWidgets(): array
    {
        return [
            LiveTrackingMapWidget::class, // <-- Ini penyebab iframe ganda!
        ];
    }
}
