<?php $this->extend('layout/main'); ?>

<?php $this->section('content'); ?>

<div class="content">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1>Forum Diskusi</h1>
            <a href="<?= site_url('forum/create') ?>" class="btn btn-primary">+ Buat Topik Baru</a>
        </div>

        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-item">
                    <div class="post-header">
                        <div>
                            <div class="post-title"><?php echo esc($post['title']); ?></div>
                            <div class="post-meta">
                                oleh <strong><?php echo esc($post['username']); ?></strong> 
                                pada <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?>
                            </div>
                        </div>
                    </div>

                    <div class="post-content">
                        <?php echo substr(esc($post['content']), 0, 150); ?>
                        <?php if (strlen($post['content']) > 150): ?>
                            ...
                        <?php endif; ?>
                    </div>

                    <div class="post-footer">
                        <div class="reply-count">
                            💬 <?php echo $post['reply_count']; ?> balasan
                        </div>
                        <div class="post-actions">
                            <a href="<?= site_url('forum/show/' . $post['id']) ?>" class="btn btn-sm">Baca & Balas</a>
                            <?php if (session()->get('user_id') == $post['user_id']): ?>
                                <a href="<?= site_url('forum/delete/' . $post['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus topik ini?');">Hapus</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <h3>Belum ada topik diskusi</h3>
                <p>Jadilah yang pertama membuat topik di forum ini!</p>
                <a href="<?= site_url('forum/create') ?>" class="btn btn-primary" style="margin-top: 20px;">Buat Topik Pertama</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $this->endSection(); ?>

