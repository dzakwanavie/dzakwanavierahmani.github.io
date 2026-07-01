# 🚀 Quick Start Guide - Mini Forum Diskusi

## ⚡ Quick Setup (5 menit)

### 1. Database sudah dibuat ✓
```
Database: forum_diskusi
Tables: users, posts, replies
```

### 2. Jalankan aplikasi
```bash
cd c:\xamppp\htdocs\tourn
php spark serve
```

### 3. Buka browser
```
http://localhost:8080/auth/register
```

---

## 📌 Routes Utama

| URL | Fungsi |
|-----|--------|
| `/auth/register` | Form registrasi |
| `/auth/login` | Form login |
| `/auth/logout` | Logout |
| `/forum` | Lihat semua posting |
| `/forum/create` | Buat posting baru |
| `/forum/show/{id}` | Lihat detail posting & reply |

---

## 📁 File Structure

```
app/
├── Controllers/
│   ├── Auth.php              (register, login, logout)
│   └── Forum.php             (posting, reply, delete)
├── Models/
│   ├── UserModel.php         (user operations)
│   ├── PostModel.php         (post operations)
│   └── ReplyModel.php        (reply operations)
├── Database/Migrations/
│   ├── 2024-01-01-000001_CreateUsersTable.php
│   ├── 2024-01-02-000002_CreatePostsTable.php
│   └── 2024-01-03-000003_CreateRepliesTable.php
└── Views/
    ├── layout/template.php   (main template)
    ├── auth/
    │   ├── register.php
    │   └── login.php
    └── forum/
        ├── index.php         (list semua posts)
        ├── create.php        (form create post)
        └── show.php          (detail post + replies)
```

---

## 🎯 Test Scenario

### Scenario 1: Registrasi & Login
1. Buka http://localhost:8080/auth/register
2. Isi form:
   - Username: `testuser`
   - Email: `test@example.com`
   - Password: `password123`
3. Klik "Daftar"
4. Login dengan username & password

### Scenario 2: Buat Posting
1. Dari forum homepage, klik "+ Buat Posting Baru"
2. Isi form:
   - Judul: `Belajar CodeIgniter 4`
   - Konten: `Saya sedang belajar membuat aplikasi forum dengan CodeIgniter...`
3. Klik "Posting"

### Scenario 3: Beri Balasan
1. Buka posting yang baru dibuat
2. Scroll ke bawah
3. Isi form "Tulis Balasan"
4. Klik "Kirim Balasan"

---

## 🔑 Key Features Implemented

✅ User Registration dengan validation
✅ Login/Logout dengan Session
✅ Password hashing (bcrypt)
✅ CSRF Protection di semua form
✅ Relasi database (user → posts → replies)
✅ Input sanitization (XSS prevention)
✅ Ownership check (hanya bisa delete posting sendiri)
✅ Responsive UI dengan modern design

---

## 🎓 Skills Practiced

| Skill | Contoh Kode |
|-------|------------|
| **Session** | `session()->set()`, `session()->get()` |
| **Database Relations** | `JOIN`, Foreign Key, Cascade Delete |
| **Validation** | `is_unique`, `min_length`, `matches` |
| **Security** | `password_hash()`, CSRF token, input escape |
| **OOP** | Models, Controllers, inheritance |
| **Views** | Template extend, sections, loops |

---

## ⚠️ Penting untuk Diingat

1. **Session hanya hidup di browser yang sama**
   - Buka tab private untuk test multiple users
   
2. **Database akan reset jika:**
   ```bash
   php spark migrate:refresh
   ```
   
3. **Untuk development:**
   ```bash
   # Update code saat live
   php spark serve --host localhost --port 8080
   ```

4. **Menghapus posting juga menghapus replies**
   - Karena setup CASCADE DELETE di database

---

## 💡 Next Steps

Setelah comfortable dengan forum ini, Anda bisa:

1. **Tambah fitur Edit Posting**
   - Add `edit()` method di Forum controller
   - Create edit view

2. **Tambah fitur Like/Vote**
   - Add `likes` column ke posts table
   - Create `likes` table (post_id, user_id, user_vote)

3. **User Profile Page**
   - Show user's posts
   - Show user's replies
   - Count statistics

4. **Forum Categories**
   - Add `categories` table
   - Group posts by category
   - Filter by category

5. **Admin Panel**
   - Admin bisa delete semua posts
   - Admin bisa moderate content
   - View statistics

---

## 🐛 Jika Ada Error

### Error: "Class Auth not found"
```bash
php spark migrate:refresh
php spark serve
```

### Error: "Database not found"
```bash
mysql -u root -e "CREATE DATABASE forum_diskusi;"
php spark migrate
```

### Error: Views tidak tampil
- Pastikan folder `app/Views/` sudah ada
- Cek nama file view sesuai dengan `view()` di controller

---

## 📞 File Penting untuk Dimodifikasi

1. **Tambah field ke users table:**
   - Buat migration baru
   - Update UserModel validation

2. **Tambah fitur baru:**
   - Buat method baru di controller
   - Tambah route di `app/Config/Routes.php`
   - Buat view baru

3. **Ubah styling:**
   - Edit CSS di `app/Views/layout/template.php`
   - Atau create file CSS terpisah

---

**Enjoy coding! 🎉**

Untuk detail lengkap, lihat `FORUM_SETUP.md`
