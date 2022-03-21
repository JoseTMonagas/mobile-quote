<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleForm;
use App\Models\Item;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SaleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaleForm $request)
    {
        $form = $request->validated();
        $form["user_id"] = Auth::user()->id;
        $sale = Sale::create($form);
        foreach ($form["items"] as $sale_item) {
            $sale_item["sale_id"] = $sale->id;
            unset($sale_item["selected"]);
            $item = Item::find($sale_item["id"]);
            $item->update($sale_item);
        }

        $receiptUrl = route("sales.receipt", $sale);
        return response()->json($receiptUrl, 201);
    }

    /**
     * Generate a pdf receipt for the given Sale.
     * @param Sale $sale
     * @return
     */
    public function receipt(Sale $sale)
    {
        $pdf = PDF::loadView("sale-receipt", compact("sale"))
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => 'true',
            ]);
        return $pdf->download("receipt.pdf");
    }

    /**
     * Show report for Sale
     */
    public function showReport(): Response
    {
        return Inertia::render("Items/Report");
    }

    /**
     *  Returns JSON listing all Item sold between a given
     *  date range ($start - $end).
     *
     *  @param Request $request
     *  @return JsonResponse
     */
    public function generateReport(Request $request): JsonResponse
    {
        $start = $request->start;
        $end = strtotime("1 day", strtotime($request->end));

        $items = Item::where([
            ["sold", ">=", $start],
            ["sold", "<=", date("Y-m-d", $end)],
        ])->whereNotNull("sale_id")
            ->select("sale_id")
            ->distinct()
            ->get();

        $sale_pks = $items->map(function ($item) {
            return $item->sale_id;
        })->toArray();

        $sales = Sale::whereIn("id", $sale_pks);

        if (Auth::user()->role != "OWNER") {
            $store = Auth::user()->store;
            $ids = $store->users->pluck("id");

            switch (Auth::user()->role) {
                case "ADMIN":
                    $store = Auth::user()->store;
                    $ids = $store->users->pluck("id");
                    $sales->whereIn("user_id", $ids->toArray());
                    break;
                case "USER":
                    $sales->whereIn("user_id", [Auth::id()]);
                    break;
                default:
                    abort(403, "Unauthorized.");
                    break;
            }
        }

        $sales = $sales->get();

        $response = [];
        foreach ($sales as $sale) {
            $tax = intval($sale->tax) / 100;
            foreach ($sale->items as $item) {

                $battery = $item->battery;
                if (is_numeric($item->battery)) {
                    $battery = "$battery %";
                }

                $cost = number_format(floatval($item->cost), 2);
                $subtotal = number_format($item->selling_price, 2);
                $total = number_format($item->selling_price + ($item->selling_price * $tax), 2);
                $profit =  number_format($total - $item->cost, 2);

                $sold = new DateTime($item->sold);

                $item["sold"] = $sold->format("Y-m-d");
                $item["cost"] = "$ $cost";
                $item["subtotal"] = "$ $subtotal";
                $item["total"] = "$ $total";
                $item["profit"] = "$ $profit";
                $item["battery"] = $battery;

                $response[] = $item;
            }
        }

        return response()->json($response);
    }
}
