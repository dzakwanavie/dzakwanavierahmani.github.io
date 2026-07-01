<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table            = 'posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'title', 'content'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|is_natural_no_zero',
        'title'   => 'required|min_length[5]|max_length[255]',
        'content' => 'required|min_length[10]',
    ];

    protected $validationMessages = [
        'user_id' => [
            'required'            => 'User ID wajib diisi',
            'is_natural_no_zero'  => 'User ID tidak valid',
        ],
        'title' => [
            'required'    => 'Judul wajib diisi',
            'min_length'  => 'Judul minimal 5 karakter',
            'max_length'  => 'Judul maksimal 255 karakter',
        ],
        'content' => [
            'required'    => 'Konten wajib diisi',
            'min_length'  => 'Konten minimal 10 karakter',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get all posts with user info
     */
    public function getAllPostsWithUser()
    {
        return $this->select('posts.*, users.username')
                    ->join('users', 'users.id = posts.user_id')
                    ->orderBy('posts.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get single post with user info
     */
    public function getPostWithUser($postId)
    {
        return $this->select('posts.*, users.username')
                    ->join('users', 'users.id = posts.user_id')
                    ->where('posts.id', $postId)
                    ->first();
    }

    /**
     * Get posts by user
     */
    public function getPostsByUser($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
