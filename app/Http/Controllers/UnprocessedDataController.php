<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UnprocessedData;

use Unlu\Laravel\Api\QueryBuilder;

class UnprocessedDataController extends Controller
{
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new UnprocessedData, $request);
        
        // Single UnprocessedData
        if($request->limit && $request->limit == 1)
        {
            $unprocesseddata = json_decode("{}");
            $status = 0;

            if(isset($queryBuilder->build()->get()[0]))
            {
                $unprocesseddata = $queryBuilder->build()->get()[0];
                $status = 200;
            }
            else
            {
                $status = 404;
            }

            return response()->json([
                'status' => $status,
                'unprocesseddata' => $unprocesseddata
            ],200);
        }
        else
        {
            $unprocesseddata = $queryBuilder->build()->get();
            $status = 0;
            
            if($unprocesseddata && count($unprocesseddata) > 0) $status = 200;
            else $status = 404;

            return response()->json([
                'status' => $status,
                'unprocesseddata' => $unprocesseddata
            ],200);
        }
    }

    public function show(UnprocessedData $unprocesseddata)
    {
        return response()->json([
            'status' => 200,
            'unprocesseddata' => $unprocesseddata
        ], 200);
    }

    public function store(Request $request)
    {
        $unprocesseddata = UnprocessedData::create($request->all());

        return response()->json([
            'status' => 200,
            'unprocesseddata' => $unprocesseddata
        ], 201);
    }

    public function update(Request $request, UnprocessedData $unprocesseddata)
    {
        $unprocesseddata->update($request->all());

        return response()->json([
            'status' => 200,
            'unprocesseddata' => $unprocesseddata
        ], 200);
    }

    public function delete(UnprocessedData $unprocesseddata)
    {
        $unprocesseddata->delete();

        return response()->json(null, 204);
    }
}
