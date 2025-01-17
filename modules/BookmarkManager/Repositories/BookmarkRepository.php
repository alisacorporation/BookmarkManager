<?php

namespace Modules\BookmarkManager\Repositories;

use Modules\BookmarkManager\Entities\Bookmark;

class BookmarkRepository
{
    /**
     * Get all bookmarks from the uploads directory
     *
     * @return array<Bookmark>
     */
    public static function getAllBookmarks(): array
    {
        $bookmarkFile = WRITEPATH . 'uploads/bookmarks.json';
        $bookmarks = [];

        try {
            $jsonData = json_decode(file_get_contents($bookmarkFile), true);

            if ($jsonData && isset($jsonData['roots'])) {
                // Process bookmark_bar folder
                if (isset($jsonData['roots']['bookmark_bar']['children'])) {
                    $bookmarks = array_merge(
                        $bookmarks,
                        self::processBookmarkItems($jsonData['roots']['bookmark_bar']['children'], 'bookmark_bar')
                    );
                }

                // Process other folder
                if (isset($jsonData['roots']['other']['children'])) {
                    $bookmarks = array_merge(
                        $bookmarks,
                        self::processBookmarkItems($jsonData['roots']['other']['children'], 'other')
                    );
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to parse bookmark file: ' . $bookmarkFile . ' - ' . $e->getMessage());
        }

        return $bookmarks;
    }

    /**
     * Recursively process bookmark items
     * @param array $items
     * @param string $parentFolder
     * @return array
     */
    private static function processBookmarkItems(array $items, string $parentFolder): array
    {
        $bookmarks = [];

        foreach ($items as $item) {
            // Skip folders (items with 'children' but no 'url')
            if (! isset($item['url'])) {
                // If it has children, process them recursively
                if (isset($item['children'])) {
                    $folderPath = $parentFolder . '/' . ($item['name'] ?? 'Unnamed Folder');
                    $bookmarks = array_merge(
                        $bookmarks,
                        self::processBookmarkItems($item['children'], $folderPath)
                    );
                }

                continue;
            }

            $bookmarks[] = Bookmark::fromJson($item, $parentFolder);
        }

        return $bookmarks;
    }

    /**
     * Get a single bookmark by filename
     * @param string $filename
     * @return Bookmark|null
     */
    public function getBookmark(string $filename): ?Bookmark
    {
        $filepath = WRITEPATH . 'uploads/' . $filename;

        if (! file_exists($filepath)) {
            return null;
        }

        try {
            $jsonData = json_decode(file_get_contents($filepath), true);

            return $jsonData ? Bookmark::fromJson($jsonData) : null;
        } catch (\Exception $e) {
            log_message('error', 'Failed to parse bookmark file: ' . $filepath . ' - ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Save a bookmark to a JSON file
     *
     * @param Bookmark $bookmark
     * @param string $filename
     *
     * @return bool
     */
    public function saveBookmark(Bookmark $bookmark, string $filename): bool
    {
        $filepath = WRITEPATH . 'uploads/' . $filename;

        try {
            $jsonData = json_encode($bookmark->toArray(), JSON_PRETTY_PRINT);

            return file_put_contents($filepath, $jsonData) !== false;
        } catch (\Exception $e) {
            log_message('error', 'Failed to save bookmark file: ' . $filepath . ' - ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Delete a bookmark file
     * @param string $filename
     * @return bool
     */
    public function deleteBookmark(string $filename): bool
    {
        $filepath = WRITEPATH . 'uploads/' . $filename;

        if (! file_exists($filepath)) {
            return false;
        }

        try {
            return unlink($filepath);
        } catch (\Exception $e) {
            log_message('error', 'Failed to delete bookmark file: ' . $filepath . ' - ' . $e->getMessage());

            return false;
        }
    }
}
