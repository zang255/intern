<?php


namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Http\Resources\BookResource;
// use Illuminate\Support\Facades\DB;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\User;
use App\Repositories\BookRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    protected $bookRepository; 
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository; 
    }
    public function index()
    {
        $books = $this->bookRepository->all();
        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách book thành công.',
            'data' => BookResource::collection($books)
        ], 200);
    }

    public function show($id)
    {
        $book = $this->bookRepository->find($id);
        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết book thành công.',
            'data' => new BookResource($book)
        ], 200);
    }

    public function store(Request $request)
    {
        $bookData = [
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'published_year' => $request->input('published_year'),
            'code' => $request->input('code'),
            'user_id' => Auth::user()->id,
        ];
        // tạo item mới
        try {
            $books = Book::create($request->all());
            $book = Book::create($bookData);
            Cache::forget('books'); // Xóa cache
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

    public function update(Request $request, $id)
    {
        try {
            $book = $this->bookRepository->find($id);
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
    public function getDeletedBooks()
    {
        $deletedBooks = Book::onlyTrashed()->get();
        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách sách đã bị xóa thành công.',
            'data' => BookResource::collection($deletedBooks),
        ], 200);
    }

    public function destroy($id)
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
    public function search(Request $request)
{
     
     $query = $request->input('query');
        
     // Gọi phương thức search trong model Book
     $books = Book::search($query);

     // Trả về kết quả dưới dạng JSON hoặc view
     return response()->json($books);
}
}
