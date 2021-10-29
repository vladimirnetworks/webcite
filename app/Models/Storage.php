<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Storage extends Model
{
    use HasFactory;

    protected $fillable = ['webcite', 'origin', 'path', 'origin_data'];


    public static function CGET()
    {
        return Storage::where("webcite", '=', citename());
    }

    public static function CCREATE($inp)
    {

        $inp['webcite'] = citename();
        return Storage::create($inp);
    }



    public static function Insert($path, $origin,$origin_data)
    {

        $createstorage = null;

        $inserttedid = 0;
        $atemp = 0;
        do {
            $atemp++;
            try {
                $createstorage =  Storage::CCREATE([

                    'path' => $path,
                    'origin' => $origin,
                    'origin_data' => $origin_data,

                ]);

                $inserttedid = $createstorage->id;
            } catch (QueryException $e) {

               
                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) {

                    $path = dupli($path);
                 
                
                }
            }
        } while ($inserttedid == 0 && $atemp <= 10000);

      
        return  $createstorage;
    }
}
