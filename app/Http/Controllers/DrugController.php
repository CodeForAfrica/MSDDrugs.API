<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Drug;

use Unlu\Laravel\Api\QueryBuilder;

class DrugController extends Controller
{
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new Drug, $request);
        
        // Single Drug
        if($request->limit && $request->limit == 1)
        {
            $drug = json_decode("{}");
            $status = 0;

            if(isset($queryBuilder->build()->get()[0]))
            {
                $drug = $queryBuilder->build()->get()[0];
                $status = 200;
            }
            else
            {
                $status = 404;
            }

            return response()->json([
                'status' => $status,
                'drug' => $drug
            ],200);
        }
        else
        {
            $drugs = $queryBuilder->build()->get();
            $status = 0;
            
            if($drugs && count($drugs) > 0) $status = 200;
            else $status = 404;

            return response()->json([
                'status' => $status,
                'drugs' => $drugs
            ],200);
        }
    }

    public function show(Drug $drug)
    {
        $status = "";
        if($drug) $status = 200;
        else $status = 404;

        return response()->json([
            'status' => $status,
            'drug' => $drug
        ], 200);
    }

    public function store(Request $request)
    {
        $drug = Drug::create($request->all());

        return response()->json([
            'status' => 201,
            'drug' => $drug
        ], 201);
    }

    public function update(Request $request, Drug $drug)
    {
        $drug->update($request->all());

        return response()->json([
            'status' => 200,
            'drug' => $drug
        ], 200);
    }

    public function delete(Drug $drug)
    {
        $drug->delete();

        return response()->json(null, 204);
    }
}
