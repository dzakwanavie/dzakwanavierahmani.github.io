<?php $this->extend('layout/main'); ?>

<?php $this->section('content'); ?>

<div class="content">
    <div class="container">
        <h1>Selamat Datang, <?php echo session()->get('username'); ?>! 👋</h1>
        
        <div class="forum-stats">
            <div class="stat-box">
                <div class="stat-box-value">0</div>
                <div class="stat-box-label">Topik Saya</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-value">0</div>
                <div class="stat-box-label">Balasan Saya</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-value">0</div>
                <div class="stat-box-label">Topik Diikuti</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-value">0</div>
                <div class="stat-box-label">Reputasi</div>
            </div>
        </div>

        <div style="text-align: center; padding: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; color: white;">
            <h2>Siap untuk Memulai Diskusi?</h2>
            <p style="margin: 15px 0; font-size: 16px;">Mulai topik baru dan bagikan pengetahuan Anda dengan komunitas kami.</p>
            <a href="<?= site_url('forum/create') ?>" class="btn btn-light" style="display: inline-block;">+ Buat Topik Baru</a>
        </div>

        <div style="margin-top: 40px;">
            <h2 style="margin-bottom: 20px;">Lihat Forum</h2>
            <a href="<?= site_url('forum') ?>" class="btn btn-primary">Ke Halaman Forum →</a>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
