<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Project extends Model {
    protected $table = 'Projects';
    protected $primaryKey = 'project_id';
    public $timestamps = false;

    protected $fillable = [
        'manager_id', 'project_name', 'start_date', 'end_date'
    ];

}