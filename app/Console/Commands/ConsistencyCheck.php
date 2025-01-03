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
            $items = Item::whereHas('models', function ($query) {
                $query->whereColumn('brand_id', '!=', 'items.brand_id');
            })->get();
            foreach ($items as $item) {
                $item->update([
                    'brand_id' => $item->models->brand_id,
                ]);
            }
            DB::commit();

            $this->info("Successfully updated items");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}
