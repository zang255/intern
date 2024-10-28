<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Permission extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];
    // Một quyền thuộc về nhiều vai trò
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}