<?php

namespace App\Http\Controllers;

use App\libs\bencurl;
use Illuminate\Http\Request;

use App\Models\Storage;
use Illuminate\Database\QueryException;


class storageController extends Controller
{
    public function show($filename)
    {
        // $this->insert();


        $storeage = Storage::CGET()->where('path', '=', urlencode($filename))->get();

        header("content-type: " . $storeage[0]->origin_type);

        $f = new bencurl($storeage[0]->origin);
        echo $f->download();
    }
}
