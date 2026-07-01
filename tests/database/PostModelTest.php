<?php

namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\UserModel;
use App\Models\PostModel;

/**
 * @internal
 */
final class PostModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';
    protected $migrate = true;

    public function testInsertPostSuccess(): void
    {
        $userModel = new UserModel();
        $postModel = new PostModel();

        // 1. Insert a user
        $userId = $userModel->insert([
            'username' => 'testuser',
            'email'    => 'testuser@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]);

        $this->assertGreaterThan(0, $userId, 'User insert failed: ' . json_encode($userModel->errors()));

        // 2. Insert a post
        $postData = [
            'user_id' => $userId,
            'title'   => 'This is a valid title',
            'content' => 'This is a valid content that has more than ten characters.',
        ];

        $postId = $postModel->insert($postData);

        if (!$postId) {
            $errors = $postModel->errors();
            $this->fail('Post insert failed: ' . json_encode($errors));
        }

        $this->assertGreaterThan(0, $postId);
        
        $insertedPost = $postModel->find($postId);
        $this->assertNotNull($insertedPost);
        $this->assertEquals('This is a valid title', $insertedPost['title']);
    }
}
