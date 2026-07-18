<?php $__env->startSection('title', 'مدیریت کاربران'); ?>
<?php $__env->startSection('content'); ?>
<h5 class="fw-bold mb-4">مدیریت کاربران</h5>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover data-table">
            <thead><tr><th>#</th><th>نام</th><th>ایمیل</th><th>نقش</th><th>وضعیت</th><th>تاریخ عضویت</th><th>عملیات</th></tr></thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td><?php echo e($user->id); ?></td>
                    <td><?php echo e($user->name); ?></td>
                    <td><?php echo e($user->email); ?></td>
                    <td><span class="badge bg-primary"><?php echo e($user->roles->pluck('name')->implode(', ') ?: 'ندارد'); ?></span></td>
                    <td><span class="badge bg-<?php echo e($user->is_active ? 'success' : 'secondary'); ?>"><?php echo e($user->is_active ? 'فعال' : 'غیرفعال'); ?></span></td>
                    <td class="small text-muted"><?php echo e(\Morilog\Jalali\Jalalian::fromCarbon($user->created_at)->format('Y/m/d')); ?></td>
                    <td><a href="<?php echo e(route('admin.users.show', $user)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>
        <?php echo e($users->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\cartix\resources\views/admin/users/index.blade.php ENDPATH**/ ?>