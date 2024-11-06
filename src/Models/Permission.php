<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {
    protected $table = 'Permissions';
    protected $primaryKey = 'permission_id';
    public $timestamps = false;
    
    protected $fillable = [
        'resource', 'action'
    ];
}