# 📚 Implementation Guide & Best Practices

## Pengenalan

Mini Forum Diskusi ini mengimplementasikan beberapa konsep penting dalam web development modern:

---

## 🔐 1. Security Best Practices

### A. Password Hashing (PHP 5.5+)
```php
// ✅ BENAR - Di UserModel::store()
$password = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);

// ❌ SALAH
$password = md5($this->request->getPost('password')); // Deprecated & weak
```

**Kenapa bcrypt?**
- Slow hashing (resistant terhadap brute force)
- Built-in salt generation
- Future proof (PASSWORD_ARGON2 untuk PHP 7.2+)

---

### B. CSRF Protection
```php
// Di semua form di views
<?php echo csrf_field(); ?>

// CodeIgniter automatically validates
// Jika token salah, form akan ditolak
```

**Alur:**
1. Form render → Server generate token & simpan di session
2. User submit form → Token dikirim bersama data
3. Server verify → Match dengan session? Terima/tolak

---

### C. Input Validation & Sanitization
```php
// ✅ Validate input
if (!$this->validate([
    'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
    'email'    => 'required|valid_email|is_unique[users.email]',
])) {
    return redirect()->back()->with('errors', $this->validator->getErrors());
}

// ✅ Escape output (XSS Prevention)
<?php echo esc($post['title']); ?>
```

**Contoh XSS Attack:**
```php
// Jika tidak escape
Input: <script>alert('hacked')</script>
Output: Script akan execute

// Jika escape
Input: <script>alert('hacked')</script>
Output: &lt;script&gt;alert('hacked')&lt;/script&gt;
```

---

### D. Authorization Check
```php
// ✅ Hanya pemilik post yang bisa delete
if ($post['user_id'] != session()->get('user_id')) {
    return redirect()->back()->with('error', 'Anda tidak memiliki akses');
}
```

---

## 💾 2. Database Relations & Migrations

### A. Foreign Key dengan Cascade Delete
```php
// Di migration
$this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

// Artinya:
// - Jika user dihapus → semua posts user juga terhapus
// - Jika post dihapus → semua replies post juga terhapus
```

### B. Efficient Query dengan Join
```php
// ✅ GOOD - 1 query
$posts = $this->select('posts.*, users.username')
              ->join('users', 'users.id = posts.user_id')
              ->findAll();

// ❌ BAD - N+1 query problem
$posts = $this->findAll();
foreach ($posts as &$post) {
    $post['username'] = $userModel->find($post['user_id'])['username'];
    // Jika 100 posts → 101 queries!
}
```

---

## 🛡️ 3. Session Management

### A. Set Session after Login
```php
if (password_verify($password, $user['password'])) {
    session()->set([
        'user_id'   => $user['id'],
        'username'  => $user['username'],
        'email'     => $user['email'],
        'logged_in' => true,
    ]);
}
```

### B. Check Login di Controller
```php
if (!session()->has('user_id')) {
    return redirect()->to('/auth/login');
}
```

### C. Display User Info di View
```php
<?php if (session()->has('user_id')): ?>
    Selamat datang, <?php echo session()->get('username'); ?>
<?php endif; ?>
```

---

## 🏗️ 4. MVC Architecture

### A. Model (Database Logic)
```php
// UserModel.php
class UserModel extends Model {
    public function getUserByUsername($username) {
        return $this->where('username', $username)->first();
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
```

### B. Controller (Business Logic)
```php
// Auth.php
public function authenticate() {
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');
    
    $user = $this->userModel->getUserByUsername($username);
    
    if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
        session()->set(['user_id' => $user['id']]);
        return redirect()->to('/forum');
    }
}
```

### C. View (UI Logic)
```php
// auth/login.php
<form action="/auth/authenticate" method="POST">
    <?php echo csrf_field(); ?>
    <input type="text" name="username" required>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
```

---

## ✨ 5. Form Validation Rules

| Rule | Contoh | Pesan Error |
|------|--------|------------|
| `required` | `name` wajib ada | "Name wajib diisi" |
| `min_length[n]` | `password` min 6 char | "Minimal 6 karakter" |
| `max_length[n]` | `title` max 255 char | "Maksimal 255 karakter" |
| `valid_email` | `email` format valid | "Format email tidak valid" |
| `is_unique[table.field]` | `username` unik | "Username sudah terdaftar" |
| `matches[field]` | `password` = `confirm_password` | "Password tidak cocok" |
| `is_natural_no_zero` | `user_id` > 0 | "ID tidak valid" |

---

