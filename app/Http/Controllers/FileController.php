<?php

namespace App\Http\Controllers;
use App\Utilities\FileStorage;
use App\Models\Media;
use Str,Auth,Validator;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function storeFile(Request $request)
    {
        $datas=$request->all();
        $validator = Validator::make($datas, [
          //  "file" => "required|mimes:application/pdf"
            "file" => "required"
        ],
        [
            'file.required' => 'Le fichier est requis',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }


        $filename='';
        if($request->file('file')) {
        $filename=FileStorage::setFile('public',$request->file('file'),'reformes-docs', Str::slug($request->name.date("ymdhis")));
        $media=Media::create([
            "chemin"=>env('APP_URL')."/storage/reformes-docs/".$filename,
            "name"=>$filename
        ]);


        return response()->json([
            "success"=>true,
            "message"=>"Stats",
            "data"=>$media
        ],200);
        } else {
            return response()->json([
                "success"=>true,
                "message"=>"Stats",
            ],500);        }



    }

}
