<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class fireController extends Controller
{
  //



  public function index(Request $request)
  {

    $posts = Post::CGET()->orderBy('id', 'DESC')->paginate(10, ['*'], 'page', $request->page);
    return view("center",["pageTitle"=>"z","posts"=>$posts]);
  }
}
