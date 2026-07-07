<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'username', 'password', 'role'];

    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}
