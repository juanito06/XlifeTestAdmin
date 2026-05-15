<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'content',
        'status', 'views', 'likes', 'shares'
    ];

    protected $appends = ['viral_score'];

    public function getViralScoreAttribute(): float
    {
        return round(
            ($this->likes * 2 + $this->shares * 5 + $this->views) / 10,
            2
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}