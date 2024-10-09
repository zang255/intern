<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class Validate
{
    public function handle(Request $request, Closure $next)
{
    
    $language = $request->input('lang', 'en'); // Lấy ngôn ngữ từ query param
    app()->setLocale($language);
    

    $rules = [
        'title' => 'required',
        'author' => 'required',
        'published_year' => 'required|integer',
        'code' => 'required|unique:books'
    ];

    // Sử dụng thông báo lỗi đa ngôn ngữ
    $messages = [
        'title.required' => __('validation.title_required'),
        'author.required' => __('validation.author_required'),
        'published_year.required' => __('validation.published_year_required'),
        'published_year.integer' => __('validation.published_year_integer'),
        'code.required' => __('validation.code_required'),
        'code.unique' => __('validation.code_unique')
    ];

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