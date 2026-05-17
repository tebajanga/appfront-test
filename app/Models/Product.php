<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
    ];

    /**
     * Support both old public images and new storage-based uploads.
     */
    public function getImageUrlAttribute(): string
    {
        if (str_contains($this->image, 'uploads/products')) {
            return asset('storage/' . $this->image);
        }
        
        return asset($this->image);
    }
}
