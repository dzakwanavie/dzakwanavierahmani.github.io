# 📊 Application Flow & Architecture

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Mini Forum Diskusi                        │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                  User Browser                        │   │
│  └──────────┬───────────────────────────────────────────┘   │
│             │                                                 │
│             ▼                                                 │
│  ┌──────────────────────────────────────────────────────┐   │
│  │            HTTP Request / Response                   │   │
│  │                     (Routes)                         │   │
│  └──────────┬───────────────────────────────────────────┘   │
│             │                                                 │
│  ┌──────────┴───────────────────────────────────────────┐   │
│  │              Controllers Layer                       │   │
│  ├───────────────┬──────────────────┬──────────────────┤   │
│  │   Auth        │     Forum        │  (Future: Chat)  │   │
│  │ - register    │  - index         │                  │   │
│  │ - login       │  - create        │                  │   │
│  │ - logout      │  - store         │                  │   │
│  │               │  - show          │                  │   │
│  │               │  - storeReply    │                  │   │
│  │               │  - delete        │                  │   │
│  └──────────┬────────┬─────────────┬────────────────────┘   │
│             │        │             │                         │
│  ┌──────────┴────────┴─────────────┴────────────────────┐   │
│  │              Models Layer                           │   │
│  ├──────────────────┬──────────────────┬──────────────┤   │
│  │   UserModel      │   PostModel      │  ReplyModel  │   │
│  │ - getUserBy...   │ - getAllPosts... │ - getReplies │   │
│  │ - verifyPass...  │ - getPostWith... │ - getReply.. │   │
│  │                  │ - getPostsByUser │              │   │
│  └──────────┬────────┬──────────────────┬──────────────┘   │
│             │        │                  │                    │
│  ┌──────────┴────────┴──────────────────┴────────────────┐  │
│  │              Database Layer                          │  │
│  ├──────────────────┬──────────────────┬──────────────┤  │
│  │     users        │      posts       │   replies    │  │
│  │  - id (PK)       │  - id (PK)       │ - id (PK)    │  │
│  │  - username      │  - user_id (FK)  │ - post_id(FK)│  │
│  │  - email         │  - title         │ - user_id(FK)│  │
│  │  - password      │  - content       │ - content    │  │
│  │  - timestamps    │  - timestamps    │ - timestamps │  │
│  └──────────────────┴──────────────────┴──────────────┘  │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

---

## Data Flow Diagram

### 1. Registration Flow
```
┌─────────────────┐
│  /auth/register │
│  (GET Request)  │
└────────┬────────┘
         │
         ▼
    ┌─────────────────────────────┐
    │  Show Registration Form     │
    │  (auth/register.php view)   │
    └──────────┬──────────────────┘
               │
               │ User Fill Form
               │ & Submit (POST)
               │
         ┌─────┴──────────────────┐
         │ /auth/store (POST)     │
         │ Auth::store()          │
         └────────┬───────────────┘
                  │
         ┌────────┴─────────────────┐
         │ Validate Input           │
         │ - Required fields?       │
         │ - Min length?            │
         │ - Unique username/email? │
         └────────┬────────┬────────┘
                  │        │
         Fail─────┤        └─────Pass
         │        │              │
         │        ▼              ▼
         │    Redirect     Hash Password
         │    Back with        │
         │    Errors           ▼
         │        │        Save to DB
         │        │            │
         │        │            ▼
         │        │        Redirect to
         │        │        /auth/login
         │        │        with Success
         │        │        Message
         │        │            │
         └────────┴────────────┘
                  │
                  ▼
         User sees feedback
```

### 2. Login & Session Flow
```
┌──────────────────────┐
│  /auth/login (GET)   │
│  Show Login Form     │
└──────────┬───────────┘
           │
           │ User enters
           │ username & password
           │
     ┌─────┴────────────────────┐
     │ /auth/authenticate (POST)│
     │ Auth::authenticate()     │
     └────────┬─────────────────┘
              │
       ┌──────┴──────────────────────┐
       │  Query User by Username     │
       │  from Database              │
       └────────┬────────────────┬───┘
                │                │
         Not Found────┐      Found
                │     │           │
                ▼     ▼           ▼
             Error  Verify
             Message Password
                │           │
                │     ┌──────┴──────────┐
                │     │ Match?          │
                │     ├──────┬──────────┤
                │   Fail     Pass
                │   │        │
                │   │        ▼
                │   │    Set Session:
                │   │    - user_id
                │   │    - username
                │   │    - email
                │   │    - logged_in
                │   │        │
                │   │        ▼
                ▼   ▼   Redirect to
             Redirect   /forum
             Back with  with Success
             Error      Message
```

