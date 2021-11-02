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
        if (Auth::user()->store) {
            $storePercent = Auth::user()
                ->store
                ->price_percent;
        }


        return Inertia::render("GenerateQuote", [
            "storePercent" => $storePercent,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkCreate()
    {
        $storePercent = 0;
        if (Auth::user()->store) {
            $storePercent = Auth::user()
                ->store
                ->price_percent;
        }


        return Inertia::render("GenerateBulkQuote", [
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

    /**
     * Stores a new Quote with multiple Device.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkStore($request)
    {
        dd($request);

        return response()->json(201);
    }

    public function receipt(Quote $quote)
    {
        $store = $quote->user->store;
        $header = null;
        $footer = null;
        $logo = null;
        if ($store) {
            $header = $store->header;
            $footer = $store->footer;
            if (!is_null($store->logo)) {
                $logo = base64_encode(file_get_contents(storage_path() . "/app/" . $store->logo));
            }
        }


        $pdf = PDF::loadView("receipt", compact("quote", "header", "footer", "logo"))
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => 'true',
            ]);
        return $pdf->download("receipt.pdf");
    }
}
