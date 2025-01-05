<?php

namespace App\Console\Commands;

use App\Exports\LowStockExport;
use App\Models\Item;
use App\Services\EmailService;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
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
        $export = new LowStockExport($items);

        $timestamp = now()->format('d-m-Y_H-i');
        $fileName = "reports/low_stock_report_{$timestamp}.xlsx";
        $filePath = storage_path("app/public/{$fileName}");

        Excel::store($export, $fileName, 'public');
        $this->info('Report has been generated and saved to ' . $filePath);

        $emailSent = $this->emailService->sendEmail(
            'link2mubashir@yahoo.com',
            'Low Stock Report',
            'Please find the attached low stock report',
            $filePath
        );

        if ($emailSent) {
            $this->info('Email sent successfully.');
        } else {
            $this->error('Email sending failed.');
        }
    }
}
