<?php

namespace App\Services;

use Illuminate\Support\Str;

class UploadService {

    public function saveToLocal($file) : array
    {
        $name = $file->getClientOriginalName();
        $extension = $file->extension();
        $hash = Str::uuid() .".$extension";
        $public_file_path = $file->store('public');
        $storage_file_path = str_replace('public', 'storage', $public_file_path);

        return [
            'name'              => $name,
            'hash'              => $hash,
            'storage_file_path' => $storage_file_path,
            'public_file_path'  => $public_file_path,
        ];
    }
}
