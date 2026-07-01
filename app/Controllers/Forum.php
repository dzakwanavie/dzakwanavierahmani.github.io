<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\ReplyModel;

class Forum extends BaseController
{
    protected $postModel;
    protected $replyModel;

    public function __construct()
    {
        $this->postModel  = new PostModel();
        $this->replyModel = new ReplyModel();
    }

    /**
     * Check if user is logged in
     */
    private function checkLogin()
    {
        if (!session()->has('user_id')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silakan login terlebih dahulu.');
        }
    }

    /**
     * Show all posts (forum homepage)
     */
    public function index()
    {
        // Cek apakah user login
        if (!session()->has('user_id')) {
            return redirect()->to(site_url('auth/login'));
        }

        $posts = $this->postModel->getAllPostsWithUser();
        
        // Tambahkan reply count untuk setiap post
        foreach ($posts as &$post) {
            $post['reply_count'] = $this->replyModel->getReplyCount($post['id']);
        }

        $data = [
            'posts' => $posts,
        ];

        return view('forum/index', $data);
    }

    /**
     * Show form to create new post
     */
    public function create()
    {
        // Cek login
        if (!session()->has('user_id')) {
            return redirect()->to(site_url('auth/login'));
        }

        return view('forum/create', [
            'validation' => \Config\Services::validation(),
        ]);
    }

    /**
     * Store new post
     */
    public function store()
    {
        // Cek login
        if (!session()->has('user_id')) {
            return redirect()->to(site_url('auth/login'));
        }

        // Validasi
        if (!$this->validate([
            'title'   => 'required|min_length[5]|max_length[255]',
            'content' => 'required|min_length[10]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Sanitasi input
        $data = [
            'user_id' => session()->get('user_id'),
            'title'   => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
        ];

        if ($this->postModel->insert($data)) {
            return redirect()->to(site_url('forum'))->with('success', 'Posting berhasil dibuat!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat posting.');
        }
    }

    /**
     * Show single post with replies
     */
    public function show($postId)
    {
        // Cek login
        if (!session()->has('user_id')) {
            return redirect()->to(site_url('auth/login'));
        }

        $post = $this->postModel->getPostWithUser($postId);

        if (!$post) {
            return redirect()->to(site_url('forum'))->with('error', 'Posting tidak ditemukan.');
        }

        $replies = $this->replyModel->getRepliesByPost($postId);

        $data = [
            'post'       => $post,
            'replies'    => $replies,
            'validation' => \Config\Services::validation(),
        ];

        return view('forum/show', $data);
    }

    /**
     * Store reply to a post
     */
    public function storeReply($postId)
    {
        // Cek login
        if (!session()->has('user_id')) {
            return redirect()->to(site_url('auth/login'));
        }

        // Cek post exist
        $post = $this->postModel->find($postId);
        if (!$post) {
            return redirect()->to(site_url('forum'))->with('error', 'Posting tidak ditemukan.');
        }

        // Validasi
        if (!$this->validate([
            'content' => 'required|min_length[3]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan reply
        $data = [
            'post_id' => $postId,
            'user_id' => session()->get('user_id'),
            'content' => $this->request->getPost('content'),
        ];

        if ($this->replyModel->insert($data)) {
            return redirect()->to(site_url('forum/show/' . $postId))->with('success', 'Balasan berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan balasan.');
        }
    }

    /**
     * Delete post (only owner or admin)
     */
    public function delete($postId)
    {
        // Cek login
        if (!session()->has('user_id')) {
            return redirect()->to(site_url('auth/login'));
        }

        $post = $this->postModel->find($postId);

        if (!$post) {
            return redirect()->to(site_url('forum'))->with('error', 'Posting tidak ditemukan.');
        }

        // Cek apakah user adalah pemilik post
        if ($post['user_id'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'Anda hanya bisa menghapus posting milik Anda sendiri.');
        }

        if ($this->postModel->delete($postId)) {
            return redirect()->to(site_url('forum'))->with('success', 'Posting berhasil dihapus!');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus posting.');
        }
    }
}
