<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmark Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex flex-col min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-gray-800">Bookmark Manager</h1>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="<?= base_url('bookmarks') ?>" 
                           class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Bookmarks
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto py-6 sm:px-6 lg:px-8">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-lg mt-auto">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                Bookmark Manager &copy; <?= date('Y') ?>
            </p>
        </div>
    </footer>
</body>
</html>
