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
                    $store = Auth::user()->store;
                    $ids = $store->users->pluck("id");
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

        $response = [];

        foreach ($quotes as $quote) {
            foreach ($quote->items as $item) {
                $deduction = 0;
                $issues = collect([]);
                foreach ($item["issues"] as $issue) {
                    $deduction += $issue["pivot"]["deduction"];
                    $issues->push($issue["name"]);
                }
                $basePrice = $item["device"]["base_price"] - $deduction;

                $row = [
                    "id" => $quote->id,
                    "date" => date('d-m-Y', strtotime($quote->created_at)),
                    "store" => $quote->user->store->name ?? "No Store assigned",
                    "location" => $quote->user->location->name ?? "No Location assigned",
                    "user" => $quote->user->name,
                    "base_price" => "$ {$basePrice}",
                    "device" => $item["device"]["model"],
                    "issues" => $issues->join(', '),
                    "value" => "$ {$item["value"]}",
                    "serial_ref" => $item["serialNumber"],
                    "internal_ref" => $quote->internal_number,
                    "item" => $item,
                ];

                $response[] = $row;
            }
        }

        return response()->json($response);
    }
}
