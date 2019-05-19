<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class UsersController {
    public function index(Request $request) {
        return User::paginate($request->per_page);
    }

    public function store(Request $request) {
        try {
            $user = User::create($request->all());
        } catch(\Exception $e) {
            if($e->getCode() == 23000) {
                return response()->json(['error' => 'Integrity constraint violation'], 409);
            }
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json($user, 201);
    }

    public function show(int $id) {
        $user = Redis::get(User::CACHEKEY . $id);
        
        if (empty($user)) {
            $user = User::find($id);
            if(is_null($user)) {
                return response()->json('', 204);
            }

            Redis::set(User::CACHEKEY . $id, $user);
        }

        return response()->json($user);
    }

    public function update(int $id, Request $request) {
        $user = User::find($id);
        if(is_null($user)) {
            return response()->json(['error' => 'resource not found'], 404);
        }
        $user->fill($request->all());
        try {
            $user->save();
        } catch(\Exception $e) {
            if($e->getCode() == 23000) {
                return response()->json(['error' => 'Integrity constraint violation'], 409);
            }
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if(Redis::exists(User::CACHEKEY . $user->id)) {
            Redis::set(User::CACHEKEY . $id, $user);
        }
        
        return response()->json($user, 200);
    }

    public function destroy(int $id) {
        $amountOfResourcesRemoved = User::destroy($id);
        if($amountOfResourcesRemoved === 0) {
            return response()->json(['error' => 'resource not found'], 404);
        }

        Redis::del(User::CACHEKEY . $id);
        
        return response()->json('', 200);
    }
}
