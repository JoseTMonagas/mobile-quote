<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkQuoteForm;
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
        $attributes = $request->validated();
        $attributes["user_id"] = Auth::user()->id;
        $quote = new Quote($attributes);

        if ($quote->save()) {
            return response()->json($quote, 201);
        }

        return response()->json($quote, 500);
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
