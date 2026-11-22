<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProfilPasar;

class KontakController extends Controller
{
    public function index()
    {
        $profil = ProfilPasar::current();
        return view('public.kontak', compact('profil'));
    }
}