# Mini Forum Diskusi - CodeIgniter 4.7.3

## 📋 Daftar Isi
1. [Pengenalan Fitur](#pengenalan-fitur)
2. [Struktur Database](#struktur-database)
3. [Fitur Utama](#fitur-utama)
4. [Setup dan Instalasi](#setup-dan-instalasi)
5. [Penggunaan](#penggunaan)
6. [Skill yang Dipelajari](#skill-yang-dipelajari)
7. [Troubleshooting](#troubleshooting)

---

## 🎯 Pengenalan Fitur

Mini Forum Diskusi adalah aplikasi pembelajaran CodeIgniter 4 yang memungkinkan user untuk:
- Mendaftar dan login
- Membuat posting diskusi
- Memberikan balasan pada posting orang lain
- Menghapus posting milik sendiri

Aplikasi ini dirancang sebagai latihan dasar sebelum membuat fitur chat per grup yang lebih kompleks.

---

## 🗄️ Struktur Database

### Tabel `users`
```sql
- id (INT, Primary Key)
- username (VARCHAR 100, Unique)
- email (VARCHAR 150, Unique)
- password (VARCHAR 255, hashed)
- created_at (DATETIME)
- updated_at (DATETIME)
```

### Tabel `posts`
```sql
- id (INT, Primary Key)
- user_id (INT, Foreign Key → users.id)
- title (VARCHAR 255)
- content (LONGTEXT)
- created_at (DATETIME)
- updated_at (DATETIME)
```

### Tabel `replies`
```sql
- id (INT, Primary Key)
- post_id (INT, Foreign Key → posts.id)
- user_id (INT, Foreign Key → users.id)
- content (LONGTEXT)
- created_at (DATETIME)
- updated_at (DATETIME)
```

---

## ✨ Fitur Utama

### 1. Registrasi User
- Validasi input (username min 3 char, password min 6 char)
- Pengecekan username & email yang unik
- Password di-hash menggunakan bcrypt
- Form dengan CSRF protection

### 2. Login & Logout
- Session management
- Password verification
- Redirect otomatis jika sudah login
- Logout menghapus session

### 3. Membuat Posting
- Form untuk membuat posting baru
- Validasi judul (min 5 char) dan konten (min 10 char)
- Otomatis menyimpan user_id dari session
- Redirect ke forum setelah berhasil

### 4. Melihat Posting & Balasan
- Tampilkan semua posting dengan user dan tanggal
- Hitung jumlah balasan per posting
- Tampilkan balasan dalam format berurutan
- Tampilkan yang membuat balasan dan tanggalnya

### 5. Memberikan Balasan
- Form reply pada halaman detail posting
- Validasi konten balasan
- Balasan ditampilkan sesuai urutan waktu

### 6. Menghapus Posting
- Hanya pemilik posting yang bisa menghapus
- Cascade delete otomatis menghapus semua replies
- Konfirmasi sebelum delete

---

## 🚀 Setup dan Instalasi

### Prerequisites
- XAMPP/WAMP dengan PHP 7.4+ dan MySQL
- CodeIgniter 4.7.3 sudah terinstall
- Composer (untuk autoload)

### Langkah Instalasi

1. **File sudah disiapkan di:**
   ```
   app/
   ├── Controllers/
   │   ├── Auth.php
   │   └── Forum.php
   ├── Models/
   │   ├── UserModel.php
   │   ├── PostModel.php
   │   └── ReplyModel.php
   ├── Database/Migrations/
   │   ├── 2024-01-01-000001_CreateUsersTable.php
   │   ├── 2024-01-02-000002_CreatePostsTable.php
   │   └── 2024-01-03-000003_CreateRepliesTable.php
   └── Views/
       ├── layout/template.php
       ├── auth/
       │   ├── register.php
       │   └── login.php
       └── forum/
           ├── index.php
           ├── create.php
           └── show.php
   ```

2. **Konfigurasi Database (.env):**
   ```
   CI_ENVIRONMENT = development
   app.baseURL = http://localhost/tourn
   
   database.default.hostname = localhost
   database.default.database = forum_diskusi
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```

3. **Buat Database:**
   ```bash
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS forum_diskusi;"
   ```

4. **Jalankan Migrations:**
   ```bash
   php spark migrate
   ```

5. **Update Routes (app/Config/Routes.php):**
   ```php
   // Auth Routes
   $routes->get('/auth/register', 'Auth::register');
   $routes->post('/auth/store', 'Auth::store');
   $routes->get('/auth/login', 'Auth::login');
   $routes->post('/auth/authenticate', 'Auth::authenticate');
   $routes->get('/auth/logout', 'Auth::logout');
   
   // Forum Routes
   $routes->get('/forum', 'Forum::index');
   $routes->get('/forum/create', 'Forum::create');
   $routes->post('/forum/store', 'Forum::store');
   $routes->get('/forum/show/(:num)', 'Forum::show/$1');
   $routes->post('/forum/storeReply/(:num)', 'Forum::storeReply/$1');
   $routes->get('/forum/delete/(:num)', 'Forum::delete/$1');
   ```

6. **Jalankan Server:**
   ```bash
   php spark serve
   ```

7. **Akses di Browser:**
   ```
   http://localhost:8080/auth/register
   ```

---

## 👥 Penggunaan

### Alur User Baru

1. **Registrasi**
   - Klik "Daftar" atau buka `/auth/register`
   - Isi form dengan username, email, password
   - Klik "Daftar"
   - Redirect ke halaman login

2. **Login**
   - Isi username dan password
   - Klik "Login"
   - Session dimulai dan redirect ke forum

3. **Melihat Forum**
   - Lihat semua posting dari semua user
   - Lihat jumlah balasan per posting
   - Klik "Baca & Balas" untuk melihat detail

4. **Membuat Posting**
   - Klik "+ Buat Posting Baru"
   - Isi judul dan konten
   - Klik "Posting"
   - Posting muncul di forum

5. **Memberikan Balasan**
   - Buka posting
   - Scroll ke bawah
   - Isi form "Tulis Balasan"
   - Klik "Kirim Balasan"

6. **Menghapus Posting**
   - Klik tombol "Hapus" pada posting milik Anda
   - Konfirmasi
   - Semua replies otomatis terhapus

---

## 📚 Skill yang Dipelajari

### 1. Session Management
```php
// Set session
session()->set([
    'user_id'   => $user['id'],
    'username'  => $user['username'],
    'logged_in' => true,
]);

// Check session
if (session()->has('user_id')) { ... }

// Get session value
$userId = session()->get('user_id');

// Destroy session
session()->destroy();
```

### 2. Database Relations
```php
// Foreign Key
$this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

// Join query
$this->select('posts.*, users.username')
     ->join('users', 'users.id = posts.user_id')
     ->findAll();
```

### 3. Password Security
```php
// Hash password
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Verify password
password_verify($input, $hashed);
```

### 4. Form Validation
```php
$this->validate([
    'username' => 'required|min_length[3]|is_unique[users.username]',
    'email'    => 'required|valid_email',
    'password' => 'required|matches[confirm_password]',
]);
```

### 5. CSRF Protection
```php
// Di form
<?php echo csrf_field(); ?>

// Atau dalam Twig
{{ csrf_field() }}
```

### 6. Input Sanitization
```php
// Escape output
<?php echo esc($post['title']); ?>

// Atau dalam Twig
{{ post.title|esc }}

// Line break preservation
<?php echo nl2br(esc($content)); ?>
```

### 7. Model Relations
```php
// Get posts with user info
public function getAllPostsWithUser()
{
    return $this->select('posts.*, users.username')
                ->join('users', 'users.id = posts.user_id')
                ->findAll();
}

// Count related records
public function getReplyCount($postId)
{
    return $this->where('post_id', $postId)->countAllResults();
}
```

### 8. View Templating
```php
// Extend layout
<?php $this->extend('layout/template'); ?>

// Define section
<?php $this->section('content'); ?>
    <!-- konten -->
<?php $this->endSection(); ?>
```

---

## 🔧 Troubleshooting

### 1. Database Connection Error
**Error:** "Unable to connect to the specified database"

**Solusi:**
- Pastikan MySQL running
- Cek `.env` file sudah benar
- Jalankan: `php spark migrate`

### 2. Table Not Found
**Error:** "Table 'forum_diskusi.users' doesn't exist"

**Solusi:**
```bash
php spark migrate:refresh
```

### 3. CSRF Token Mismatch
**Error:** "The action you performed could not be validated"

**Solusi:**
- Pastikan form memiliki `<?php echo csrf_field(); ?>`
- Clear browser cache/cookies

### 4. Session Data Lost
**Error:** User tiba-tiba logout

**Solusi:**
- Cek `app/Config/Session.php` configuration
- Pastikan session driver benar (database atau file)

### 5. Password Hash Error
**Error:** "Call to undefined function password_hash()"

**Solusi:**
- Pastikan PHP 5.5+ terinstall
- Cek php.ini sudah enable openssl extension

### 6. Validation Error Not Showing
**Problem:** Error validation tidak tampil

**Solusi:**
```php
// Di view
<?php if (isset($validation)): ?>
    <?php echo $validation->listErrors(); ?>
<?php endif; ?>
```

---

## 📝 Contoh Use Case

### User Story 1: Registrasi Member Baru
```
1. Budi buka http://localhost/auth/register
2. Isi form: username=budi99, email=budi@example.com, password=rahasia123
3. Klik "Daftar"
4. Sistem validasi dan hash password
5. Redirect ke login
```

### User Story 2: Buat & Balas Posting
```
1. Budi login dengan akun barunya
2. Klik "+ Buat Posting Baru"
3. Buat judul: "Bagaimana cara belajar coding?"
4. Isi konten: "Saya pemula ingin belajar programming..."
5. Klik "Posting"
6. Posting muncul di forum dengan username Budi

7. Andi lihat posting Budi
8. Klik "Baca & Balas"
9. Baca konten posting Budi
10. Scroll ke bawah, isi form reply
11. Klik "Kirim Balasan"
12. Reply muncul di bawah posting
```

---

## 🎓 Homework/Challenge

### Level 1: Basic Enhancement
1. Tambahkan fitur "edit posting" (hanya pemilik)
2. Tambahkan fitur search posting
3. Tampilkan jumlah posting dan reply per user

### Level 2: Intermediate
1. Tambahkan "like/vote" untuk posting
2. Buat "user profile" halaman
3. Tambahkan pagination di forum

### Level 3: Advanced
1. Buat "kategori forum"
2. Implementasi "role" (admin/user)
3. Tambahkan "notification system"
4. Buat API untuk mobile access

---

## 📖 Reference

- [CodeIgniter 4 Documentation](https://codeigniter.com/user_guide/)
- [Session Library](https://codeigniter.com/user_guide/libraries/sessions.html)
- [Form Validation](https://codeigniter.com/user_guide/libraries/validation.html)
- [Database](https://codeigniter.com/user_guide/database/index.html)
- [Models](https://codeigniter.com/user_guide/models/model.html)

---

**Selesai! Forum Diskusi siap digunakan. Selamat belajar! 🎉**
