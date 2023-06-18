<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CrudService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Stringable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

/** @OA\Info(title="API Liberfly", version="1.0.0")
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Base Crud Controller",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */
abstract class BaseCrudController extends Controller
{
    /**
    * CrudService for BaseCrudController.
    *
    * @var CrudService
    */
    protected $service;

    protected $user;

    protected $filePath;

    protected $showWith = [];
    protected $showAll = false;

    protected $callbackStore = null;

    public function __construct()
    {
        try {
            // $this->middleware('auth.jwt');
            $this->middleware('auth.jwt', ['except' => ['imagem']]);

            $this->user = JWTAuth::parseToken()->authenticate();

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json('token_expired', 401);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json('token_invalid', 401);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json('token_absent', 401);
        } catch (JWTException $exception) {
            $message = $exception->getMessage();
            return response()->json([
                'success' => false,
                'message' => 'Sessão encerrada. Por favor efetue o login novamente. '.$message,
            ], 401);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sessão encerrada. Por favor efetue o login novamente. '.$error->getMessage(),
            ], 401);
        }
    }

    protected function buildValidator($data) {
        $model = $this->service->repository->buildModel();
        return Validator::make($data, $model->rules, $model->messages);
    }

    protected function buildWheres(Request $request) {
        return [];
    }

    /**
     * Display a count of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function count(Request $request)
    {
        return response()->json($this->service->repository->count($this->buildWheres($request)), 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->query("page", 1);
        $take = $request->query("take", 15);
        $orderBy = $request->query("orderBy", "");
        return response()->json($this->service->repository->getAll(null, $page, $take, true, $this->buildWheres($request), $this->showAll ? $this->showWith: [], [$orderBy]));
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return response()->json($this->service->repository->findByID($id, true, $this->showWith), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->json()->all();

        if (isset($data["password"])) {
            $data["password"] = Hash::make($data["password"]);
        }

        $validator = $this->buildValidator($data);

        if ($validator->fails()) {
            return response()->json(array_merge($validator->errors()->all(), ["success" => false]), 422);
        }

        $dados = $this->service->save($data, $this->callbackStore);
        if (is_array($dados)) {
            return response()->json($dados, 500);
        }
        return response()->json($this->service->repository->findByID($dados->id), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->json()->all();

        $validator = $this->buildValidator($data);

        if ($validator->fails()) {
            return response()->json(array_merge($validator->errors()->all(), ["success" => false]), 422);
        }

        $this->service->update($data, $id);

        return response()->json($this->service->repository->findByID($id, true, $this->showWith ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        return response()->json($this->service->delete($id));
    }

    /**
     * Display the specified audit resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function showAudit(Request $request, $id)
    {
        return response()->json($this->service->showAudit($id), 200);
    }
}
