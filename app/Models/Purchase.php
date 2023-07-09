<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $appends = ["created_at_format"];

    public function getCreatedAtFormatAttribute()
    {
        return $this->created_at->format("Y-m-d g:i A");
    }
}
