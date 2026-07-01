# ⚡ Quick Reference Guide

## 🔧 Common Commands

### Database
```bash
# Create database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS forum_diskusi;"

# Run migrations
php spark migrate

# Rollback migrations
php spark migrate:rollback

# Refresh (rollback & migrate)
php spark migrate:refresh

# Check migration status
php spark migrate:status

# Seed database
php spark db:seed UserSeeder
```

### Development Server
```bash
# Start server on localhost:8080
php spark serve

# Start on different port
php spark serve --port 3000

# Start on different host
php spark serve --host 192.168.1.100
```

### Code Generation
```bash
# Generate controller
php spark make:controller Forum

# Generate model
php spark make:model Post

# Generate migration
php spark make:migration CreateUsersTable

# Generate seeder
php spark make:seeder UserSeeder
```

### Cache & Clearing
```bash
# Clear all cache
php spark cache:clear

# Clear specific cache group
php spark cache:clear --group=models

# View cache info
php spark cache:info
```

---

## 📝 Code Snippets

### User Authentication

#### Registration
```php
// Controller
public function store()
{
    if (!$this->validate([
        'username' => 'required|min_length[3]|is_unique[users.username]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]|matches[confirm_password]',
    ])) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
        'username' => $this->request->getPost('username'),
        'email'    => $this->request->getPost('email'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
    ];

    if ($this->userModel->insert($data)) {
        return redirect()->to('/auth/login')->with('success', 'Registrasi berhasil!');
    }
}
```

#### Login
```php
// Controller
public function authenticate()
{
    if (!$this->validate(['username' => 'required', 'password' => 'required'])) {
        return redirect()->back()->with('errors', $this->validator->getErrors());
    }

    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');
    $user = $this->userModel->getUserByUsername($username);

    if ($user && password_verify($password, $user['password'])) {
        session()->set([
            'user_id'  => $user['id'],
            'username' => $user['username'],
            'logged_in' => true,
        ]);
        return redirect()->to('/forum');
    }

    return redirect()->back()->with('error', 'Username atau password salah.');
}
```

#### Logout
```php
// Controller
public function logout()
{
    session()->destroy();
    return redirect()->to('/auth/login')->with('success', 'Logout berhasil!');
}
```

### Forum Operations

#### Check Login
```php
// Controller
if (!session()->has('user_id')) {
    return redirect()->to('/auth/login');
}
```

#### Create Post
```php
// Controller
public function store()
{
    if (!session()->has('user_id')) {
        return redirect()->to('/auth/login');
    }

    if (!$this->validate([
        'title'   => 'required|min_length[5]|max_length[255]',
        'content' => 'required|min_length[10]',
    ])) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
        'user_id' => session()->get('user_id'),
        'title'   => $this->request->getPost('title'),
        'content' => $this->request->getPost('content'),
    ];

    if ($this->postModel->insert($data)) {
        return redirect()->to('/forum')->with('success', 'Posting berhasil dibuat!');
    }

    return redirect()->back()->withInput()->with('error', 'Gagal membuat posting.');
}
```

#### Get Posts with User Info
```php
// Model
public function getAllPostsWithUser()
{
    return $this->select('posts.*, users.username')
                ->join('users', 'users.id = posts.user_id')
                ->orderBy('posts.created_at', 'DESC')
                ->findAll();
}

// Controller
$posts = $this->postModel->getAllPostsWithUser();
```

#### Delete with Authorization
```php
// Controller
public function delete($postId)
{
    if (!session()->has('user_id')) {
        return redirect()->to('/auth/login');
    }

    $post = $this->postModel->find($postId);

    if (!$post) {
        return redirect()->to('/forum')->with('error', 'Posting tidak ditemukan.');
    }

    if ($post['user_id'] != session()->get('user_id')) {
        return redirect()->back()->with('error', 'Anda tidak punya akses.');
    }

    if ($this->postModel->delete($postId)) {
        return redirect()->to('/forum')->with('success', 'Posting berhasil dihapus!');
    }

    return redirect()->back()->with('error', 'Gagal menghapus posting.');
}
```

### Views & Forms

#### CSRF Protection
```php
<?php echo csrf_field(); ?>
```

#### Form with Error Display
```php
<form action="/auth/store" method="POST">
    <?php echo csrf_field(); ?>

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo old('username'); ?>" required>
        <?php if ($validation->hasError('username')): ?>
            <span class="error"><?php echo $validation->getError('username'); ?></span>
        <?php endif; ?>
    </div>

    <button type="submit">Submit</button>
</form>
```

#### Display Flash Messages
```php
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?php echo session()->getFlashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">
        <?php echo session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>
```

#### Display List with Owner Check
```php
<?php foreach ($posts as $post): ?>
    <div class="post">
        <h3><?php echo esc($post['title']); ?></h3>
        <p>By <strong><?php echo esc($post['username']); ?></strong></p>
        
        <?php if (session()->get('user_id') == $post['user_id']): ?>
            <a href="/forum/delete/<?php echo $post['id']; ?>" 
               onclick="return confirm('Yakin?');">
                Hapus
            </a>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
```

---

## 🔐 Security Patterns

### Password Hashing
```php
// Saat registrasi
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Saat login verify
if (password_verify($input, $hashed)) {
    // Password cocok
}
```

### Input Escaping
```php
// Display user input
<?php echo esc($userInput); ?>

// For line breaks
<?php echo nl2br(esc($content)); ?>

// For attributes
<input value="<?php echo esc($value, 'attr'); ?>">
```

