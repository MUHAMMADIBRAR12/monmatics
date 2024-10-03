<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('parentPageTitle', 'Tickets'); ?>
<?php $__env->startSection('page-style'); ?>
    <?php use App\Libraries\appLib; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/css/sw.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/css/list.css')); ?>">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <button class="btn btn-primary" style="align:right"
                            onclick="window.location.href = '<?php echo e(url('Tmg/option/' . $optionKey . '/')); ?>';">New
                            <?php echo e($title); ?></button>
                        <?php if(Session::has('msg')): ?>
                            <div class="alert alert-danger"><?php echo e(Session::get('msg')); ?></div>
                        <?php endif; ?>

                        <table class="table table-bordered table-striped table-hover" id="cust_category">
                            <thead>
                                <tr>
                                    <th class="px-1 py-0 text-center">Actions</th>
                                    <th><?php echo e($title); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="action">
                                            <?php if($option->is_editable == '0'): ?>
                                                <a class="btn btn-success btn-sm"
                                                    href="<?php echo e(url('Tmg/option/' . $optionKey . '/' . $option->id)); ?>">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </a>
                                                <a class="btn btn-danger btn-sm del" data-toggle="modal"
                                                    data-target="#modalCenter<?php echo e($option->id); ?>">
                                                    <input type="hidden" id="userId" value="<?php echo e($option->id); ?>">
                                                    <i class="zmdi zmdi-delete text-white"></i>
                                                </a>
                                            <?php else: ?>
                                                Not EditAble
                                            <?php endif; ?>
                                        </td>

                                        <td class="column_size"><?php echo e($option->description ?? ''); ?></td>
                                    </tr>
                                    <div class="modal fade" id="modalCenter<?php echo e($category->id ?? ''); ?>" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Are You Sure to
                                                        Delete</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-footer">
                                                    <a class="btn btn-secondary" data-dismiss="modal">No</a>
                                                    <a class="btn btn-primary model-delete"
                                                        href="<?php echo e(url('Tmg/option/{type}/{id?}', ['id' => $option->id ?? ''])); ?>">Yes</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-script'); ?>
    <?php echo $__env->make('datatable-list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
    <script>
        $('#cust_category').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'pageLength',
                    className: 'btn cl mr-2 px-3 rounded'
                },
                {
                    extend: 'copy',
                    className: 'btn bg-dark mr-2 px-3 rounded',
                    title: 'Customer Categories',
                    exportOptions: {
                        columns: [1] // Exclude columns with the class 'actions'
                    }
                },
                {
                    extend: 'csv',
                    className: 'btn btn-info mr-2 px-3 rounded',
                    title: 'Customer Categories',
                    exportOptions: {
                        columns: [1] // Exclude columns with the class 'actions'
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger mr-2 px-3 rounded',
                    title: 'Customer Categories',
                    exportOptions: {
                        columns: [1] // Exclude columns with the class 'actions'
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-warning mr-2 px-3 rounded',
                    title: 'Customer Categories',
                    exportOptions: {
                        columns: [1] // Exclude columns with the class 'actions'
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-success mr-2 px-3 rounded',
                    title: 'Customer Categories',
                    exportOptions: {
                        columns: [1] // Exclude columns with the class 'actions'
                    }
                },
                {
                    extend: 'colvis',
                    className: 'visible btn rounded'
                }
            ],
            "bDestroy": true,
            "lengthMenu": [
                [100, 200, 500, -1],
                [100, 200, 500, "All"]
            ],
            // DataTable configuration...
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\app\resources\views/tickets/ticketOptionsList.blade.php ENDPATH**/ ?>