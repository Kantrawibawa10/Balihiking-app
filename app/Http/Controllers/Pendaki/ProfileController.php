<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('pendaki.profil', compact('user'));
    }
}