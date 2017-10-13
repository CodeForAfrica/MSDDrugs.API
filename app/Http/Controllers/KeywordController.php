<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Keyword;

use Unlu\Laravel\Api\QueryBuilder;

class KeywordController extends Controller
{
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new Keyword, $request);
        
        // Single Keyword
        if($request->limit && $request->limit == 1)
        {
            $keyword = json_decode("{}");
            $status = 0;

            if(isset($queryBuilder->build()->get()[0]))
            {
                $keyword = $queryBuilder->build()->get()[0];
                $status = 200;
            }
            else
            {
                $status = 404;
            }

            return response()->json([
                'status' => $status,
                'keyword' => $keyword
            ],200);
        }
        else
        {
            $keywords = $queryBuilder->build()->get();
            $status = 0;
            
            if($keywords && count($keywords) > 0) $status = 200;
            else $status = 404;

            return response()->json([
                'status' => $status,
                'keywords' => $keywords
            ],200);
        }
    }

    public function show(Keyword $keyword)
    {
        return response()->json([
            'status' => 200,
            'keyword' => $keyword
        ], 200);
    }

    public function store(Request $request)
    {
        $keyword = Keyword::create($request->all());

        return response()->json([
            'status' => 200,
            'keyword' => $keyword
        ], 201);
    }

    public function update(Request $request, Keyword $keyword)
    {
        $keyword->update($request->all());

        return response()->json([
            'status' => 200,
            'keyword' => $keyword
        ], 200);
    }

    public function delete(Keyword $keyword)
    {
        $keyword->delete();

        return response()->json(null, 204);
    }
}
