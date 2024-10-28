<?php

namespace App\Models;

use App\Http\Resources\BookResource;
use App\Mail\TestMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Book extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'books';
    protected $fillable = [
        'title',
        'author',
        'published_year',
        'code',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getBooks()
    {
        // Cache dữ liệu trong 60 phút
        $books = Cache::remember('books', 60, function () {
            return Book::all();
        });

        if ($books->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có book nào.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách book thành công.',
            'data' => BookResource::collection($books)
        ], 200);
    }
    public static function createBook($bookData)
    {
        // tạo item mới
        try {
            $book = Book::create($bookData);
            Cache::forget('books'); // Xóa cache

            // Gửi email
            try {
                Log::info('Book created:', $book->toArray());
                Mail::to('tubaph01@gmail.com')->send(new TestMail($book->toArray()));
            } catch (\Exception $e) {
                Log::error('Error sending email: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi gửi email. ' . $e->getMessage()
                ], 500);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Tạo books thành công.',
                'data' => new BookResource($book)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo books.' . $e
            ], 500);
        }
    }
    public static function showBook($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy book.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết book thành công.',
            'data' => new BookResource($book)
        ], 200);
    }
    public static function editBook($id, $request)
    {
        $book = Book::query()->find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy book để cập nhật.'
            ], 404);
        }

        // Cập nhật book
        try {
            $book->update($request->all());
            Cache::forget('books'); // Xóa cache
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật book thành công.',
                'data' => new BookResource($book)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật book.' . $e
            ], 500);
        }
    }
    public static function deleteBook($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy book để xóa.'
            ], 404);
        }

        try {
            $book->delete();
            Cache::forget('books'); // Xóa cache
            return response()->json([
                'success' => true,
                'message' => 'Xóa book thành công.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa book.'
            ], 500);
        }
    }

    public static function getTrash()
    {
        $deletedBooks = Book::onlyTrashed()->get();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách sách đã bị xóa thành công.',
            'data' => BookResource::collection($deletedBooks),
        ], 200);
    }
}