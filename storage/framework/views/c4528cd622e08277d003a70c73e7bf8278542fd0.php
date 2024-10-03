<?php $__env->startSection('title', 'Calls List'); ?>
<?php $__env->startSection('parentPageTitle', 'Crm'); ?>
<?php use App\Libraries\appLib; ?>
<?php $__env->startSection('page-style'); ?>
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
                    <button class="btn btn-primary" style="align:right"
                        onclick="window.location.href = '<?php echo e(url('Crm/Calls/Create')); ?>';">Add New Call</button>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>From</label>
                                <div class="input-group">
                                    <input type="date" id="from_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>To</label>
                                <div class="input-group">
                                    <input type="date" id="to_date" class="form-control">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Related To</label>
                                <select name="related_to_type" id="related_to_type" class="form-control show-tick ms select2"
                                    data-placeholder="Select">
                                    <option value="" selected disabled>Select Related</option>
                                    <?php
                                        $related = appLib::$related_table;
                                    ?>
                                    <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e(ucfirst($key)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label for=""></label>
                            <button id="generate" type="button"
                                class="btn btn-primary waves-effect font-weight-bold mt-3">Generate </button>
                        </div>


                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="calls">
                            <thead>
                                <tr>
                                    <th>SR#</th>
                                    <th>Subject</th>
                                    <th>Start Date</th>
                                    <th>Related To</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $callsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $call): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td class="column_size"><a
                                                href="<?php echo e(url('Crm/Calls/Create/' . $call['id'])); ?>"><?php echo e($call['subject']); ?></a>
                                        </td>
                                        <td class="column_size"><?php echo e($call['start_date']); ?></td>
                                        <td class="column_size"><?php echo e(isset($call['related_to']) ? $call['related_to']->name : ''); ?></td>

                                    </tr>
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
        t = $('#calls').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'pageLength',
                    className: 'btn cl mr-2 px-3 rounded'
                },
                {
                    extend: 'copy',
                    className: 'btn bg-dark mr-2 px-3 rounded',
                    title: 'Calls List'
                },
                {
                    extend: 'csv',
                    className: 'btn btn-info mr-2 px-3 rounded',
                    title: 'Calls List'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger mr-2 px-3 rounded',
                    title: 'Calls List'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-warning mr-2 px-3 rounded',
                    title: 'Calls List'
                },
                {
                    extend: 'print',
                    className: 'btn btn-success mr-2 px-3 rounded',
                    title: 'Calls List'
                },
                {
                    extend: 'colvis',
                    className: 'visible btn rounded'
                },
            ],
            "bDestroy": true,
            "lengthMenu": [
                [100, 200, 500, -1],
                [100, 200, 500, "All"]
            ],

        });
    </script>

    <script>
        var token = "<?php echo e(csrf_token()); ?>";
        var url = "<?php echo e(url('Crm/getCallsList')); ?>";

        $('#generate').click(function() {
            t.rows().remove().draw();
            $('.even').remove();
            $('.odd').remove();
            var sr = 1;
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var related_to_type = $('#related_to_type').val();
            $.post(url, {
                from_date: from_date,
                to_date: to_date,
                related_to_type: related_to_type,
                _token: token
            }, function(data) {
                var sr = 1;
                data.map(function(val, i) {
                    t.row.add([
                        '<td>' + sr + '</td>',
                        '<td class="column_size"><a href="<?php echo e(url('Crm/Calls/Create/')); ?>/' +
                        val
                        .id + '">' +
                        val.subject + '</a></td>',
                        '<td class="column_size">' + val.start_date + '</td>',
                        '<td class="column_size">' + val.related_to.name + '</td>',
                        '</tr>',
                    ]).draw(false);
                    sr++;
                });
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\app\resources\views/crm/calls_list.blade.php ENDPATH**/ ?>