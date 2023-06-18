<?php


namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class LoginController
{
    public function showLoginForm() {
        return view('login.index');
    }
}
