<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    protected $table = "rating";
    public $timestamps = false;
    protected $fillable = [
        'book_id',
        'user_id',
        'rating',
        'review'
    ];
    public function book() : BelongsTo 
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function user() : BelongsTo 
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
