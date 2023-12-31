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

    public function authors() {
        return $this->contributors()->wherePivot('role', 'author');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'book_genre', 'book_id', 'genre_id');
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shelf', 'book_id', 'user_id');
    }

    public function shelf(User $user) {
        $shelf = $this->users()->where('user_id', $user->account_id)->as('shelves')->withPivot('shelf_name', 'date_added')->first();
        if (!isset($shelf)) return null;
        return $shelf->shelves;
    }

    public function ratings() : HasMany 
    {
        return $this->hasMany(Rating::class, 'book_id');
    }

    public function rating(User $user) {
        return $this->ratings()->where('user_id', $user->account_id)->first();
    }

    public function similars()
    {
        $thisGenres = $this->genres()->pluck('genre_id');
        $allBooks = self::where('id', '!=', $this->id)->get();

        if ($thisGenres->isEmpty() || $allBooks->isEmpty()) {
            return collect();
        }

        $similarBooks = $allBooks->map(function ($book) use ($thisGenres) {
            $bookGenres = $book->genres()->pluck('genre_id');
            $commonGenres = $thisGenres->intersect($bookGenres);
            $differentGenres = $thisGenres->diff($bookGenres);

            $similarity = $commonGenres->count() / ($commonGenres->count() + $differentGenres->count());

            $book->similarity = $similarity;

            return $book;
        })->sortByDesc('similarity')->take(10);

        return $similarBooks;
    }
}
