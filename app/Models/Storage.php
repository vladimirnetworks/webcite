<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;

    protected $fillable = ['webcite','origin','path','data','type'];


    public static function CGET()
    {
        return Storage::where("webcite", '=', citename());
    }

    public static function CCREATE($inp)
    {

        $inp['webcite'] = citename();
        return Storage::create($inp);
    }


}
