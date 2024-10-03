<?php $__env->startSection('title', 'Setting'); ?>
<?php $__env->startSection('parentPageTitle', 'SMTP'); ?>
<?php $__env->startSection('page-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css')); ?>" />
    <script lang="javascript/text">
        var token = "<?php echo e(csrf_token()); ?>";
    </script>
    <style>
        #cust_category_filter,
        label {
            float: inline-end;
        }

        .form-label {
            float: unset
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card col-lg-12">
                <div class="table-responsive contact">
                    <div class="body">
                        <?php if(session()->has('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo e(session()->get('error')); ?>

                            </div>
                        <?php endif; ?>
                        <?php if(session()->has('message')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session()->get('message')); ?>

                            </div>
                        <?php endif; ?>
                        <p><a class="btn btn-primary" style="float: inline-end"
                                href="<?php echo e(url('Admin/Modules/List')); ?>">back</a>
                        <form>
                            <input type="hidden" name="id" id="credentialId" value="<?php echo e($credential->id ?? ''); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Host</label>
                                    <input type="text" class="form-control value" name="imap_host" placeholder="Host"
                                        id="imap_host" value="<?php echo e($credential->imap_host ?? ''); ?>">
                                </div>
                                <div class="mb-3  col-md-2">
                                    <label class="form-label">Port</label>
                                    <input type="text" class="form-control value" name="imap_port" placeholder="Port"
                                        id="imap_port" value="<?php echo e($credential->imap_port ?? ''); ?>">
                                </div>
                                <div class="mb-3  col-md-2">
                                    <label class="form-label">Protocol</label>
                                    <select name="imap_protocol" class="form-control show-tick ms select2"
                                        data-placeholder="Select" id="imap_protocol" required>
                                        <option selected>Select Protocol</option>
                                        <option <?php echo e($credential && $credential->imap_protocol === 'imap' ? 'selected' : ''); ?>

                                            value="imap">Imap</option>
                                        <option <?php echo e($credential && $credential->imap_protocol === 'pop' ? 'selected' : ''); ?>

                                            value="pop">Pop</option>

                                    </select>
                                </div>
                                <div class=" col-md-2">
                                    <label class="form-label">Encryption</label>

                                    <select name="imap_encryption" class="form-control show-tick ms select2"
                                        data-placeholder="Select" id="imap_encryption" required>
                                        <option selected>Select Encryption</option>
                                        <option
                                            <?php echo e($credential && $credential->imap_encryption == 'ssl' ? 'selected' : ''); ?>

                                            value="ssl">ssl</option>
                                        <option
                                            <?php echo e($credential && $credential->imap_encryption == 'tls' ? 'selected' : ''); ?>

                                            value="tls">tls</option>
                                        <option
                                            <?php echo e($credential && $credential->imap_encryption == 'auto' ? 'selected' : ''); ?>value="auto">
                                            auto</option>
                                    </select>
                                </div>

                                <div class="mb-3  col-md-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control value" name="imap_username"
                                        placeholder="Username" id="imap_username"
                                        value="<?php echo e($credential->imap_username ?? ''); ?>">
                                </div>
                                <div class="mb-3  col-md-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control value" name="imap_password"
                                        placeholder="Password" id="imap_password"
                                        value="<?php echo e($credential->imap_password ?? ''); ?>">
                                </div>
                                <div class="mb-3  col-md-3">
                                    <label class="form-label">Department</label>
                                    <select class="form-control show-tick ms select2" data-placeholder="Select"
                                        id="department" name="department" required>
                                        <option selected>Select Department</option>
                                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option id="department" value="<?php echo e($department->name); ?>">
                                                <?php echo e($department->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <!-- Add this line for debugging -->
                                

                                <div class="dom col-md-3">
                                    <label class="form-label">Select to delete Mails or Read Mails</label> <br>
                                    <select name="message" class="form-control show-tick ms select2"
                                        data-placeholder="Select" id="message" required>
                                        <option selected>Select..</option>
                                        <option
                                            <?php echo e($credential && $credential->message == 'delete' ? 'selected' : ''); ?>

                                            value="delete">Delete</option>
                                        <option
                                            <?php echo e($credential && $credential->message == 'read' ? 'selected' : ''); ?>

                                            value="read">Read</option>
                                    </select>
                                </div>




                            </div>

                            <button class="btn btn-primary" id="submit">Submit </button>
                        </form>
                        <table class="table table-bordered table-striped table-hover" id="cust_category">
                            <thead>
                                <tr>
                                    <th>Host</th>
                                    <th>Port</th>
                                    <th>Protocol</th>
                                    <th>Encryption</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                    <th>Department</th>
                                    <th>Message </th>
                                    <th>Activate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $credentials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $credential): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr id="rsm<?php echo e($credential->id ?? ''); ?>">

                                        <td class="column_size"><?php echo e($credential->imap_host ?? ''); ?></td>
                                        <td class="column_size"><?php echo e($credential->imap_port ?? ''); ?></td>
                                        <td class="column_size"><?php echo e($credential->imap_protocol ?? ''); ?></td>
                                        <td class="column_size"><?php echo e($credential->imap_encryption ?? ''); ?></td>
                                        <td class="column_size"><?php echo e($credential->imap_username ?? ''); ?></td>
                                        <td class="column_size"><?php echo e($credential->imap_password ?? ''); ?></td>
                                        <td class="column_size"><?php echo e($credential->status ?? ''); ?></td>
                                        <td class="column_size"><?php echo e($credential->department ?? ''); ?></td>
                                        <td class="column_size"><?php echo e($credential->message ?? ''); ?></td>

                                        <td>
                                            
                                            <a href="<?php echo e(url('Admin/Smtp/Setting/Active', $credential->id)); ?>"
                                                class="btn btn-primary"
                                                style="margin: 0; padding: 2px;width: 38px;">Active</a>
                                            <a href="<?php echo e(url('Admin/Smtp/Setting/DeActive', $credential->id)); ?>"
                                                class="btn btn-danger" id="deactive" data-id="<?php echo e($credential->id); ?>"
                                                style="margin: 0; padding: 2px;width: 38px;">Deactive</a>
                                            <a href="<?php echo e(url('Admin/Smtp/Test', $credential->id)); ?>"
                                                class="btn btn-success" id="deactive" data-id="<?php echo e($credential->id); ?>"
                                                style="margin: 0; padding: 2px;width: 42px; height:33px">Test</a>
                                        </td>



                                        <td class="action">
                                            <a class="btn btn-success btn-sm"
                                                href="<?php echo e(url('Admin/Smtp/Setting', $credential->id)); ?>">
                                                <i class="zmdi zmdi-edit"></i>
                                            </a>
                                            <a class="btn btn-danger btn-sm del" data-toggle="modal"
                                                data-target="#modalCenter<?php echo e($credential->id ?? ''); ?>">
                                                <i class="zmdi zmdi-delete text-white"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modalCenter<?php echo e($credential->id ?? ''); ?>" tabindex="-1"
                                        data-credential-id="<?php echo e($credential->id ?? ''); ?>" role="dialog"
                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                                        href="<?php echo e(url('Admin/Smtp/Setting/Delete', $credential->id)); ?>">Yes</a>
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
        <!-- Modal for delete confirmation -->
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
        <script>
            $(document).ready(function() {
                // Use event delegation for dynamic elements
                $('form').submit(function(e) {
                    e.preventDefault();

                    var credentialId = $('#credentialId').val();
                    var port = $('#imap_port').val();
                    var host = $('#imap_host').val();
                    var username = $('#imap_username').val();
                    var password = $('#imap_password').val();
                    var encryption = $('#imap_encryption').val();
                    var protocol = $('#imap_protocol').val();
                    var message = $('#message').val();
                    var department = $('#department').val();



                    $.ajax({
                        url: '<?php echo e(route('smtp.setting')); ?>',
                        type: 'post',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                            credential_id: credentialId,
                            imap_port: port,
                            imap_host: host,
                            imap_username: username,
                            imap_password: password,
                            imap_encryption: encryption,
                            imap_protocol: protocol,
                            department: department,
                            message: message,



                        },
                        success: function(response) {
                            var action = credentialId ? 'Edit' : 'Add';

                            if (action === 'Add') {
                                // Add a new row for the added data
                                var newRow = '<tr id="rsm' + response.id + '"><td>' + response
                                    .host + '</td><td>' +
                                    response.port + '</td><td>' + response.protocol + '</td><td>' +
                                    response.encryption +
                                    '</td><td>' + response.username + '</td><td>' + response
                                    .password + '</td><td>' +
                                    response.status + '</td><td>' +
                                    response.department + '</td><td>' +
                                    response.message + '</td><td>' +
                                    '<a href="<?php echo e(url('Admin/Smtp/Setting/Active')); ?>/' + response
                                    .id +
                                    '" class="btn btn-primary" style="margin: 0; padding: 2px;width: 38px;">Active</a>' +
                                    '<a href="<?php echo e(url('Admin/Smtp/Setting/DeActive')); ?>/' + response
                                    .id +
                                    '" class="btn btn-danger" id="deactive" data-id="' + response
                                    .id +
                                    '" style="margin: 0; padding: 2px;width: 38px;">Deactive</a>' +
                                    '<a href="<?php echo e(url('Admin/Smtp/Test')); ?>/' + response.id +
                                    '" class="btn btn-success"' +
                                    ' id="deactive" data-id="' + response.id +
                                    '" style="margin: 0; padding: 2px;width: 42px; height:33px">Test</a>' +
                                    '</td><td class="action"><a class="btn btn-success btn-sm" href="<?php echo e(url('Admin/Smtp/Setting')); ?>/' +
                                    response.id + '"><i class="zmdi zmdi-edit"></i></a>' +
                                    '<a class="btn btn-danger btn-sm del" data-credential-id="' +
                                    response.id +
                                    '"><i class="zmdi zmdi-delete text-white"></i></a></td></tr>';

                                // Append the new row to the table
                                $('#cust_category tbody').append(newRow);
                                $(".value").val("");
                            } else if (action === 'Edit') {
                                // Update the existing row with the edited data
                                var editedRow = $('#rsm' + credentialId);
                                editedRow.find('td:eq(0)').text(response.host);
                                editedRow.find('td:eq(1)').text(response.port);
                                editedRow.find('td:eq(2)').text(response.protocol);
                                editedRow.find('td:eq(3)').text(response.encryption);
                                editedRow.find('td:eq(4)').text(response.username);
                                editedRow.find('td:eq(5)').text(response.password);
                                editedRow.find('td:eq(6)').text(response.status);
                                editedRow.find('td:eq(7)').text(response.department);
                                editedRow.find('td:eq(8)').text(response.message);


                                $(".value").val("");


                            }

                        },
                        error: function(error) {
                            // Handle errors here
                            console.log(error);
                        }
                    });

                    return false;
                });
            });
        </script>
        

    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\app\resources\views/system/smtp_pop.blade.php ENDPATH**/ ?>