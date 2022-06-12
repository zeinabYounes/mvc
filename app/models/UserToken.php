<?php
namespace App\Models;
use \Core\{Model,View};
use App\Models\User;

class UserToken extends Model{
   protected static $table = "user_token";
   protected static $fillable = ['token','user_id','created_at','end_at'];

   protected static $primary_key ="id" ;
   public function users(){
     return $this->belongsTo("App\Models\User","user_id");
   }
}
