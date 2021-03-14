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
        if (Auth::user()->stores->count()) {
            $storePercent = Auth::user()
                ->stores
                ->first()
                ->pluck("price_percent");
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
        $quote = Quote::create($form);
        $issues = collect($form["issues"])->pluck("id");

        if ($issues->count() > 0) {
            $quote->issues()->attach($issues);
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
