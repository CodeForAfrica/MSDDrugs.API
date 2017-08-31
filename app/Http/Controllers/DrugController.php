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

        // Buying price checking.
        if($request->name && $request->buying_price)
        {
            $drug = json_decode("{}");
            $price_check = json_decode("{}");
            $status = 0;

            if(isset($queryBuilder->build()->get()[0]))
            {
                $drug = $queryBuilder->build()->get()[0];
                $status = 200;

                // Checking
                $buying_price = str_ireplace(",","",$request->buying_price);
                $buying_price = str_ireplace("/","",$buying_price);
                $buying_price = str_ireplace("=","",$buying_price);

                $buying_price = (int)$buying_price;

                $drug_price = str_ireplace(",","",$drug->price);
                $drug_price = str_ireplace("/","",$drug_price);
                $drug_price = str_ireplace("=","",$drug_price);

                $drug_price = (int)$drug_price;

                $buying_price_status = "";
                $extra_amount = 0;

                if($buying_price == $drug_price)
                {
                    $buying_price_status = "equal";
                    $extra_amount = $buying_price - $drug_price;
                }
                else if($buying_price > $drug_price)
                {
                    $buying_price_status = "above";
                    $extra_amount = $buying_price - $drug_price;
                }
                else if($buying_price < $drug_price)
                {
                    $buying_price_status = "below";
                    $extra_amount = $drug_price - $buying_price;
                }

                $price_check = array(
                    'buying_price_status' => $buying_price_status,
                    'extra_amount' => $extra_amount
                );
            }
            else
            {
                $status = 404;
            }

            return response()->json([
                'status' => $status,
                'drug' => $drug,
                'price_check' => $price_check
            ],200);
        }
        
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
