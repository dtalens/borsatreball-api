<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alumno;
use App\Models\Empresa;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\LoginResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use App\Notifications\SignupActivate;


/**
 * @OA\Post(
 * path="/api/auth/login",
 * summary="Sign in",
 * description="Login by email, password",
 * operationId="authLogin",
 * tags={"auth"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
 *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Resposta usuari autenticat",
 *    @OA\JsonContent(ref="#/components/schemas/LoginResource")
 * ),
 * @OA\Response(
 *    response=421,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Login or password are wrong.")
 *        )
 *     )
 * )
 */


/**
 * @OA\Post(
 * path="/api/auth/signup",
 * summary="Sign up",
 * description="Register user",
 * operationId="authSignup",
 * tags={"auth"},
 * @OA\RequestBody(
 *    required=true,
 *    description="User fields",
 *    @OA\JsonContent(ref="#/components/schemas/UserStoreRequest")
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Resposta usuari autenticat",
 *    @OA\JsonContent(ref="#/components/schemas/LoginResource")
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Dades invalides",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="The given data was invalid."),
 *       @OA\Property(property="errors", type="string", example="['email'=>'The field email is required.']")
 *     )
 * )
 * )
 */

class AuthController extends Controller
{

    public function signup(UserStoreRequest $request)
    {
        $user = new User([
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'rol'      => $request->rol,
            'activation_token' => Str::random(60),
         ]);

        DB::transaction(function () use ($user,$request) {
            $user->save();
            if ($user->isAlumno()){
                Alumno::create(['nombre' => $request->nombre, 'id' => $user->id,
                    'domicilio'=>$request->domicilio,'telefono'=>$request->telefono,
                    'apellidos'=>$request->apellidos,'info'=>$request->info??0,'bolsa'=>$request->bolsa??0,
                    'cv_enlace'=>$request->cv_enlace]);
            } elseif ($user->isEmpresa()) {
                Empresa::create(['nombre' => $request->nombre, 'id' => $user->id,
                'domicilio'=>$request->domicilio,'telefono'=>$request->telefono,
                'cif'=>$request->cif,'localidad'=>$request->localidad,'contacto'=>$request->contacto,
                'web'=>$request->web,'descripcion'=>$request->descripcion]);
            } else {
                throw new UnauthorizedException('The given rol was invalid.');
            }
        });
        $this->getToken($user);
        $user->notify(new SignupActivate($user));
        return new LoginResource($user);
    }

    private function getToken($user){
        $tokenResult = $user->createToken('Token Acceso Personal');
        $token = $tokenResult->token;
        $token->save();
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
            throw new AuthenticationException('Login or password are wrong.');
        }

        return new LoginResource($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' =>
            'Successfully logged out']);
    }


}
