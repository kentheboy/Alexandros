<?php

namespace App\Models\Alexandros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedules';

    protected $fillable = [
        'product_id',
        'user_id',
        'start_at',
        'end_at',
        'total_fee',
        'customfields'
    ];
}
