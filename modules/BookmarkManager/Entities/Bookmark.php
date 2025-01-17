<?php

namespace Modules\BookmarkManager\Entities;

use CodeIgniter\Entity\Entity;

class Bookmark extends Entity
{
    protected $attributes = [
        'id' => null,
        'title' => null,
        'url' => null,
        'description' => '',
        'dateAdded' => null,
        'lastModified' => null,
        'folder' => '',
        'tags' => [],
    ];

    protected $casts = [
        'id' => 'string',
        'dateAdded' => 'datetime',
        'lastModified' => 'datetime',
        'tags' => 'array',
    ];

    /**
     * Create a Bookmark from JSON data
     * @param array $jsonData
     * @param string $folder The root folder (bookmark_bar or other)
     */
    public static function fromJson(array $jsonData, string $folder = ''): self
    {
        $bookmark = new self();

        // Convert Chrome's timestamp (microseconds since 1601) to Unix timestamp
        $dateAdded = null;
        $dateModified = null;

        if (isset($jsonData['date_added'])) {
            // Convert to seconds and adjust for epoch difference
            $dateAdded = floor(($jsonData['date_added'] / 1000000) - 11644473600);
        }

        if (isset($jsonData['date_modified'])) {
            // Convert to seconds and adjust for epoch difference
            $dateModified = floor(($jsonData['date_modified'] / 1000000) - 11644473600);
        }

        $bookmark->fill([
            'id' => $jsonData['id'] ?? null,
            'title' => $jsonData['name'] ?? '', // Chrome bookmark title is in 'name'
            'url' => $jsonData['url'] ?? '',
            'description' => $jsonData['meta_info']['Description'] ?? '', // If description exists in meta info
            'folder' => $folder,
            'dateAdded' => $dateAdded,
            'lastModified' => $dateModified,
        ]);

        return $bookmark;
    }

    /**
     * Set the bookmark's date added
     */
    public function setDateAdded($timestamp): self
    {
        if ($timestamp) {
            $this->attributes['dateAdded'] = date('Y-m-d H:i:s', $timestamp);
        }

        return $this;
    }

    /**
     * Set the bookmark's last modified date
     */
    public function setLastModified($timestamp): self
    {
        if ($timestamp) {
            $this->attributes['lastModified'] = date('Y-m-d H:i:s', $timestamp);
        }

        return $this;
    }
}
