<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $table = 'book';
    public $timestamps = false;
    public function contributors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_contribution', 'book_id', 'contributor_id')
                    ->as('contribution')
                    ->withPivot('role');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'book_genre', 'book_id', 'genre_id');
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shelf', 'book_id', 'user_id');
    }

    public function ratings() : HasMany 
    {
        return $this->hasMany(Rating::class, 'book_id');
    }

    public function rating(User $user) {
        return Rating::where('book_id', $this->id)->where('user_id', $user->id)->get();
    }

    public function similar() {
        //return similar books
    }
}
