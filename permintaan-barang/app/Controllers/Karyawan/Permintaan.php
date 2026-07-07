<?php namespace App\Controllers\Karyawan;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;

class Permintaan extends BaseController
{
    public function index()
    {
        $model   = new PermintaanModel();
        $id_user = session()->get('id_user');
        $status  = $this->request->getGet('status') ?? 'semua';

        // Ambil permintaan user
        $permintaan = $model->getPermintaanByUser($id_user, $status);

        // Hitung info masa tunggu untuk setiap permintaan Alat Kantor (yang bukan ditolak)
        $db = \Config\Database::connect();
        foreach ($permintaan as &$p) {
            $p['masa_tunggu'] = null; // default

            if ($p['kategori'] === 'Alat Kantor' && !empty($p['barang_id']) && $p['status'] !== 'Ditolak') {
                $barang = $db->table('master_alat_kantor')->where('id', $p['barang_id'])->get()->getRowArray();
                $batas = (int) ($barang['batas_pengajuan_ulang'] ?? 0);
                if ($batas > 0) {
                    $tanggalAjukan = strtotime($p['created_at']);
                    $tanggalBoleh  = $tanggalAjukan + ($batas * 86400);
                    $sekarang      = time();

                    if ($sekarang < $tanggalBoleh) {
                        $sisaHari = (int) ceil(($tanggalBoleh - $sekarang) / 86400);
                        $p['masa_tunggu'] = [
                            'sisa_hari'    => $sisaHari,
                            'tanggal_bisa' => date('d/m/Y', $tanggalBoleh),
                            'batas'        => $batas,
                        ];
                    } else {
                        $p['masa_tunggu'] = ['selesai' => true, 'tanggal_bisa' => date('d/m/Y', $tanggalBoleh)];
                    }
                }
            }
        }
        unset($p);

        return view('karyawan/permintaan/index', [
            'title'      => 'Riwayat Permintaan',
            'permintaan' => $permintaan,
            'status'     => $status,
        ]);
    }

    public function tambah()
    {
        $db     = \Config\Database::connect();
        $barang = $db->table('master_alat_kantor')
                     ->orderBy('nama_barang', 'ASC')
                     ->get()->getResultArray();

        return view('karyawan/permintaan/tambah', [
            'title'         => 'Tambah Permintaan',
            'master_barang' => $barang,
        ]);
    }

