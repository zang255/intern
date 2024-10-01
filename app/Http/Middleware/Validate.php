<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class Validate
{
    public function handle(Request $request, Closure $next)
    {
        $rules = [
            'title' => 'required',
            'author' => 'required',
            'published_year' => 'required|integer',
            'code' => 'required|unique:books'
        ]; 
        
        // Định nghĩa thông báo lỗi tùy chỉnh
        $messages = [
            'title.required' => 'Tên sách không được bỏ trống.',
            'author.required' => 'Tác giả không được bỏ trống.',
            'published_year.required' => 'Năm xuất bản không được bỏ trống.',
            'published_year.integer' => 'Năm xuất bản phải là một số.',
            'code.required' => 'Mã sách không được bỏ trống.',
            'code.unique' => 'Mã sách đã tồn tại.'
        ];
        // Sử dụng Validator để kiểm tra yêu cầu
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }
        return $next($request);
    }
}