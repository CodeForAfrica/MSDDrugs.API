<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data;

use Unlu\Laravel\Api\QueryBuilder;

class DataController extends Controller
{
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new Data, $request);
        
        // Single Data
        if($request->limit && $request->limit == 1)
        {
            $data = json_decode("{}");
            $status = 0;

            if(isset($queryBuilder->build()->get()[0]))
            {
                $data = $queryBuilder->build()->get()[0];
                $status = 200;
            }
            else
            {
                $status = 404;
            }

            return response()->json([
                'status' => $status,
                'data' => $data
            ],200);
        }
        else
        {
            $data = $queryBuilder->build()->get();
            $status = 0;
            
            if($data && count($data) > 0) $status = 200;
            else $status = 404;

            return response()->json([
                'status' => $status,
                'data' => $data
            ],200);
        }
    }

    public function show(Data $data)
    {
        return response()->json([
            'status' => 200,
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $data = Data::create($request->all());

        return response()->json([
            'status' => 200,
            'data' => $data
        ], 201);
    }

    public function update(Request $request, Data $data)
    {
        $data->update($request->all());

        return response()->json([
            'status' => 200,
            'data' => $data
        ], 200);
    }

    public function delete(Data $data)
    {
        $data->delete();

        return response()->json(null, 204);
    }
}
