<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleForm;
use App\Models\Item;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $sale = Sale::create([
            "user_id" => Auth::user()->id
        ]);
        foreach ($request->validated() as $sale_item) {
            $sale_item["sale_id"] = $sale->id;
            $sale_item["sold"] = Carbon::now();
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
        $total = 0;
        foreach ($sale->items as $item) {
            $total += $item->subtotal;
        }
        $pdf = PDF::loadView("sale-receipt", compact("sale", "total"))
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => 'true',
            ]);
        return $pdf->download("receipt.pdf");
    }
}
