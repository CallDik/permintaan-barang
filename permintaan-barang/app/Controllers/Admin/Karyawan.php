<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PermintaanModel;

class Karyawan extends BaseController
{
    public function index()
    {
        $model           = new UserModel();
        $permintaanModel = new PermintaanModel();

        return view('admin/karyawan/index', [
            'title'    => 'Data Karyawan',
            'karyawan' => $model->where('role', 'karyawan')->orderBy('nama', 'ASC')->findAll(),
            'pending'  => $permintaanModel->countPending(),
        ]);
    }

    public function tambah()
    {
        $permintaanModel = new PermintaanModel();
        return view('admin/karyawan/tambah', [
            'title'   => 'Tambah Karyawan',
            'pending' => $permintaanModel->countPending(),
        ]);
    }

    public function simpan()
    {
        $model = new UserModel();

        $rules = [
            'nama'     => 'required',
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[6]',
        ];

        $messages = [
            'username' => ['is_unique' => 'Username sudah dipakai, pilih username lain!'],
            'password' => ['min_length' => 'Password minimal 6 karakter!'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->insert([
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'karyawan',
        ]);

        return redirect()->to('/admin/karyawan')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function hapus($id)
    {
        $model = new UserModel();
        $model->delete($id);
        return redirect()->to('/admin/karyawan')->with('success', 'Karyawan berhasil dihapus!');
    }
}
