<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService
{
    public function uploadFile(UploadedFile $file, $filename, $folder){
        $imgFolder = 'assets/uploaded/img/'.$folder;
        $imgFile = $folder.'_'.$filename.'_'.time().'_'.$file->getClientOriginalName();

        $file->move($imgFolder, $imgFile);

        $path = $imgFolder.'/'.$imgFile;

        return $path;
    }
}
