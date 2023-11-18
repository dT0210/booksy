<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    //Show all authors
    public function index() {
        return view('');
    }

    public function show(Author $author) {
        return view('authors.show', [
            'author' => $author
        ]);
    }
}
