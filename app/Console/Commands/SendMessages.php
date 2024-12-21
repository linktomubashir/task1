<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Brand;
use App\Events\SendEmail;

class SendMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:messages';

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
        try {
            // $brands = Brand::all();

            $brands = Brand::where('created_at', '>=', now()->subDays(7))->get();
            foreach ($brands as $brand) {
                // $this->info('Sending email to ' . $brand->name);
                event(new SendEmail($brand));
            } 
            $this->info('Messages sent successfully.');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
