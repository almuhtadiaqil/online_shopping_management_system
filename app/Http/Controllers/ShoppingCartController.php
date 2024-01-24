<?php

namespace App\Http\Controllers;

use App\Repositories\ShoppingCartRepository;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    protected ShoppingCartRepository $repository;
    public function __construct(ShoppingCartRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth:api');
        $this->middleware('role:pembeli');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $validated = $request->validate([
                'page_index' => 'numeric',
                'page_size' => 'numeric',
            ]);
            if ($validated) {
                return [
                    'status' => 200,
                    'data' => $this->repository->getPagination($request),
                ];
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 400,
                    'message' => $th->getMessage(),
                ],
                400
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'products' => 'array|min:1',
                'products.*.quantity' => 'numeric|required',
                'products.*.product_id' => 'numeric|required',
            ]);
            if ($validated) {
                return [
                    'status' => 201,
                    'data' => $this->repository->store($request),
                ];
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 400,
                    'message' => $th->getMessage(),
                ],
                400
            );
        }
    }

    public function checkout(Request $request)
    {
        try {
            $validated = $request->validate([
                'shopping_cart_id' => 'array|min:1',
            ]);
            if ($validated) {
                return [
                    'status' => 201,
                    'data' => $this->repository->checkout($request),
                ];
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 400,
                    'message' => $th->getMessage(),
                ],
                400
            );
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
