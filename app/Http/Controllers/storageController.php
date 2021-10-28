<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Storage;
use Illuminate\Database\QueryException;


class storageController extends Controller
{
    public function show()
    {
        $this->insert();
        return "xx";
    }


    public function insert()
    {

        $pathii = "aaa.jpg";



        $inserttedid = 0;
        $atemp = 0;
        do {
            $atemp++;
            try {
                $xx =  Storage::CCREATE([

                    'path' => $pathii,
                    'origin' => "http://origin.jpg",
                    'type' => "image/jpeg",

                ]);

                $inserttedid = $xx->id;
            } catch (QueryException $e) {

                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) {

                    $pathii = dupli($pathii);
                    echo $pathii . "<br>";
                }
            }
        } while ($inserttedid == 0 && $atemp <= 10000);
    }
}
