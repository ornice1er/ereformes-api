<?php
namespace App\Services;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Str;

class AwsService
{


    function upload($file,$path)  {
       try {
        $extension = $file->getClientOriginalExtension();
        $fileName = $file->getClientOriginalName().'__'.Str::random(20).'_'.date('His').rand(1, 999999).'.'.$extension;

        $full_url = Storage::disk('s3')->url($file->storeAs($path, $fileName, 's3'));

       return [
            'file_name' => $fileName,
            'full_url' => $full_url,
            'full_path' => $path.'/'.$fileName,
       ];
       } catch (\Throwable $th) {
       // throw $th;
        info($th->getMessage());
       return false;
       }
    }

}