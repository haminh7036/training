<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromArray, ShouldAutoSize, WithMapping, WithHeadings
{
    use Exportable;

    protected $customers;

    public function __construct(array $customers)
    {
        $this->customers = $customers;
    }

    public function array(): array
    {
        return $this->customers;
    }

    public function map($data): array
    {
        return [
            $data ['customer_name'],
            $data ['email'],
            $data ['tel_num'],
            $data ['address']
        ];
    }

    public function headings(): array
    {
        return [
            'Tên khách hàng',
            'Email',
            'TelNum',
            'Địa chỉ'
        ];
    }
}
