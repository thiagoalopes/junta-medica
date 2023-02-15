<?php

namespace App\Http\Controllers\Auth;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['sair','alterarSenha']);
    }

    public function sair()
    {
        if(Auth::user() != null)
        {
            Auth::logout();
        }
        return response()->json(null, 203);
    }

}
