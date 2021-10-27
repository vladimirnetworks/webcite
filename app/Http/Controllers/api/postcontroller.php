<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\libs\photoTeleg;
use Illuminate\Http\Request;
use App\Models\Post;
use App\libs\shd;
use App\libs\Telegram;

class postcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {

        return ($post->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        return ($post->delete());
    }



    public function botstore(Request $request)
    {






        $base = "https://www.google.com/jasem/isok/post3.html";





        $html = '<div> <img src="kdj.jpg"/> </div>';


        $dom = shd::str_get_html($html);

        $imgs = $dom->find('img');


        $phototeleg = new photoTeleg(new Telegram("tokebbb"));
        

        foreach ($imgs as $img) {

            $imgurl = $img->attr['src'];



            $imgurl = fiximgurl($imgurl, $base);
            $img = $phototeleg->toteleg($imgurl);
            //
            // ...


        }
        exit;

        Post::CCREATE([

            'path' => "test",
            'title' => "zzz",
            'text' => "ddd",

        ]);
        return "ok";
    }
}
