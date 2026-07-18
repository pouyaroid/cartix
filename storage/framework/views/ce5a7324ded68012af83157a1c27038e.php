<?php $__env->startSection('title', 'مدیریت فونت‌ها'); ?>
<?php $__env->startSection('content'); ?>
<h5 class="fw-bold mb-4">مدیریت فونت‌ها</h5>
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover">
                    <thead><tr><th>نام</th><th>فرمت</th><th>وضعیت</th><th>عملیات</th></tr></thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $fonts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $font): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr>
                            <td><?php echo e($font->name); ?></td>
                            <td><span class="badge bg-secondary"><?php echo e(strtoupper($font->file_format)); ?></span></td>
                            <td><span class="badge bg-<?php echo e($font->is_active ? 'success' : 'secondary'); ?>"><?php echo e($font->is_active ? 'فعال' : 'غیرفعال'); ?></span></td>
                            <td>
                                <form action="<?php echo e(route('admin.fonts.toggle', $font)); ?>" method="POST" class="d-inline"><?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-warning"><i class="bi bi-toggle-<?php echo e($font->is_active ? 'on' : 'off'); ?>"></i></button></form>
                                <form action="<?php echo e(route('admin.fonts.destroy', $font)); ?>" method="POST" class="d-inline"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف شود؟')"><i class="bi bi-trash"></i></button></form>
                            </td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent"><h6 class="fw-bold mb-0">آپلود فونت</h6></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.fonts.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <label class="form-label">نام فونت</label>
                    <input type="text" class="form-control mb-2" name="name" required>
                    <label class="form-label">فرمت</label>
                    <select class="form-select mb-2" name="file_format" required><option value="woff2">WOFF2</option><option value="woff">WOFF</option><option value="ttf">TTF</option></select>
                    <label class="form-label">فایل</label>
                    <input type="file" class="form-control mb-3" name="file" required>
                    <button class="btn btn-primary btn-sm w-100"><i class="bi bi-upload ms-1"></i> آپلود</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\cartix\resources\views/admin/fonts/index.blade.php ENDPATH**/ ?>