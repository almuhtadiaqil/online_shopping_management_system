<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestPaginate;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserService $service
     */
    protected UserService $service;

    /**
     * constructor function for UserController
     *
     * @param UserService $s
     */
    public function __construct(UserService $s)
    {
        $this->service = $s;
    }

    /**
     * Display a listing of the resource.
     *
     * @param RequestPaginate $request
     * @return JsonResponse
     */
    public function index(RequestPaginate $request): JsonResponse
    {
        return response()->json($this->service->getPagination($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return  JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json($this->service->store($request));
    }

    /**
     * Display the specified resource.
     *
     * @param User $request
     * @return JsonResponse
     */
    public function show(User $request): JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  User  $model
     * @return JsonResponse
     */
    public function update(Request $request, User $model): JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        //
    }
}
