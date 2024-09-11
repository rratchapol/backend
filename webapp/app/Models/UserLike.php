<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLike extends Model
{
    use HasFactory;

    protected $table = 'userlikes';

    protected $primaryKey = 'userlike_id';

    protected $fillable = [
        'user_id',
        'product_id',
    ];
}
