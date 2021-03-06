<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Storage extends Model
{
    use HasFactory;

    protected $fillable = ['webcite', 'origin', 'path', 'origin_type', 'origin_size','origin_width','origin_height'];


    public static function CGET()
    {
        return Storage::where("webcite", '=', citename());
    }

    public static function CCREATE($inp)
    {

        $inp['webcite'] = citename();
        return Storage::create($inp);
    }



    public static function Insert($inp)
    {

        $createstorage = null;

        $inserttedid = 0;
        $atemp = 0;
        do {
            $atemp++;
            try {
                $createstorage =  Storage::CCREATE($inp);

                $inserttedid = $createstorage->id;
            } catch (QueryException $e) {


                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) {

                    $inp['path'] = dupli($inp['path']);
                }
            }
        } while ($inserttedid == 0 && $atemp <= 10000);


        return  $createstorage;
    }
}
