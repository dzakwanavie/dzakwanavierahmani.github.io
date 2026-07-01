<?php $this->extend('layout/main'); ?>

<?php $this->section('content'); ?>

<div class="content">
    <div class="container" style="max-width: 900px;">
        <div style="margin-bottom: 30px;">
            <a href="<?= site_url('forum') ?>" style="color: var(--primary); text-decoration: none;">← Kembali ke Forum</a>
        </div>

        <!-- Original Post -->
        <div class="post-item" style="border-left: 4px solid var(--primary);">
            <div class="post-header">
                <div style="flex: 1;">
                    <div class="post-title"><?php echo esc($post['title']); ?></div>
                    <div class="post-meta">
                        oleh <strong><?php echo esc($post['username']); ?></strong> 
                        pada <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?>
                    </div>
                </div>
                <?php if (session()->get('user_id') == $post['user_id']): ?>
                    <div>
                        <a href="<?= site_url('forum/delete/' . $post['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus topik ini?');">Hapus</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="post-content">
                <?php echo nl2br(esc($post['content'])); ?>
            </div>
        </div>

        <!-- Replies Section -->
        <div style="margin-top: 40px; margin-bottom: 40px;">
            <h2 style="margin-bottom: 20px;">
                💬 Balasan (<?php echo count($replies); ?>)
            </h2>

            <?php if (count($replies) > 0): ?>
                <?php foreach ($replies as $reply): ?>
                    <div class="reply-item">
                        <div class="reply-meta">
                            <strong><?php echo esc($reply['username']); ?></strong> 
                            pada <?php echo date('d/m/Y H:i', strtotime($reply['created_at'])); ?>
                        </div>
                        <div class="reply-content">
                            <?php echo nl2br(esc($reply['content'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 30px; color: #999;">
                    <p>Belum ada balasan. Jadilah yang pertama memberikan balasan!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Reply Form -->
        <div style="margin-top: 40px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <h2 style="margin-bottom: 20px;">📝 Tulis Balasan</h2>

            <form action="<?= site_url('forum/storeReply/' . $post['id']) ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="content" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark);">Balasan Anda</label>
                    <textarea id="content" name="content" placeholder="Tulis balasan Anda di sini" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 5px; font-size: 14px; min-height: 100px; font-family: Arial, sans-serif;" required><?php echo old('content'); ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('content')): ?>
                        <div style="color: var(--error); font-size: 12px; margin-top: 5px;">
                            <?php echo $validation->getError('content'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Kirim Balasan</button>
                    <a href="<?= site_url('forum') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>

