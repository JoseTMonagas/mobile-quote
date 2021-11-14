<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class OldQuoteToNewQuote extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oldQuotes = Quote::all();
        $newQuotes = collect();

        foreach ($oldQuotes as $quote) {
            try {
                $user = User::findOrFail($quote->user_id);
                $device = Device::findOrFail($quote->device_id);
            } catch (ModelNotFoundException $ke) {
                continue;
            }
            $issues_id = DB::table("issue_quote")
                ->where("quote_id", $quote->id)
                ->get("issue_id")->pluck("issue_id");
            $storePercent = 0;

            if ($user->store) {
                $storePercent = $user
                    ->store
                    ->price_percent;
            }

            $issues = $device->issues()->whereIn("issue_id", $issues_id)->get();

            $itemPrototype = [
                "condition" => "EXCELLENT",
                "device" => $device,
                "issues" => $issues,
                "quantity" => 1,
                "serialNumber" => $quote->serial_ref,
                "value" => $quote->value,
            ];
            $quotePrototype = [
                "internal_number" => $quote->internal_ref,
                "items" => [$itemPrototype],
                "name" => "",
                "store_margin" => $storePercent,
                "user_id" => $user->id,
                "created_at" => $quote->created_at,
                "updated_at" => $quote->updated_at,
            ];

            $newQuotes->push($quotePrototype);
        }
        Artisan::call("migrate");

        foreach ($newQuotes as $quote) {
            $newQuote = new Quote($quote);
            $newQuote->save();
        }
    }
}
