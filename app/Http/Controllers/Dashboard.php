<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Dashboard extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $startOfMonth = date("Y-m-01");
        $endOfMonth = date("Y-m-d");
        $devicesInInventory = Item::whereNull("sold")->count();
        $tradesThisMonth = Item::whereBetween("date", [$startOfMonth, $endOfMonth])->count();
        $soldThisMonth = Item::whereBetween("sold", [$startOfMonth, $endOfMonth])->count();
        $costSoldThisMonth = round(Item::whereBetween("sold", [$startOfMonth, $endOfMonth])->sum("cost"));
        $inventoryValue = round(Item::whereNull("sold")->sum("cost"));
        $saleValue = round(Item::whereNull("sold")->sum("selling_price"));
        $soldValueThisMonth = round(Item::whereBetween("sold", [$startOfMonth, $endOfMonth])->sum("subtotal"));
        $profitThisMonth = round(Item::whereBetween("sold", [$startOfMonth, $endOfMonth])->sum("profit"));

        $context = [
            "devicesInInventory" => $devicesInInventory,
            "tradesThisMonth" => $tradesThisMonth,
            "soldThisMonth" => $soldThisMonth,
            "costSoldThisMonth" => $costSoldThisMonth,
            "inventoryValue" => $inventoryValue,
            "saleValue" => $saleValue,
            "soldValueThisMonth" => $soldValueThisMonth,
            "profitThisMonth" => $profitThisMonth,
        ];


        return Inertia::render("Dashboard", $context);
    }
}
