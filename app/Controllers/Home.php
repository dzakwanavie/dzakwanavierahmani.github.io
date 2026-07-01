<?php

namespace App\Controllers;

class Home extends BaseController
{
    /**
     * Landing Page / Homepage
     */
    public function index()
    {
        // Jika user sudah login, redirect ke forum
        if (session()->has('user_id')) {
            return redirect()->to(site_url('forum'));
        }

        $data = [
            'title' => 'Mini Forum Diskusi - Tempat Berbagi dan Berdiskusi',
        ];

        return view('home/landing', $data);
    }

    /**
     * Dashboard (setelah login)
     */
    public function dashboard()
    {
        if (!session()->has('user_id')) {
            return redirect()->to(site_url('/'));
        }

        $data = [
            'title' => 'Dashboard - Forum Diskusi',
        ];

        return view('home/dashboard', $data);
    }
}
