<?php $__env->startSection('title', 'Company'); ?>
<?php $__env->startSection('parentPageTitle', 'Solutions Wave'); ?>
<?php $__env->startSection('parent_title_icon', 'zmdi zmdi-home'); ?>
<?php $__env->startSection('page-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/dropify/css/dropify.min.css')); ?>" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Company</strong> Information</h2>
            </div>
            <?php if(session()->has('message')): ?>
                <div class="alert alert-success">
                    <?php echo e(session()->get('message')); ?>

                </div>
            <?php endif; ?>
            <div class="body">
                <form method="post" action="<?php echo e(url('Admin/Company/Add')); ?>" enctype="multipart/form-data">
                    <?php echo e(csrf_field()); ?>

                    <input type="hidden" name="id" value="<?php echo e($company->id ?? ''); ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Company Name</label>
                            <div class="form-group">
                                <input type="text" name="name" class="form-control"
                                    value="<?php echo e(old('name', $company->name ?? '')); ?>" required>
                            </div>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="website">Website</label>
                            <div class="form-group">
                                <input type="text" name="website" class="form-control"
                                    value="<?php echo e(old('website', $company->website ?? '')); ?>" required>
                            </div>
                            <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="phone">Phone</label>
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control"
                                    value="<?php echo e(old('phone', $company->phone ?? '')); ?>" required>
                            </div>
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control"
                                    value="<?php echo e(old('email', $company->email ?? '')); ?>" required>
                            </div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="address">Address</label>
                            <div class="form-group">
                                <input type="text" name="address" class="form-control"
                                    value="<?php echo e(old('address', $company->address ?? '')); ?>" required>
                            </div>
                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="fax">Fax</label>
                            <div class="form-group">
                                <input type="text" name="fax" class="form-control"
                                    value="<?php echo e(old('fax', $company->fax ?? '')); ?>" required>
                            </div>
                            <?php $__errorArgs = ['fax'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="state">State</label>
                            <div class="form-group">
                                <input type="text" name="state" class="form-control"
                                    value="<?php echo e(old('state', $company->state ?? '')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="address_two">Address 2</label>
                            <div class="form-group">
                                <input type="text" name="address_two" class="form-control"
                                    value="<?php echo e(old('address_two', $company->address_two ?? '')); ?>" required>
                            </div>
                            <?php $__errorArgs = ['address_two'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="currency">Currency</label>
                            <div class="form-group">
                                <select name="currency" class="form-control show-tick ms select2" data-placeholder="Select"
                                    required>
                                    <option value="">-- Select Currency --</option>
                                    <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($currency->code); ?>"
                                            <?php echo e(old('currency', isset($company) ? $company->currency : '') == $currency->code ? 'selected' : ''); ?>>
                                            <?php echo e($currency->code); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="country">Country</label>
                            <div class="form-group">
                                <select name="country" class="form-control show-tick ms select2" data-placeholder="Select"
                                    required>
                                    <option value="">-- Select Country --</option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country->name); ?>"
                                            <?php echo e(old('country', isset($company) ? $company->country : '') == $country->name ? 'selected' : ''); ?>>
                                            <?php echo e($country->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="tax_number">Tax Number</label>
                            <div class="form-group">
                                <input type="text" name="tax_number"
                                    value="<?php echo e(old('tax_number', $company->tax_number ?? '')); ?>" class="form-control">
                            </div>
                            <?php $__errorArgs = ['tax_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check mt-4">
                                <input class="form-check-input" name="multi_currency" type="checkbox"
                                    <?php echo e($multiCurrencyChecked ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="defaultCheck1">
                                    Multi Currency
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="fiscal_year">Fiscal Year</label>
                            <div class="form-group">
                                <select name="fiscal_year" class="form-control show-tick ms select2"
                                    data-placeholder="Select" required>
                                    <option value="">Select Month</option>
                                    <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($month->month); ?>"
                                            <?php echo e(old('fiscal_year', isset($company) ? $company->fiscal_year : '') == $month->month ? 'selected' : ''); ?>>
                                            <?php echo e($month->month); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="attachment">Logo</label>
                            <div class="form-group">
                                <?php if(isset($attachment)): ?>
                                    <div class="form-group" id='attRow<?php echo e($i); ?>'>
                                        <button type="button" class="btn btn-danger btn-sm attachment-btn"
                                            id="<?php echo e($company->logo); ?>"
                                            onclick="deleteFileA('<?php echo e($company->id); ?>', <?php echo e($i); ?>)"><i
                                                class="zmdi zmdi-delete"></i></button>
                                        <img src="<?php echo e(url('display/' . $attachment->id)); ?>">
                                    </div>
                                    <script>
                                        $("#logo:visible").hide()
                                    </script>
                                <?php endif; ?>
                                <input name="file" id="logo" type="file" class="dropify">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="fiscal_year">Currency Format</label>
                                    <div class="form-group">
                                        <select name="fiscal_year" class="form-control show-tick ms select2"
                                            data-placeholder="Select" required>
                                            <option value="">Select Format</option>
                                            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($month->month); ?>"
                                                    <?php echo e(old('fiscal_year', isset($company) ? $company->fiscal_year : '') == $month->month ? 'selected' : ''); ?>>
                                                    <?php echo e($month->month); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fiscal_year">Date Format</label>
                                    <div class="form-group">
                                        <select name="date_format" class="form-control show-tick ms select2"
                                            data-placeholder="Select" required>
                                            <option value="">Select Month</option>
                                                <option value="M-d-Y">Month-day-Year</option>
                                                <option value="d-M-Y">day-Month-Year</option>
                                                <option value="Y-m-d">Year-month-day</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-md-6">
                                        <label for="fiscal_year">&nbsp;</label>
                                        <div class="form-group">
                                            <select class="form-control show-tick ms select2" data-placeholder="Select"
                                                required>
                                                <option value="">Select Month</option>
                                                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <option value="<?php echo e($month->month); ?>"
                                                        <?php echo e($month->month == ($company->fiscal_year ?? '') ? 'selected' : ''); ?>>
                                                        <?php echo e($month->month); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div> -->
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="ml-auto">
                            <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Model For Delete -->
    <div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center">
                    <span class="text-danger" id="exampleModalLongTitle">Are You Want to Delete This Logo?</span>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <a class="btn btn-secondary" data-dismiss="modal">No</a>
                    <a class="btn btn-success model-delete attach-del" data-dismiss="modal">Yes</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Model For Delete -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-script'); ?>
    <script src="<?php echo e(asset('public/assets/plugins/dropify/js/dropify.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/pages/forms/dropify.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/sw.js')); ?>"></script>
    <script>
        var attachmentURL = "<?php echo e(url('companyAttachDelete')); ?>";
        var token = "<?php echo e(csrf_token()); ?>";
        $('document').ready(function() {
            $('.attachment-btn').click(function() {
                var img = $(this).attr('id');
                $('.attach-del').click(function() {
                    $.post(attachmentURL, {
                        img: img,
                        _token: token
                    }, function(data) {
                        location.reload(true);
                    });
                });
            });
        });

        function deleteFileA(id, num) {
            var x = confirm("Are you sure you want to delete?");
            if (x) {
                var url = '<?php echo e(url('delete/')); ?>';
                deleteFile(url, id, token);
                $('#attRow' + num).html('');
                $("#logo:visible").show()
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\app\resources\views/system/company.blade.php ENDPATH**/ ?>