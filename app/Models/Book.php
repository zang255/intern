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
}