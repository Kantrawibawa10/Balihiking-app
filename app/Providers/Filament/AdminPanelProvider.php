<?php

namespace App\Providers\Filament;

use App\Filament\Pages\LiveTracking;
use App\Filament\Widgets\AdminStatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    /*
    |--------------------------------------------------------------------------
    | PANEL
    |--------------------------------------------------------------------------
    */

    public function panel(
        Panel $panel
    ): Panel {
        return $panel
            ->default()

            ->id(
                'admin'
            )

            ->path(
                'admin'
            )

            ->login()

            /*
            |--------------------------------------------------------------------------
            | BRAND
            |--------------------------------------------------------------------------
            */

            ->brandName(
                'BaliHiking Admin'
            )

            ->brandLogo(
                asset(
                    'icons/icon-192.png'
                )
            )

            ->brandLogoHeight(
                '2.5rem'
            )

            ->favicon(
                asset(
                    'icons/icon-192.png'
                )
            )

            /*
            |--------------------------------------------------------------------------
            | COLOR
            |--------------------------------------------------------------------------
            */

            ->colors([
                'primary' =>
                    Color::hex(
                        '#173D2E'
                    ),
            ])

            /*
            |--------------------------------------------------------------------------
            | RESOURCES
            |--------------------------------------------------------------------------
            */

            ->discoverResources(
                in:
                    app_path(
                        'Filament/Resources'
                    ),

                for:
                    'App\\Filament\\Resources'
            )

            /*
            |--------------------------------------------------------------------------
            | PAGES
            |--------------------------------------------------------------------------
            */

            ->discoverPages(
                in:
                    app_path(
                        'Filament/Pages'
                    ),

                for:
                    'App\\Filament\\Pages'
            )

            ->pages([
                Pages\Dashboard::class,
                LiveTracking::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | WIDGETS
            |--------------------------------------------------------------------------
            */

            ->discoverWidgets(
                in:
                    app_path(
                        'Filament/Widgets'
                    ),

                for:
                    'App\\Filament\\Widgets'
            )

            ->widgets([
                AdminStatsOverview::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | LOGIN STYLE
            |--------------------------------------------------------------------------
            */

            ->renderHook(
                PanelsRenderHook::HEAD_END,

                fn (): string =>
                    $this->isLoginPage()
                        ? Blade::render(
                            $this->loginStyles()
                        )
                        : ''
            )

            /*
            |--------------------------------------------------------------------------
            | LOGIN LEFT PANEL
            |--------------------------------------------------------------------------
            */

            ->renderHook(
                PanelsRenderHook::BODY_START,

                fn (): string =>
                    $this->isLoginPage()
                        ? Blade::render(
                            $this->loginShell()
                        )
                        : ''
            )

            /*
            |--------------------------------------------------------------------------
            | BODY SCRIPT
            |--------------------------------------------------------------------------
            */

            ->renderHook(
                PanelsRenderHook::BODY_END,

                fn (): string =>
                    Blade::render(
                        $this->bodyScripts()
                    )
            )

            /*
            |--------------------------------------------------------------------------
            | MIDDLEWARE
            |--------------------------------------------------------------------------
            */

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | AUTH
            |--------------------------------------------------------------------------
            */

            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN PAGE
    |--------------------------------------------------------------------------
    */

    private function isLoginPage(): bool
    {
        return request()->is(
            'admin/login'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN STYLES
    |--------------------------------------------------------------------------
    */

    private function loginStyles(): string
    {
        return <<<'HTML'

<style>
    /*
    |--------------------------------------------------------------------------
    | LIGHT VARIABLES
    |--------------------------------------------------------------------------
    */

    :root {
        --bh-orange:
            #f26335;

        --bh-orange-hover:
            #e9572d;

        --bh-orange-soft:
            #fff1eb;


        /*
        |--------------------------------------------------------------------------
        | PAGE
        |--------------------------------------------------------------------------
        */

        --bh-page:
            #f4f7f5;

        --bh-panel:
            #ffffff;

        --bh-input:
            #ffffff;


        /*
        |--------------------------------------------------------------------------
        | TEXT
        |--------------------------------------------------------------------------
        */

        --bh-text:
            #17241f;

        --bh-text-soft:
            #45574e;

        --bh-muted:
            #7b8e84;

        --bh-placeholder:
            #9dafb5;


        /*
        |--------------------------------------------------------------------------
        | BORDER
        |--------------------------------------------------------------------------
        */

        --bh-border:
            rgba(
                18,
                61,
                44,
                .14
            );

        --bh-border-soft:
            rgba(
                18,
                61,
                44,
                .07
            );


        /*
        |--------------------------------------------------------------------------
        | LEFT PANEL
        |--------------------------------------------------------------------------
        */

        --bh-left-start:
            #08291d;

        --bh-left-middle:
            #103c2c;

        --bh-left-end:
            #14533b;

        --bh-left-text:
            #ffffff;

        --bh-left-muted:
            rgba(
                255,
                255,
                255,
                .57
            );

        --bh-left-muted-soft:
            rgba(
                255,
                255,
                255,
                .38
            );

        --bh-left-card:
            rgba(
                255,
                255,
                255,
                .075
            );


        /*
        |--------------------------------------------------------------------------
        | SHELL
        |--------------------------------------------------------------------------
        */

        --bh-shell-width:
            min(
                940px,
                calc(
                    100vw -
                    64px
                )
            );

        --bh-shell-height:
            min(
                520px,
                calc(
                    100dvh -
                    64px
                )
            );

        --bh-left-width:
            44%;
    }


    /*
    |--------------------------------------------------------------------------
    | DARK VARIABLES
    |--------------------------------------------------------------------------
    |
    | Filament memberikan class "dark" pada <html>.
    |--------------------------------------------------------------------------
    */

    html.dark {
        --bh-page:
            #07110d;

        --bh-panel:
            #0d1b15;

        --bh-input:
            #12241b;


        --bh-text:
            #f4f7f5;

        --bh-text-soft:
            #c5d0ca;

        --bh-muted:
            #8da098;

        --bh-placeholder:
            #6f8278;


        --bh-border:
            rgba(
                255,
                255,
                255,
                .11
            );

        --bh-border-soft:
            rgba(
                255,
                255,
                255,
                .07
            );


        --bh-left-start:
            #04150f;

        --bh-left-middle:
            #08271c;

        --bh-left-end:
            #0d3b2a;


        --bh-left-text:
            #ffffff;

        --bh-left-muted:
            rgba(
                255,
                255,
                255,
                .58
            );

        --bh-left-muted-soft:
            rgba(
                255,
                255,
                255,
                .38
            );

        --bh-left-card:
            rgba(
                255,
                255,
                255,
                .065
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RESET
    |--------------------------------------------------------------------------
    */

    *,
    *::before,
    *::after {
        box-sizing:
            border-box;
    }


    html,
    body {
        min-height:
            100%;
    }


    body.bh-login {
        overflow:
            hidden !important;

        background:
            var(--bh-page)
            !important;

        transition:
            background-color .2s ease;
    }


    /*
    |--------------------------------------------------------------------------
    | TOP BAR
    |--------------------------------------------------------------------------
    */

    body.bh-login::before {
        content:
            '';

        position:
            fixed;

        top:
            0;

        right:
            0;

        left:
            0;

        z-index:
            9999;

        height:
            5px;

        background:
            #283746;
    }


    html.dark
    body.bh-login::before {
        background:
            #101b16;
    }


    /*
    |--------------------------------------------------------------------------
    | PAGE
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-layout {
        min-height:
            100dvh !important;

        background:
            radial-gradient(
                circle at 90% 10%,
                rgba(
                    242,
                    99,
                    53,
                    .04
                ),
                transparent 28rem
            ),
            radial-gradient(
                circle at 10% 90%,
                rgba(
                    18,
                    61,
                    44,
                    .05
                ),
                transparent 30rem
            ),
            var(--bh-page)
            !important;

        transition:
            background-color .2s ease;
    }


    html.dark
    body.bh-login
    .fi-simple-layout {
        background:
            radial-gradient(
                circle at 90% 8%,
                rgba(
                    242,
                    99,
                    53,
                    .055
                ),
                transparent 28rem
            ),
            radial-gradient(
                circle at 10% 90%,
                rgba(
                    37,
                    110,
                    76,
                    .10
                ),
                transparent 32rem
            ),
            var(--bh-page)
            !important;
    }


    /*
    |--------------------------------------------------------------------------
    | MASTER SHELL
    |--------------------------------------------------------------------------
    */

    .bh-login-shell {
        position:
            fixed;

        top:
            50%;

        left:
            50%;

        z-index:
            2;

        width:
            var(--bh-shell-width);

        height:
            var(--bh-shell-height);

        transform:
            translate(
                -50%,
                -50%
            );

        overflow:
            hidden;

        border:
            1px solid
            var(--bh-border);

        border-radius:
            20px;

        background:
            var(--bh-panel);

        box-shadow:
            0 25px 65px
            rgba(
                28,
                52,
                40,
                .11
            );

        transition:
            background-color .2s ease,
            border-color .2s ease;
    }


    html.dark
    .bh-login-shell {
        box-shadow:
            0 28px 75px
            rgba(
                0,
                0,
                0,
                .35
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FILAMENT CONTAINER
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-main-ctn {
        position:
            fixed !important;

        top:
            50% !important;

        left:
            50% !important;

        z-index:
            10;

        width:
            var(--bh-shell-width)
            !important;

        height:
            var(--bh-shell-height)
            !important;

        min-height:
            0 !important;

        transform:
            translate(
                -50%,
                -50%
            );

        display:
            grid !important;

        grid-template-columns:
            44%
            56% !important;

        align-items:
            stretch !important;

        padding:
            0 !important;

        overflow:
            hidden;

        border:
            none !important;

        border-radius:
            20px;

        background:
            transparent !important;

        box-shadow:
            none !important;
    }


    /*
    |--------------------------------------------------------------------------
    | RIGHT LOGIN AREA
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-main {
        grid-column:
            2 !important;

        align-self:
            stretch !important;

        justify-self:
            center !important;

        display:
            flex !important;

        width:
            396px !important;

        min-width:
            0 !important;

        max-width:
            calc(
                100% -
                72px
            )
            !important;

        height:
            100% !important;

        flex-direction:
            column !important;

        margin:
            0 !important;

        padding:
            46px
            0
            46px
            !important;

        border:
            none !important;

        border-radius:
            0 !important;

        background:
            transparent !important;

        color:
            var(--bh-text)
            !important;

        box-shadow:
            none !important;
    }


    /*
    |--------------------------------------------------------------------------
    | CHILD WIDTH
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-main > *,

    body.bh-login
    .fi-simple-main form,

    body.bh-login
    .fi-simple-main form > *,

    body.bh-login
    .fi-form,

    body.bh-login
    .fi-form > *,

    body.bh-login
    .fi-fo-component-ctn {
        width:
            100% !important;

        max-width:
            none !important;
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-header {
        display:
            flex !important;

        width:
            100% !important;

        flex-direction:
            column !important;

        align-items:
            flex-start !important;

        margin:
            0 0 31px
            !important;

        text-align:
            left !important;
    }


    /*
    |--------------------------------------------------------------------------
    | HIDE DEFAULT LOGO
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-header
    .fi-logo {
        display:
            none !important;
    }


    /*
    |--------------------------------------------------------------------------
    | WELCOME
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-header::before {
        content:
            'WELCOME BACK';

        display:
            block;

        margin-bottom:
            7px;

        color:
            var(--bh-orange);

        font-size:
            8px;

        font-weight:
            900;

        letter-spacing:
            .15em;
    }


    /*
    |--------------------------------------------------------------------------
    | TITLE
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-header-heading {
        width:
            100% !important;

        margin:
            0 !important;

        color:
            var(--bh-text)
            !important;

        font-size:
            28px !important;

        line-height:
            1 !important;

        font-weight:
            900 !important;

        letter-spacing:
            -.045em !important;

        text-align:
            left !important;
    }


    body.bh-login
    .fi-simple-header-subheading {
        display:
            none !important;
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOM SUBTITLE
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-header::after {
        content:
            'Gunakan akun Administrator BaliHiking untuk mengakses dashboard.';

        display:
            block;

        width:
            100%;

        margin-top:
            10px;

        padding-bottom:
            20px;

        color:
            var(--bh-muted);

        font-size:
            9px;

        font-weight:
            500;

        line-height:
            1.55;

        background:
            linear-gradient(
                to right,
                var(--bh-orange) 0,
                var(--bh-orange) 42px,
                transparent 42px
            )
            bottom left
            /
            100%
            3px
            no-repeat;
    }


    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-main form {
        display:
            flex !important;

        width:
            100% !important;

        flex-direction:
            column !important;
    }


    body.bh-login
    .fi-fo-component-ctn {
        display:
            flex !important;

        width:
            100% !important;

        flex-direction:
            column !important;

        gap:
            16px !important;
    }


    body.bh-login
    .fi-fo-field-wrp {
        width:
            100% !important;
    }


    /*
    |--------------------------------------------------------------------------
    | LABEL
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-fo-field-wrp-label,

    body.bh-login
    .fi-fo-field-wrp-label label,

    body.bh-login
    .fi-fo-field-wrp-label span {
        color:
            var(--bh-text)
            !important;

        font-size:
            11px !important;

        font-weight:
            700 !important;

        text-align:
            left !important;
    }


    /*
    |--------------------------------------------------------------------------
    | INPUT WRAPPER
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-input-wrp {
        width:
            100% !important;

        min-height:
            47px !important;

        overflow:
            hidden;

        border:
            1px solid
            var(--bh-border)
            !important;

        border-radius:
            11px !important;

        background:
            var(--bh-input)
            !important;

        box-shadow:
            0 1px 4px
            rgba(
                18,
                61,
                44,
                .035
            )
            !important;

        transition:
            background-color .2s ease,
            border-color .15s ease,
            box-shadow .15s ease;
    }


    body.bh-login
    .fi-input-wrp:hover {
        border-color:
            color-mix(
                in srgb,
                var(--bh-text)
                24%,
                transparent
            )
            !important;
    }


    body.bh-login
    .fi-input-wrp:focus-within {
        border-color:
            rgba(
                242,
                99,
                53,
                .70
            )
            !important;

        box-shadow:
            0 0 0 3px
            rgba(
                242,
                99,
                53,
                .09
            )
            !important;
    }


    /*
    |--------------------------------------------------------------------------
    | INPUT
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-input {
        width:
            100% !important;

        min-height:
            45px !important;

        padding:
            0 14px
            !important;

        color:
            var(--bh-text)
            !important;

        background:
            transparent
            !important;

        font-size:
            10px !important;

        font-weight:
            500 !important;

        caret-color:
            var(--bh-orange);
    }


    body.bh-login
    .fi-input::placeholder {
        color:
            var(--bh-placeholder)
            !important;
    }


    /*
    |--------------------------------------------------------------------------
    | AUTOFILL
    |--------------------------------------------------------------------------
    |
    | Mencegah Chrome mengubah input menjadi abu/biru terang.
    |--------------------------------------------------------------------------
    */

    body.bh-login
    input:-webkit-autofill,

    body.bh-login
    input:-webkit-autofill:hover,

    body.bh-login
    input:-webkit-autofill:focus,

    body.bh-login
    input:-webkit-autofill:active {
        -webkit-text-fill-color:
            var(--bh-text)
            !important;

        -webkit-box-shadow:
            0 0 0 1000px
            var(--bh-input)
            inset
            !important;

        transition:
            background-color
            9999s
            ease-in-out
            0s;
    }


    /*
    |--------------------------------------------------------------------------
    | PASSWORD ICON
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-input-wrp-btn {
        min-width:
            47px;

        border-left:
            1px solid
            var(--bh-border-soft);

        color:
            var(--bh-muted)
            !important;
    }


    body.bh-login
    .fi-input-wrp-btn:hover {
        color:
            var(--bh-orange)
            !important;
    }


    /*
    |--------------------------------------------------------------------------
    | REMEMBER CHECKBOX
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-main
    input[type="checkbox"],

    body.bh-login
    .fi-checkbox-input {
        position:
            relative;

        display:
            inline-grid !important;

        width:
            18px !important;

        min-width:
            18px !important;

        height:
            18px !important;

        min-height:
            18px !important;

        flex:
            0 0 18px !important;

        place-content:
            center;

        margin:
            0 !important;

        padding:
            0 !important;

        appearance:
            none !important;

        -webkit-appearance:
            none !important;

        border:
            1.5px solid
            var(--bh-border)
            !important;

        border-radius:
            5px !important;

        background:
            var(--bh-input)
            !important;

        box-shadow:
            0 1px 2px
            rgba(
                0,
                0,
                0,
                .06
            )
            !important;

        cursor:
            pointer;

        transition:
            background-color .15s ease,
            border-color .15s ease,
            box-shadow .15s ease;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECKBOX HOVER
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-main
    input[type="checkbox"]:hover,

    body.bh-login
    .fi-checkbox-input:hover {
        border-color:
            var(--bh-orange)
            !important;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECKBOX FOCUS
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-main
    input[type="checkbox"]:focus-visible,

    body.bh-login
    .fi-checkbox-input:focus-visible {
        outline:
            none !important;

        border-color:
            var(--bh-orange)
            !important;

        box-shadow:
            0 0 0 3px
            rgba(
                242,
                99,
                53,
                .12
            )
            !important;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECKED
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-main
    input[type="checkbox"]:checked,

    body.bh-login
    .fi-checkbox-input:checked {
        border-color:
            var(--bh-orange)
            !important;

        background:
            var(--bh-orange)
            !important;
    }


    body.bh-login
    .fi-simple-main
    input[type="checkbox"]:checked::before,

    body.bh-login
    .fi-checkbox-input:checked::before {
        content:
            '';

        width:
            8px;

        height:
            4px;

        margin-top:
            -2px;

        border-left:
            2px solid
            #ffffff;

        border-bottom:
            2px solid
            #ffffff;

        transform:
            rotate(
                -45deg
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REMEMBER LABEL
    |--------------------------------------------------------------------------
    */

    body.bh-login
    label:has(
        input[type="checkbox"]
    ) {
        display:
            inline-flex !important;

        align-items:
            center !important;

        gap:
            10px !important;

        color:
            var(--bh-text)
            !important;
    }


    /*
    |--------------------------------------------------------------------------
    | BUTTON
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-btn.fi-color-primary {
        width:
            100% !important;

        min-height:
            44px !important;

        margin-top:
            2px;

        border:
            none !important;

        border-radius:
            10px !important;

        background:
            var(--bh-orange)
            !important;

        color:
            #ffffff !important;

        font-size:
            10px !important;

        font-weight:
            850 !important;

        box-shadow:
            none !important;

        transition:
            background-color .15s ease,
            opacity .12s ease,
            transform .12s ease;
    }


    body.bh-login
    .fi-btn.fi-color-primary:hover {
        background:
            var(--bh-orange-hover)
            !important;
    }


    body.bh-login
    .fi-btn.fi-color-primary:active {
        transform:
            scale(.988);
    }


    /*
    |--------------------------------------------------------------------------
    | FOOTER RIGHT
    |--------------------------------------------------------------------------
    */

    body.bh-login
    .fi-simple-main::after {
        content:
            'Akses terbatas untuk Administrator BaliHiking yang telah terdaftar.';

        display:
            block;

        width:
            100%;

        margin-top:
            auto;

        padding-top:
            16px;

        padding-bottom:
            3px;

        border-top:
            1px solid
            var(--bh-border-soft);

        color:
            var(--bh-muted);

        font-size:
            6.5px;

        font-weight:
            500;

        line-height:
            1.5;

        text-align:
            center;
    }


    /*
    |--------------------------------------------------------------------------
    | LEFT PANEL
    |--------------------------------------------------------------------------
    */

    .bh-login-left {
        position:
            relative;

        width:
            var(--bh-left-width);

        height:
            100%;

        overflow:
            hidden;

        color:
            var(--bh-left-text);

        background:
            linear-gradient(
                150deg,
                var(--bh-left-start)
                0%,
                var(--bh-left-middle)
                56%,
                var(--bh-left-end)
                100%
            );

        transition:
            background .2s ease;
    }


    /*
    |--------------------------------------------------------------------------
    | LEFT DECORATION
    |--------------------------------------------------------------------------
    */

    .bh-login-left::before {
        content:
            '';

        position:
            absolute;

        top:
            -145px;

        right:
            -100px;

        width:
            310px;

        height:
            310px;

        border-radius:
            50%;

        background:
            rgba(
                255,
                255,
                255,
                .04
            );
    }


    .bh-login-left::after {
        content:
            '';

        position:
            absolute;

        right:
            -90px;

        bottom:
            -190px;

        width:
            360px;

        height:
            360px;

        border-radius:
            50%;

        background:
            rgba(
                242,
                99,
                53,
                .055
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LEFT INNER
    |--------------------------------------------------------------------------
    */

    .bh-left-inner {
        position:
            relative;

        z-index:
            2;

        display:
            flex;

        height:
            100%;

        flex-direction:
            column;

        padding:
            38px;
    }


    /*
    |--------------------------------------------------------------------------
    | BRAND
    |--------------------------------------------------------------------------
    */

    .bh-brand {
        display:
            flex;

        align-items:
            center;

        gap:
            11px;
    }


    .bh-brand-icon {
        display:
            flex;

        width:
            40px;

        height:
            40px;

        flex:
            none;

        align-items:
            center;

        justify-content:
            center;

        border:
            1px solid
            rgba(
                255,
                255,
                255,
                .12
            );

        border-radius:
            10px;

        background:
            rgba(
                255,
                255,
                255,
                .07
            );
    }


    .bh-brand-icon img {
        width:
            32px;

        height:
            32px;

        border-radius:
            8px;

        object-fit:
            cover;
    }


    .bh-brand strong {
        display:
            block;

        color:
            var(--bh-left-text);

        font-size:
            12px;

        line-height:
            1.2;

        font-weight:
            900;
    }


    .bh-brand small {
        display:
            block;

        margin-top:
            3px;

        color:
            rgba(
                255,
                255,
                255,
                .42
            );

        font-size:
            6px;

        font-weight:
            700;

        letter-spacing:
            .08em;

        text-transform:
            uppercase;
    }


    /*
    |--------------------------------------------------------------------------
    | LEFT CONTENT
    |--------------------------------------------------------------------------
    */

    .bh-left-content {
        margin-top:
            40px;
    }


    .bh-eyebrow {
        color:
            #72ddb3;

        font-size:
            7px;

        font-weight:
            900;

        letter-spacing:
            .16em;

        text-transform:
            uppercase;
    }


    .bh-title {
        max-width:
            345px;

        margin:
            12px 0 0;

        color:
            var(--bh-left-text);

        font-size:
            29px;

        line-height:
            1.03;

        font-weight:
            900;

        letter-spacing:
            -.05em;
    }


    .bh-title span {
        color:
            var(--bh-orange);
    }


    .bh-description {
        max-width:
            330px;

        margin:
            15px 0 0;

        color:
            var(--bh-left-muted);

        font-size:
            7.5px;

        line-height:
            1.7;
    }


    /*
    |--------------------------------------------------------------------------
    | FEATURES
    |--------------------------------------------------------------------------
    */

    .bh-features {
        display:
            flex;

        flex-direction:
            column;

        gap:
            10px;

        margin-top:
            22px;
    }


    .bh-feature {
        display:
            grid;

        grid-template-columns:
            31px
            minmax(
                0,
                1fr
            );

        align-items:
            center;

        gap:
            10px;
    }


    .bh-feature-icon {
        display:
            flex;

        width:
            31px;

        height:
            31px;

        align-items:
            center;

        justify-content:
            center;

        border-radius:
            8px;

        background:
            var(--bh-left-card);

        color:
            #73ddb4;
    }


    .bh-feature-icon svg {
        width:
            14px;

        height:
            14px;
    }


    .bh-feature strong {
        display:
            block;

        color:
            rgba(
                255,
                255,
                255,
                .93
            );

        font-size:
            7.5px;

        font-weight:
            850;
    }


    .bh-feature small {
        display:
            block;

        margin-top:
            2px;

        color:
            var(--bh-left-muted-soft);

        font-size:
            5.8px;

        line-height:
            1.45;
    }


    /*
    |--------------------------------------------------------------------------
    | LEFT FOOTER
    |--------------------------------------------------------------------------
    */

    .bh-left-footer {
        display:
            flex;

        align-items:
            center;

        justify-content:
            space-between;

        margin-top:
            auto;

        color:
            rgba(
                255,
                255,
                255,
                .29
            );

        font-size:
            5.5px;
    }


    .bh-secure {
        display:
            flex;

        align-items:
            center;

        gap:
            5px;
    }


    .bh-dot {
        width:
            6px;

        height:
            6px;

        border-radius:
            50%;

        background:
            #35dc82;

        box-shadow:
            0 0 0 3px
            rgba(
                53,
                220,
                130,
                .10
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TABLET
    |--------------------------------------------------------------------------
    */

    @media (
        max-width: 900px
    ) {
        :root {
            --bh-left-width:
                40%;
        }


        body.bh-login
        .fi-simple-main-ctn {
            grid-template-columns:
                40%
                60%
                !important;
        }


        body.bh-login
        .fi-simple-main {
            width:
                360px !important;

            max-width:
                calc(
                    100% -
                    44px
                )
                !important;

            padding:
                40px
                0
                42px
                !important;
        }


        .bh-left-inner {
            padding:
                30px;
        }


        .bh-title {
            font-size:
                25px;
        }


        .bh-feature:last-child {
            display:
                none;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    @media (
        max-width: 700px
    ) {
        body.bh-login {
            overflow-y:
                auto !important;
        }


        .bh-login-shell {
            display:
                none;
        }


        body.bh-login
        .fi-simple-main-ctn {
            position:
                static !important;

            width:
                100% !important;

            height:
                auto !important;

            min-height:
                100dvh !important;

            transform:
                none;

            display:
                flex !important;

            align-items:
                center !important;

            justify-content:
                center !important;

            padding:
                20px
                16px
                !important;

            background:
                var(--bh-page)
                !important;
        }


        body.bh-login
        .fi-simple-main {
            display:
                flex !important;

            width:
                100% !important;

            max-width:
                410px !important;

            height:
                auto !important;

            padding:
                26px
                21px
                30px
                !important;

            border:
                1px solid
                var(--bh-border)
                !important;

            border-radius:
                20px !important;

            background:
                var(--bh-panel)
                !important;

            box-shadow:
                0 18px 50px
                rgba(
                    0,
                    0,
                    0,
                    .09
                )
                !important;
        }


        html.dark
        body.bh-login
        .fi-simple-main {
            box-shadow:
                0 18px 55px
                rgba(
                    0,
                    0,
                    0,
                    .30
                )
                !important;
        }


        body.bh-login
        .fi-simple-header-heading {
            font-size:
                27px !important;
        }


        body.bh-login
        .fi-simple-main::after {
            margin-top:
                26px;
        }
    }
</style>

HTML;
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN SHELL
    |--------------------------------------------------------------------------
    */

    private function loginShell(): string
    {
        return <<<'HTML'

<script>
    document
        .body
        .classList
        .add(
            'bh-login'
        );
</script>


<div
    class="bh-login-shell"
    aria-hidden="true"
>

    <section class="bh-login-left">

        <div class="bh-left-inner">

            {{-- ================================================= --}}
            {{-- BRAND --}}
            {{-- ================================================= --}}

            <div class="bh-brand">

                <div class="bh-brand-icon">

                    <img
                        src="{{ asset('icons/icon-192.png') }}"
                        alt=""
                    >

                </div>


                <div>

                    <strong>
                        BaliHiking Admin
                    </strong>

                    <small>
                        Administration Portal
                    </small>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- CONTENT --}}
            {{-- ================================================= --}}

            <div class="bh-left-content">

                <div class="bh-eyebrow">
                    Hiking Management
                </div>


                <h2 class="bh-title">

                    Mountain Operations
                    &

                    <span>
                        Safety Center
                    </span>

                </h2>


                <p class="bh-description">
                    Portal informasi untuk membantu administrator
                    mengelola aktivitas pendakian, perizinan SIMAKSI,
                    jalur pendakian dan pemantauan keselamatan pendaki.
                </p>


                {{-- ============================================= --}}
                {{-- FEATURES --}}
                {{-- ============================================= --}}

                <div class="bh-features">

                    {{-- LIVE TRACKING --}}

                    <div class="bh-feature">

                        <div class="bh-feature-icon">

                            <svg
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="8"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 8v4l3 2"
                                />
                            </svg>

                        </div>


                        <div>

                            <strong>
                                Live Tracking
                            </strong>

                            <small>
                                Monitoring perjalanan pendaki
                                secara real-time.
                            </small>

                        </div>

                    </div>


                    {{-- SIMAKSI --}}

                    <div class="bh-feature">

                        <div class="bh-feature-icon">

                            <svg
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 3h9l3 3v15H6V3Z"
                                />

                                <path
                                    stroke-linecap="round"
                                    d="M9 11h6M9 15h4"
                                />
                            </svg>

                        </div>


                        <div>

                            <strong>
                                SIMAKSI Management
                            </strong>

                            <small>
                                Verifikasi dan pengelolaan
                                izin pendakian.
                            </small>

                        </div>

                    </div>


                    {{-- TRAIL --}}

                    <div class="bh-feature">

                        <div class="bh-feature-icon">

                            <svg
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m3 19 6-10 4 6 3-4 5 8H3Z"
                                />
                            </svg>

                        </div>


                        <div>

                            <strong>
                                Trail Management
                            </strong>

                            <small>
                                Kelola gunung, jalur dan
                                checkpoint pendakian.
                            </small>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- FOOTER --}}
            {{-- ================================================= --}}

            <div class="bh-left-footer">

                <span>
                    © {{ date('Y') }} BaliHiking
                </span>


                <div class="bh-secure">

                    <span class="bh-dot"></span>

                    Secure Access

                </div>

            </div>

        </div>

    </section>

</div>

HTML;
    }

    /*
    |--------------------------------------------------------------------------
    | BODY SCRIPT
    |--------------------------------------------------------------------------
    */

    private function bodyScripts(): string
    {
        return <<<'HTML'

<script>
    /*
    |--------------------------------------------------------------------------
    | PLACEHOLDER
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        () => {
            if (
                window.location.pathname
                !==
                '/admin/login'
            ) {
                return;
            }


            const email =
                document.querySelector(
                    'input[type="email"]'
                );


            const password =
                document.querySelector(
                    'input[type="password"]'
                );


            if (email) {
                email.placeholder =
                    'admin@balihiking.id';
            }


            if (password) {
                password.placeholder =
                    'Masukkan password';
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | SERVICE WORKER
    |--------------------------------------------------------------------------
    */

    if (
        'serviceWorker'
        in navigator
    ) {
        window.addEventListener(
            'load',
            () => {
                navigator
                    .serviceWorker
                    .register(
                        '/sw.js'
                    )
                    .catch(
                        console.error
                    );
            }
        );
    }
</script>

HTML;
    }
}