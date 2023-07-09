<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function barcode()
    {
        return $this->belongsTo(Barcode::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
    public function sales_invoices()
    {
        return $this->hasMany(Sales_invoice::class);
    }



    public function getCreatedAtAttribute($value)
    {
        $value = Carbon::parse($value);
        return $value->format("Y-m-d g:i") . ($value->format("A") === "AM" ? " صباحًا ": " مساءًا ");
    }
    public function getUpdatedAtAttribute($value)
    {
        $value = Carbon::parse($value);
        return $value->format("Y-m-d g:i") . ($value->format("A") === "AM" ? " صباحًا ": " مساءًا ");
    }


    protected $appends = ['total_price',"total_selling_price"];
    public function getTotalPriceAttribute()
    {
        return $this->price * $this->quantity;
    }
    public function getTotalSellingPriceAttribute()
    {
        return $this->selling_price * $this->quantity;
    }
}
