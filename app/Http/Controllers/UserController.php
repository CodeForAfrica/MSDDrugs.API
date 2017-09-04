<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use Unlu\Laravel\Api\QueryBuilder;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new User, $request);
        
        // Single User
        if($request->limit && $request->limit == 1)
        {
            $user = json_decode("{}");
            $status = 0;

            if(isset($queryBuilder->build()->get()[0]))
            {
                $user = $queryBuilder->build()->get()[0];
                $status = 200;
            }
            else
            {
                $status = 404;
            }

            return response()->json([
                'status' => $status,
                'user' => $user
            ],200);
        }
        else
        {
            $users = $queryBuilder->build()->get();
            $status = 0;
            
            if($users && count($users) > 0) $status = 200;
            else $status = 404;

            return response()->json([
                'status' => $status,
                'users' => $users
            ],200);
        }
    }

    public function show(User $user)
    {
        return response()->json([
            'status' => 200,
            'user' => $user
        ], 200);
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());

        return response()->json([
            'status' => 200,
            'user' => $user
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        return response()->json([
            'status' => 200,
            'user' => $user
        ], 200);
    }

    public function delete(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
}