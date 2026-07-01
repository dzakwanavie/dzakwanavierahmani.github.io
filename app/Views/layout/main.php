<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Mini Forum Diskusi'; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #48bb78;
            --error: #f56565;
            --warning: #ed8936;
            --light: #f7fafc;
            --dark: #2d3748;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: var(--dark);
            line-height: 1.6;
        }

        a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: var(--secondary);
        }

        /* Navigation */
        nav {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        nav .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .logo {
            font-size: 24px;
            font-weight: bold;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        nav .links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        nav .links a, nav .links button {
            color: var(--dark);
            font-weight: 500;
            transition: color 0.3s;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 14px;
        }

        nav .links a:hover {
            color: var(--primary);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: var(--primary);
            color: white;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
        }

        .btn-danger {
            background: var(--error);
            color: white;
        }

        .btn-danger:hover {
            box-shadow: 0 5px 20px rgba(245, 101, 101, 0.4);
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 100px 20px;
            text-align: center;
            min-height: calc(100vh - 60px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.95;
            max-width: 600px;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .hero .btn {
            padding: 12px 30px;
            font-size: 16px;
        }

        .btn-light {
            background: white;
            color: var(--primary);
        }

        .btn-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Features Section */
        .features {
            padding: 80px 20px;
            background: white;
        }

        .features h2 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 50px;
            color: var(--dark);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: var(--light);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .feature-card h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .feature-card p {
            color: #718096;
            line-height: 1.8;
        }

        /* Stats Section */
        .stats {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 80px 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            text-align: center;
        }

        .stat {
            padding: 20px;
        }

        .stat-number {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 16px;
            opacity: 0.95;
        }

        /* CTA Section */
        .cta {
            background: white;
            padding: 80px 20px;
            text-align: center;
        }

        .cta h2 {
            font-size: 36px;
            margin-bottom: 20px;
            color: var(--dark);
        }

        .cta p {
            font-size: 18px;
            color: #718096;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border: 1px solid #fc8787;
        }

        .alert-info {
            background: #bee3f8;
            color: #2c5282;
            border: 1px solid #90cdf4;
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        footer p {
            margin-bottom: 10px;
        }

        /* Content Section */
        .content {
            padding: 40px 20px;
            min-height: calc(100vh - 60px);
        }

        .content h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: var(--dark);
        }

        /* Forum Stats */
        .forum-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid var(--primary);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-box-value {
            font-size: 28px;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-box-label {
            color: #718096;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 32px;
            }

            .hero p {
                font-size: 16px;
            }

            nav .links {
                gap: 15px;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .hero .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="container">
            <div class="logo">💬 Forum Diskusi</div>
            <div class="links">
                <?php if (session()->has('user_id')): ?>
                    <span><?php echo session()->get('username'); ?></span>
                    <a href="<?= site_url('forum') ?>">Forum</a>
                    <a href="<?= site_url('auth/logout') ?>" class="btn btn-secondary btn-sm">Logout</a>
                <?php else: ?>
                    <a href="<?= site_url('auth/login') ?>">Login</a>
                    <a href="<?= site_url('auth/register') ?>" class="btn btn-primary btn-sm">Daftar Gratis</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            ✓ <?php echo session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            ✗ <?php echo session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error">
            <ul style="margin-left: 20px; list-style-type: disc;">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?php echo esc($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php echo $this->renderSection('content'); ?>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Mini Forum Diskusi - Tempat Berdiskusi & Berbagi Pengetahuan</p>
        <p>Dibangun dengan CodeIgniter 4.7.3</p>
    </footer>
</body>
</html>
