<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = ['title', 'body'];

    use HasFactory;
}