### 3. Forum Posting Flow
```
┌────────────────────────┐
│  /forum (GET)          │
│  Forum::index()        │
└────────┬───────────────┘
         │
    Check login
         │
    ┌────┴──────────────────┐
    │ Not Logged In?        │
    ├────┬─────────────────┤
   Yes   No
    │    │
    │    ▼
    │  Query all posts
    │  with user info
    │  (join users table)
    │    │
    │    ▼
    │  Count replies
    │  per post
    │    │
    │    ▼
    │  Render forum/index.php
    │  with posts array
    │    │
    └────┴──────┬──────────────────┐
                │                  │
         Redirect to         Display
         /auth/login         Forum List
```

### 4. Create & Reply Flow
```
Create Post:
┌────────────────────────────────────────────────────┐
│ /forum/create → Show Form                         │
│ User submit → /forum/store (POST)                 │
│ Validate & Hash → Insert to DB                    │
│ Redirect to /forum with success message           │
└────────────────────────────────────────────────────┘

Reply to Post:
┌────────────────────────────────────────────────────┐
│ /forum/show/{id} → Display Post + Reply Form      │
│ User submit → /forum/storeReply/{id} (POST)       │
│ Validate → Insert to replies table                │
│ Redirect to /forum/show/{id} with success         │
└────────────────────────────────────────────────────┘
```

---

## Session Management

```
┌─────────────────────────────────────────────────────────┐
│              Session Storage (PHP)                      │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  Session Created After Login:                         │
│  ┌─────────────────────────────────────────┐           │
│  │ $_SESSION = [                           │           │
│  │   'user_id' => 1,                       │           │
│  │   'username' => 'budi99',               │           │
│  │   'email' => 'budi@example.com',        │           │
│  │   'logged_in' => true,                  │           │
│  │   '__ci_last_regenerate' => timestamp  │           │
│  │ ]                                       │           │
│  └─────────────────────────────────────────┘           │
│                                                         │
│  Usage:                                                │
│  - Check: if (session()->has('user_id'))              │
│  - Get:   $userId = session()->get('user_id')         │
│  - Set:   session()->set(['user_id' => 1])            │
│  - Clear: session()->destroy()                         │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

---

## Database Relations

```
┌──────────────────────────────────────────────────────┐
│                 DATABASE SCHEMA                      │
├──────────────────────────────────────────────────────┤
│                                                      │
│  ┌─────────────┐        ┌──────────────┐            │
│  │   USERS     │◄──────►│    POSTS     │            │
│  ├─────────────┤    1:M ├──────────────┤            │
│  │ id (PK)     │        │ id (PK)      │            │
│  │ username    │        │ user_id (FK) │            │
│  │ email       │        │ title        │            │
│  │ password    │        │ content      │            │
│  │ created_at  │        │ created_at   │            │
│  │ updated_at  │        │ updated_at   │            │
│  └─────────────┘        └──┬───────────┘            │
│         ▲                   │                        │
│         │            1:M    │                        │
│         │                   ▼                        │
│         │          ┌──────────────────┐              │
│         └──────────│    REPLIES       │              │
│                    ├──────────────────┤              │
│                    │ id (PK)          │              │
│                    │ post_id (FK)     │              │
│                    │ user_id (FK)─────┘              │
│                    │ content                         │
│                    │ created_at                      │
│                    │ updated_at                      │
│                    └──────────────────┘              │
│                                                      │
│  Relations:                                          │
│  - 1 User dapat memiliki banyak Posts (1:M)         │
│  - 1 Post dapat memiliki banyak Replies (1:M)       │
│  - 1 User dapat membuat banyak Replies (1:M)        │
│                                                      │
│  Cascade Delete:                                     │
│  - Delete User → Delete semua Posts & Replies user  │
│  - Delete Post → Delete semua Replies post          │
│                                                      │
└──────────────────────────────────────────────────────┘
```

---

## Validation Flow

```
┌──────────────────────────────────────────────┐
│         Form Input Validation                │
├──────────────────────────────────────────────┤
│                                              │
│  User Submit Form                           │
│         │                                   │
│         ▼                                   │
│  CodeIgniter Validator runs                │
│         │                                   │
│  ┌──────┴────────────────────────────┐     │
│  │  Check each validation rule:     │     │
│  │  - required?                     │     │
│  │  - correct format?               │     │
│  │  - length constraints?           │     │
│  │  - database unique?              │     │
│  │  - field matches?                │     │
│  └──────┬─────────────┬──────────────┘     │
│         │             │                    │
│    All Pass      Some Fail                 │
│         │             │                    │
│         ▼             ▼                    │
│     Process       Collect Errors          │
│     Request       & Redirect Back          │
│         │             │                    │
│         │             ▼                    │
│         │      Show form dengan            │
│         │      - Error messages            │
│         │      - Old input values          │
│         │      (via old() helper)          │
│         │                                  │
│         ▼                                  │
│    Save to DB                              │
│    Redirect with                           │
│    Success Message                         │
│                                            │
└──────────────────────────────────────────────┘
```

---

## Security Flow

```
┌────────────────────────────────────────────────────┐
│          SECURITY MEASURES                         │
├────────────────────────────────────────────────────┤
│                                                    │
│  1. CSRF Prevention:                              │
│     User Submit → CSRF Token sent                │
│     Server → Verify token dari session           │
│     Token match? → Process | Not match? → Reject │
│                                                    │
│  2. SQL Injection Prevention:                     │
│     Raw Input → Parameterized Query              │
│     $this->where('username', $username)          │
│     (NOT: "WHERE username = '$username'")        │
│                                                    │
│  3. XSS Prevention:                               │
│     Database → Store raw data                    │
│     Display → Use esc() function                 │
│     <?php echo esc($post['title']); ?>           │
│     Result: HTML chars become &lt;&gt; etc       │
│                                                    │
│  4. Password Security:                            │
│     User input → password_hash(bcrypt)           │
│     Store hashed → Not reversible                │
│     Login verify → password_verify()             │
│                                                    │
│  5. Authorization:                                │
│     User A delete post? → Check user_id          │
│     $post['user_id'] == session()->get('user_id')│
│     Match? → Allow | Not? → Deny                 │
│                                                    │
└────────────────────────────────────────────────────┘
```

---

## User Journey Map

```
┌─────────────────────────────────────────────────────────┐
│             TYPICAL USER JOURNEY                        │
├─────────────────────────────────────────────────────────┤
│                                                         │
│ Day 1 - New User                                       │
│ ─────────────────                                      │
│ 1. Visit /auth/register                               │
│ 2. Fill registration form                             │
│ 3. Validate & create account                          │
│ 4. Redirect to /auth/login                            │
│ 5. Fill login form                                    │
│ 6. Session created → Redirect to /forum               │
│                                                         │
│ Day 2 - Active User                                   │
│ ─────────────────                                      │
│ 7. Login lagi                                         │
│ 8. Browse all posts di /forum                         │
│ 9. Click "Baca & Balas" → /forum/show/{id}           │
│ 10. Read post dan comments                            │
│ 11. Write & submit reply                              │
│ 12. See reply published                               │
│ 13. Click "+ Buat Posting Baru"                       │
│ 14. Create new post                                   │
│ 15. See post di forum homepage                        │
│                                                         │
│ Day 3 - Manage Content                                │
│ ─────────────────────                                 │
│ 16. Find own post                                     │
│ 17. See "Hapus" button                                │
│ 18. Click delete & confirm                            │
│ 19. Post & replies terhapus                           │
│ 20. Click Logout                                      │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

