<?php
namespace App\Controllers\Api;
use Core\{Controller,Validator,DB,Request,Response,Middleware,Auth,Cookie};
use App\Models\Post;
use App\Models\User;

use App\Middlewares\Kernal;
class ApiBlogController extends BaseController{
  public function index(){
    $posts = Post::with('user');
    return $this->ReturnData("posts",$posts,$msg="ok");
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function store(Request $req){
    $errors = Validator::getValidate($req->get(),[
    'title'=>['required','string','max:100','min:3'] ,
    'text'=>['required','string','max:2000','min:3']
    ]);
    if(count($errors) > 0){
      return $this->ReturnError($errors,"400");
    }
    $post = new Post;
    $post->p_title = $req->title;
    $post->user_id = 100;
    $post->p_text = $req->text;
    $post->save();
    return $this->ReturnSuccess("your posts have created",$errorNum="0000");
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function update($id,Request $req){
    $errors = Validator::getValidate($req->get(),[
      'title'=>['required','string','max:100','min:3'] ,
      'text'=>['required','string','max:2000','min:3']
    ]);
    if(count($errors) > 0){
      return $this->ReturnError($errors,"400");
    }
    $post = Post::findOrFail($id);
    $post->p_title = $req->title;
    $post->user_id = 100;
    $post->p_text = $req->text;
    $post->update();
    return $this->ReturnSuccess("your posts have updated",$errorNum="0000");
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function delete($id){
    $post = Post::findOrFail($id);
    $post->delete();
    return $this->ReturnSuccess("your posts have deleted",$errorNum="0000");
  }
}
?>
