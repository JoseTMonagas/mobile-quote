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
        return Inertia::render("Reports/Show");
    }

    public function generate(Request $request)
    {
        $start = $request->start;
        $end = strtotime("1 day", strtotime($request->end));

        $quotes = Quote::where([
            ["created_at", ">=", $start],
            ["created_at", "<=", date("Y-m-d", $end)],
        ]);

        if (Auth::user()->role != "OWNER") {
            $store = Auth::user()->store;
            $ids = $store->users->pluck("id");

            switch (Auth::user()->role) {
                case "ADMIN":
                    $quotes->whereIn("user_id", $ids->toArray());
                    break;
                case "USER":
                    $quotes->whereIn("user_id", [Auth::id()]);
                    break;
                default:
                    abort(403, "Unauthorized.");
                    break;
            }
        }

        $quotes = $quotes->get();

        $response = $quotes->map(function ($quote) {
            $deduction = $quote->device->base_price - $quote->value;
            return [
                "date" => date("d-m-Y H:i", strtotime($quote->created_at)),
                "store" => $quote->user->store->name ?? "No Store assigned",
                "location" =>
                    $quote->user->location->name ?? "No Location assigned",
                "user" => $quote->user->name,
                "base_price" => "{$quote->device->base_price}$",
                "device" => $quote->device->model,
                "issues" => $quote->issues->pluck("name")->join(", "),
                "deduction" => "{$deduction}$",
                "value" => "{$quote->value}$",
                "serial_ref" => $quote->serial_ref,
                "internal_ref" => $quote->internal_ref,
            ];
        });

        return response()->json($response);
    }
}
