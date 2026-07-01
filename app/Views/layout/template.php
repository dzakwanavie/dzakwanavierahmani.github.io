<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Forum Diskusi'; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 24px;
        }

        nav a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        nav a:hover {
            opacity: 0.8;
        }

        .content {
            padding: 30px;
            min-height: 400px;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: 500;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .errors {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .errors ul {
            list-style: none;
            padding-left: 0;
        }

        .errors li {
            margin-bottom: 5px;
        }

        form {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 150px;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.5);
        }

        button, .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        button:hover, .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }

        .post-item {
            border: 1px solid #e0e0e0;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            background: white;
            transition: box-shadow 0.3s;
        }

        .post-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }

        .post-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .post-meta {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
        }

        .post-meta strong {
            color: #667eea;
        }

        .post-content {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .post-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid #f0f0f0;
        }

        .reply-count {
            color: #667eea;
            font-weight: 500;
        }

        .post-actions {
            display: flex;
            gap: 10px;
        }

        .reply-item {
            background: #f9f9f9;
            padding: 15px;
            margin: 10px 0 10px 30px;
            border-left: 3px solid #667eea;
            border-radius: 3px;
        }

        .reply-meta {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
        }

        .reply-content {
            color: #555;
            line-height: 1.6;
        }

        .auth-form {
            max-width: 400px;
            margin: 50px auto;
        }

        .auth-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .auth-link {
            text-align: center;
            margin-top: 20px;
            color: #999;
        }

        .auth-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #999;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            color: #666;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        footer {
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            color: #999;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>💬 Forum Diskusi</h1>
            <nav>
                <?php if (session()->has('user_id')): ?>
                    <span><?php echo session()->get('username'); ?></span>
                    <a href="<?= site_url('forum') ?>">Forum</a>
                    <a href="<?= site_url('forum/create') ?>">+ Buat Posting</a>
                    <a href="<?= site_url('auth/logout') ?>">Logout</a>
                <?php else: ?>
                    <a href="<?= site_url('auth/login') ?>">Login</a>
                    <a href="<?= site_url('auth/register') ?>">Daftar</a>
                <?php endif; ?>
            </nav>
        </header>

        <div class="content">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert success">
                    ✓ <?php echo session()->getFlashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert error">
                    ✗ <?php echo session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('info')): ?>
                <div class="alert info">
                    ℹ <?php echo session()->getFlashdata('info'); ?>
                </div>
            <?php endif; ?>

            <?php if ($errors = session()->getFlashdata('errors')): ?>
                <div class="errors">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php echo $this->renderSection('content'); ?>
        </div>

        <footer>
            <p>&copy; 2024 Mini Forum Diskusi | Dibangun dengan CodeIgniter 4.7.3</p>
        </footer>
    </div>
</body>
</html>
