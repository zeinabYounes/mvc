<?php
namespace App\Models;
use \Core\{Model,View};
use App\Models\User;

class Photo extends Model{
   protected static $table = "photos";
   protected static $fillable = ['name','path','user_id'];

   protected static $primary_key ="id" ;
   public function user(){
     return $this->belongsTo("App\Models\User","user_id");
   }
}
