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
        $html = '<div> <img alt="کریم بنزمال" src="http://dl.topnaz.com/fun/2012/4/image/217561_826.jpg"/> 
        
        <img  src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Atat%C3%BCrk_Kemal.jpg/428px-Atat%C3%BCrk_Kemal.jpg"/>
        
        
        </div>';


        $html = '

        <img src="https://sc.upid.ir/upload/15tcjvtg/%D8%B7%D8%B1%D8%A7%D8%AD%DB%8C-%D9%84%D8%A7%DA%A9-%D9%86%D8%A7%D8%AE%D9%86.jpg" />
';
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
            foreach ((array) $allheaders[count($allheaders) - 1] as $header) {




                if (preg_match("!content\-type\:\s!i", $header)) {




                  
                 

                    if (preg_match("!jpeg|jpg|png|gif!i", $header)/* && $filesize >= 1024*/) {

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


                        

                        $path = urlize($filename) . "." . $exten;

                        $imgload = new bencurl($imgurl);

                        $image = imagecreatefromstring($imgload->download());

                        $width  = imagesx($image);
                        $height = imagesy($image);



                        $inserted = Storage::Insert(

                            [

                                'path' => $path,
                                'origin' => $imgurl,
                                'origin_type' => $type,
                                'origin_size' => $filesize,
                                'origin_width' => $width,
                                'origin_height' => $height,

                            ]

                        );


                        if (isset($inserted->id)) {

                            $maxwidth = 320;

                            if ($width > 320) {

                                $prc = $width / $maxwidth;
                                $height = round($height / $prc);

                                $newelem  = '<a href="' . $inserted->path . '"><img src="' . $inserted->path . '?size=small" width="' . $maxwidth . '" height="' . $height . '" /></a>';
                            } else {

                                $newelem  = '<img src="' . $inserted->path . '" width="' . $width . '" height="' . $height . '" />';
                            }


                            echo $newelem;
                        }
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
