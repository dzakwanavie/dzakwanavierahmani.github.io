<?php $this->extend('layout/main'); ?>

<?php $this->section('content'); ?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Selamat Datang di Forum Diskusi</h1>
        <p>Tempat terbaik untuk berbagi pengetahuan, bertanya, dan berdiskusi dengan komunitas kami yang aktif dan berpengalaman.</p>
        <div class="hero-buttons">
            <a href="<?= site_url('auth/register') ?>" class="btn btn-light">Daftar Sekarang</a>
            <a href="<?= site_url('auth/login') ?>" class="btn btn-secondary" style="background: rgba(255,255,255,0.2); color: white;">Masuk ke Forum</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="container">
        <h2>Fitur Utama Forum</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📝</div>
                <h3>Buat Diskusi</h3>
                <p>Mulai topik diskusi baru dan ajukan pertanyaan kepada komunitas kami yang luas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💬</div>
                <h3>Berbagi Jawaban</h3>
                <p>Berikan solusi dan berbagi pengalaman Anda kepada sesama anggota forum.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔐</div>
                <h3>Aman & Terpercaya</h3>
                <p>Platform kami dilengkapi dengan sistem keamanan tingkat enterprise untuk melindungi data Anda.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3>Komunitas Aktif</h3>
                <p>Bergabung dengan ribuan pengguna yang saling membantu dan berdiskusi setiap hari.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Real-Time</h3>
                <p>Dapatkan update instan tentang balasan diskusi dan komentar terbaru dari komunitas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎓</div>
                <h3>Belajar & Berkembang</h3>
                <p>Tingkatkan pengetahuan Anda dengan berbagi ilmu dan belajar dari pengalaman orang lain.</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat">
                <div class="stat-number">1000+</div>
                <div class="stat-label">Anggota Aktif</div>
            </div>
            <div class="stat">
                <div class="stat-number">5000+</div>
                <div class="stat-label">Topik Diskusi</div>
            </div>
            <div class="stat">
                <div class="stat-number">15000+</div>
                <div class="stat-label">Balasan & Komentar</div>
            </div>
            <div class="stat">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Dukungan Komunitas</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta">
    <div class="container">
        <h2>Siap Bergabung dengan Komunitas Kami?</h2>
        <p>Jangan lewatkan kesempatan untuk berkembang bersama ribuan anggota komunitas kami yang luar biasa.</p>
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            <a href="<?= site_url('auth/register') ?>" class="btn btn-primary">Daftar Gratis Sekarang</a>
            <a href="<?= site_url('auth/login') ?>" class="btn btn-secondary">Saya Sudah Memiliki Akun</a>
        </div>
    </div>
</section>

<?php $this->endSection(); ?>
