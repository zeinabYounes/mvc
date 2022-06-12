<?php
namespace App\Controllers;
use Core\{Controller,Validator,DB,Request,Response,Middleware,Auth,Cookie};
use App\Models\Post;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

use App\Middlewares\Kernal;
class BlogController extends Controller{
  public function index(){
    $posts = Post::with('user');
    $this->view->render("blogs.index",compact('posts'));
    // $this->view->render("auth.createCV");

  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function create(){
    $this->view->setSiteTitle("create-post");
    $this->view->render("blogs.create");
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function store(Request $req){
    Validator::validate($req->get(),[
    'title'=>['required','string','max:100','min:3'] ,
    'text'=>['required','string','max:2000','min:3']
    ]);
    $post = new Post;
    $post->p_title = $req->title;
    $post->user_id = 100;
    $post->p_text = $req->text;
    $post->save();
    Response::redirect("blogs/index");
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function edit($id){
    $post = Post::findOrFail($id);
    $this->view->setSiteTitle("edit-post");
    $this->view->render("blogs.edit",compact('post'));
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function update($id,Request $req){
    Validator::validate($req->get(),[
      'title'=>['required','string','max:100','min:3'] ,
      'text'=>['required','string','max:2000','min:3']
    ]);
    $post = Post::findOrFail($id);
    $post->p_title = $req->title;
    $post->user_id = 100;
    $post->p_text = $req->text;
    $post->update();
    Response::redirect("blogs/index");
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function delete($id){
    $post = Post::findOrFail($id);
    $post->delete();
    Response::redirect("blogs/index");
  }

}
?>
