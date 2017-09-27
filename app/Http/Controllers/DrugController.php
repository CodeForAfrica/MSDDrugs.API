<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Drug;
use App\PriceCheck;
use App\WrongCheck;

use Unlu\Laravel\Api\QueryBuilder;

class DrugController extends Controller
{
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new Drug, $request);

        // Buying price checking.
        if($request->name && $request->measure && $request->buying_price)
        {
            $drug = json_decode("{}");
            $price_check_result = json_decode("{}");
            $status = 0;

            if(isset($queryBuilder->build()->get()[0]))
            {
                $drug = $queryBuilder->build()->get()[0];
                $status = 200;

                // Checking
                $measure = str_ireplace(",","",$request->measure);
                $measure = str_ireplace("/","",$measure);
                $measure = str_ireplace("=","",$measure);

                $measure = (int)$measure;

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
                $vidonge = 0;

                // Substracting vidonge
                $temp = explode(" ",$drug->uom);
                $vidonge = (int)$temp0;

                // Finding price per tablet
                $price_per_tab = $drug_price / $vidonge;
                $required_drug_price = $price_per_tab * $measure;

                if($buying_price == $required_drug_price)
                {
                    $buying_price_status = "equal";
                    $extra_amount = $buying_price - $required_drug_price;
                }
                else if($buying_price > $required_drug_price)
                {
                    $buying_price_status = "above";
                    $extra_amount = $buying_price - $required_drug_price;
                }
                else if($buying_price < $required_drug_price)
                {
                    $buying_price_status = "below";
                    $extra_amount = $required_drug_price - $buying_price;
                }

                $price_check_result = array(
                    'buying_price_status' => $buying_price_status,
                    'extra_amount' => $extra_amount,
                    'vidonge' => $vidonge
                );

                // Saving price check.
                $price_check = new PriceCheck();
                $price_check->drug_id = $drug->id;
                $price_check->buying_price = $buying_price;
                $price_check->status = $buying_price_status;
                $price_check->extra_amount = $extra_amount;
                $price_check->save();
            }
            else
            {
                $status = 404;

                // Saving wrong check.
                $wrong_check = new WrongCheck();
                $wrong_check->drug_name = str_replace("*","",$request->name);
                $wrong_check->buying_amount = $request->buying_price;
                $wrong_check->save();
            }

            return response()->json([
                'status' => $status,
                'drug' => $drug,
                'price_check' => $price_check_result
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
