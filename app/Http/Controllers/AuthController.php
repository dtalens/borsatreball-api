<?php

namespace App\Http\Controllers;

use App\Entities\User;
use App\Entities\Alumno;
use App\Entities\Empresa;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\LoginResource;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SignupActivate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function signup(UserStoreRequest $request)
    {
        $user = new User([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'rol'      => $request->rol,
            'activation_token' => Str::str_random(60),
         ]);

        DB::transaction(function () use ($user) {
            $user->save();
            if ($user->isAlumno()) Alumno::create(['nombre' => $user->name, 'id' => $user->id]);
            if ($user->isEmpresa()) Empresa::create(['nombre' => $user->name, 'id' => $user->id]);
        });
        $user->notify(new SignupActivate($user));
        return response()->json($this->getToken($user), 201);
    }

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json(['message' => 'El token de activación es inválido'], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->save();

        return redirect('/');
    }

    private function getToken($user){
        $tokenResult = $user->createToken('Token Acceso Personal');
        $token = $tokenResult->token;
        $token->save();

        //return new LoginResource($user,$tokenResult);
        return[
            'access_token' => $tokenResult->accessToken,
            'rol'          => $user->rol,
            'token_type'   => 'Bearer',
            'id'           => $user->id,
            'name'         => $user->name,
            'expires_at'   => Carbon::parse(
                $token->expires_at)
                ->toDateTimeString()];
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
        ]);
        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;

        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException('Unauthorized');
        }

        return response()->json(new LoginResource($request->user()), 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' =>
            'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
