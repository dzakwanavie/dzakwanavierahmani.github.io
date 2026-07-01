<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Show registration form
     */
    public function register()
    {
        // Jika sudah login, arahkan ke forum
        if (session()->has('user_id')) {
            return redirect()->to(site_url('forum'));
        }

        return view('auth/register');
    }

    /**
     * Handle registration
     */
    public function store()
    {
        // Validasi CSRF
        if (!$this->validate([
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to(site_url('auth/login'))->with('success', 'Registrasi berhasil! Silakan login.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat registrasi.');
        }
    }

    /**
     * Show login form
     */
    public function login()
    {
        // Jika sudah login, arahkan ke forum
        if (session()->has('user_id')) {
            return redirect()->to(site_url('forum'));
        }

        return view('auth/login');
    }

    /**
     * Handle login
     */
    public function authenticate()
    {
        // Validasi input
        if (!$this->validate([
            'username' => 'required',
            'password' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // Set session
            session()->set([
                'user_id'   => $user['id'],
                'username'  => $user['username'],
                'email'     => $user['email'],
                'logged_in' => true,
            ]);

            return redirect()->to(site_url('forum'))->with('success', 'Login berhasil!');
        } else {
            return redirect()->back()->with('error', 'Username atau password salah.');
        }
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('auth/login'))->with('success', 'Logout berhasil!');
    }
}
