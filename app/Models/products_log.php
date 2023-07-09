<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products_log extends Model
{
    use HasFactory;
    protected $guarded;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getTitleAttribute($value)
    {
        return json_decode($value);
    }
    public function getPriceAttribute($value)
    {
        return json_decode($value);
    }
    public function getSellingPriceAttribute($value)
    {
        return json_decode($value);
    }
    public function getSerialNoAttribute($value)
    {
        return json_decode($value);
    }
    public function getNotesAttribute($value)
    {
        return json_decode($value);
    }
    public function getQuantityAttribute($value)
    {
        return json_decode($value);
    }

}