### Output Safety
```php
// ✅ SAFE
<?php echo esc($post['title']); ?>
<?php echo htmlspecialchars($title); ?>

// ❌ UNSAFE
<?php echo $post['title']; ?>
<?php echo $title; ?>
```

### Query Safety
```php
// ✅ SAFE - Parameterized
$this->where('username', $username)->first();

// ❌ UNSAFE - Raw SQL
$this->db->query("SELECT * FROM users WHERE username = '$username'");
```

---

## 🗄️ Database Patterns

### Foreign Key with Cascade Delete
```php
// In migration
$this->forge->addForeignKey(
    'user_id',      // Column name
    'users',        // Foreign table
    'id',           // Foreign key column
    'CASCADE',      // On delete action
    'CASCADE'       // On update action
);
```

### Count Related Records
```php
// In ReplyModel
public function getReplyCount($postId)
{
    return $this->where('post_id', $postId)->countAllResults();
}

// Usage
$count = $this->replyModel->getReplyCount($post['id']);
```

### Join with Where Clause
```php
// Multiple conditions
$posts = $this->select('posts.*, users.username')
             ->join('users', 'users.id = posts.user_id')
             ->where('posts.user_id', $userId)
             ->where('posts.created_at >', $date)
             ->orderBy('posts.created_at', 'DESC')
             ->findAll();
```

---

## 🎯 Routing Patterns

### Basic Routes
```php
// GET request
$routes->get('/forum', 'Forum::index');

// POST request
$routes->post('/forum/store', 'Forum::store');

// With parameter
$routes->get('/forum/show/(:num)', 'Forum::show/$1');

// Multiple parameters
$routes->get('/posts/(:num)/comments/(:num)', 'Posts::comment/$1/$2');
```

### Route Groups
```php
$routes->group('api', function($routes) {
    $routes->get('posts', 'Api\Posts::index');
    $routes->post('posts', 'Api\Posts::store');
});
```

---

## 📊 Model Patterns

### Validation in Model
```php
protected $validationRules = [
    'username' => 'required|min_length[3]|is_unique[users.username]',
    'email'    => 'required|valid_email|is_unique[users.email]',
];

protected $validationMessages = [
    'username' => [
        'required'     => 'Username wajib diisi',
        'is_unique'    => 'Username sudah terdaftar',
    ],
];
```

### Timestamps Automatic
```php
protected $useTimestamps = true;
protected $createdField  = 'created_at';
protected $updatedField  = 'updated_at';

// Automatically set on insert/update
```

### Helper Methods
```php
// Get single record
$user = $this->find($id);

// Get all records
$users = $this->findAll();

// Get with where
$user = $this->where('username', 'budi')->first();

// Get count
$count = $this->countAllResults();

// Check exists
if ($this->find($id)) {
    // Exists
}
```

---

## 🧪 Testing Snippets

### Test Registration
```php
public function testRegisterSuccess()
{
    $data = [
        'username' => 'testuser123',
        'email'    => 'test@example.com',
        'password' => 'password123',
        'confirm_password' => 'password123',
    ];

    $response = $this->post('/auth/store', $data);
    
    // Should redirect to login
    $response->assertRedirect('/auth/login');
    
    // User should exist in database
    $this->seeInDatabase('users', ['username' => 'testuser123']);
}
```

### Test Login
```php
public function testLoginSuccess()
{
    $user = [
        'username' => 'testuser',
        'password' => 'password123',
    ];

    $response = $this->post('/auth/authenticate', $user);
    
    // Should redirect to forum
    $response->assertRedirect('/forum');
    
    // Session should have user_id
    $this->assertSession('user_id', $user['id']);
}
```

---

## 💡 Tips & Tricks

### Get User ID from Session
```php
$userId = session()->get('user_id');
// Or check if exists
if (session()->has('user_id')) {
    $userId = session()->get('user_id');
}
```

### Preserve Old Input
```php
// In controller, redirect back with input
return redirect()->back()->withInput();

// In view, get old input
<input value="<?php echo old('username'); ?>">
```

### Get Query Error
```php
// In model
if (!$this->insert($data)) {
    $errors = $this->errors();
    // Process errors
}
```

### Debug Query
```php
// Enable query logging
$db = \Config\Database::connect();
$db->enableQueryLog();

// Run query
$results = $this->findAll();

// Get all queries
$queries = $db->getLastQuery();
```

### Redirect with Message
```php
return redirect()->to('/forum')
                ->with('success', 'Posting berhasil dibuat!')
                ->with('info', 'Jangan lupa baca posting orang lain');
```

---

## ⚙️ Configuration

### Enable Query Logging
```php
// app/Config/Database.php
public array $default = [
    'DBDebug' => true,  // Enable debug logging
];
```

### Set Session Timeout
```php
// app/Config/Session.php
public $sessionExpiration = 7200; // 2 hours in seconds
```

### Set CSRF Settings
```php
// app/Config/Security.php
public $enableCSRFProtection = true;
public $CSRFTokenRandomize = true;
```

---

## 📚 Useful Links

- [CodeIgniter Query Builder](https://codeigniter.com/user_guide/database/query_builder.html)
- [Validation Rules](https://codeigniter.com/user_guide/libraries/validation.html#available-rules)
- [Sessions](https://codeigniter.com/user_guide/libraries/sessions.html)
- [Models](https://codeigniter.com/user_guide/models/model.html)

---

**Pro Tips:**
- Use `var_dump()` with `die()` for quick debugging
- Check browser DevTools Console for JS errors
- Use MySQL Workbench to visualize database
- Enable query logging during development
- Read error logs in `writable/logs/`

Happy coding! 🚀
