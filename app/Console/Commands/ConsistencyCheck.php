<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\Models;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConsistencyCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:check-issue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $items = Item::with('models')->whereHas('models', function ($query) {
                $query->whereColumn('brand_id', '!=', 'items.brand_id');
            })->get();
            if (!empty($items)) {
                foreach ($items as $item) {
                    $brandId = $item->models?->brand_id;

                    if ($item->models && $item->brand_id != $brandId) {
                        $item->update([
                            'brand_id' => $brandId,
                        ]);
                    }
                }
            }
            DB::commit();

            $this->info("Successfully updated items");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}
