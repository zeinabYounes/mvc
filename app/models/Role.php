<?php
namespace App\Models;
use \Core\{Model,View};
use App\Models\User;

class Role extends Model{
   protected static $table = "roles";
   protected static $fillable = ['role_name'];

   protected static $primary_key ="roleID" ;
   public function users(){
     return $this->hasMany("App\Models\User","role_id");
   }
   public function permis(){
     return $this->belongsToMany("App\Models\Permission",'role_permissions',"role_id","perm_id");
   }
}
