<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    protected $table = 'author';
    public $timestamps = false;

    public function contribution() : BelongsToMany 
    {
        return $this->belongsToMany(Book::class, 'book_contribution', 'contributor_id', 'book_id');
    }
}
