<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preorder extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'product_id',
        'qty',
        'deal_date',
        'status',
        'bill',
    ];
}
