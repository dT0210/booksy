<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BookController extends Controller
{
    //Show all books
    public function index() {
        return view('books.index', [
            'books' => Book::with('contributors', 'genres')->get(),
            'new' => Book::orderBy('publish_date','desc')
                    ->limit(10)
                    ->get(),
            'best' => Book::selectRaw('book.*, avg(rating.rating) as average_rating')
                    ->join('rating', 'rating.book_id', 'book.id')
                    ->groupBy('book.id', 'book.title', 'book.language', 'book.pages', 'book.publish_date', 'book.description', 'book.cover', 'book.purchase_link')
                    ->orderBy('average_rating','desc')
                    ->limit(10)
                    ->get(),
            'popular' => Book::selectRaw('book.*, count(rating.rating) as ratings_count')
                    ->join('rating', 'rating.book_id', 'book.id')
                    ->groupBy('book.id', 'book.title', 'book.language', 'book.pages', 'book.publish_date', 'book.description', 'book.cover', 'book.purchase_link')
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
            return redirect()->back()->with('message', 'Removed book from shelves');
        }
        $book->users()->attach($request->userId, ['shelf_name'=>$request->shelfName]);
        return redirect()->back()->with('message', 'Shelved as '. $request->shelfName);
    }

    public function rate(Request $request) {
        $existRating = Rating::where('book_id', $request->bookId)->where('user_id', $request->userId)->first();
        if ($existRating) {
            $existRating->update([
                'rating' => $request->ratingValue
            ]);
        } else {
            Rating::create([
                'book_id' => $request->bookId,
                'user_id' => $request->userId,
                'rating' => $request->ratingValue
            ]);
        }
        return redirect()->back()->with('message', $request->ratingValue . '-star rating saved');
    }

    public function search() {
        return view('wip');
    }
}
