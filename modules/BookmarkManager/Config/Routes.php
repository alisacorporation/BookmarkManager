<?php

namespace Modules\BookmarkManager\Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('bookmarks', ['namespace' => 'Modules\BookmarkManager\Controllers'], function ($routes) {
    $routes->get('/', 'BookmarkController::index');
    $routes->get('upload', 'BookmarkController::upload');
    $routes->post('upload', 'BookmarkController::doUpload');
});
