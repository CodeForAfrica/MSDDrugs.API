<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Checker;

use Unlu\Laravel\Api\QueryBuilder;

class CheckerController extends Controller
{
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new Checker, $request);
        
        // Single Check
        if($request->limit && $request->limit == 1)
        {
            $checker = json_decode("{}");
            $status = 0;

            if(isset($queryBuilder->build()->get()[0]))
            {
                $checker = $queryBuilder->build()->get()[0];
                $status = 200;
            }
            else
            {
                $status = 404;
            }

            return response()->json([
                'status' => $status,
                'checker' => $checker
            ],200);
        }
        else
        {
            $checkers = $queryBuilder->build()->get();
            $status = 0;
            
            if($checkers && count(checkers) > 0) $status = 200;
            else $status = 404;

            return response()->json([
                'status' => $status,
                'checkers' => $checkers
            ],200);
        }
    }
}
