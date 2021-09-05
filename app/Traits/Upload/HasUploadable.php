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
        $path = '';
        
        if ($request->hasFile($property))
        {
            $file = $request->{$property};

            $originalFilename = $file->getClientOriginalName();
            $fileName = pathinfo($originalFilename, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            $newFileName = $fileName .'-'. time() . ".${extension}";
            $path = $pathToStore . '/' . $newFileName;

            $dir = storage_path('app/public/' . $pathToStore);

            if (! File::isDirectory($dir)) {
                File::makeDirectory($dir);
            }

            $imageResize = Image::make($file->getRealPath());
            $imageResize->resize($width, $height, fn($constraint) => $constraint->aspectRatio());
            $imageResize->save(storage_path('app/public/' . $path));
        }

        return Storage::disk('public')->url($path);
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

            $path = $file->storeAs($pathToStore, $newFileName, 'public');
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
                $paths[] = str_replace(env('APP_URL') . "/storage/", 'public/', $path);
            }
        }

        Storage::delete($paths);
    }

}