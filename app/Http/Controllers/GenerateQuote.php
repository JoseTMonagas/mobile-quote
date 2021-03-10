<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteForm;
use App\Models\Quote;
use Illuminate\Http\Request;

class GenerateQuote extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(QuoteForm $request)
    {
        $form = $request->validated();
        $quote = Quote::create($request->validated());
        $issues = collect($form["issues"])->pluck("id");

        if ($issues->count() > 0) {
            $quote->issues()->attach($issues);
        }

        return response()->json($quote, 201);


    }
}
