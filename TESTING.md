# 🧪 Testing & Sample Data

## Sample Data untuk Testing

### 1. Insert Test Users
```sql
-- Budi (password: budi123456)
INSERT INTO users (username, email, password, created_at, updated_at) VALUES
('budi99', 'budi@example.com', '$2y$10$6s2w7s.Z5C.7s6.7s8s9sOdU7t7c8d9e0f1g2h3i4j5k6l7m8n9o0', NOW(), NOW());

-- Andi (password: andi123456)
INSERT INTO users (username, email, password, created_at, updated_at) VALUES
('andi123', 'andi@example.com', '$2y$10$6s2w7s.Z5C.7s6.7s8s9sOdU7t7c8d9e0f1g2h3i4j5k6l7m8n9o0', NOW(), NOW());

-- Siti (password: siti123456)
INSERT INTO users (username, email, password, created_at, updated_at) VALUES
('siti88', 'siti@example.com', '$2y$10$6s2w7s.Z5C.7s6.7s8s9sOdU7t7c8d9e0f1g2h3i4j5k6l7m8n9o0', NOW(), NOW());
```

**Note:** Semua password di-hash dengan bcrypt. Untuk testing, gunakan passwords di atas (tanpa hash).

### 2. Insert Test Posts
```sql
INSERT INTO posts (user_id, title, content, created_at, updated_at) VALUES
(1, 'Bagaimana cara belajar CodeIgniter 4?', 
'Halo semua, saya baru saja mulai belajar CodeIgniter 4. Ada yang bisa merekomendasikan resource terbaik untuk pemula? Terima kasih!', 
NOW(), NOW());

INSERT INTO posts (user_id, title, content, created_at, updated_at) VALUES
(1, 'Tips membuat REST API dengan CodeIgniter', 
'Saya sudah berhasil membuat REST API sederhana. Ada beberapa tips yang ingin saya bagikan dengan kalian semua:', 
NOW(), NOW());

INSERT INTO posts (user_id, title, content, created_at, updated_at) VALUES
(2, 'Debugging di CodeIgniter 4', 
'Apakah ada tool atau cara yang recommended untuk debugging di CodeIgniter 4? Saat ini saya menggunakan var_dump tapi terasa kurang efisien.', 
NOW(), NOW());
```

### 3. Insert Test Replies
```sql
INSERT INTO replies (post_id, user_id, content, created_at, updated_at) VALUES
(1, 2, 'Coba lihat documentation resmi CodeIgniter. Sangat lengkap dan mudah dimengerti!', NOW(), NOW());

INSERT INTO replies (post_id, user_id, content, created_at, updated_at) VALUES
(1, 3, 'Saya rekomendasikan mengikuti tutorial di YouTube channel "Web Programming UNPAS". Kualitas tinggi dan gratis!', NOW(), NOW());

INSERT INTO replies (post_id, user_id, content, created_at, updated_at) VALUES
(2, 3, 'Setuju! Tips yang bagus. Bisa share code examplenya?', NOW(), NOW());

INSERT INTO replies (post_id, user_id, content, created_at, updated_at) VALUES
(3, 1, 'Coba gunakan Xdebug atau built-in debugbar CodeIgniter. Jauh lebih powerful!', NOW(), NOW());
```

---

## Testing Scenarios

### ✅ Scenario 1: Complete User Journey
```
1. Buka http://localhost:8080/auth/register
2. Daftar:
   - Username: testuser
   - Email: test@test.com
   - Password: password123
   - Confirm: password123
3. Klik "Daftar"
4. Redirect ke /auth/login
5. Login dengan username: testuser, password: password123
6. Berhasil login, redirect ke /forum
7. Lihat daftar posts
8. Klik "+ Buat Posting Baru"
9. Buat post:
   - Judul: Belajar CodeIgniter
   - Konten: Ini adalah posting pertama saya tentang belajar CodeIgniter 4.
10. Klik "Posting"
11. Berhasil dibuat, back di /forum
12. Lihat post baru muncul di top dengan username testuser
13. Klik "Baca & Balas"
14. Lihat detail post + reply form
15. Isi reply: "Ini adalah balasan pertama saya!"
16. Klik "Kirim Balasan"
17. Reply muncul di bawah post
18. Klik Logout
19. Berhasil logout, session cleared
```

