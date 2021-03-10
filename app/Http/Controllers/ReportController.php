<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Show report view to generate file
     */
    public function show()
    {
        return Inertia::render('Reports/Show');
    }

    public function generate(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        $quotes = Quote::where([
            ["created_at", ">=", $start],
            ["created_at", "<=", $end],
        ])->get();

        $response = $quotes->map(function ($quote) {
            return [
                "date" => $quote->created_at,
                "device" => $quote->device->model,
                "issues" => $quote->issues->pluck('name')->join(', '),
                "quote" => $quote->value,
            ];
        });

        return response()->json($response);
    }
}
