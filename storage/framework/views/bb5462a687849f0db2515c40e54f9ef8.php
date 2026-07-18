<?php $__env->startSection('title', $qrCode->title ?: 'جزئیات کد QR'); ?>
<?php $__env->startSection('page-title', 'جزئیات کد QR'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-3">
    <a href="<?php echo e(route('dashboard.qr.index')); ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-right ms-1"></i> بازگشت
    </a>
</div>

<div class="row g-4">
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h5 class="fw-bold mb-3"><?php echo e($qrCode->title); ?></h5>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($qrImage): ?>
                    <img src="<?php echo e($qrImage); ?>" alt="QR Code" class="mb-3" style="max-width:200px;">
                <?php else: ?>
                    <div class="text-muted mb-3">پیش‌نمایش در دسترس نیست</div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="d-flex justify-content-center gap-2">
                    <a href="<?php echo e(route('dashboard.qr.download', [$qrCode, 'png'])); ?>" class="btn btn-sm btn-success">
                        <i class="bi bi-download ms-1"></i> PNG
                    </a>
                    <a href="<?php echo e(route('dashboard.qr.download', [$qrCode, 'svg'])); ?>" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-download ms-1"></i> SVG
                    </a>
                </div>

                <hr>

                <div class="text-start small">
                    <div class="mb-2"><span class="text-muted">نوع:</span> <span class="badge bg-<?php echo e($qrCode->type === 'dynamic' ? 'info' : 'secondary'); ?>"><?php echo e($qrCode->type === 'dynamic' ? 'پویا' : 'ثابت'); ?></span></div>
                    <div class="mb-2"><span class="text-muted">لینک:</span> <span class="text-break"><?php echo e($qrCode->content); ?></span></div>
                    <div class="mb-2"><span class="text-muted">رنگ پیش‌زمینه:</span> <span class="d-inline-block rounded" style="width:16px;height:16px;background:<?php echo e($qrCode->foreground_color); ?>;vertical-align:middle;"></span> <?php echo e($qrCode->foreground_color); ?></div>
                    <div class="mb-2"><span class="text-muted">رنگ پس‌زمینه:</span> <span class="d-inline-block rounded" style="width:16px;height:16px;background:<?php echo e($qrCode->background_color); ?>;border:1px solid #ddd;vertical-align:middle;"></span> <?php echo e($qrCode->background_color); ?></div>
                    <div class="mb-2"><span class="text-muted">اندازه:</span> <?php echo e($qrCode->size); ?>px</div>
                    <div><span class="text-muted">تاریخ ساخت:</span> <?php echo e(\Morilog\Jalali\Jalalian::fromCarbon($qrCode->created_at)->format('Y/m/d')); ?></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-8">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">کل اسکن‌ها</div>
                    <div class="fw-bold fs-4"><?php echo e(number_format($scanStats['total'])); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">امروز</div>
                    <div class="fw-bold fs-4"><?php echo e(number_format($scanStats['today'])); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">این هفته</div>
                    <div class="fw-bold fs-4"><?php echo e(number_format($scanStats['this_week'])); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">این ماه</div>
                    <div class="fw-bold fs-4"><?php echo e(number_format($scanStats['this_month'])); ?></div>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="fw-bold mb-0">اسکن‌های اخیر</h6>
            </div>
            <div class="card-body p-0">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentScans->count()): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>تاریخ</th>
                                    <th>دستگاه</th>
                                    <th>مرورگر</th>
                                    <th>سیستم‌عامل</th>
                                    <th>آی‌پی</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentScans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <tr>
                                        <td class="small"><?php echo e(\Morilog\Jalali\Jalalian::fromCarbon($scan->created_at)->format('Y/m/d H:i')); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e(match($scan->device_type) { 'mobile' => 'info', 'tablet' => 'warning', default => 'secondary' }); ?>">
                                                <?php echo e($scan->device_type === 'mobile' ? 'موبایل' : ($scan->device_type === 'tablet' ? 'تبلت' : 'دسکتاپ')); ?>

                                            </span>
                                        </td>
                                        <td class="small"><?php echo e($scan->browser); ?></td>
                                        <td class="small"><?php echo e($scan->os); ?></td>
                                        <td class="small text-muted"><?php echo e($scan->ip_address); ?></td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-3"></i>
                        <p class="mt-2 small">هنوز اسکنی ثبت نشده</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\cartix\resources\views/dashboard/qr-codes/show.blade.php ENDPATH**/ ?>