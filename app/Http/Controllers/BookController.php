<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index()
    {
        $books = DB::table('books')->get();
        return response()->json($books);
    }

    public function show($id)
    {
        $book = DB::table('books')->where('id', $id)->first();
        return response()->json($book);
    }

    public function store(Request $request)
    {
        $data = $request->only('title', 'author', 'description');
        DB::table('books')->insert($data);
        return response()->json(['message' => 'Book created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only('title', 'author', 'description');
        DB::table('books')->where('id', $id)->update($data);
        return response()->json(['message' => 'Book updated successfully']);
    }

    public function destroy($id)
    {
        DB::table('books')->where('id', $id)->delete();
        return response()->json(['message' => 'Book deleted successfully']);
    }
}