## 🎨 6. Template & View Inheritance

### A. Base Layout
```php
// layout/template.php
<!DOCTYPE html>
<html>
<head>...</head>
<body>
    <header>...</header>
    <div class="content">
        <?php echo $this->renderSection('content'); ?>
    </div>
</body>
</html>
```

### B. Child View
```php
// forum/index.php
<?php $this->extend('layout/template'); ?>

<?php $this->section('content'); ?>
    <h1>Forum Diskusi</h1>
    <!-- konten spesifik -->
<?php $this->endSection(); ?>
```

---

## 🔄 7. Request-Response Cycle

```
1. User ke /forum/create
   ↓
2. Route match → Forum::create()
   ↓
3. Controller check login
   ├─ Jika tidak login → redirect /auth/login
   └─ Jika login → return view('forum/create')
   ↓
4. View render form
   ↓
5. User submit form POST /forum/store
   ↓
6. Controller validate input
   ├─ Jika invalid → redirect back with errors
   └─ Jika valid → insert to database
   ↓
7. Redirect ke forum/index dengan success message
```

---

## 💡 8. Common Patterns

### A. Redirect with Flash Message
```php
return redirect()->to('/forum')
                ->with('success', 'Posting berhasil dibuat!');

// Di view
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?php echo session()->getFlashdata('success'); ?>
    </div>
<?php endif; ?>
```

### B. Redirect Back with Input
```php
return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());

// Di view
<input type="text" name="username" value="<?php echo old('username'); ?>">
```

### C. Conditional Access
```php
// Hanya owner yang bisa delete
if ($post['user_id'] == session()->get('user_id')) {
    // Show delete button
}

// Or check at controller
if ($post['user_id'] != session()->get('user_id')) {
    return redirect()->back()->with('error', 'Tidak punya akses');
}
```

---

## 🧪 9. Testing Checklist

### Manual Testing
- [ ] Registrasi dengan data valid
- [ ] Registrasi dengan duplicate username
- [ ] Registrasi dengan invalid email
- [ ] Login dengan password salah
- [ ] Login dengan user tidak ada
- [ ] Buat posting tanpa login (redirect ke login)
- [ ] Buat posting dengan title < 5 char (error)
- [ ] Delete posting orang lain (error)
- [ ] Balas posting dengan konten kosong (error)
- [ ] Logout dan session hilang

### Security Testing
- [ ] Coba submit form tanpa CSRF token (error)
- [ ] Coba inject HTML di title (harus escape)
- [ ] Coba inject script di konten (harus escape)
- [ ] Verify password di database di-hash (bukan plain text)

---

## 🚀 10. Deployment Checklist

```php
// 1. .env production settings
CI_ENVIRONMENT = production
database.default.password = [set secure password]

// 2. Disable debugbar
app.debugbar.enabled = false

// 3. Enable CSRF protection
security.csrf.protection = 'cookie'
security.csrf.tokenName = 'csrf_token_name'

// 4. Set secure headers
security.contentSecurityPolicy.enabled = true

// 5. Backup database
mysqldump -u root forum_diskusi > backup.sql

// 6. Set correct permissions
chmod 755 writable/
chmod 755 writable/cache/
chmod 755 writable/logs/
chmod 755 writable/uploads/
```

---

## 📖 Learning Resources

### CodeIgniter Official
- [Security](https://codeigniter.com/user_guide/concepts/security.html)
- [Sessions](https://codeigniter.com/user_guide/libraries/sessions.html)
- [Validation](https://codeigniter.com/user_guide/libraries/validation.html)
- [Database](https://codeigniter.com/user_guide/database/index.html)

### External Resources
- OWASP Top 10 Security Issues
- SQL Injection Prevention
- XSS (Cross-Site Scripting) Prevention
- CSRF (Cross-Site Request Forgery) Prevention

---

## 🎓 Exercises

### Level 1: Understand
1. Jelaskan kenapa menggunakan `password_hash()` lebih baik dari `md5()`
2. Jelaskan perbedaan `validate()` dan `esc()` function
3. Jelaskan alur login dari registrasi sampai redirect ke forum

### Level 2: Modify
1. Tambahkan field `bio` ke users table (migration + model)
2. Ubah validation rule password harus ada angka & huruf
3. Tambahkan "created X minutes ago" formatting di views

### Level 3: Create
1. Buat fitur "edit posting" (hanya owner bisa)
2. Buat fitur "like posting" dengan vote count
3. Buat halaman "user profile" dengan stats

---

**Happy Learning! 🚀**
