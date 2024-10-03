<?php $__env->startSection('title', 'Note'); ?>
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
        var userURL = "<?php echo e(url('userSearch')); ?>";
        var token = "<?php echo e(csrf_token()); ?>";
        var url = "<?php echo e(url('')); ?>";
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2>Note</h2>
            </div>
            <?php if(session()->has('message')): ?>
                <div class="alert alert-success">
                    <?php echo e(session()->get('message')); ?>

                </div>
            <?php endif; ?>
            <div class="body">
                <form method="post" action="<?php echo e(url('Crm/Notes/Add')); ?>" enctype="multipart/form-data">
                    <?php echo e(csrf_field()); ?>

                    <input type="hidden" name="id" value="<?php echo e($note->id ?? ''); ?>">
                    <input type="hidden" name="backURL" value="<?php echo e($backURL ?? ''); ?>">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Subject</label>
                            <div class="form-group">
                                <input type="text" name="subject" class="form-control"
                                    value="<?php echo e($note->subject ?? ''); ?>" placeholder="Subject" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Related To</label>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <select name="related_to_type" id="related_to"
                                        class=" form-control show-tick ms select2">
                                        <option value="">Select Type</option>
                                        <?php
                                            $related = appLib::$related_to;
                                        ?>
                                        <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related_to): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($related_to); ?>" <?php echo e(( $related_to == ( $note->related_to_type ?? $relatedTo ?? '' )) ? 'selected' : ''); ?> ><?php echo e($related_to); ?></option>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-sm-8 col-md-8">
                                    <input type="text" name="related" class="form-control related" id="<?php echo e($note->related_to_type ?? ''); ?>" value="<?php echo e(isset($relatedToInfo) ? $relatedToInfo->name : ''); ?>" placeholder="Search" onkeyup="autoFill(this.id, url+'/'+this.id+'Search', token);">
                                    <input type="hidden" name="related_ID" class="related_ID" id="<?php echo e((isset($note->related_to_type)) ? $note->related_to_type.'_ID':''); ?>" value="<?php echo e(isset($relatedToInfo) ? $relatedToInfo->id : ''); ?>">
                                </div>
                                
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="email">Assign To</label>
                            <div class="form-group">
                                <input type="text" name="assign" id="assign" class="form-control"
                                    value="<?php echo e($note->user_name ?? ''); ?>" placeholder="Contact"
                                    onkeyup="autoFill(this.id, userURL, token)">
                                <input type="hidden" name="assign_ID" id="assign_ID"
                                    value="<?php echo e($note->assigned_to ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="fax">Description</label>
                            <div class="form-group">
                                <textarea name="description" rows="4" class="form-control no-resize" placeholder="Description"> <?php echo e($note->description ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class=" ml-auto">
                            <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\app\resources\views/crm/note.blade.php ENDPATH**/ ?>