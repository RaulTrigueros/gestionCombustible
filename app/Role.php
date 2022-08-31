<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name','guard_name','condicion'];
    public $timestamps = false;
 
    public function users()
    {
        return $this->hasMany('App\User');
    }
 
}