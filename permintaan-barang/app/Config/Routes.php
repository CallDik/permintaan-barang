<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// AUTH
$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// ADMIN
$routes->group('admin', ['filter' => 'authAdmin'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Permintaan
    $routes->get('permintaan', 'Admin\Permintaan::index');
    $routes->get('permintaan/detail/(:num)', 'Admin\Permintaan::detail/$1');
    $routes->post('permintaan/setujui/(:num)', 'Admin\Permintaan::setujui/$1');
    $routes->post('permintaan/tolak/(:num)', 'Admin\Permintaan::tolak/$1');
    $routes->get('permintaan/edit/(:num)', 'Admin\Permintaan::edit/$1');
    $routes->post('permintaan/update/(:num)', 'Admin\Permintaan::update/$1');
    $routes->get('permintaan/hapus/(:num)', 'Admin\Permintaan::hapus/$1');

    // Karyawan
    $routes->get('karyawan', 'Admin\Karyawan::index');
    $routes->get('karyawan/tambah', 'Admin\Karyawan::tambah');
    $routes->post('karyawan/simpan', 'Admin\Karyawan::simpan');
    $routes->get('karyawan/hapus/(:num)', 'Admin\Karyawan::hapus/$1');

    // Stok Barang (semua kategori - tetap ada)
    $routes->get('stok', 'Admin\Stok::index');
    $routes->get('stok/tambah', 'Admin\Stok::tambah');
    $routes->post('stok/simpan', 'Admin\Stok::simpan');
    $routes->get('stok/edit/(:num)', 'Admin\Stok::edit/$1');
    $routes->post('stok/update/(:num)', 'Admin\Stok::update/$1');
    $routes->get('stok/hapus/(:num)', 'Admin\Stok::hapus/$1');

    // Master Alat Kantor
    $routes->get('master-alat-kantor', 'Admin\MasterAlatKantor::index');
    $routes->get('master-alat-kantor/tambah', 'Admin\MasterAlatKantor::tambah');
    $routes->post('master-alat-kantor/simpan', 'Admin\MasterAlatKantor::simpan');
    $routes->get('master-alat-kantor/edit/(:num)', 'Admin\MasterAlatKantor::edit/$1');
    $routes->post('master-alat-kantor/update/(:num)', 'Admin\MasterAlatKantor::update/$1');
    $routes->get('master-alat-kantor/hapus/(:num)', 'Admin\MasterAlatKantor::hapus/$1');
    $routes->get('master-alat-kantor/tambah-stok/(:num)', 'Admin\MasterAlatKantor::tambahStok/$1');
    $routes->post('master-alat-kantor/simpan-stok/(:num)', 'Admin\MasterAlatKantor::simpanStok/$1');
    $routes->get('master-alat-kantor/riwayat', 'Admin\MasterAlatKantor::riwayat');
    $routes->get('master-alat-kantor/riwayat/(:num)', 'Admin\MasterAlatKantor::riwayat/$1');
    // API JSON endpoints (digunakan oleh AJAX di form karyawan & admin)
    $routes->get('master-alat-kantor/api/stok/(:num)', 'Admin\MasterAlatKantor::getStok/$1');
    $routes->get('master-alat-kantor/api/list', 'Admin\MasterAlatKantor::listBarang');
});

// KARYAWAN
$routes->group('karyawan', ['filter' => 'authKaryawan'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Karyawan\Dashboard::index');

    // Permintaan
    $routes->get('permintaan', 'Karyawan\Permintaan::index');
    $routes->get('permintaan/tambah', 'Karyawan\Permintaan::tambah');
    $routes->post('permintaan/simpan', 'Karyawan\Permintaan::simpan');

    // API JSON untuk karyawan (cek stok saat pilih barang)
    $routes->get('api/stok-barang/(:num)', 'Karyawan\Permintaan::getStokBarang/$1');
    $routes->get('api/list-barang', 'Karyawan\Permintaan::listBarang');
});
