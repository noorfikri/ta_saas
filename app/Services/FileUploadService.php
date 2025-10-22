<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService
{
    public function uploadFile(UploadedFile $file, $filename, $folder){
        $imgFolder = 'uploaded/img/'.$folder;
        $imgFile = $folder.'_'.$filename.'_'.time().'_'.$file->getClientOriginalName();

        $path = Storage::disk('public')->putFileAs($imgFolder, $file, $imgFile);

        return $path;
    }
}
