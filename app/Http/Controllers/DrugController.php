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
            $drug_with_low_price = array();
            $price_check_result = json_decode("{}");
            $status = 0;

            //Getting all drugs based on the query input.
            $drugs = $queryBuilder->build()->get();

            if($drugs && count($drugs) > 0)
            {
                // Extracting measure
                $measure = str_ireplace(",","",$request->measure);
                $measure = str_ireplace("/","",$measure);
                $measure = str_ireplace("=","",$measure);
                $measure = (int)$measure;

                // Extracting buying_price
                $buying_price = str_ireplace(",","",$request->buying_price);
                $buying_price = str_ireplace("/","",$buying_price);
                $buying_price = str_ireplace("=","",$buying_price);
                $buying_price = (int)$buying_price;

                // Finding price per each drug item.
                for($x=0; $x<count($drugs); $x++){
                    // Extracting drug price
                    $drug_price = str_ireplace(",","",$drugs[$x]->price);
                    $drug_price = str_ireplace("/","",$drug_price);
                    $drug_price = str_ireplace("=","",$drug_price);
                    $drug_price = (int)$drug_price;

                    // Substracting UOM
                    $temp = explode(" ",$drugs[$x]->uom);
                    $items = (int)$temp[0];

                    // Finding price per item
                    $price_per_drug_item = $drug_price / $items;
                    $required_drug_price = $price_per_drug_item * $measure;

                    // Attaching the variables
                    $drugs[$x]->items = $items;
                    $drugs[$x]->price_per_drug_item = $price_per_drug_item;
                    $drugs[$x]->required_drug_price = $required_drug_price;
                }

                // Finding the drug with low price.
                $drug_with_low_price = $drugs[0];
                for($x=0; $x<count($drugs); $x++){
                    if($drugs[$x]->required_drug_price < $drug_with_low_price->required_drug_price)
                        $drug_with_low_price = $drugs[$x];
                }

                // Checking drug type
                $drug_type = "";
                
                if($drug_with_low_price->form == "Tablet") $drug_type = "vidonge";
                else if($drug_with_low_price->form == "Injection") $drug_type = "sindano";
                else $drug_type = "kipimo";

                $sms = 'Bei ya chini iliyohifadhiwa ya '.$drug_with_low_price->name.' ni TZS '.$drug_with_low_price->price.'/= kwa '.$drug_type.' '.$drug_with_low_price->items.'.';

                // Finding buying price status
                if($buying_price == $drug_with_low_price->required_drug_price)
                {
                    $buying_price_status = "equal";
                    $extra_amount = $buying_price - $drug_with_low_price->required_drug_price;

                    $sms .= ' Umenunua '.$drug_type.' '.$measure.' kwa TZS '.$buying_price.'/=, umelipa bei halali';
                }
                else if($buying_price > $drug_with_low_price->required_drug_price)
                {
                    $buying_price_status = "above";
                    $extra_amount = $buying_price - $drug_with_low_price->required_drug_price;

                    $sms .= ' Umenunua '.$drug_type.' '.$measure.' kwa TZS '.$buying_price.'/=, umelipa TZS '.$extra_amount.'/= zaidi.';
                    $sms .= ' Nenda duka la dawa la '.$drug_with_low_price->location.' ununue dawa hii kwa bei nafuu.';
                }
                else if($buying_price < $drug_with_low_price->required_drug_price)
                {
                    $buying_price_status = "below";
                    $extra_amount = $drug_with_low_price->required_drug_price - $buying_price;

                    $sms .= ' Umenunua '.$drug_type.' '.$measure.' kwa TZS '.$buying_price.'/=, umelipa TZS '.$extra_amount.'/= pungufu.';
                }

                // Results
                $status = 200;
                $drug = $drug_with_low_price;

                $price_check_result = array(
                    'buying_price_status' => $buying_price_status,
                    'extra_amount' => $extra_amount,
                    'items' => $drug_with_low_price->items,
                    'message' => $sms,
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
