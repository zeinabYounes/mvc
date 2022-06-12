<?php
namespace App\Models;
use \Core\{Model,View};
use App\Models\Role;

class Permission extends Model{
   protected static $table = "permissions";
   protected  static $fillable = ['perm_name','perm_description'];
   protected static $primary_key ="permID" ;
   public function roles(){
     return $this->belongsToMany("App\Models\Role",'role_permissions',"perm_id","role_id");
   }
}
