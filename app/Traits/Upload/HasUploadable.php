<?php

namespace App\Traits\Upload;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

trait HasUploadable
{
    /**
     * File upload
     *
     * @param  $request
     * @param  string $property
     * @param  string $pathToStore
     * @return string
     */
    public function upload($request, string $property, string $pathToStore): string
    {
        $path = '';
        
        if ($request->hasFile($property))
        {
            $file = $request->{$property};

            $original = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $fileName = pathinfo($original, PATHINFO_FILENAME);

            $fileToStore = "${fileName}_" . time() . ".${ext}";

            $path = $file->storeAs($pathToStore, $fileToStore, 'public');
        }

        return Storage::disk('public')->url($path);
    }
    
    /**
     * deleteFile
     *
     * @param  $request
     * @param  array $file
     * @return void
     */
    public function deleteFile($request, array $file)
    {
        $paths = [];

        foreach ($file as $name => $path) {
            if ($request->hasFile($name)) {
                $path = str_replace('http://localhost:8000/storage/', 'public/', $path);
                $paths[] = $path;
            }
        }

        Storage::delete($paths);
    }

}