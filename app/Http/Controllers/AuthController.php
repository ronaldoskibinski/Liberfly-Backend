<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Professor;
use App\Models\Responsavel;
use App\Models\TurmaProfessor;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private $service;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'newUser']]);
        $this->service = new AuthService();
    }

    private function validatorNewUser($data)
    {
        return Validator::make($data, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ], [
            'name.required' => 'O nome é obrigatório',
            'email.required' => 'O email é obrigatório',
            'password.required' => 'A senha é obrigatória',
        ]);
    }

    /**
     * @OA\Post (
     *     path="/api/auth/new-user",
     *     description="Create User",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *          required=true,
     *          description="New User",
     *           @OA\JsonContent(
     *              required={"name", "email", "password"},
     *              @OA\Property(property="name", type="string", format="string", example="Carlos"),
     *              @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *              @OA\Property(property="password", type="string", format="string", example=""),
     *          ),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Return",
     *    ),
     * )
     */
    public function newUser(Request $request)
    {
        $validate = $this->validatorNewUser($request->all());
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar usuário',
                'erros' => $validate->errors()->all()
            ], 500);
        }

        $data = $request->all();

        $service = new UserService();
        $model = $service->createUser($data);

        return response()->json($model, 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     * @OA\Post(
     * path="/api/auth/login",
     * summary="Login",
     * description="Login with email and password",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Login Credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     */
    public function login(Request $request)
    {
        return $this->service->login($request);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     * @OA\Get (
     *     path="/api/auth/me",
     *     description="My datas",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="",
     *    )
     * )
     */
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(["success" => true, "user" => $user]);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ], 401);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     * @OA\Post (
     *     path="/api/auth/logout",
     *     description="Do Logout",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="Return",
     *    ),
     * )
     */
    public function logout()
    {
        JWTAuth::parseToken()->invalidate();

        return response()->json([
            'success' => true,
            'message' => 'Usuário deslogado com sucesso!'
        ]);
    }
}
