<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Promise extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'promises';
    protected $fillable = [
        'description',
    ];
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_promise');
    }
}