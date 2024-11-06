<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class Role_Permission extends Model {
    protected $table = 'Permissions_Role';
    protected $fillable = [
        'role_id', 'permission_id'
    ];

    // Buat relasi nanti
}