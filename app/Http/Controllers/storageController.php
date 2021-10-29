<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Storage;
use Illuminate\Database\QueryException;


class storageController extends Controller
{
    public function show($filename)
    {
        // $this->insert();

        $storeage = Storage::CGET()->where('path', '=', $filename)->get();

        return $storeage[0]->origin;
    }


    public function insert()
    {

        $path = "aaa.jpg";
        $origin  = "http://origin.jpg";


        $inserttedid = 0;
        $atemp = 0;
        do {
            $atemp++;
            try {
                $xx =  Storage::CCREATE([

                    'path' => $path,
                    'origin' => $origin,
                    'type' => "image/jpeg",

                ]);

                $inserttedid = $xx->id;
            } catch (QueryException $e) {

                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) {

                    $pathii = dupli($path);
                    echo $path . "<br>";
                }
            }
        } while ($inserttedid == 0 && $atemp <= 10000);
    }
}
