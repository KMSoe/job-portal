<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Recruitment\Entities\JobOfferAttachment;
use Modules\Storage\App\Classes\LocalStorage;
use Modules\Storage\App\Interfaces\StorageInterface;

class JobOfferAttachmentController extends Controller
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

        $filePath = $this->storage->store('offer_attachments', $uploadedFile);

        $attachment = JobOfferAttachment::create([
            'file_path' => $filePath,
        ]);

        return response()->json([
            'status'  => true,
            'data'    => [
                'attachment' => $attachment,
            ],
            'message' => 'Uploaded successfully!',
        ], 201);
    }
}
