<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        ]);

        if (Auth::user()->role != "OWNER") {
            $usersId = [];
            if (Auth::user()->stores->count() > 0) {
                $usersId = Auth::user()
                    ->stores
                    ->load("users")
                    ->pluck("users")
                    ->flatten()
                    ->pluck("id");
            }

            $quotes->whereIn("user_id", $usersId);
        }

        $quotes = $quotes->get();


        $response = $quotes->map(function ($quote) {
            return [
                "date" => $quote->created_at,
                "store" => $quote->user->first()->stores->first()->name ?? "No Store assigned",
                "device" => $quote->device->model,
                "issues" => $quote->issues->pluck('name')->join(', '),
                "quote" => $quote->value,
            ];
        });

        return response()->json($response);
    }
}
