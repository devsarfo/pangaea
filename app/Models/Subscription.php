<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable
     * @var array
     */
    protected $fillable = [
        'topic_id', 'url', 'created_at', 'updated_at'
    ];
}
