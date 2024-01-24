<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected ProductRepository $repository;
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth:api');
        $this->middleware('role:admin', ['except' => ['index']]);
        $this->middleware('role:pembeli', ['only' => ['index']]);
    }
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
                'name' => 'string|required',
                'description' => 'string|required',
                'price' => 'numeric',
                'image' => 'url',
                'stock' => 'numeric',
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return response()->json([
                'status' => 200,
                'data' => $this->repository->get_by_column('id', $id),
            ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'string',
                'description' => 'string',
                'price' => 'numeric',
                'image' => 'url',
                'stock' => 'numeric',
            ]);
            if ($validated) {
                return [
                    'status' => 201,
                    'data' => $this->repository->update($request, 'id', $id),
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
        try {
            $this->repository->destroy('id', $id);
            return response()->json([
                'status' => 200,
                'data' => null,
            ]);
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
}
