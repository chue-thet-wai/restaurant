<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FerryPayment implements FromCollection, WithHeadings
{
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Invoice Id',
            'Student ID',
            'Name',
            'Paid Date',
            'Payment Type',
            'Pay Period From',
            'Pay Period To',
            'Net Total'
        ];
    }
}