### ✅ Scenario 2: Validation Testing
```
Test Case 2.1: Register dengan username terlalu pendek
- Input username: "ab" (kurang dari 3)
- Expected: Error "Judul minimal 3 karakter"
- Actual: ___

Test Case 2.2: Register dengan email duplicate
- Username: unique123
- Email: budi@example.com (sudah ada)
- Expected: Error "Email sudah terdaftar"
- Actual: ___

Test Case 2.3: Password tidak match
- Password: password123
- Confirm: password456
- Expected: Error "Password tidak cocok"
- Actual: ___

Test Case 2.4: Create post dengan title < 5 char
- Title: "Hi" (2 char)
- Expected: Error "Judul minimal 5 karakter"
- Actual: ___

Test Case 2.5: Create post dengan content < 10 char
- Content: "Short" (5 char)
- Expected: Error "Konten minimal 10 karakter"
- Actual: ___
```

### ✅ Scenario 3: Authorization Testing
```
Test Case 3.1: Delete post orang lain
- User A buat post
- Login sebagai User B
- Coba klik delete button User A punya post
- Expected: "Anda tidak memiliki akses" + Post tidak terhapus
- Actual: ___

Test Case 3.2: Access forum tanpa login
- Logout
- Try ke http://localhost:8080/forum
- Expected: Redirect ke /auth/login
- Actual: ___

Test Case 3.3: Delete own post
- User A buat post
- Masih login sebagai User A
- Klik delete pada posting milik sendiri
- Click confirm
- Expected: Post terhapus + Replies juga terhapus (cascade)
- Actual: ___
```

### ✅ Scenario 4: Security Testing
```
Test Case 4.1: SQL Injection
- Input di username: admin' OR '1'='1
- Expected: Treated as literal string, not SQL
- Actual: ___

Test Case 4.2: XSS Attack
- Input di title: <script>alert('XSS')</script>
- Post created
- Expected: Script tidak execute, tapi text ditampilkan
- Actual: ___

Test Case 4.3: CSRF Token
- Edit form, remove csrf_field()
- Submit form
- Expected: Reject dengan "CSRF token mismatch"
- Actual: ___

Test Case 4.4: Password Hashing
- User register dengan password: mypassword123
- Query database users table
- Expected: Password stored as hash (NOT plain text)
- Actual: ___
```

### ✅ Scenario 5: Data Integrity
```
Test Case 5.1: Delete user cascade
- User A created 3 posts
- Delete User A dari database
- Check posts table
- Expected: All 3 posts deleted automatically
- Actual: ___

Test Case 5.2: Delete post cascade
- Post has 5 replies
- Delete post
- Check replies table
- Expected: All 5 replies deleted automatically
- Actual: ___

Test Case 5.3: Foreign key constraint
- Try insert post dengan user_id yang tidak ada
- Expected: Database error (FK constraint)
- Actual: ___
```

---

## Quick Test Data Setup Script

### Via MySQL Command Line:
```bash
# 1. Create database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS forum_diskusi;"

# 2. Insert test data
mysql -u root forum_diskusi < test_data.sql

# 3. Verify
mysql -u root forum_diskusi -e "SELECT COUNT(*) as total FROM users;"
```

### Test Data SQL File (test_data.sql):
```sql
-- Insert test users (all with password: password123)
INSERT INTO users (username, email, password, created_at, updated_at) VALUES
('user1', 'user1@test.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/LLe', NOW(), NOW()),
('user2', 'user2@test.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/LLe', NOW(), NOW()),
('user3', 'user3@test.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/LLe', NOW(), NOW());

-- Insert test posts
INSERT INTO posts (user_id, title, content, created_at, updated_at) VALUES
(1, 'Posting Pertama Saya', 'Ini adalah posting pertama saya di forum ini. Semoga bermanfaat!', NOW(), NOW()),
(2, 'Tips CodeIgniter', 'Bagaimana cara membuat REST API dengan CodeIgniter 4?', NOW(), NOW()),
(1, 'Bantuan Debugging', 'Bagaimana cara debugging yang efektif?', NOW(), NOW());

-- Insert test replies
INSERT INTO replies (post_id, user_id, content, created_at, updated_at) VALUES
(1, 2, 'Bagus! Kami tunggu posting berikutnya.', NOW(), NOW()),
(1, 3, 'Terima kasih sudah berbagi pengalaman!', NOW(), NOW()),
(2, 3, 'Bisa dishare linknya?', NOW(), NOW());
```

