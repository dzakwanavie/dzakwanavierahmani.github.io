<?php $this->extend('layout/main'); ?>

<?php $this->section('content'); ?>

<div class="content">
    <div class="container" style="max-width: 800px;">
        <h1 style="margin-bottom: 30px;">Buat Topik Diskusi Baru</h1>

        <form action="<?= site_url('forum/store') ?>" method="POST" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <?php echo csrf_field(); ?>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="title" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">Judul Topik</label>
                <input type="text" id="title" name="title" placeholder="Masukkan judul topik (minimal 5 karakter)" value="<?php echo old('title'); ?>" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 5px; font-size: 14px;">
                <?php if (isset($validation) && $validation->hasError('title')): ?>
                    <div style="color: var(--error); font-size: 12px; margin-top: 5px;">
                        <?php echo $validation->getError('title'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="content" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">Konten Topik</label>
                <textarea id="content" name="content" placeholder="Tulis konten topik Anda di sini (minimal 10 karakter)" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 5px; font-size: 14px; min-height: 200px; font-family: Arial, sans-serif;"><?php echo old('content'); ?></textarea>
                <?php if (isset($validation) && $validation->hasError('content')): ?>
                    <div style="color: var(--error); font-size: 12px; margin-top: 5px;">
                        <?php echo $validation->getError('content'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Posting Topik</button>
                <a href="<?= site_url('forum') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php $this->endSection(); ?>

