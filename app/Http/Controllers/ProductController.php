<?php


namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends BaseCrudController
{
    public function __construct()
    {
        parent::__construct();
        $this->service = new ProductService();
    }

    protected function buildWheres(Request $request)
    {
        $name = $request->query("name", null);

        $wheres = [];

        if ($name) {
            array_push($wheres, [
                "coluna" => "name",
                "condicional" => "like",
                "valor" => "%" . $name . "%"
            ]);
        }

        return $wheres;
    }

    /**
     * Display a count of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Get (
     *     path="/api/product/count",
     *     description="Find products quantity",
     *     tags={"Product"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *          response="200",
     *          description="",
     *     )
     * )
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
     *     path="/api/product",
     *     description="Find Products",
     *     tags={"Product"},
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
     *     path="/api/product/{id}",
     *     description="Find Product by Id",
     *     tags={"Product"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *          response="200",
     *          description="",
     *     ),
     *    @OA\Parameter(
     *          @OA\Schema(
     *              type="number"
     *          ),
     *          name="id",
     *          in="path",
     *          description="product Id.",
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
     *     path="/api/product",
     *     description="Create Product",
     *     tags={"Product"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          description="Product data",
     *          @OA\JsonContent(
     *              required={"name", "code", "value"},
     *              @OA\Property(property="name", type="string", format="", example="Nome"),
     *              @OA\Property(property="code", type="string", format="string", example="10sa2"),
     *              @OA\Property(property="value", type="number", format="string", example="2.50"),
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
     *     path="/api/product/{id}",
     *     description="Change Product",
     *     tags={"Product"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          description="Product data",
     *          @OA\JsonContent(
     *              required={"name", "code", "value"},
     *              @OA\Property(property="name", type="string", format="", example="Nome"),
     *              @OA\Property(property="code", type="string", format="string", example="10sa2"),
     *              @OA\Property(property="value", type="number", format="string", example="2.50"),
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
     *          description="Product id.",
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
     *     path="/api/product/{id}",
     *     description="Delete Product",
     *     tags={"Product"},
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
     *          description="Product id.",
     *          required=true
     *     )
     * )
     */
    public function destroy(Request $request, $id)
    {
        return parent::destroy($request, $id);
    }
}
