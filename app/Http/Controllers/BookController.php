<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{

    
    public function index()
    {
        // Cache dữ liệu trong 60 phút
        // $books = Cache::remember('books', 60, function () {
        //     return Book::all();
        // });

        // if ($books->isEmpty()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Không có book nào.'
        //     ], 404);
        // }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Lấy danh sách book thành công.',
        //     'data' => BookResource::collection($books)
        // ], 200);
        return Book::getBooks();
    }

    public function store(Request $request)
    {

        // validate
        // $this->validate($request, [
        //     'title' => 'required',
        //     'author' => 'required',
        //     'published_year' => 'required|integer',
        //     'code' => 'required|unique:books'
        // ]);

        // $checkBook = Book::where('code', $request->input('code'))->first();
        // if ($checkBook) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Mã sách đã tồn tại. Vui lòng sử dụng mã khác.'
        //     ], 409); // 409 lỗi conflict
        // }

        $bookData = [
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'published_year' => $request->input('published_year'),
            'code' => $request->input('code'),
            'user_id' => Auth::user()->id,
        ];

        // tạo item mới
        // try {
        //     $book = Book::create($bookData);
        //     Cache::forget('books'); // Xóa cache
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Tạo books thành công.',
        //         'data' => new BookResource($book)
        //     ], 201);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Có lỗi xảy ra khi tạo books.' . $e
        //     ], 500);
        // }
        
        return Book::createBook($bookData , $request);
    }
    public function show($id)
    {
        // $book = Book::find($id);

        // if (!$book) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Không tìm thấy book.'
        //     ], 404);
        // }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Lấy chi tiết book thành công.',
        //     'data' => new BookResource($book)
        // ], 200);
        return Book::showBook($id);
    }
    public function update(Request $request, $id)
    {
        // $book = Book::query()->find($id);

        // if (!$book) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Không tìm thấy book để cập nhật.'
        //     ], 404);
        // }

        // // Cập nhật book
        // try {
        //     $book->update($request->all());
        //     Cache::forget('books'); // Xóa cache
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Cập nhật book thành công.',
        //         'data' => new BookResource($book)
        //     ], 200);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Có lỗi xảy ra khi cập nhật book.' . $e
        //     ], 500);
        // }
        return Book::editBook($id,$request);
    }
    public function destroy($id)
    {
        // $book = Book::find($id);

        // if (!$book) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Không tìm thấy book để xóa.'
        //     ], 404);
        // }

        // try {
        //     $book->delete();
        //     Cache::forget('books'); // Xóa cache
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Xóa book thành công.'
        //     ], 200);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Có lỗi xảy ra khi xóa book.'
        //     ], 500);
        // }
        return Book::deleteBook($id);
    }

    public function getDeletedBooks()
    {
        // $deletedBooks = Book::onlyTrashed()->get();

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Lấy danh sách sách đã bị xóa thành công.',
        //     'data' => BookResource::collection($deletedBooks),
        // ], 200);
        return Book::getTrash();
    }
}