<?php
namespace App\Traits;
use Illuminate\Http\Request;
trait FileUploader{
    public function uploader($file){
        $fileName = time().'.'.$file->extension();
        $file->move(public_path('uploads/user'), $fileName);
        return asset('uploads/user/'.$fileName);
    }
}
