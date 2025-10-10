<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Storage\App\Classes\LocalStorage;
use Modules\Storage\App\Interfaces\StorageInterface;

class FileController extends Controller
{
    private StorageInterface $storage;

    public function __construct(LocalStorage $storage)
    {
        $this->storage = $storage;
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
            ],
        ]);

        $uploadedFile = $request->file('file');

        $file_path = $this->storage->store('offer_attachments', $uploadedFile);

        return response()->json([
            'status'  => true,
            'data'    => [
                'file_path' => $file_path,
            ],
            'message' => 'Uploaded successfully!',
        ], 201);
    }
}
