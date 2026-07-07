<?php namespace App\Controllers\Karyawan;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $model   = new PermintaanModel();
        $id_user = session()->get('id_user');

        return view('karyawan/dashboard', [
            'title'         => 'Dashboard Karyawan',
            'total_pending' => $model->where('user_id', $id_user)->where('status', 'Pending')->countAllResults(),
            'total_valid'   => $model->where('user_id', $id_user)->where('status', 'Valid')->countAllResults(),
            'total_ditolak' => $model->where('user_id', $id_user)->where('status', 'Ditolak')->countAllResults(),
            'riwayat'       => $model->getPermintaanByUser($id_user),
        ]);
    }
}
