<?php

namespace Modules\Storage\App\Classes;

use Modules\Storage\App\Interfaces\StorageInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalStorage implements StorageInterface
{
     public function getFilePathFromUrl($url)
    {
        $url  = parse_url($url);
        $path = $url['path'] ?? '';

        return Str::replace('/uploads/', '', $path);
    }

    public function checkFileExists($path)
    {
        return Storage::exists($path) ? true : false;
    }

    public function getFile($path)
    {
        return Storage::get($path);
    }

    public function getFileAsResponse($path)
    {
        return Storage::response($path);
    }

    public function getMimeType($path)
    {
        return Storage::mimeType($path);
    }

    public function getFileSize($path)
    {
        return Storage::size($path);
    }

    public static function getUrl($file_path)
    {
        if (Storage::exists($file_path)) {
            return asset("uploads/" . $file_path);
            // return Storage::url($file_path);
        }

        return null;
    }

    public function store($path, $file, $name = '')
    {
        if (!$this->checkFileExists($path)) {
            Storage::makeDirectory($path, 0777, true, true);
        }

        if ($name) {
            $url = Storage::putFileAs($path ?? 'files', $file, $name);

            return $url;
        }

        $url = Storage::put($path ?? 'files', $file);

        return $url;
    }

    public function getUniqueIfHasSameFileName($basename, $same_name_counts)
    {
        $file_name = pathinfo($basename)['filename'];
        $file_type = pathinfo($basename)['extension'] ?? '';

        return $file_name . '-' . $same_name_counts . '.' . $file_type;
    }

    public function delete($path)
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        return true;
    }
}
