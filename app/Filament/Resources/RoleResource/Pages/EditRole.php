<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class EditRole extends EditRecord
{
    protected static string $resource =
        RoleResource::class;


    protected function afterSave(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ADMIN SELALU SEMUA PERMISSION
        |--------------------------------------------------------------------------
        */

        if (
            $this->record->name
            ===
            'admin'
        ) {
            $this->record
                ->syncPermissions(
                    Permission::query()
                        ->where(
                            'guard_name',
                            'web'
                        )
                        ->get()
                );
        }


        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();
    }


    protected function getHeaderActions(): array
    {
        return [

            Actions\DeleteAction::make()
                ->label(
                    'Hapus Role'
                )
                ->visible(
                    fn (): bool =>
                        RoleResource::canDelete(
                            $this->record
                        )
                ),

        ];
    }


    protected function getRedirectUrl(): string
    {
        return static::$resource
            ::getUrl(
                'index'
            );
    }
}