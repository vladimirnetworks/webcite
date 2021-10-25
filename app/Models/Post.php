<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

function citename()
{

    return strtolower(preg_replace("!^(www\.)!i", "", $_SERVER['HTTP_HOST']));
}

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['webcite','path','title','text'];

    public static function CGET()
    {
        return Post::where("webcite", '=', citename());
    }

    public static function CCREATE($inp)
    {

        $inp['webcite'] = citename();
        return Post::create($inp);
    }
}