---

## Page Navigation Map

```
                    ┌────────────────────┐
                    │ /auth/register     │
                    │ (Registration)     │
                    └─────────┬──────────┘
                              │
                              ▼
                    ┌────────────────────┐
                    │ /auth/login        │
                    │ (Login Form)       │
                    └─────────┬──────────┘
                              │
                              ▼
                    ┌────────────────────────┐
                    │ /forum                 │
                    │ (Forum Homepage)       │
                    └──────┬────────┬───────┘
                           │        │
                ┌──────────┘        └──────────────┐
                │                                   │
                ▼                                   ▼
        ┌──────────────────┐         ┌──────────────────┐
        │ /forum/show/{id} │         │ /forum/create    │
        │ (Detail Post)    │         │ (Create Form)    │
        │ + Reply Form     │         └────────┬─────────┘
        └───┬────┬────────┘                   │
            │    │                           ▼
            │    │          ┌─────────────────────────┐
            │    │          │ /forum/store (POST)    │
            │    │          │ (Save new post)        │
            │    │          └──────────┬──────────────┘
            │    │                     │
            │    └─────────────────────┘
            │
            ▼
    ┌───────────────────────────────┐
    │ /forum/storeReply/{id} (POST) │
    │ (Save reply)                  │
    └────────────┬──────────────────┘
                 │
                 ▼
    ┌────────────────────────┐
    │ Back to Forum Homepage │
    │ or Detail Post         │
    └────────────┬───────────┘
                 │
                 ▼
    ┌────────────────────────┐
    │ /auth/logout           │
    │ (Logout)               │
    └────────────┬───────────┘
                 │
                 ▼
    ┌────────────────────────┐
    │ Redirect to /auth/login│
    │ Session Destroyed      │
    └────────────────────────┘
```

---

**Diagram lengkap menunjukkan alur aplikasi dari sisi user, database, dan security.**
