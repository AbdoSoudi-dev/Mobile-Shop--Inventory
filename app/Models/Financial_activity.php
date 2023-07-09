<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financial_activity extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function financial_accounts_type()
    {
        return $this->belongsTo(Financial_accounts_type::class);
    }
    public function sales_invoices()
    {
        return $this->hasMany(Sales_invoice::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    protected $appends = ["created_at_format"];

    public function getCreatedAtFormatAttribute()
    {
        return $this->created_at->format("Y-m-d g:i A");
    }
}
