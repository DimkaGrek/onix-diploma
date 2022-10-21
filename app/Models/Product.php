<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Получить фотографии продукта.
     */
    public function productimages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function orders() {
        return $this->belongsToMany(Order::class);
    }
}
