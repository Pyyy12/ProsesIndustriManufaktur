<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function index()
    {
        // Karena data dasar (nama, nim/nip, type) sudah ada di Session,
        // kita hanya perlu mengarahkan ke view.
        // Jika Anda perlu mengambil data tambahan dari database, Anda bisa melakukannya di sini.

        return view('profile.index');
    }
}