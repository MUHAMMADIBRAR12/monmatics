<?php $__env->startSection('title', 'Call'); ?>
<?php $__env->startSection('parentPageTitle', 'Crm'); ?>
<?php $__env->startSection('parent_title_icon', 'zmdi zmdi-home'); ?>
<?php $__env->startSection('page-style'); ?>
<?php  use App\Libraries\appLib; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/select2/select2.css')); ?>"/>
<link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/morrisjs/morris.css')); ?>"/>
<link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css')); ?>"/>
<link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')); ?>"/>
<link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css')); ?>"/>
<link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/nouislider/nouislider.min.css')); ?>"/>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="<?php echo e(asset('assets/plugins/dropify/css/dropify.min.css')); ?>"/>
<style>
.input-group-text {
    padding: 0 .75rem;
}

.amount{
    width: 150px;
    text-align: right;
}
.table td{
    padding: 0.10rem;
}
.dropify
{
    width: 200px;
    height: 200px;
}
</style>
<script lang="javascript/text">
var contactURL = "<?php echo e(url('contactsSearch')); ?>";
var userURL = "<?php echo e(url('userSearch')); ?>";
var token =  "<?php echo e(csrf_token()); ?>";
var url="<?php echo e(url('')); ?>";
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2>Call</h2>
            </div>
            <?php if(session()->has('message')): ?>
                <div class="alert alert-success">
                    <?php echo e(session()->get('message')); ?>

                </div>
            <?php endif; ?>
            <div class="body">
                <form method="post" action="<?php echo e(url('Crm/Calls/Add')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?> 
                    <input type="hidden" name="id" value="<?php echo e($call->id ?? ''); ?>">
                    <input type="hidden" name="backURL" value="<?php echo e($backURL ?? ''); ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Subject</label>
                            <div class="form-group">
                                <input type="text" name="subject" class="form-control" value="<?php echo e($call->subject ?? ''); ?>" placeholder="Subject"  required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="category">Status</label>
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select" >
                                    <option value="">Select Status</option>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statuses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($statuses->description); ?>" <?php echo e(( $statuses->description == ( $call->status ?? '')) ? 'selected' : ''); ?> ><?php echo e($statuses->description); ?></option>
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
                                        <select name="related_to_type" id="related_to" class=" form-control show-tick ms select2" >
                                            <option value="">Select Type</option>
                                        <?php
                                        $related=appLib::$related_to;
                                        ?>
                                        <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related_to): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($related_to); ?>" <?php echo e(( $related_to == ( $call->related_to_type ?? $relatedTo ?? '' )) ? 'selected' : ''); ?> ><?php echo e($related_to); ?></option>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                </div>
                                <div class="col-sm-8 col-md-8">
                                    <input type="text" name="related" class="form-control related" id="<?php echo e($call->related_to_type ?? ''); ?>"  value="<?php echo e(isset($relatedToInfo) ? $relatedToInfo->name : ''); ?>" placeholder="Search" onkeyup="autoFill(this.id, url+'/'+this.id+'Search', token);">


                                    <input type="hidden" name="related_ID" class="related_ID" id="<?php echo e((isset($call->related_to_type)) ? $call->related_to_type.'_ID':''); ?>" value="<?php echo e($relatedToInfo->id ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="code">Contact Name</label>
                                <div class="form-group">
                                    <input type="text" name="contact" id="contact" class="form-control" value="<?php echo e($call->contact_name ?? ''); ?>" placeholder="Contact" onkeyup="autoFill(this.id, contactURL, token)" >
                                    <input type="hidden" name="contact_ID" id="contact_ID" value="<?php echo e($call->contact_id ?? ''); ?>">
                                </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="email">Start Date</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                            if(isset($call))
                                            {
                                            $s_date=appLib:: setDateFormat($call->start_date);
                                            $s_hour=appLib:: getHour($call->start_date);
                                            $s_minutes=appLib:: getMinutes($call->start_date);
                                            $e_date=appLib:: setDateFormat($call->end_date);
                                            $e_hour=appLib:: getHour($call->end_date);
                                            $e_minutes=appLib:: getMinutes($call->end_date);
                                            }
                                        ?>

                                        <input type="date" name="start_date"   class="form-control" value="<?php echo e($s_date ?? date('Y-m-d')); ?>"  >
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <select name="s_hour" id="s_hour" class=" form-control show-tick ms select2" >
                                            <option value="">Hrs</option>
                                        <?php
                                        $hours=appLib::$hours;
                                        ?>
                                        <?php $__currentLoopData = $hours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($hour); ?>" <?php echo e(( $hour == ( $s_hour ?? '')) ? 'selected' : ''); ?> ><?php echo e($hour); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <select name="s_minute" id="s_minute" class=" form-control show-tick ms select2" >
                                            <option value="">Min</option>
                                        <?php
                                        $minutes=appLib::minutes();
                                        ?>
                                        <?php $__currentLoopData = $minutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $minute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($minute); ?>" <?php echo e(( $minute == ( $s_minutes ?? '')) ? 'selected' : ''); ?> ><?php echo e($minute); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email">End Date</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="date" name="end_date"   class="form-control" value="<?php echo e($e_date ?? date('Y-m-d')); ?>"  >
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <select name="e_hour" id="e_hour" class=" form-control show-tick ms select2" >
                                            <option value="">Hrs</option>
                                        <?php
                                        $hours=appLib::$hours;
                                        ?>
                                        <?php $__currentLoopData = $hours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($hour); ?>" <?php echo e(( $hour == ( $e_hour ?? '' ) ) ? 'selected' : ''); ?> ><?php echo e($hour); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <select name="e_minute" id="e_minute" class=" form-control show-tick ms select2" >
                                            <option value="">Min</option>
                                        <?php
                                        $minutes=appLib::minutes();
                                        ?>
                                        <?php $__currentLoopData = $minutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $minute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($minute); ?>" <?php echo e(( $minute == ( $e_minutes ?? '')) ? 'selected' : ''); ?> ><?php echo e($minute); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                        <label for="category">Communication Type</label>
                            <div class="form-group">
                                <select name="communication_type" class="form-control show-tick ms select2" data-placeholder="Select" >
                                    <option value="">Select Communication Type</option>
                                    <?php $__currentLoopData = $communication_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type->description); ?>" <?php echo e(( $type->description == ( $call->communication_type ?? '')) ? 'selected' : ''); ?> ><?php echo e($type->description); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email">Assign To</label>
                            <div class="form-group">
                                <input type="text" name="assign" id="assign" class="form-control" value="<?php echo e($call->user_name ?? ''); ?>" placeholder="Contact" onkeyup="autoFill(this.id, userURL, token)">
                                <input type="hidden" name="assign_ID" id="assign_ID" value="<?php echo e($call->assigned_to ?? ''); ?>" >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <label for="fax">Description</label>
                            <div class="form-group">
                                <textarea name="description" rows="4" class="form-control no-resize" placeholder="Description"> <?php echo e($call->description ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
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

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\app\resources\views/crm/calls.blade.php ENDPATH**/ ?>