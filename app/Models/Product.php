<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['supplier_id', 'category_id', 'name', 'description', 'price', 'stock'];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function orderRequests()
    {
        return $this->hasMany(OrderRequest::class, 'product_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        // for logging purposes
        /*
        $reviews = $this->reviews()->get();
        $count = $reviews->count();
        $ratings = $reviews->pluck('rating');

        Log::info('Product ID ' . $this->id . ' has ' . $count . ' reviews with ratings: ' . $ratings->implode(', '));
        */

        $averageRating = $this->reviews()->avg('rating') ?? 0;
        // Log::info('Calculated average rating for product ID ' . $this->id . ': ' . $averageRating);
        return $averageRating;
    }
}
