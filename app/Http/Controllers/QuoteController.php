<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Http\Requests\QuoteForm;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class QuoteController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $storePercent = 0;
        if (Auth::user()->stores->count() > 0) {
            $storePercent = Auth::user()
                ->stores
                ->first()
                ->price_percent;
        }


        return Inertia::render("GenerateQuote", [
            "storePercent" => $storePercent,
        ]);
    }

    /**
     * Stores a new Quote.
     *
     * @param  QuoteForm  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuoteForm $request)
    {
        $form = $request->validated();
        $user = Auth::user();
        $form["user_id"] = $user->id;

        $issues = collect($form["issues"])->mapWithKeys(function ($issue) {
            return [$issue["id"] => [
                "deduction" => $issue["pivot"]["deduction"] ?? 0,
            ]];
        });
        $quote = Quote::create($form);

        if ($issues->count() > 0) {
            $quote->issues()->attach($issues->toArray());
        }

        return response()->json($quote, 201);
    }

    public function receipt(Quote $quote)
    {
        $pdf = PDF::loadView("receipt", compact("quote"))
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => 'true',
            ]);
        return $pdf->download("receipt.pdf");
    }
}
