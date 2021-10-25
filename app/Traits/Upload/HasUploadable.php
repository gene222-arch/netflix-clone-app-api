<?php

namespace App\Traits\Upload;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Storage;

trait HasUploadable
{
    
    public function upload($request, string $property, string $pathToStore, int $width, int $height): string
    {
        $newFileName = '';
        
        if ($request->hasFile($property))
        {
            $file = $request->{$property};

            $originalFilename = $file->getClientOriginalName();
            $fileName = pathinfo($originalFilename, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            $newFileName = $fileName .'-'. time() . ".${extension}";
            $imageResize = Image::make($file->getRealPath())
                ->resize($width, $height, fn ($constraint) => $constraint->aspectRatio())
                ->encode($extension);
            
            Storage::disk('s3')->put($pathToStore . $newFileName, $imageResize->getEncoded());
        }

        return Storage::disk('s3')->url($pathToStore . $newFileName);
    }


    public function videoUpload($request, string $property, string $pathToStore): string
    {
        $path = '';
        
        if ($request->hasFile($property))
        {
            $file = $request->{$property};

            $originalFilename = $file->getClientOriginalName();
            $fileName = pathinfo($originalFilename, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            $newFileName = $fileName .'-'. time() . ".${extension}";

            $path = $file->storeAs($pathToStore, $newFileName, 's3');
        }

        return Storage::disk('s3')->url($path);
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
                $paths[] = str_replace(env('APP_URL') . "/storage/", 'public/', $path);
            }
        }

        Storage::delete($paths);
    }

}