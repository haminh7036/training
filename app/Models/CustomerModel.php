<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    use HasFactory;

    protected $table = 'mst_customers';

    protected $guarded = [
        'customer_id'
    ];

    protected $primaryKey = 'customer_id';

    public function scopeActive($query, $status)
    {
        return $query->where('is_active', '=', intval($status));
    }
}
