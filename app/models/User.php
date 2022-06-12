<?php
namespace App\Models;
use \Core\{Model,View};
class User extends Model{
  protected static $table = "users";
  protected static $fillable = ['username','email','created_at','updated_at','role_id'];
  protected static $hidden = ['remember_token','password'];
  public function posts(){
    return $this->hasMany("App\Models\Post","user_id");
  }
  public function photos(){
    return $this->hasMany("App\Models\Photo","user_id");
  }
  public function role(){
    return $this->belongsTo("App\Models\Role","role_id");
  }
}
