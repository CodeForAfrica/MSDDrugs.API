<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Keyword;
use App\Data;

class StatisticsCheckController extends Controller
{
    public function index(Request $request)
    {
        $status = 0;
        $message = "";
        $type = "";
        $passed_keyword = "";

        if($request->type)
        {
            $type = $request->type;
            
            // Is keyword passed also?
            if($request->filled('keyword')){
                $passed_keyword = $request->keyword;

                $keyword = Keyword::where('name_short',$passed_keyword)->first();

                if($keyword)
                {
                    if($type == "fupi")
                    {
                        // Getting the data
                        $data = Data::where('keyword_id', $keyword->id)->get();

                        if($data)
                        {
                            $sum = 0;
                            foreach($data as $d){
                                $sum = $sum + $d->amount;
                            }

                            $status = 200;
                            $message = 'Jumla ya '.$keyword->name_long.' ni TZS '.$sum.'/=';
                        }
                    }
                }
                else
                {
                    // Saving wrong statistic

                    $status = 404;
                    $message = ''.$passed_keyword.' - Haitambuliki.';
                }
            }
            else
            {
                // Getting summary information
                $keywords = Keyword::all();
            
                if($keywords)
                {
                    $n = 1;
                    foreach($keywords as $keyword)
                    {
                        // Calculating total amount
                        // Getting the data
                        $data = Data::where('keyword_id', $keyword->id)->get();
                        $sum = 0;

                        if($data)
                        {
                            foreach($data as $d){
                                $sum = $sum + $d->amount;
                            }
                        }
                        else $sum = 0;

                        $status = 200;
                        $message .= "".$n.". ".$keyword->name_long." - TZS ".$sum."/=\n";
                        $n++;
                    }
                }
                else
                {
                    $status = 404;
                    $message = "Hakuna maneno yaliyohifadhiwa.";
                }
            }
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ],200);
    }
}