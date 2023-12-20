<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BookController extends Controller
{
    //Show all books
    public function index() {
        return view('books.index', [
            'new' => Book::orderBy('publish_date','desc')
                    ->limit(10)
                    ->get(),
            'best' => Book::selectRaw('book.*, avg(rating.rating) as average_rating')
                    ->join('rating', 'rating.book_id', 'book.id')
                    ->groupBy('book.id', 'book.title', 'book.pages', 'book.publish_date', 'book.description', 'book.cover', 'book.purchase_link')
                    ->orderBy('average_rating','desc')
                    ->limit(10)
                    ->get(),
            'popular' => Book::selectRaw('book.*, count(rating.rating) as ratings_count')
                    ->join('rating', 'rating.book_id', 'book.id')
                    ->groupBy('book.id', 'book.title', 'book.pages', 'book.publish_date', 'book.description', 'book.cover', 'book.purchase_link')
                    ->orderBy('ratings_count','desc')
                    ->limit(10)
                    ->get()
        ]);
    }
    //Show a single book
    public function show(Book $book) {
        return view('books.show', [
            'book' => $book
        ]);
    }

    public function shelf(Request $request) {
        if ($request->userId == 'not signed in') {
            Session::flash('message', 'Please sign in to continue');
            return view('welcome');
        }
        $book = Book::find($request->bookId);
        $book->users()->detach($request->userId);
        if ($request->shelfName == 'remove') {
            $book->ratings()->where('user_id', $request->userId)->delete();
            return redirect()->back()->with('message', 'Removed book from shelves');
        }
        if ($request->shelfName == 'read')
            $shelfName = 'Read';
        elseif ($request->shelfName == 'reading')
            $shelfName = 'Currently Reading';
        else {
            $shelfName = 'Want to Read';
        }
        $book->users()->attach($request->userId, ['shelf_name'=>$request->shelfName, 'date_added'=>date('Y-m-d')]);
        return redirect()->back()->with('message', 'Shelved as '. $shelfName);
    }

    public function rate(Request $request) {
        if ($request->userId == 'not signed in') {
            Session::flash('message', 'Please sign in to continue');
            return view('welcome');
        }
        $existRating = Rating::where('book_id', $request->bookId)->where('user_id', $request->userId)->first();
        if ($existRating) {
            if (isset($request->ratingValue))
                $existRating->update(['rating' => $request->ratingValue]);
            if (isset($request->review))
                $existRating->update(['review' => $request->review]);
        } else {
            Rating::create([
                'book_id' => $request->bookId,
                'user_id' => $request->userId,
                'rating' => $request->ratingValue,
                'review' => $request->review
            ]);
            $book = Book::where('id', $request->bookId)->first();
            $shelf = $book->shelf(User::where('account_id', $request->userId)->first());
            if (!isset($shelf)) {
                $book->users()->attach($request->userId, ['shelf_name'=>'read', 'date_added'=>date('Y-m-d')]);
            }
        }
        if (isset($request->ratingValue)) 
            $message = $request->ratingValue . '-star rating saved';
        else 
            $message = 'Review saved';
        return redirect()->back()->with('message', $message);
    }

    public function search() {
        $query = request('query');

        $results = Book::where('title', 'LIKE', "%{$query}%")
                    ->orWhereHas('contributors', function ($contributors) use ($query) {
                        $contributors->where('name', 'LIKE', "%{$query}%");
                    })
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhereHas('genres', function ($genres) use ($query) {
                        $genres->where('name', 'LIKE', "%{$query}%");
                    });
        return view('books.search', [
            'query' => $query,
            'results' => $results
        ]);
    }

    public function genre(Genre $genre) {
        return view('genres.show', [
            'genre' => $genre,
            'new' => $genre->books()->orderBy('publish_date','desc')
                    ->limit(10)
                    ->get(),
            'best' => Book::selectRaw('book.*, avg(rating.rating) as average_rating')
                    ->join('rating', 'rating.book_id', 'book.id')
                    ->join('book_genre', 'book_genre.book_id', '=', 'book.id')
                    ->where('book_genre.genre_id', $genre->id)
                    ->groupBy('book.id', 'book.title', 'book.language', 'book.pages', 'book.publish_date', 'book.description', 'book.cover', 'book.purchase_link')
                    ->orderBy('average_rating','desc')
                    ->limit(10)
                    ->get(),
            'popular' => Book::selectRaw('book.*, count(rating.rating) as ratings_count')
                    ->join('rating', 'rating.book_id', 'book.id')
                    ->join('book_genre', 'book_genre.book_id', '=', 'book.id')
                    ->where('book_genre.genre_id', $genre->id)
                    ->groupBy('book.id', 'book.title', 'book.language', 'book.pages', 'book.publish_date', 'book.description', 'book.cover', 'book.purchase_link')
                    ->orderBy('ratings_count','desc')
                    ->limit(10)
                    ->get()
        ]);

    }
}
