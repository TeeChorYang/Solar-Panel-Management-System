<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['request_id', 'order_date', 'status', 'shipping_fees', 'shipping_address'];

    public function request()
    {
        return $this->belongsTo(OrderRequest::class, 'request_id', 'id');
    }

    public function installations()
    {
        return $this->hasMany(Installation::class, 'order_id', 'id');
    }
}