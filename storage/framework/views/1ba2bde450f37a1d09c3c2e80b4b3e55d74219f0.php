<?php $__env->startSection('title', 'Company List'); ?>
<?php $__env->startSection('parentPageTitle', 'Admin'); ?>
<?php $__env->startSection('page-style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css')); ?>" />
<link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css')); ?>" />
<style>
    .dataTables_filter{
        float: inline-end;
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
        <a href="<?php echo e(url('Admin/Company/Create')); ?>" class="btn btn-primary" style="align:right" >
            <b><i class="zmdi zmdi-plus"></i>Add Company</b>
        </a>
            <div class="table-responsive contact">
                <table class="table table-hover mb-0 c_list c_table" id="company">
                    <thead>
                        <tr>
                            <th>Edit</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody >
                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <a class="btn btn-primary btn-sm"  href="<?php echo e(url('Admin/Company/Create/'.$company->id)); ?>">
                                    <i class="zmdi zmdi-edit"></i>
                                </a>
                            </td>
                            <td>

                                <img src="<?php echo e(url('display/'.$company->id)); ?>" class="avatar w30" alt="">
                                <p class="c_name"><?php echo e($company->name); ?></p>
                            </td>
                            <td>
                                <span class="email"><a href="javascript:void(0);" title=""><?php echo e($company->phone); ?></a></span>
                            </td>
                            <td><?php echo e($company->email); ?></td>
                            <td><?php echo e($company->address); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal for delete confirmation -->
<div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are You Sure to Delete The Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary" data-dismiss="modal">No</a>
                <a class="btn btn-primary model-delete" href="">Yes</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-script'); ?>
<script src="<?php echo e(asset('public/assets/bundles/footable.bundle.js')); ?>"></script>
<script src="<?php echo e(asset('public/assets/js/pages/tables/footable.js')); ?>"></script>
<script>
$(document).ready(function(){
$(document).on('click','.del',function(){
var id=$(this).find('#userId').val();
$(".model-delete").attr("href", "removeUser/"+id);
});
});
</script>

<?php echo $__env->make('datatable-list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<script>
$('#company').DataTable( {
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'pageLength',
            className: 'btn cl mr-2 px-3 rounded'
        },
        {
            extend: 'copy',
            className: 'btn bg-dark mr-2 px-3 rounded',
            title: 'Company list',

        },
        {
            extend: 'csv',
            className: 'btn btn-info mr-2 px-3 rounded',
            title: 'Company list',

        },
        {
            extend: 'pdf',
            className: 'btn btn-danger mr-2 px-3 rounded',
            title: 'Company list',

        },
        {
            extend: 'excel',
            className: 'btn btn-warning mr-2 px-3 rounded',
            title: 'Company list',

        },
        {
            extend: 'print',
            className: 'btn btn-success mr-2 px-3 rounded',
            title: 'Company list',

        },
        { extend: 'colvis', className: 'visible btn rounded' }
    ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],

});


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\app\resources\views/system/company_list.blade.php ENDPATH**/ ?>