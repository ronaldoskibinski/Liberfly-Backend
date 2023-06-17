<?php


namespace App\Http\Controllers;

use App\Services\PasswordService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends BaseCrudController
{
    public function __construct()
    {
        parent::__construct();
        $this->service = new UserService();
    }

    protected function buildWheres(Request $request)
    {
        $name = $request->query("name", null);
        $email = $request->query("email", null);

        $wheres = [];

        if ($name) {
            array_push($wheres, [
                "coluna" => "name",
                "condicional" => "like",
                "valor" => "%" . $name . "%"
            ]);
        }

        if ($email) {
            array_push($wheres, [
                "coluna" => "email",
                "condicional" => "like",
                "valor" => "%" . $email . "%"
            ]);
        }

        return $wheres;
    }

    /**
     * Display a count of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function count(Request $request)
    {
        return parent::count($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @OA\Get (
     *     path="/api/user",
     *     description="List user data",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="",
     *    ),
     *    @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          description="Data page",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="take",
     *          in="query",
     *          required=false,
     *          description="Register Quantity per page",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     )
     * )
     */
    public function index(Request $request)
    {
        return parent::index($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     * @OA\Get (
     *     path="/api/user/{id}",
     *     description="List user data",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *          response="200",
     *          description="",
     *     ),
     *    @OA\Parameter(
     *          @OA\Schema(
     *              type="string"
     *          ),
     *          name="id",
     *          in="path",
     *          description="User id.",
     *          required=true
     *     )
     * )
     */
    public function show(Request $request, $id)
    {
        return parent::show($request, $id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post (
     *     path="/api/user",
     *     description="Create user",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          description="User data",
     *          @OA\JsonContent(
     *              required={"nome", "email", "password"},
     *              @OA\Property(property="nome", type="string", format="string", example=""),
     *              @OA\Property(property="email", type="string", format="email", example="user1@mail.com")
     *          ),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Return",
     *    )
     * )
     */
    public function store(Request $request)
    {
        return parent::store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     *
     * @OA\Put (
     *     path="/api/user/{id}",
     *     description="Change user",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          description="User data",
     *          @OA\JsonContent(
     *              required={"nome", "email", "password"},
     *              @OA\Property(property="nome", type="string", format="string", example=""),
     *              @OA\Property(property="email", type="string", format="email", example="user1@mail.com")
     *          ),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Return",
     *    ),
     *    @OA\Parameter(
     *          @OA\Schema(
     *              type="string"
     *          ),
     *          name="id",
     *          in="path",
     *          description="User id.",
     *          required=true
     *    )
     * )
     */
    public function update(Request $request, $id)
    {
        return parent::update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     * @OA\Delete (
     *     path="/api/user/{id}",
     *     description="Delete user",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *          response="200",
     *          description="",
     *     ),
     *    @OA\Parameter(
     *          @OA\Schema(
     *              type="string"
     *          ),
     *          name="id",
     *          in="path",
     *          description="User id.",
     *          required=true
     *     )
     * )
     */
    public function destroy(Request $request, $id)
    {
        return parent::destroy($request, $id);
    }
}
