<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\GroupMenu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $validator = Validator::make($credentials, [
            'username' => 'required',
            'password' => 'required',
        ]);

        // Verificar si las credenciales son válidas
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid credentials'], 400);
        }

        $user = User::where("username", $request->username)->first();

        if (!$user) {
            return view('auth.login');
        }

        if (Hash::check($request->password, $user->password)) {
            // Autenticar al usuario

            Auth::loginUsingId($user->id);

            $token = $user->createToken('auth_token', ['expires' => now()->addHours(2)])->plainTextToken;
            $user = User::with(['typeUser'])->find($user->id);

            $typeUser = $user->typeUser;

            $groupMenu = GroupMenu::getFilteredGroupMenus($typeUser->id);

            return redirect()->route('vistaInicio');
        } else {
            return view('auth.login');
        }

    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        Auth::logout();
        return view('auth.login');
    }

}
