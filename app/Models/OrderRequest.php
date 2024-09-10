<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['customer_id', 'product_id', 'quantity', 'status', 'total_amount', 'approved_at'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'request_id', 'id');
    }
}