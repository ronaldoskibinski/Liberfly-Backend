<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserLoginRepository;

class AuthService extends CrudService
{
    protected $repositoryClass = UserLoginRepository::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function login(Request $request)
    {
        try {
            $validator = $this->validator($request->all());

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao realizar o login, campos inválidos',
                    'erros' => $validator->errors()->all()
                ], 401);
            }

            $credentials = $request->only('email', 'password');

            if (!JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email ou Senha inválido.',
                ], 401);
            }

            return $this->authFromUser(JWTAuth::user(), $request);
        } catch (\Throwable $th) {
            return [
                "success" => false,
                "message" => "Erro ao alterar no banco de dados!",
                "trace" => env("APP_DEBUG", false) ? $th->getTraceAsString() : ""
            ];
        }
    }

    private function validator($data)
    {

        return Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|min:5'
        ], [
            'email.required' => 'O email é obrigatório',
            'password.required' => 'A senha é obrigatória'
        ]);
    }

    private function authFromUser($oUser, Request $request)
    {

        $oLoggedUser = User::find($oUser->id);

        if (empty($oLoggedUser) || !$userToken = JWTAuth::fromUser($oLoggedUser)) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao realizar o login, credenciais inválidas!',
            ], 401);
        }

        $usuarioLoginService = new UserLoginService();
        $usuarioLoginService->save([
            'fk_user' => $oLoggedUser->id,
            'data_start' => now(),
            'ip' => $request->ip(),
            'navigator' => $request->header('User-Agent') ?: null
        ]);

        return response()->json([
            'success' => true,
            'token' => $userToken,
            'user' => $oLoggedUser
        ]);
    }
}
