<?php
namespace App\Models;
use \Core\{Model,View};
use App\Models\User;

class Post extends Model{
   protected static $table = "posts";
   protected static $fillable = ['user_id','p_title','created_at','updated_at','p_text'];
   protected static $primary_key ="p_id" ;
   public function user(){
     return $this->belongsTo("App\Models\User","user_id");
   }
}
