<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;
use App\Models\MasterAlatKantorModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $permintaanModel = new PermintaanModel();
        $masterModel     = new MasterAlatKantorModel();
        $userModel       = new UserModel();
        $stats           = $masterModel->getDashboardStats();

        $data = [
            'title'           => 'Dashboard Admin',
            'total_pending'   => $permintaanModel->where('status', 'Pending')->countAllResults(),
            'total_valid'     => $permintaanModel->where('status', 'Valid')->countAllResults(),
            'total_ditolak'   => $permintaanModel->where('status', 'Ditolak')->countAllResults(),
            'total_karyawan'  => $userModel->where('role', 'karyawan')->countAllResults(),
            'permintaan_baru' => $permintaanModel->getPermintaanWithUser('Pending'),
            // Alat Kantor
            'total_jenis_barang'  => $stats['total_barang'],
            'total_stok_kantor'   => $stats['total_stok'],
            'jumlah_hampir_habis' => $stats['hampir_habis'],
            'jumlah_habis'        => $stats['habis'],
            'barang_hampir_habis' => $masterModel->getHampirHabis(),
            'barang_habis'        => $masterModel->getHabis(),
        ];

        return view('admin/dashboard', $data);
    }
}
