<?php $__env->startSection('title', 'مدیریت پلن‌ها'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">مدیریت پلن‌ها</h5>
    <a href="<?php echo e(route('admin.plans.create')); ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg ms-1"></i> پلن جدید</a>
</div>
<div class="row g-3">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm h-100 <?php echo e($plan->is_active ? 'border-primary' : ''); ?>">
            <div class="card-body text-center">
                <h5 class="fw-bold"><?php echo e($plan->name); ?></h5>
                <div class="display-6 fw-bold text-primary my-3"><?php echo e(number_format($plan->price_monthly)); ?><small class="fs-6"> تومان/ماه</small></div>
                <ul class="list-unstyled small text-muted">
                    <li><?php echo e($plan->max_qr_codes == -1 ? 'نامحدود' : $plan->max_qr_codes); ?> کد QR</li>
                    <li><?php echo e($plan->max_storage_mb >= 1024 ? ($plan->max_storage_mb / 1024) . ' گیگابایت' : $plan->max_storage_mb . ' مگابایت'); ?> فضا</li>
                </ul>
            </div>
            <div class="card-footer bg-transparent d-flex gap-1">
                <a href="<?php echo e(route('admin.plans.edit', $plan)); ?>" class="btn btn-sm btn-outline-primary flex-grow-1">ویرایش</a>
                <form action="<?php echo e(route('admin.plans.destroy', $plan)); ?>" method="POST"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف شود؟')"><i class="bi bi-trash"></i></button></form>
            </div>
        </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\cartix\resources\views/admin/plans/index.blade.php ENDPATH**/ ?>