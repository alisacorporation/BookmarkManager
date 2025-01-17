<?= $this->extend('Modules\BookmarkManager\Views\layouts\main') ?>

<?= $this->section('content') ?>
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h2 class="text-lg leading-6 font-medium text-gray-900">Upload Bookmarks</h2>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Import your bookmarks from a Chrome/Firefox JSON export file.</p>
    </div>

    <div class="p-6">
        <?php if (session()->has('error')): ?>
            <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?= session('error') ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('bookmarks/upload') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>
            <div class="max-w-xl">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Bookmark File
                </label>
                <div id="dropZone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors duration-200">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="bookmarks" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>Upload a file</span>
                                <input id="bookmarks" name="bookmarkFile" type="file" accept=".json" class="sr-only" required>
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            JSON files up to 5MB
                        </p>
                        <p id="selectedFileName" class="text-sm text-indigo-600 mt-2 hidden"></p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="<?= base_url('bookmarks') ?>"
                    class="mr-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Upload Bookmarks
                </button>
            </div>
        </form>

        <div class="mt-8 border-t border-gray-200 pt-6">
            <h3 class="text-lg font-medium text-gray-900">How to export your bookmarks</h3>
            <div class="mt-4 prose prose-sm text-gray-500">
                <h4 class="text-base font-medium text-gray-900">From Chrome:</h4>
                <ol class="list-decimal pl-4">
                    <li>Open Chrome and click the three dots in the top-right corner</li>
                    <li>Go to Bookmarks → Bookmark Manager</li>
                    <li>Click the three dots in the Bookmark Manager</li>
                    <li>Select "Export bookmarks"</li>
                    <li>Save the JSON file</li>
                </ol>

                <h4 class="text-base font-medium text-gray-900 mt-4">From Firefox:</h4>
                <ol class="list-decimal pl-4">
                    <li>Open Firefox and click the menu button (three lines)</li>
                    <li>Go to Bookmarks → Manage Bookmarks</li>
                    <li>Click "Import and Backup" → "Export Bookmarks to JSON"</li>
                    <li>Save the JSON file</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('bookmarks');
        const selectedFileName = document.getElementById('selectedFileName');

        // Prevent defaults for drag events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Add/remove highlight class
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
        }

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files;
                updateFileName(files[0]);
            }
        }

        // Handle file input change
        fileInput.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                updateFileName(this.files[0]);
            }
        });

        // Update file name display
        function updateFileName(file) {
            selectedFileName.textContent = `Selected file: ${file.name}`;
            selectedFileName.classList.remove('hidden');

            // Validate file type
            if (!file.name.toLowerCase().endsWith('.json')) {
                selectedFileName.classList.add('text-red-500');
                selectedFileName.textContent = 'Error: Please select a JSON file';
            } else {
                selectedFileName.classList.remove('text-red-500');
            }
        }
    });
</script>
<?= $this->endSection() ?>