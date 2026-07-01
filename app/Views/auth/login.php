<?php $this->extend('layout/main'); ?>

<?php $this->section('content'); ?>

<div class="content">
    <div class="container" style="max-width: 500px; margin: 0 auto; padding: 40px 20px;">
        <h1 style="text-align: center; font-size: 28px; margin-bottom: 10px;">Masuk ke Forum</h1>
        <p style="text-align: center; color: #718096; margin-bottom: 30px;">Akses akun Anda dan bergabung dengan diskusi</p>

        <form action="<?= site_url('auth/authenticate') ?>" method="POST" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <?php echo csrf_field(); ?>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="username" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">Username atau Email</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username atau email" value="<?php echo old('username'); ?>" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 5px; font-size: 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label for="password" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 5px; font-size: 14px;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 16px;">Masuk</button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #718096;">
            Belum punya akun? <a href="<?= site_url('auth/register') ?>" style="color: var(--primary); font-weight: 600;">Daftar di sini</a>
        </p>
    </div>
</div>

<?php $this->endSection(); ?>