    public function simpan()
    {
        $model   = new PermintaanModel();
        $db      = \Config\Database::connect();
        $id_user = session()->get('id_user');
        $kategori = $this->request->getPost('kategori');

        $rules = [
            'kategori'  => 'required',
            'jumlah'    => 'required|integer|greater_than[0]',
            'deskripsi' => 'required',
        ];

        if ($kategori === 'Alat Kantor') {
            $rules['barang_id'] = 'required|integer|greater_than[0]';
        } else {
            $rules['nama_permintaan'] = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $jumlah     = (int) $this->request->getPost('jumlah');
        $barangId   = null;
        $namaBarang = '';

        if ($kategori === 'Alat Kantor') {
            $barangId = (int) $this->request->getPost('barang_id');

            // Ambil barang langsung via DB tanpa model (hindari error kolom baru)
            $barang = $db->table('master_alat_kantor')->where('id', $barangId)->get()->getRowArray();

            if (!$barang) {
                return redirect()->back()->withInput()->with('error', 'Barang tidak ditemukan!');
            }

            // Cek interval — tangkap error jika kolom belum ada
            try {
                $cekInterval = $this->_cekInterval($db, $barangId, $id_user, $barang);
                if (!$cekInterval['boleh']) {
                    return redirect()->back()->withInput()->with('error_interval', $cekInterval);
                }
            } catch (\Exception $e) {
                // Kolom belum ada — lewati saja, izinkan pengajuan
            }

            // Cek stok
            if ($jumlah > (int) $barang['stok']) {
                return redirect()->back()->withInput()
                    ->with('error', 'Jumlah permintaan melebihi stok. Stok ' . $barang['nama_barang'] . ': ' . $barang['stok']);
            }

            $namaBarang = $barang['nama_barang'];
        } else {
            $namaBarang = $this->request->getPost('nama_permintaan');
        }

        $model->insert([
            'kode_permintaan' => $model->generateKode(),
            'user_id'         => $id_user,
            'nama_permintaan' => $namaBarang,
            'barang_id'       => $barangId,
            'kategori'        => $kategori,
            'jumlah'          => $jumlah,
            'deskripsi'       => $this->request->getPost('deskripsi'),
            'status'          => 'Pending',
        ]);

        return redirect()->to('/karyawan/permintaan')->with('success', 'Permintaan berhasil dikirim!');
    }

    // -------------------------------------------------------
    // API: Ambil stok + cek interval (AJAX) — query langsung ke DB
    // -------------------------------------------------------
    public function getStokBarang($id)
    {
        $db = \Config\Database::connect();

        // Query langsung tanpa model agar tidak tergantung allowedFields/cache
        $barang = $db->table('master_alat_kantor')->where('id', $id)->get()->getRowArray();

        if (!$barang) {
            return $this->response->setJSON(['error' => 'Barang tidak ditemukan', 'stok' => 0]);
        }

        $id_user = session()->get('id_user');

        // Default response
        $result = [
            'id'                    => (int) $barang['id'],
            'nama_barang'           => $barang['nama_barang'],
            'stok'                  => (int) $barang['stok'],
            'batas_pengajuan_ulang' => (int) ($barang['batas_pengajuan_ulang'] ?? 0),
            'boleh_ajukan'          => true,
            'sisa_hari'             => null,
            'tanggal_bisa'          => null,
        ];

        // Cek interval — bungkus try/catch agar tidak 500 jika kolom belum ada
        try {
            $cek = $this->_cekInterval($db, $id, $id_user, $barang);
            $result['boleh_ajukan'] = $cek['boleh'];
            $result['sisa_hari']    = $cek['sisa_hari']    ?? null;
            $result['tanggal_bisa'] = $cek['tanggal_bisa'] ?? null;
        } catch (\Exception $e) {
            // Lewati — izinkan pengajuan jika ada error
        }

        return $this->response->setJSON($result);
    }

    // -------------------------------------------------------
    // Helper: cek interval pengajuan per user per barang
    // Hitung dari saat user SUBMIT permintaan (created_at), bukan saat disetujui.
    // Permintaan berstatus 'Ditolak' TIDAK dihitung (user tidak menerima barang).
    // -------------------------------------------------------
    private function _cekInterval($db, $barangId, $userId, $barang)
    {
        $batas = (int) ($barang['batas_pengajuan_ulang'] ?? 0);
        if ($batas <= 0) return ['boleh' => true];

        // Cari permintaan terakhir user untuk barang ini yang BUKAN ditolak
        // Batas dihitung dari saat submit (created_at), bukan tanggal disetujui
        $last = $db->table('permintaan')
            ->where('user_id', $userId)
            ->where('barang_id', $barangId)
            ->where('status !=', 'Ditolak')
            ->orderBy('created_at', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        if (!$last) return ['boleh' => true];

        $tanggalAjukan = strtotime($last['created_at']);
        $tanggalBoleh  = $tanggalAjukan + ($batas * 86400);
        $sekarang      = time();

        if ($sekarang >= $tanggalBoleh) return ['boleh' => true];

        $sisaHari = (int) ceil(($tanggalBoleh - $sekarang) / 86400);

        return [
            'boleh'          => false,
            'sisa_hari'      => $sisaHari,
            'tanggal_bisa'   => date('d/m/Y', $tanggalBoleh),
            'nama_barang'    => $barang['nama_barang'],
            'batas'          => $batas,
            'tanggal_ajukan' => date('d/m/Y H:i', $tanggalAjukan),
            'kode_terakhir'  => $last['kode_permintaan'] ?? null,
            'status_terakhir'=> $last['status'] ?? null,
        ];
    }

    public function listBarang()
    {
        $db     = \Config\Database::connect();
        $barang = $db->table('master_alat_kantor')->orderBy('nama_barang', 'ASC')->get()->getResultArray();
        return $this->response->setJSON($barang);
    }
}
