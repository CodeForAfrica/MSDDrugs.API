<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Keyword;
use App\Data;

use Carbon\Carbon;

class StatisticsCheckController extends Controller
{
    public function index(Request $request)
    {
        $status = 0;
        $message = "";
        $sms = "";
        $type = "";
        $passed_keyword = "";
        $data = null;

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
                        // Getting data summary
                        $data = Data::where('keyword_id', $keyword->id)->get();
                    }

                    if($type == "leo")
                    {
                        // Today's statistics
                        $today = Carbon::today();
                        $data = Data::where('keyword_id', $keyword->id)
                                    ->where('created_at', '>', $today->toDateTimeString())
                                    ->get();

                        $sms = "kwa Leo";
                    }

                    if($type == "jana")
                    {
                        // Yesterday's statistics
                        $today = Carbon::yesterday();
                        $data = Data::where('keyword_id', $keyword->id)
                                    ->where('created_at', '=', $today->toDateTimeString())
                                    ->get();

                        $sms = "kwa Jana";
                    }

                    if($type == "wiki")
                    {
                        // Week statistics
                        $today = Carbon::today()->subWeek();
                        $data = Data::where('keyword_id', $keyword->id)
                                    ->where('created_at', '>', $today->toDateTimeString())
                                    ->get();

                        $sms = "kwa Wiki iliyopita";
                    }

                    if($type == "mwezi")
                    {
                        // Month statistics
                        $today = Carbon::today()->subMonth();
                        $data = Data::where('keyword_id', $keyword->id)
                                    ->where('created_at', '>', $today->toDateTimeString())
                                    ->get();

                        $sms = "kwa Mwezi uliopita";
                    }

                    // Preparing data output
                    if($data)
                    {
                        $sum = 0;
                        foreach($data as $d){
                            $sum = $sum + $d->amount;
                        }

                        $status = 200;
                        $message = 'Jumla ya '.$keyword->name_long.' '.$sms.' ni TZS '.$sum.'/=';
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
                        if($type == "fupi")
                        {
                            // Getting data summary
                            $data = Data::where('keyword_id', $keyword->id)->get();

                            $sms = "TAARIFA ZOTE\n";
                        }

                        if($type == "leo")
                        {
                            // Today's statistics
                            $today = Carbon::today();
                            $data = Data::where('keyword_id', $keyword->id)
                                        ->where('created_at', '>', $today->toDateTimeString())
                                        ->get();
    
                            $sms = "TAARIFA ZA LEO\n";
                        }
    
                        if($type == "jana")
                        {
                            // Yesterday's statistics
                            $today = Carbon::yesterday();
                            $data = Data::where('keyword_id', $keyword->id)
                                        ->where('created_at', '=', $today->toDateTimeString())
                                        ->get();
    
                            $sms = "TAARIFA ZA JANA\n";
                        }
    
                        if($type == "wiki")
                        {
                            // Week statistics
                            $today = Carbon::today()->subWeek();
                            $data = Data::where('keyword_id', $keyword->id)
                                        ->where('created_at', '>', $today->toDateTimeString())
                                        ->get();
    
                            $sms = "TAARIFA ZA WIKI ILIYOPITA\n";
                        }
    
                        if($type == "mwezi")
                        {
                            // Month statistics
                            $today = Carbon::today()->subMonth();
                            $data = Data::where('keyword_id', $keyword->id)
                                        ->where('created_at', '>', $today->toDateTimeString())
                                        ->get();
    
                            $sms = "TAARIFA ZA MWEZI ULIOPITA\n";
                        }
                        
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

                    $message = $sms . $message;
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