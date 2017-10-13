<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Keyword;
use App\Data;
use App\UnprocessedData;

class DataCollectorController extends Controller
{
    public function index(Request $request)
    {
        $status = 0;
        $message = "";
        $keyword = null;
        $amount = 0;

        // Checking for passed keyword and amount
        if($request->keyword && $request->amount)
        {
            $passed_keyword = $request->keyword;
            $amount = str_ireplace(",","",$request->amount);
            $amount = str_ireplace("/","",$amount);
            $amount = str_ireplace("=","",$amount);

            // Getting the keyword
            $keyword = Keyword::where('name_short',$passed_keyword)->first();

            if($keyword)
            {
                // Saving the data
                Data::create([
                    'keyword_id' => $keyword->id,
                    'amount' => $amount
                ]);

                $status = 200;
                $message = 'TZS '.$amount.'/= imehifadhiwa kwenye '.$keyword->name_long.'.';
            }
            else
            {
                // Saving the unprocessed data
                UnprocessedData::create([
                    'keyword' => $passed_keyword,
                    'amount' => $amount
                ]);

                $status = 404;
                $message = '"'.$passed_keyword.'" - haitambuliki.';
            }
        }
        else
        {
            $status = 404;
            $message = "Neno na kiasi vinahitajika.";
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ],200);
    }
}
