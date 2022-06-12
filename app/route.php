<?php
use \Core\Router;
Router::get("posts/create","PostController@create");
//////////////////////////////////////////////////////////////
Router::get("/login","AuthController@get_login");
Router::get("/","AuthController@get_login");
Router::post("/login","AuthController@login");
Router::get("/register","AuthController@getRegister");
Router::post("/register","AuthController@register");
Router::get("/logout","AuthController@logout");
/////////////////////////////////////////////////////////////

Router::get("/blogs/create","BlogController@create",['auth']);
Router::post("/blogs","BlogController@store");
Router::get("/blogs/index","BlogController@index",['auth','perm:show_posts']);
Router::post("/blogs/update/{id}","BlogController@update",['auth']);
Router::get("/blogs/delete/{id}","BlogController@delete");


Router::get("/blogs/show/{id}","BlogController@show");
Router::get("/blogs/edit/{id}","BlogController@edit");


Router::get("blogs/index5","BlogController@index");
/////////////////////////////////////////////api////////////////////////////////////////////////////
Router::post('/api/login','api\ApiAuthController@login');
Router::get('/api/logout','api\ApiAuthController@logout');
Router::get('/api/post/index','api\ApiBlogController@index',['api_auth','api_perm:show_posts']);
