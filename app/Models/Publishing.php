<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publishing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable
     * @var array
     */
    protected $fillable = [
        'topic_id', 'payload', 'status', 'created_at', 'updated_at'
    ];

    /**
     * Get associated topic
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
}
