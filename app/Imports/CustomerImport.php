<?php

namespace App\Imports;

use App\Models\CustomerModel;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class CustomerImport implements ToModel, WithBatchInserts, WithChunkReading, WithValidation, SkipsOnFailure
{
    use RemembersRowNumber, SkipsFailures, Importable;

    public function model(array $row)
    {
        $currentRow = $this->getRowNumber();
        if ($currentRow === 1) {
            return null;
        }

        return new CustomerModel([
            'customer_name' => $row [0],
            'email' => $row [1],
            'tel_num' => $row [2],
            'address' => $row [3]
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }
    
    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            '0' => [
                'required',
                'string',
                'min:5',
                'regex:/^([a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ]+\$)*[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+$/i'
            ],
            '1' => 'required|email|unique:mst_customers,email',
            '2' => [
                'required', 
                'numeric', 
                'regex:/(84|0[3|5|7|8|9])+([0-9]{8})/'
            ],
            '3' => 'required'
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '0' => 'Tên khách hàng',
            '1' => 'Email',
            '2' => 'Số điện thoại',
            '3' => 'Địa chỉ'
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        return $this->failures = $failures;
    }

}
