<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $params = $request->all();

        $lists = $this->service->get($params);

        return $lists ? response()->successJson($lists) : response()->errorJson('Object not found', 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return Response
     */
    public function store(StoreRequest $request)
    {
        $params = $request->validated();
        $model = $this->service->create($params);

        return $model ? response()->successJson($model) : response()->errorJson('Error', 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(int $id)
    {
        $user = $this->service->show($id);

        return $user ? response()->successJson($user) : response()->errorJson('Object not found', 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateRequest $request, int $id)
    {
        $params = $request->validated();
        $model = $this->service->update($params, $id);

        return $model ? response()->successJson($model) : response()->errorJson('Object not found', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $model = $this->service->delete($id);

        return $model ? response()->successJson('Successfully deleted') : response()->errorJson('Object not found', 404);
    }

}
