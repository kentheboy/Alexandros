<?php

namespace App\Models\Alexandros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    //product status
    const PUBLISHED = 1;
    const UNPUBLISHED = 0;

    protected $fillable = [
        'name',
        'description',
        'images',
        'status',
        'price',
        'start_at',
        'end_at',
        'customfields'
    ];
}
