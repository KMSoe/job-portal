<?php

namespace Modules\Storage\App\Interfaces;

interface StorageInterface
{
    public function getFilePathFromUrl($url);
    public function checkFileExists($path);
    public function getFile($path);
    public function getMimeType($path);
    public function getFileSize($path);
    public function getFileAsResponse($path);
    public static function getUrl($file_path);
    public function store($path, $file, $name = '');
    public function getUniqueIfHasSameFileName($basename, $same_name_counts);
    public function delete($path);
}
