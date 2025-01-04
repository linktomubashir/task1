<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LowStockExport implements FromCollection, WithHeadings
{
    protected $items; 
    public function __construct($items) {
         $this->items = $items; 
        }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
{
    return $this->items->map(function ($item) {
        return [
            'ID' => $item->id,
            'Item Name' => $item->name,
            'Quantity' => $item->quantity,
            'Amount' => $item->amount,
            'Brand ID' => $item->brand_id,
            'Model ID' => $item->model_id,
            'Created At' => $item->created_at,
        ];
    });
}


    public function headings(): array
    {
        return [
            'ID',
            'Item Name',
            'Quantity',
            'Amount',
            'Brand ID',
            'Model ID',
            'Created At'
        ];
    }
}
