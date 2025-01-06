<?php

namespace App\Console\Commands;

use App\Exports\LowStockExport;
use App\Models\Item;
use App\Services\EmailService;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ItemReport extends Command
{
    protected $emailService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(EmailService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = 5;
        $items = Item::where('quantity', '<=', $threshold)->get();

        if (!empty($items)) {
            $export = new LowStockExport($items);

            $timestamp = now()->format('d-m-Y_H-i');
            $fileName = "reports/low_stock_report_{$timestamp}.xlsx";
            $filePath = storage_path("app/public/{$fileName}");

            $isSaved = Excel::store($export, $fileName, 'public');
            if ($isSaved) {
                $this->info('Report has been generated and saved to ' . $filePath);

                $emailSent = $this->emailService->sendEmail(
                    'link2mubashir@yahoo.com',
                    'Low Stock Report',
                    'Please find the attached low stock report',
                    $filePath
                );

                $this->info($emailSent ? 'Email sent successfully.' : 'Email sending failed.');
            } else {
                $this->error('Failed to save the report file.');
            }
        } else {
            $this->info('No items found with quantity less than or equal to ' . $threshold);
        }
    }
}
