<?php namespace App\Models;

use CodeIgniter\Model;

class MasterAlatKantorModel extends Model
{
    protected $table         = 'master_alat_kantor';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nama_barang', 'stok', 'batas_pengajuan_ulang'];
    protected $useTimestamps = true;

    public function getAllOrderedByName()
    {
        return $this->orderBy('nama_barang', 'ASC')->findAll();
    }

    public function getByNama($nama)
    {
        return $this->where('nama_barang', $nama)->first();
    }

    // -------------------------------------------------------
    // Cek apakah karyawan boleh ajukan barang ini
    // Hitung dari saat user SUBMIT (created_at), bukan tanggal disetujui.
    // Permintaan berstatus 'Ditolak' TIDAK dihitung (user tidak menerima barang).
    // Return: ['boleh' => true] atau ['boleh' => false, 'sisa_hari' => X, 'tanggal_bisa' => '...']
    // -------------------------------------------------------
    public function cekIntervalPengajuan($barangId, $userId)
    {
        $barang = $this->find($barangId);
        if (!$barang) return ['boleh' => true];

        $batas = (int) $barang['batas_pengajuan_ulang'];
        if ($batas <= 0) return ['boleh' => true]; // tidak ada batas

        // Cari permintaan terakhir user untuk barang ini yang BUKAN ditolak
        // Batas dihitung dari saat submit (created_at)
        $db = \Config\Database::connect();
        $last = $db->table('permintaan')
            ->where('user_id', $userId)
            ->where('barang_id', $barangId)
            ->where('status !=', 'Ditolak')
            ->orderBy('created_at', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        if (!$last) return ['boleh' => true]; // belum pernah ajukan

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

    // Tambah stok + catat riwayat
    public function tambahStok($id, $jumlah, $userId, $namaUser, $keterangan = 'Penambahan stok')
    {
        $barang = $this->find($id);
        if (!$barang) return false;

        $stokSebelum = (int) $barang['stok'];
        $stokSesudah = $stokSebelum + (int) $jumlah;

        $this->update($id, ['stok' => $stokSesudah, 'updated_at' => date('Y-m-d H:i:s')]);
        $this->catatRiwayat($id, $barang['nama_barang'], 'Masuk', $jumlah, $stokSebelum, $stokSesudah, $keterangan, $userId, $namaUser);

        return $stokSesudah;
    }

    // Kurangi stok + catat riwayat
    public function kurangiStok($id, $jumlah, $userId, $namaUser, $keterangan = 'Permintaan disetujui')
    {
        $barang = $this->find($id);
        if (!$barang) return false;

        $stokSebelum = (int) $barang['stok'];
        $stokSesudah = max(0, $stokSebelum - (int) $jumlah);

        $this->update($id, ['stok' => $stokSesudah, 'updated_at' => date('Y-m-d H:i:s')]);
        $this->catatRiwayat($id, $barang['nama_barang'], 'Keluar', $jumlah, $stokSebelum, $stokSesudah, $keterangan, $userId, $namaUser);

        return $stokSesudah;
    }

    private function catatRiwayat($barangId, $namaBarang, $jenis, $jumlah, $stokSebelum, $stokSesudah, $keterangan, $userId, $namaUser)
    {
        $db = \Config\Database::connect();
        $db->table('riwayat_stok')->insert([
            'barang_id'    => $barangId,
            'nama_barang'  => $namaBarang,
            'jenis'        => $jenis,
            'jumlah'       => $jumlah,
            'stok_sebelum' => $stokSebelum,
            'stok_sesudah' => $stokSesudah,
            'keterangan'   => $keterangan,
            'user_id'      => $userId,
            'nama_user'    => $namaUser,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
    }

    public function getDashboardStats()
    {
        $all         = $this->findAll();
        $totalBarang = count($all);
        $totalStok   = array_sum(array_column($all, 'stok'));
        $hampirHabis = array_filter($all, fn($b) => $b['stok'] > 0 && $b['stok'] < 10);
        $habis       = array_filter($all, fn($b) => (int)$b['stok'] === 0);

        return [
            'total_barang' => $totalBarang,
            'total_stok'   => $totalStok,
            'hampir_habis' => count($hampirHabis),
            'habis'        => count($habis),
        ];
    }

    public function getHampirHabis($threshold = 10)
    {
        return $this->where('stok <', $threshold)->where('stok >', 0)->orderBy('stok', 'ASC')->findAll();
    }

    public function getHabis()
    {
        return $this->where('stok', 0)->orderBy('nama_barang', 'ASC')->findAll();
    }
}
