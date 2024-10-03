<?php $__env->startSection('title', 'Tasks'); ?>
<?php $__env->startSection('parentPageTitle', 'Crm'); ?>
<?php $__env->startSection('parent_title_icon', 'zmdi zmdi-home'); ?>
<?php $__env->startSection('page-style'); ?>
    <?php use App\Libraries\appLib; ?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/select2/select2.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/morrisjs/morris.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/nouislider/nouislider.min.css')); ?>" />
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/dropify/css/dropify.min.css')); ?>" />
    <style>
        .input-group-text {
            padding: 0 .75rem;
        }

        .amount {
            width: 150px;
            text-align: right;
        }

        .table td {
            padding: 0.10rem;
        }

        .dropify {
            width: 200px;
            height: 200px;
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

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2>Tasks</h2>
            </div>
            <?php if(session()->has('message')): ?>
                <div class="alert alert-success">
                    <?php echo e(session()->get('message')); ?>

                </div>
            <?php endif; ?>
            <div class="body">
                <form method="post" action="<?php echo e(url('Crm/TasksSave')); ?>" enctype="multipart/form-data">
                    <?php echo e(csrf_field()); ?>

                    <input type="hidden" name="id" value="<?php echo e($task->id ?? ''); ?>">

                    <input type="hidden" name="backURL" value="<?php echo e($backURL); ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Subject</label>
                            <div class="form-group">
                                <input type="text" name="subject" class="form-control"
                                    value="<?php echo e($task->subject ?? old('subject')); ?>" placeholder="Subject" required>
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="category">Status</label>
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">Select Status</option>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($status->description); ?>"
                                            <?php echo e($status->description == ($task->status ?? '') ? 'selected' : ''); ?>>
                                            <?php echo e($status->description); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Related To</label>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">

                                    <select name="related_to_type" id="related_to"
                                        class=" form-control show-tick ms select2" required>
                                        <option value="" selected disabled>Select Type</option>
                                        <?php
                                            $relatedTo = $priority ? 'priority' : null;
                                    
                                            $relatedOptions = appLib::$related_to;
                                        ?>
                                        <?php $__currentLoopData = $relatedOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related_to): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($related_to); ?>"
                                            <?php echo e($related_to == $relatedTo ? 'selected' : ''); ?>

                                            <?php echo e($related_to ==  ($task->related_to_type ?? '')   ? 'selected' : ''); ?>>
                                                <?php echo e($related_to); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-sm-8 col-md-8">
                                    <input type="text" name="related" class="form-control related"
                                        id="<?php echo e($task->related_to_type ?? $relatedTo); ?>"
                                        value="<?php echo e(isset($relatedToInfo) ? $relatedToInfo->id : (isset($projectId) ? $project->name : '')); ?> <?php echo e($project->name ?? ''); ?> <?php echo e($task->contact_username ?? ''); ?>  <?php echo e($task->projectname ?? ''); ?> <?php echo e($task->customer_name ?? ''); ?> <?php echo e($task->cusname ?? ''); ?>"
                                        placeholder="Search" onkeyup="autoFill(this.id, url+'/'+this.id+'Search', token);">
                                    <input type="hidden" name="related_ID" class="related_ID"
                                        id="<?php echo e(isset($task->related_to_type) ? $task->related_to_type . '_ID' : $relatedTo .'_ID'); ?>"
                                        value="<?php echo e(isset($relatedToInfo) ? $relatedToInfo->id : (isset($task->related_id) ? $task->related_id : '')); ?> <?php echo e($project->id ?? ''); ?>" >
                                </div>


                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="code">Contact Name</label>
                            <div class="form-group">
                                <input type="text" name="contact" id="contact" class="form-control"
                                    value="<?php echo e($task->contact_name ?? ''); ?>" placeholder="Contact"
                                    onkeyup="autoFill(this.id, contactURL, token)">
                                <input type="hidden" name="contact_ID" id="contact_ID"
                                    value="<?php echo e($task->contact_id ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="note">Start Date</label>
                            <div class="form-group">
                                <input type="date" name="start_date" class="form-control"
                                    value="<?php echo e($task->start_date ?? date('Y-m-d')); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="note">Due Date</label>
                            <div class="form-group">
                                <input type="date" name="due_date" class="form-control"
                                    value="<?php echo e($task->due_date ?? date('Y-m-d')); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="location">Priority</label>
                            <select name="priority" class="form-control show-tick ms select2" data-placeholder="Select">
                                <option value="">Select Priority</option>
                                <?php $__currentLoopData = $priority; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($priority->description); ?>"
                                        <?php echo e($priority->description == ($task->priority ?? '') ? 'selected' : ''); ?>>
                                        <?php echo e($priority->description); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="email">Assign To</label>
                            <div class="form-group">
                                <input type="text" name="assign" id="assign" class="form-control"
                                    value="<?php echo e($task->user_name ?? ''); ?>" placeholder="Contact"
                                    onkeyup="autoFill(this.id, userURL, token)">
                                <input type="hidden" name="assign_ID" id="assign_ID"
                                    value="<?php echo e($task->assigned_to ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="fax">Description</label>
                            <div class="form-group">
                                <div class="form-group">
                                    <textarea name="description" rows="4" class="form-control no-resize" placeholder="Description"><?php echo e($task->description ?? ''); ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for=""></label>
                                <div class="form-group">
                                    <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
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
    <script>
        $('#related_to').on('change', function() {
            var $relatedOptions = $(this).val();
            $('.related').attr("id", $relatedOptions);
            $('.related_ID').attr("id", `${$relatedOptions}_ID`);
            $relatedOptions_url = `${$relatedOptions}Search`;
            console.log($relatedOptions);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\app\resources\views/crm/tasks.blade.php ENDPATH**/ ?>