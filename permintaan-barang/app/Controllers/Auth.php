<?php namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            $role = session()->get('role');
            return redirect()->to($role === 'admin' ? '/admin/dashboard' : '/karyawan/dashboard');
        }
        return view('auth/login', ['title' => 'Login']);
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $model = new UserModel();
        $user  = $model->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'logged_in' => true,
                'id_user'   => $user['id'],
                'nama'      => $user['nama'],
                'username'  => $user['username'],
                'role'      => $user['role'],
            ]);

            return redirect()->to($user['role'] === 'admin' ? '/admin/dashboard' : '/karyawan/dashboard');
        }

        return redirect()->back()->with('error', 'Username atau password salah!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
