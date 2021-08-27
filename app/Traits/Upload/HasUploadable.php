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
            $destinationPath = public_path('/' . $pathToStore);

            $newFileName = $fileName .'-'. time() . ".${extension}";
            $path = $destinationPath . $newFileName;

            $prepareImgIntervention = Image::make($file->path());
            $prepareImgIntervention->resize($width, $height, fn($constraint) => $constraint->aspectRatio())
                ->save($destinationPath . '/' . $newFileName);

            // $path = $file->storeAs($pathToStore, $fileToStore, 'public');
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