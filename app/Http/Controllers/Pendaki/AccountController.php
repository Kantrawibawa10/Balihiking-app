<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Edit Account
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request): View
    {
        return view(
            'pendaki.profile.account',
            [
                'user' => $request->user(),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Account
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request
    ): RedirectResponse {

        $user = $request->user();


        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:190',

                Rule::unique(
                    'users',
                    'email'
                )->ignore(
                    $user->id
                ),
            ],
        ]);


        $emailChanged =
            $user->email !==
            $validated['email'];


        $user->name =
            $validated['name'];

        $user->email =
            $validated['email'];


        /*
        |--------------------------------------------------------------------------
        | Reset verification ketika email berubah
        |--------------------------------------------------------------------------
        */

        if ($emailChanged) {
            $user->email_verified_at = null;
        }


        $user->save();


        return back()->with(
            'success',
            'Data akun berhasil diperbarui.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Password
    |--------------------------------------------------------------------------
    */

    public function updatePassword(
        Request $request
    ): RedirectResponse {

        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:current_password',
            ],
        ], [
            'current_password.current_password' =>
                'Password saat ini tidak sesuai.',

            'password.confirmed' =>
                'Konfirmasi password baru tidak sesuai.',

            'password.min' =>
                'Password baru minimal 8 karakter.',

            'password.different' =>
                'Password baru harus berbeda dari password lama.',
        ]);


        $request
            ->user()
            ->update([
                'password' => Hash::make(
                    $validated['password']
                ),
            ]);


        return back()->with(
            'password_success',
            'Password berhasil diperbarui.'
        );
    }
}