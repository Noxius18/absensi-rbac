<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class User extends Model {
    protected $table = 'Users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'role_id', 'username', 'email', 'password', 'status'
    ];

    public function role() {
        return $this->belongTo(Role::class, 'role_id');
    }
}