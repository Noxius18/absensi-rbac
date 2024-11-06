<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Role_Permission;

class Role extends Model {
    protected $table = 'Roles';
    protected $primaryKey = 'role_id';
    public $timestamps = false;

    protected $fillable = [
        'role_name', 'deskripsi'
    ];

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'Role_Permissions', 'role_id', 'permission_id');
    }
}