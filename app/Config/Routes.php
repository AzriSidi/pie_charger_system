<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

//scan routes
$routes->get('/scan', 'ScanningController::index');
$routes->get('/scan/dashboard', 'ScanningController::dashboard');
$routes->get('/scan/uploadCSV', 'ScanningController::uploadCSV');
$routes->get('/scan/printLabel', 'ScanningController::printLabel');
$routes->post('/scan/import-csv', 'ScanningController::importCsvToDb');
$routes->post('/scan/searchData', 'ScanningController::searchData');
$routes->get('/scan/searchItems', 'ScanningController::searchItems');
$routes->post('/scan/searchItemsAjax', 'ScanningController::searchItemsAjax');
$routes->get('/scan/downloadFile/(:any)', 'ScanningController::downloadFile/$1');
$routes->post('/scan/viewFailByModel', 'ScanningController::viewFailByModel');

// pack routes
$routes->get('/pack', 'PackingController::index');
$routes->get('/pack/dashboard', 'PackingController::dashboard');
$routes->get('/pack/searchItems', 'PackingController::searchItems');
$routes->post('/pack/searchDataPack', 'PackingController::searchDataPack');
$routes->get('/pack/addModel', 'PackingController::addModel');
$routes->post('/pack/sendModel', 'PackingController::sendModel');
$routes->get('/pack/editModel', 'PackingController::editModel');
$routes->post('/pack/searchModel', 'PackingController::searchModel');
$routes->post('/pack/saveEditModel', 'PackingController::saveEditModel');
$routes->get('/pack/login', 'PackingController::login');
$routes->get('/pack/profile', 'PackingController::profile',['filter' => 'authGuard']);
$routes->get('/pack/register', 'PackingController::register');
$routes->get('/pack/logout', 'PackingController::logout');
$routes->post('/pack/searchSN', 'PackingController::searchSN');
$routes->post('/pack/checkSN', 'PackingController::checkSN');
$routes->post('/pack/testDatatables', 'PackingController::testDatatables');
$routes->match(['get', 'post'], '/pack/store', 'PackingController::store');
$routes->match(['get', 'post'], '/pack/loginAuth', 'PackingController::loginAuth');
$routes->get('/pack/logout', 'PackingController::logout');
$routes->get('/pack/searchBoxIdSN', 'PackingController::searchBoxIdSN');
$routes->post('/pack/searchDataSN', 'PackingController::searchDataSN');
$routes->post('/pack/deleteSN', 'PackingController::deleteSN');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
