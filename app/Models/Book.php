<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
    public static function search($query)
    {
        return self::where('title', 'like', '%' . $query . '%')
               ->orWhere('author', 'like', '%' . $query . '%')
               ->orWhere('published_year', 'like', '%' . $query . '%')
               ->orWhere('code', 'like', '%' . $query . '%')
               ->orWhere('user_id', 'like', '%' . $query . '%')
            //    ->orderBy('title', 'asc')  // Sắp xếp theo 'title'
               ->paginate(10);
    }
}