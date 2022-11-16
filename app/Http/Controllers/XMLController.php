<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Orchestra\Parser\Xml\Facade as XmlParser;
use App\Models\authors;
use App\Models\books;
use DB;

class XMLController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUpload()
    {
        return view('upload');
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUploadPost(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xml|max:2048',
        ]);
  
        $file = $request->file;
        $xml = XmlParser::load($file);
        $books = $xml->parse([
            'data' => ['uses' => 'book[author,name]'],
            //'book_name' => ['uses' => 'book[name]'],
        ]);
        
        $books = head($books);

        foreach($books as $book) {
            $insertedID = authors::insertGetId([
                'name' => $book['author']
            ]);
            
            books::create([
                    'title'=>$book['name'],
                    'author_id' => $insertedID
                ]);
            }

        $bookData = DB::table('authors')
            ->join('books', 'authors.id', '=', 'books.author_id')
            ->select('authors.name', 'books.title')
            ->get();

        return view('books', compact('bookData'));
    }

    public function search(Request $request){
        if (request('search')) {
            $bookData = DB::table('authors')
                            ->join('books', 'authors.id', '=', 'books.author_id')
                            ->where('authors.name', 'like', '%' . request('search') . '%')
                            ->orWhere('books.title', 'like', '%' . request('search') . '%')
                            ->get();
        } else {
            $bookData = DB::table('authors')
            ->join('books', 'authors.id', '=', 'books.author_id')
            ->select('authors.name', 'books.title')
            ->get();
        }
    
        return view('books', compact('bookData'));
    
    }
}