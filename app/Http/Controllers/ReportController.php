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

        $store = Auth::user()->stores->first();
        $ids = $store->users->pluck("id");

        if (Auth::user()->role != "OWNER") {

            switch (Auth::user()->role) {
                case "ADMIN":
                    $store = Auth::user()->stores->first();
                    $ids = $store->users->pluck("id");
                    $quotes->whereIn("user_id", $ids->toArray());
                    break;
                case "USER":
                    $quotes->whereIn("user_id", [Auth::id()]);
                    break;
                case "OWNER":
                    break;
                default:
                    abort(403, 'Unauthorized.');
                    break;
            }
        }

        $quotes = $quotes->get();


        $response = $quotes->map(function ($quote) {
            return [
                "date" => date('d-m-Y H:i', strtotime($quote->created_at)),
                "store" => $quote->user->first()->stores->first()->name ?? "No Store assigned",
                "device" => $quote->device->model,
                "issues" => $quote->issues->pluck('name')->join(', '),
                "quote" => $quote->value,
            ];
        });

        return response()->json($response);
    }
}
