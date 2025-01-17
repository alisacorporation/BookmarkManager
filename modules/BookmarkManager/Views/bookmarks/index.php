<?= $this->extend('Modules\BookmarkManager\Views\layouts\main') ?>

<?= $this->section('content') ?>
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h2 class="text-lg leading-6 font-medium text-gray-900">Your Bookmarks</h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">A list of all your saved bookmarks.</p>
        </div>

        <?php if (! empty($bookmarks)): ?>
            <a href="<?= base_url('bookmarks/upload') ?>"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Upload New Bookmarks
            </a>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
        <?php foreach ($bookmarks as $bookmark): ?>
            <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 truncate max-w-[200px]" title="<?= esc($bookmark->title ?: 'Untitled') ?>">
                                <?= esc(mb_strimwidth($bookmark->title ?: 'Untitled', 0, 25, '...')) ?>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 truncate max-w-[200px]" title="<?= esc($bookmark->url) ?>">
                                <?= esc(mb_strimwidth(str_replace(['https://', 'http://'], '', $bookmark->url), 0, 30, '...')) ?>
                            </p>
                        </div>
                        <div class="ml-2 flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?php
                                $folderParts = explode('/', $bookmark->folder);
            echo esc(mb_strimwidth(end($folderParts), 0, 15, '...'));
            ?>
                            </span>
                            <?php if (count($folderParts) > 1): ?>
                                <div class="text-xs text-gray-400 mt-1 truncate max-w-[100px]" title="<?= esc(implode(' / ', array_slice($folderParts, 0, -1))) ?>">
                                    <?= esc(mb_strimwidth(implode(' / ', array_slice($folderParts, 0, -1)), 0, 20, '...')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($bookmark->description): ?>
                        <p class="mt-2 text-sm text-gray-600 truncate" title="<?= esc($bookmark->description) ?>">
                            <?= esc(mb_strimwidth($bookmark->description, 0, 60, '...')) ?>
                        </p>
                    <?php endif; ?>

                    <div class="mt-4 flex justify-between items-center">
                        <div class="text-xs text-gray-500">
                            Added: <?= $bookmark->dateAdded ? $bookmark->dateAdded->format('M j, Y') : 'Unknown' ?>
                        </div>
                        <a href="<?= esc($bookmark->url) ?>"
                            target="_blank"
                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Visit
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($bookmarks)): ?>

        <div class="text-center py-16">
            <div class="flex flex-col items-center">
                <!-- Empty state icon -->
                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                </svg>

                <!-- Empty state message -->
                <h3 class="mt-4 text-lg font-medium text-gray-900">No bookmarks found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by uploading your bookmark file.</p>

                <!-- Upload CTA button -->
                <a href="<?= base_url('bookmarks/upload') ?>"
                    class="mt-6 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                    <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload Bookmarks
                </a>

                <!-- Help text -->
                <p class="mt-4 text-xs text-gray-500">
                    You can export your bookmarks from Chrome or Firefox as a JSON file
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>