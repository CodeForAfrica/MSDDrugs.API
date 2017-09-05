<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PriceCheck;
use App\Drug;

use Unlu\Laravel\Api\QueryBuilder;

class PriceCheckController extends Controller
{
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new PriceCheck, $request);
        
        // Single PriceCheck
        if($request->limit && $request->limit == 1)
        {
            $pricecheck = json_decode("{}");
            $drug = json_decode("{}");
            $status = 0;

            if(isset($queryBuilder->build()->get()[0]))
            {
                $pricecheck = $queryBuilder->build()->get()[0];
                $pricecheck->drug = $drug;
                $status = 200;
            }
            else
            {
                $status = 404;
            }

            return response()->json([
                'status' => $status,
                'pricecheck' => $pricecheck
            ],200);
        }
        else
        {
            $pricechecks = $queryBuilder->build()->get();
            $drug = json_decode("{}");
            $status = 0;
            
            if($pricechecks && count($pricechecks) > 0)
            {
                $status = 200;
                for($x=0;$x<count($pricechecks);$x++){
                    $pricechecks[$x]->drug = Drug::find($pricechecks[$x]->drug_id);
                }
            }
            else $status = 404;

            return response()->json([
                'status' => $status,
                'pricechecks' => $pricechecks
            ],200);
        }
    }

    public function show(PriceCheck $pricecheck)
    {
        return response()->json([
            'status' => 200,
            'pricecheck' => $pricecheck
        ], 200);
    }

    public function store(Request $request)
    {
        $pricecheck = PriceCheck::create($request->all());

        return response()->json([
            'status' => 200,
            'pricecheck' => $pricecheck
        ], 201);
    }

    public function update(Request $request, PriceCheck $pricecheck)
    {
        $pricecheck->update($request->all());

        return response()->json([
            'status' => 200,
            'pricecheck' => $pricecheck
        ], 200);
    }

    public function delete(PriceCheck $pricecheck)
    {
        $pricecheck->delete();

        return response()->json(null, 204);
    }
}
