<?php

namespace App\Exports;

use App\Models\CustomerModel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class CustomerExport implements FromQuery, WithEvents, WithMapping, WithHeadings
{

    use Exportable;

    public function forName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function forEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    public function forActive($active = null)
    {
        if ($active === null) {
            return null;
        }
        $this->active = $active;
        return $this;
    }

    public function forAddress(string $address)
    {
        $this->address = $address;
        return $this;
    }

    public function query()
    {
        $query = CustomerModel::query();

        //query
        if (!empty($this->name)) {
            $query = $query->where("customer_name", "like", "%" .$this->name. "%");
        }

        if (!empty($this->email)) {
            $query = $query->where("email", "like", "%" .$this->email. "%");
        }

        if (!empty($this->address)) {
            $query = $query->where("address", "like", "%" .$this->address. "%");
        }

        if (isset($this->active)) {
            $query = $query->where("is_active", "=", intval($this->active));
        }

        $query = $query->orderByDesc("created_at");
        $query = $query->limit(0,20);

        return $query;
    }

    public function map($data): array
    {
        return [
            $data->customer_name,
            $data->email,
            $data->tel_num,
            $data->address
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                //$event->sheet->formatColumn('C', DataType::TYPE_STRING2);
                $event->sheet->autoSize();
            }
        ];
    }

}
