<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WrongCheck;

use Unlu\Laravel\Api\QueryBuilder;

class WrongCheckController extends Controller
{
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new WrongCheck, $request);
        
        // Single WrongCheck
        if($request->limit && $request->limit == 1)
        {
            $wrongcheck = json_decode("{}");
            $status = 0;

            if(isset($queryBuilder->build()->get()[0]))
            {
                $wrongcheck = $queryBuilder->build()->get()[0];
                $status = 200;
            }
            else
            {
                $status = 404;
            }

            return response()->json([
                'status' => $status,
                'wrongcheck' => $wrongcheck
            ],200);
        }
        else
        {
            $wrongchecks = $queryBuilder->build()->get();
            $status = 0;
            
            if($wrongchecks && count($wrongchecks) > 0) $status = 200;
            else $status = 404;

            return response()->json([
                'status' => $status,
                'wrongchecks' => $wrongchecks
            ],200);
        }
    }

    public function show(WrongCheck $wrongcheck)
    {
        return response()->json([
            'status' => 200,
            'wrongcheck' => $wrongcheck
        ], 200);
    }

    public function store(Request $request)
    {
        $wrongcheck = WrongCheck::create($request->all());

        return response()->json([
            'status' => 200,
            'wrongcheck' => $wrongcheck
        ], 201);
    }

    public function update(Request $request, WrongCheck $wrongcheck)
    {
        $wrongcheck->update($request->all());

        return response()->json([
            'status' => 200,
            'wrongcheck' => $wrongcheck
        ], 200);
    }

    public function delete(WrongCheck $wrongcheck)
    {
        $wrongcheck->delete();

        return response()->json(null, 204);
    }
}
