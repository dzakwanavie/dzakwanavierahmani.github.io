<?php

namespace App\Models;

use CodeIgniter\Model;

class ReplyModel extends Model
{
    protected $table            = 'replies';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['post_id', 'user_id', 'content'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'post_id' => 'required|is_natural_no_zero',
        'user_id' => 'required|is_natural_no_zero',
        'content' => 'required|min_length[3]',
    ];

    protected $validationMessages = [
        'post_id' => [
            'required'           => 'Post ID wajib diisi',
            'is_natural_no_zero' => 'Post ID tidak valid',
        ],
        'user_id' => [
            'required'           => 'User ID wajib diisi',
            'is_natural_no_zero' => 'User ID tidak valid',
        ],
        'content' => [
            'required'    => 'Balasan wajib diisi',
            'min_length'  => 'Balasan minimal 3 karakter',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get all replies for a post with user info
     */
    public function getRepliesByPost($postId)
    {
        return $this->select('replies.*, users.username')
                    ->join('users', 'users.id = replies.user_id')
                    ->where('replies.post_id', $postId)
                    ->orderBy('replies.created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get replies count for a post
     */
    public function getReplyCount($postId)
    {
        return $this->where('post_id', $postId)->countAllResults();
    }
}
