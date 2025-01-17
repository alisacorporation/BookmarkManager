<?php

namespace Modules\BookmarkManager\Controllers;

use CodeIgniter\Controller;
use Modules\BookmarkManager\Repositories\BookmarkRepository;

class BookmarkController extends Controller
{
    public function index()
    {
        // get all bookmarks from uploads folder
        $bookmarks = BookmarkRepository::getAllBookmarks();

        return view('Modules\BookmarkManager\Views\bookmarks\index', [
            'bookmarks' => $bookmarks,
        ]);
    }

    public function upload()
    {
        return view('Modules\BookmarkManager\Views\bookmarks\upload');
    }

    public function doUpload()
    {
        try {
            // Validate file existence
            if (! $this->validateUpload()) {
                return redirect()->to('bookmarks/upload')
                    ->with('error', 'Please select a valid bookmark file');
            }

            $file = $this->request->getFile('bookmarkFile');

            // Validate file type (JSON bookmarks)
            if (! in_array($file->getClientMimeType(), \Config\Mimes::$mimes['json'])) {
                return redirect()->to('bookmarks/upload')
                    ->with('error', 'Invalid file type. Please upload a JSON bookmark file');
            }

            // Set size limit (e.g., 5MB)
            $maxSize = 5 * 1024 * 1024;
            if ($file->getSize() > $maxSize) {
                return redirect()->to('bookmarks/upload')
                    ->with('error', 'File size exceeds maximum limit of 5MB');
            }

            // Create upload directory if it doesn't exist
            $uploadPath = WRITEPATH . 'uploads';
            if (! is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Move file directly as bookmarks.json, overwriting if exists
            if (! $file->move($uploadPath, 'bookmarks.json', true)) {
                throw new \RuntimeException('Failed to move uploaded file');
            }

            return redirect()->to('bookmarks')
                ->with('success', 'Bookmark file uploaded successfully');

        } catch (\Exception $e) {
            log_message('error', 'Bookmark upload failed: ' . $e->getMessage());

            return redirect()->to('bookmarks/upload')
                ->with('error', 'An error occurred while uploading the file');
        }
    }

    /**
     * Validates the upload request
     * @return bool
     */
    private function validateUpload(): bool
    {
        $file = $this->request->getFile('bookmarkFile');

        return $file !== null && $file->isValid() && ! $file->hasMoved();
    }
}