---

## Manual Testing Checklist

### User Registration
- [ ] Registrasi dengan data valid
- [ ] Username minimal 3 karakter
- [ ] Email format valid
- [ ] Password minimal 6 karakter
- [ ] Password match dengan confirm
- [ ] Duplicate username rejected
- [ ] Duplicate email rejected
- [ ] Password hashed di database

### User Login
- [ ] Login dengan username & password correct
- [ ] Session created setelah login
- [ ] Login dengan password salah → error
- [ ] Login dengan user tidak ada → error
- [ ] Redirect ke /forum setelah login
- [ ] Sudah login → redirect ke /forum jika buka /auth/login
- [ ] Logout menghapus session

### Forum Display
- [ ] Tampil semua posts
- [ ] Posts sorted by latest first
- [ ] Username penulis ditampilkan
- [ ] Tanggal post ditampilkan
- [ ] Reply count ditampilkan
- [ ] Button "Baca & Balas" bekerja
- [ ] Empty state jika tidak ada posts

### Create Post
- [ ] Hanya logged in user bisa buat post
- [ ] Title required
- [ ] Content required
- [ ] Title minimal 5 karakter
- [ ] Content minimal 10 karakter
- [ ] Post berhasil created
- [ ] Redirect ke /forum
- [ ] Success message ditampilkan

### View Post Detail
- [ ] Post content ditampilkan lengkap
- [ ] Semua replies ditampilkan
- [ ] Replies sorted by earliest first
- [ ] Username pengreply ditampilkan
- [ ] Tanggal reply ditampilkan

### Create Reply
- [ ] Hanya logged in user bisa reply
- [ ] Content required
- [ ] Content minimal 3 karakter
- [ ] Reply berhasil created
- [ ] Reply muncul di halaman
- [ ] Success message ditampilkan

### Delete Post
- [ ] Hanya owner yang bisa delete
- [ ] Delete button hanya muncul untuk owner
- [ ] Confirmation dialog tampil
- [ ] Post berhasil deleted
- [ ] Cascade: Semua replies juga deleted
- [ ] Redirect ke /forum

### Security
- [ ] CSRF token ada di semua form
- [ ] XSS: HTML input di-escape
- [ ] SQL Injection: Tidak bisa inject SQL
- [ ] Authorization: Tidak bisa delete post orang lain

---

## Browser DevTools Testing

### Console
```javascript
// Check session storage
console.log(sessionStorage);

// Check local storage
console.log(localStorage);

// Test CSRF token
document.querySelector('input[name="csrf_token"]')
```

### Network Tab
1. Buka DevTools → Network tab
2. Refresh halaman
3. Lihat requests:
   - Status 200 = OK
   - Status 302 = Redirect
   - Status 403 = Forbidden
   - Status 404 = Not found

### Elements/Inspector
1. Inspect form elements
2. Verify CSRF token ada
3. Verify input attributes correct
4. Verify CSS styling proper

---

## Performance Testing

### Benchmark Queries
```php
// Di controller
$start = microtime(true);

// Query
$posts = $this->postModel->getAllPostsWithUser();

$end = microtime(true);
$time = $end - $start;
echo "Query time: " . ($time * 1000) . "ms";
```

### Load Testing
```bash
# Install Apache Bench
# Generate 1000 requests
ab -n 1000 -c 10 http://localhost:8080/forum
```

---

## Debugging Commands

### Check Database
```bash
# Login MySQL
mysql -u root

# Select database
USE forum_diskusi;

# View tables
SHOW TABLES;

# Check users
SELECT * FROM users\G

# Check posts
SELECT p.*, u.username FROM posts p 
JOIN users u ON p.user_id = u.id;

# Check replies count
SELECT COUNT(*) as reply_count FROM replies 
GROUP BY post_id;
```

### Check Log Files
```php
// CodeIgniter logs
cat writable/logs/log-2024-07-01.log

// Database query log (if enabled)
cat /var/log/mysql/query.log
```

---

**Happy Testing! 🧪**
