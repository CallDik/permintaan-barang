<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StokModel;
use App\Models\PermintaanModel;

class Stok extends BaseController
{
    public function index()
    {
        $model = new StokModel();
        $permintaanModel = new PermintaanModel();

        return view('admin/stok/index', [
            'title'   => 'Manajemen Stok Barang',
            'stok'    => $model->orderBy('created_at', 'DESC')->findAll(),
            'pending' => $permintaanModel->countPending(),
        ]);
    }

    public function tambah()
    {
        $permintaanModel = new PermintaanModel();
        return view('admin/stok/tambah', [
            'title'   => 'Tambah Barang',
            'pending' => $permintaanModel->countPending(),
        ]);
    }

    public function simpan()
    {
        $model = new StokModel();

        $model->insert([
            'kode_barang' => $model->generateKode(),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $this->request->getPost('kategori'),
            'stok'        => $this->request->getPost('stok'),
        ]);

        return redirect()->to('/admin/stok')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $model = new StokModel();
        $permintaanModel = new PermintaanModel();

        return view('admin/stok/edit', [
            'title'   => 'Edit Barang',
            'data'    => $model->find($id),
            'pending' => $permintaanModel->countPending(),
        ]);
    }

    public function update($id)
    {
        $model = new StokModel();
        $model->update($id, [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $this->request->getPost('kategori'),
            'stok'        => $this->request->getPost('stok'),
        ]);

        return redirect()->to('/admin/stok')->with('success', 'Data berhasil diupdate!');
    }

    public function hapus($id)
    {
        $model = new StokModel();
        $model->delete($id);
        return redirect()->to('/admin/stok')->with('success', 'Barang berhasil dihapus!');
    }
}
