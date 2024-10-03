<?php $__env->startSection('title', 'New Ticket'); ?>
<?php $__env->startSection('parentPageTitle', 'Ticket'); ?>
<?php $__env->startSection('parent_title_icon', 'zmdi zmdi-home'); ?>
<?php $__env->startSection('page-style'); ?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/dropify/css/dropify.min.css')); ?>" />
    <style>
        .dropify {
            width: 200px;
            height: 200px;
        }

        th.project .project {
            display: block;
        }

        .colseTicket {
            float: right;
        }
    </style>
    <script lang="javascript/text">
        var contactURL = "<?php echo e(url('contactsSearch')); ?>";
        var url = "<?php echo e(url('')); ?>";
        var userURL = "<?php echo e(url('userSearch')); ?>";
        var token = "<?php echo e(csrf_token()); ?>";
    </script>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php use App\Libraries\appLib; ?>
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Ticket</strong></h2>
            </div>
            <div class="colseTicket">
                <a href="<?php echo e(url('Tmg/Listing/New')); ?>" onclick="return confirm('do you want to close?')"
                    class="btn btn-raised btn-primary waves-effect">
                    Close
                </a>
            </div>
            <?php if(session()->has('message')): ?>
                <div class="alert alert-success">
                    <?php echo e(session()->get('message')); ?>

                </div>
            <?php endif; ?>
            <div class="body">

                <form method="post" action="<?php echo e(url('Ticket/update')); ?>" enctype="multipart/form-data"
                    onsubmit="updateDescription()">
                    <?php echo e(csrf_field()); ?>


                    <input type="hidden" name="id" value="<?php echo e($ticket->id ?? ''); ?>">
                    <input type="hidden" name="backURL" value="<?php echo e($backURL); ?>">
                    <div class="row">
                        <div class="col-lg-2">
                            <label for="">Ticket #</label>
                            <p><?php echo e($ticket->number ?? '-'); ?></p>
                        </div>
                        <div class="col-lg-2">
                            <label for="">Department</label>
                            <select name="department" id="department" class="form-control show-tick ms select2"
                                data-placeholder="Select">
                                <option value="1" selected disabled>--Select--</option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($department->name); ?>"
                                        <?php echo e($department->name == ($ticket->department ?? '') ? 'selected' : ''); ?>

                                        <?php echo e($department->name == ($sessiondepartment ?? '') ? 'selected' : ''); ?>>
                                        <?php echo e($department->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label for="fiscal_year">Category</label>
                            <div class="form-group">
                                <select name="category" id="category" class="form-control show-tick ms select2">
                                    <option value="1" selected disabled>--Select--</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->description); ?>"
                                            <?php echo e($category->description == ($ticket->category ?? '') ? 'selected' : ''); ?>

                                            <?php echo e($category->description == ($sessioncategory ?? '') ? 'selected' : ''); ?>>
                                            <?php echo e($category->description); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label for="fiscal_year">Priority</label>
                            <div class="form-group">
                                <select name="priority" id="priority" class="form-control show-tick ms select2">
                                    <option value="1" selected disabled>--Select--</option>
                                    <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($priority->description); ?>"
                                            <?php echo e($priority->description == ($ticket->priority ?? '') ? 'selected' : ''); ?>

                                            <?php echo e($priority->description == ($sessionpriority ?? '') ? 'selected' : ''); ?>>
                                            <?php echo e($priority->description); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label for="fiscal_year">Status</label>
                            <div class="form-group">
                                <select name="status" id="status" class="form-control show-tick ms select2">
                                    <option value="1" selected disabled>--Select--</option>
                                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($status->description); ?>"
                                            <?php echo e($status->description == ($ticket->status ?? '') ? 'selected' : ''); ?>

                                            <?php echo e($status->description == ($sessionstatus ?? '') ? 'selected' : ''); ?>>
                                            <?php echo e($status->description); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Email</label>
                            <div class="form-group" id="">
                                <input type="email" name="email" class="form-control"
                                    value="<?php echo e($ticket->email ?? ''); ?>" id="mail_c">
                                <?php if(session()->has('mail')): ?>
                                    <div class="text-danger">
                                        <?php echo e(session()->get('mail')); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="code">Subject</label>
                            <div class="form-group">
                                <input type="text" name="subject" class="form-control"
                                    value="<?php echo e(old('email') ?? ($ticket->subject ?? '')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Related To</label>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <select name="related_to_type" id="related_to"
                                        class=" form-control show-tick ms select2">
                                        <option value="" value="1" selected disabled>Select Type</option>
                                        <?php
                                            $projectFind = DB::table('prj_projects')
                                                ->where('id', $project->id ?? '')
                                                ->first();
                                            $relatedTo = $projectFind ? 'project' : null;
                                            $relatedOptions = appLib::$related_to;
                                        ?>
                                        <?php $__currentLoopData = $relatedOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related_to): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($related_to); ?>"
                                                <?php echo e($related_to == $relatedTo ? 'selected' : ''); ?>

                                                <?php echo e($related_to == ($ticket->related_to ?? '') ? 'selected' : ''); ?>

                                                <?php echo e($related_to == ($sessionrelated_to ?? '') ? 'selected' : ''); ?>>
                                                <?php echo e($related_to); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-sm-8 col-md-8">
                                    <input type="text" name="related" class="form-control related"
                                        id="<?php echo e($ticket->related_to ?? ($relatedTo ?? ($sessionrelated_to ?? ''))); ?>"
                                        value="<?php echo e(isset($relatedToInfo) ? $relatedToInfo->id : (isset($projectId) ? $project->name : '')); ?> <?php echo e($project->name ?? ''); ?> <?php echo e($ticket->project_name ?? ''); ?> <?php echo e($ticket->customer_name ?? ''); ?> <?php echo e($ticket->contact_firstname ?? ''); ?> <?php echo e($ticket->contact_lastname ?? ''); ?> <?php echo e($customer->name ?? ''); ?> <?php echo e($customer2->name ?? ''); ?> <?php echo e($projects->name ?? ''); ?> <?php echo e($contact->first_name ?? ''); ?>  <?php echo e($contact->last_name ?? ''); ?>"
                                        placeholder="Search" onkeyup="autoFill(this.id, url+'/'+this.id+'Search', token);">
                                    <input type="hidden" name="related_ID" class="related_ID"
                                        id="<?php echo e(($ticket->related_to ?? ($relatedTo ?? ($sessionrelated_to ?? ''))) . '_ID'); ?>"
                                        value="<?php echo e(isset($relatedToInfo) ? $relatedToInfo->id : (isset($task->related_id) ? $task->related_id : '')); ?> <?php echo e($project->id ?? ''); ?> <?php echo e($ticket->related_to_id ?? ''); ?> <?php echo e($sessionrelated_to_id ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label for="fiscal_year">Description</label>
                            <div class="form-group" id="note">

                                <textarea name="body"><?php echo e($ticket->body ?? ''); ?></textarea>

                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">

                            <label for="fiscal_year">Attachment</label>
                            <br>
                            <?php if($attachmentRecord ?? ''): ?>
                                <table>
                                    <?php $i=0 ; ?>
                                    <?php $__currentLoopData = $attachmentRecord; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $i++ ; ?>
                                        <tr id='attRow<?php echo e($i); ?>'>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="deleteFileA('<?php echo e($attachment->id); ?>', <?php echo e($i); ?>)"><i
                                                        class="zmdi zmdi-delete"></i></button>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(url('download/' . $attachment->id)); ?>"
                                                    download><?php echo e($attachment->file); ?></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            <?php endif; ?>
                            <input name="file" type="file" class="dropify">

                        </div>
                        <div class="col-md-4">
                            <label for="email">Assign To</label>
                            <div class="form-group">
                                <input type="text" name="assign" id="assign" class="form-control"
                                    value="<?php echo e($assignedToName->name ?? ''); ?>" placeholder="Assign To"
                                    onkeyup="autoFill(this.id, userURL, token)">

                                <input type="hidden" name="assign_ID" id="assign_ID"
                                    value="<?php echo e($ticket->assign_to ?? ''); ?>">
                            </div>
                        </div>

                    </div>

                    <div class="row" style="background:lightgrey; margin-top: 15px;">

                        <div class="col-md-2">
                            <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
                        </div>
                        <div class="col-md-2">
                            <input type="checkbox" name="more_ticket" value="1" id="more_ticket" class="ml-2">
                            <label>Create more Ticket</label>
                        </div>
                        <div class="col-md-2">
                            <span id="send_customer">
                                <input type="checkbox" name="send_customer" value="1" id="more_ticket"
                                    class="ml-2">
                                <label>Mail To Customer</label>
                            </span>
                        </div>
                        <div class="col-md-2">
                            <input type="checkbox" name="is_global" id="is_global" class="ml-2">
                            <label>Global</label>
                        </div>
                        <div class="col-md-2">
                            <input type="checkbox" name="closed"
                                <?php echo e(($ticket->is_closed ?? '') == 'on' ? 'checked' : ''); ?> id="closed" class="ml-2">
                            <label>Close Ticket</label>
                        </div>
                        <div class="col-md-2">
                            <input type="checkbox" name="is_billable"
                                <?php echo e(($ticket->is_billable ?? '') == 'on' ? 'checked' : ''); ?> id="closed" class="ml-2">
                            <label>Billable</label>
                        </div>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-script'); ?>
    <script src="<?php echo e(asset('public/assets/plugins/dropify/js/dropify.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/pages/forms/dropify.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/sw.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/bundles/footable.bundle.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/pages/tables/footable.js')); ?>"></script>
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

    <script>
        $('#related_to').on('change', function() {
            var $relatedOptions = $(this).val();
            $('.related').attr("id", $relatedOptions);
            $('.related_ID').attr("id", `${$relatedOptions}_ID`);
            $relatedOptions_url = `${$relatedOptions}Search`;
            console.log($relatedOptions);
        });

        function deleteFileA(id, num) {
            var x = confirm("Are you sure you want to delete?");
            if (x) {
                var url = '<?php echo e(url('delete/')); ?>';
                deleteFile(url, id, token);
                $('#attRow' + num).html('');
            }
        }
    </script>
    <script>
        function updateDescription() {
            var descriptionMain = document.getElementById('descriptionmain');
            var descriptionInput = document.getElementById('description');
            descriptionInput.value = descriptionMain.innerHTML;
        }
    </script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('body');
        });
    </script>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\app\resources\views/tickets/ticket.blade.php ENDPATH**/ ?>