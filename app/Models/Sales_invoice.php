<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales_invoice extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function service_center_repair()
    {
        return $this->belongsTo(Service_center_repair::class);
    }
    public function financial_activity()
    {
        return $this->belongsTo(Sales_invoice::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
