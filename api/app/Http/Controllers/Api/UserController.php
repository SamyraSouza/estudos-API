<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        protected User $repository
    )
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->repository->paginate();

        return UserResource::collection($users);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateUserRequest $request)
    {
        $data = $request->validated();

        $data['password'] = bcrypt($request->password);

        $user = $this->repository->create($data);

       return new UserResource($user);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    //    $user = User::find($id)
       //$user = User::where('id', '=' , $id)->first();
    //    if(!$user){
    //     return response()->json(['message' => "user not found"], 404);
    //    }
    $user = $this->repository->findOrFail($id);

    return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateUserRequest $request, string $id)
    {

        $data = $request->validated();

        if($request->password){
        $data['password'] = bcrypt($request->getPassword());
        }
        $user = $this->repository->findOrFail($id);
        $user->update($data);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->repository->findOrFail($id);
        $user->delete();

        return Response()->json([], Response::HTTP_NO_CONTENT);
    }
}
