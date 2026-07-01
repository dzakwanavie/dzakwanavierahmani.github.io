<?php

namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\UserModel;
use App\Models\PostModel;

/**
 * @internal
 */
final class ForumControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';
    protected $migrate = true;

    public function testStorePostController(): void
    {
        $userModel = new UserModel();

        // 1. Insert a user
        $userId = $userModel->insert([
            'username' => 'forumuser',
            'email'    => 'forumuser@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]);

        $this->assertGreaterThan(0, $userId);

        // 2. Mock session and make POST request to forum/store
        $sessionData = [
            'user_id'   => $userId,
            'username'  => 'forumuser',
            'email'     => 'forumuser@example.com',
            'logged_in' => true,
        ];

        // We simulate a request with session
        $result = $this->withSession($sessionData)
                       ->post('forum/store', [
                           'title'   => 'This is my discussion title',
                           'content' => 'This is my discussion content that has more than 10 characters.',
                       ]);

        // Assert it redirects to forum index
        $result->assertRedirectTo(site_url('forum'));

        // Assert post is in database
        $postModel = new PostModel();
        $post = $postModel->where('title', 'This is my discussion title')->first();
        $this->assertNotNull($post, 'Post was not found in database!');
    }
}
