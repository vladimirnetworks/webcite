<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\libs\photoTeleg;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Storage;
use App\libs\shd;
use App\libs\Telegram;
use App\libs\bencurl;

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
        $html = '<div> <img alt="کریم بنزمال" src="http://dl.topnaz.com/fun/2012/4/image/217561_826.jpg"/> </div>';
        $title = "this is benz";


        $dom = shd::str_get_html($html);
        $imgs = $dom->find('img');


        //$phototeleg = new photoTeleg(new Telegram("tokebbb"));


        foreach ($imgs as $img) {

            $filename = $title;

            $imgurl = $img->attr['src'];
            $imgurl = fiximgurl($imgurl, $base);

            $imgfile = new bencurl($imgurl);
            $allheaders = $imgfile->Headers();
            $filesize = $imgfile->filesize();
            foreach ($allheaders[count($allheaders) - 1] as $header) {

                if (preg_match("!content\-type\:\s!i", $header)) {
                    if (preg_match("!jpeg|jpg|png|gif!i", $header) && $filesize >= 1024) {

                        if (isset($img->attr['title'])) {
                            $filename = $img->attr['title'];
                        }

                        if (isset($img->attr['alt'])) {
                            $filename = $img->attr['alt'];
                        }

                        if (preg_match("!jpeg|jpg!i", $header)) {
                            $exten = "jpg";
                            $type = "image/jpeg";
                        }

                        if (preg_match("!png!i", $header)) {
                            $exten = "png";
                            $type = "image/png";
                        }

                        if (preg_match("!gif!i", $header)) {
                            $exten = "gif";
                            $type = "image/gif";
                        }

                        $path = ($filename).".".$exten;

                      

                        Storage::Insert($path, $imgurl, json_encode([$filesize,$type]));

                    }
                }
            }

            //$img = $phototeleg->toteleg($imgurl);
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
