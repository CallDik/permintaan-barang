<?php namespace App\Models;

use CodeIgniter\Model;

class PermintaanModel extends Model
{
    protected $table      = 'permintaan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode_permintaan', 'user_id', 'nama_permintaan', 'barang_id',
        'kategori', 'jumlah', 'deskripsi', 'status',
        'keterangan_admin', 'stok_sudah_dikurangi', 'tanggal_disetujui'
    ];

    public function getPermintaanWithUser($status = null)
    {
        $builder = $this->db->table('permintaan p')
            ->select('p.*, u.nama as nama_karyawan')
            ->join('users u', 'u.id = p.user_id');

        if ($status && $status !== 'semua') {
            $builder->where('p.status', $status);
        }

        return $builder->orderBy('p.created_at', 'DESC')->get()->getResultArray();
    }

    public function getPermintaanByUser($user_id, $status = null)
    {
        $builder = $this->where('user_id', $user_id);
        if ($status && $status !== 'semua') {
            $builder->where('status', $status);
        }
        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    public function getDetail($id)
    {
        return $this->db->table('permintaan p')
            ->select('p.*, u.nama as nama_karyawan')
            ->join('users u', 'u.id = p.user_id')
            ->where('p.id', $id)
            ->get()->getRowArray();
    }

    public function generateKode()
    {
        $tahun = date('Y');
        $last  = $this->db->table('permintaan')
            ->like('kode_permintaan', 'PRM-' . $tahun, 'after')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        if ($last) {
            $num = (int) substr($last['kode_permintaan'], -4) + 1;
        } else {
            $num = 1;
        }

        return 'PRM-' . $tahun . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    public function countPending()
    {
        return $this->where('status', 'Pending')->countAllResults();
    }
}
