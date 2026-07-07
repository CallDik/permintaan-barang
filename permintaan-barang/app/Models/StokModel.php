<?php namespace App\Models;

use CodeIgniter\Model;

class StokModel extends Model
{
    protected $table      = 'stok_barang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_barang', 'nama_barang', 'kategori', 'stok'];

    public function generateKode()
    {
        $last = $this->orderBy('id', 'DESC')->first();
        if ($last) {
            $num = (int) substr($last['kode_barang'], 4) + 1;
        } else {
            $num = 1;
        }
        return 'BRG-' . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    public function getByNama($nama)
    {
        return $this->where('nama_barang', $nama)->first();
    }
}
