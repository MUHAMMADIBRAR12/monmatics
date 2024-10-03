<?php $__env->startSection('title', isset($profile) ? 'User Profile' : 'Add User Information'); ?>
<?php $__env->startSection('parentPageTitle', 'Admin'); ?>
<?php $__env->startSection('page-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/dropify/css/dropify.min.css')); ?>" />
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script>
        var verifyEmailURL = "<?php echo e(url('Admin/Users/aj_verifyEmail')); ?>";
        var token = "<?php echo e(csrf_token()); ?>";
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row clearfix">
        <?php if(isset($profile)): ?>
            <!-- section of user prfile start -->

            <?php if(Auth::user()->id == $userData->id): ?>
                <div class="col-lg-4 col-md-12">
                    <div class="card mcard_3">
                        <div class="body">
                            <a href="profile.html"><img src="<?php echo e(url('display/' . $userData->id)); ?>"
                                    class="rounded-circle shadow " alt="profile-image"></a>
                            <h4 class="m-t-10 mb-0 text-secondary"><?php echo e($userData->firstName); ?> <?php echo e($userData->lastName); ?></h4>
                            <span class="mb-2 text-success"><?php echo e($userData->role); ?></span>
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-muted"><?php echo e($userData->email); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="card cols text-center">
                        <div class="body p-2">
                            <span class="text-primary text-bold" style="cursor:pointer">Update User Profile and
                                Password</span>
                        </div>
                    </div>
                    <div class="card collapse">
                        <div class="body pb-5">
                            <?php if(Session::has('msg')): ?>
                                <div class="alert alert-danger"><?php echo e(Session::get('msg')); ?></div>
                            <?php endif; ?>
                            <form id="form_advanced_validation" action="<?php echo e(url('Admin/UserProfile/Update/' . $profile)); ?>"
                                method="post" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo e($userData->id ?? ''); ?>">
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <label for="">Password</label>

                                            <input type="password" name="password" id="password" class="form-control"
                                                placeholder="password.." />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Confirm Password</label>
                                            <input type="password" name="cnfrmPassword" id="confirmPassword"
                                                class="form-control" placeholder="cnfrm password.." />
                                            <span id="message" style="color:red"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="modules">Module</label>
                                        <div class="form-group">
                                            <select name="module" id="module"
                                                class="form-control show-tick ms select2" data-placeholder="Select"
                                                required>
                                                <option value="">-- Select Module --</option>
                                                <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($modules->mdl_id); ?>"><?php echo e($modules->module); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" id="childModule">
                                        <label for="modules">Child Module</label>
                                        <div class="form-group">
                                            <select name="route" id="role"
                                                class="form-control show-tick ms select2" data-placeholder="Select"
                                                required>
                                                <option value="">-- Select Module --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-10">
                                        <label for="modules">Profile Image</label>
                                        <input type="file" name="profile" class="dropify">
                                    </div>
                                </div>
                                <div class="row clearfix m-auto float-right">
                                    <button class="btn btn-success save">Save</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-danger">You are not authorized to update Other's profile...</h5>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- section of user prfile end -->
        <?php else: ?>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div>
                            <?php if(Session::has('msg')): ?>
                                <div class="alert alert-danger"><?php echo e(Session::get('msg')); ?></div>
                            <?php endif; ?>
                            <form id="form_advanced_validation" action="<?php echo e(url('Admin/Users/Add')); ?>" method="post"
                                class="form  my-3" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php if(isset($userData)): ?>
                                    <input type="hidden" name="id" value="<?php echo e($userData->id ?? ''); ?>">
                                <?php endif; ?>
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <label for="">First Name <?php echo e($user['fname'] ?? ''); ?></label>
                                            <input type="text" name="fname" class="form-control"
                                                placeholder="First Name.."
                                                value="<?php echo e(old('fname', $userData->firstName ?? '')); ?>" maxlength="10"
                                                minlength="3" required />
                                            <?php $__errorArgs = ['fname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-danger"><?php echo e(str_replace('fname', 'Name', $message)); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>


                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <label for="">Last Name</label>
                                            <input type="text" name="lname" class="form-control"
                                                placeholder="Last Name.."
                                                value="<?php echo e(old('lname', $userData->lastName ?? '')); ?>" maxlength="10"
                                                minlength="3" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <label for="">User Email</label>
                                            <input type="email" name="email" id="email"
                                                <?php echo e(isset($userData->email) ? ' ' : 'onblur=verifyEmail(this.value,verifyEmailURL,token)'); ?>

                                                class="form-control" placeholder="Email.."
                                                value="<?php echo e(old('email', $userData->email ?? '')); ?>" required />
                                            <!-- <span id="email-error" style="color:red"></span> -->
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
                                    <div class="col-sm-4">
                                        <label for="modules">Roles</label>
                                        <div class="form-group">
                                            <select name="role" id="role"
                                                class="form-control show-tick ms select2" data-placeholder="Select"
                                                required>
                                                <option value="">-- Select Role --</option>
                                                <?php $__currentLoopData = $roleName; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $selected = '';
                                                        if (old('role') == $name->name) {
                                                            $selected = 'selected';
                                                        } elseif (isset($userData) && $userData->role == $name->name) {
                                                            $selected = 'selected';
                                                        }
                                                    ?>
                                                    <option value="<?php echo e($name->name); ?>" <?php echo e($selected); ?>>
                                                        <?php echo e($name->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="modules">Status</label>
                                        <div class="form-group">
                                            <select name="status" id="status"
                                                class="form-control show-tick ms select2" data-placeholder="Select"
                                                required>
                                                <option value="" selected disabled>-- Status --</option>
                                                <option value="0"
                                                    <?php echo e(old('status', $userData->status ?? '') == 0 ? 'selected' : ''); ?>>
                                                    Suspended</option>
                                                <option value="1"
                                                    <?php echo e(old('status', $userData->status ?? '') == 1 ? 'selected' : ''); ?>>
                                                    Active</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <label for="">Password</label>

                                            <input type="password" name="password" id="password" class="form-control"
                                                placeholder="password.."
                                                <?php echo e(isset($userData->email) ? ' ' : '  required'); ?> />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Confirm Password</label>
                                            <input type="password" name="cnfrmPassword" id="confirmPassword"
                                                class="form-control" placeholder="Confirm password.." />
                                            <span id="message" style="color:red"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="modules">Module</label>
                                        <div class="form-group">
                                            <select name="module" id="module"
                                                class="form-control show-tick ms select2" data-placeholder="Select"
                                                required>
                                                <option value="">-- Select Module --</option>
                                                <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($modules->id); ?>"><?php echo e($modules->module); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" id="childModule">
                                        <label for="modules">Child Module</label>
                                        <div class="form-group">
                                            <select name="route" id="role"
                                                class="form-control show-tick ms select2" data-placeholder="Select"
                                                required>
                                                <option value="">-- Select Module --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-6">
                                        <label for="">Select Your Company</label>
                                        <?php if(isset($user_companies)): ?>
                                            <?php $__currentLoopData = $user_companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user_company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="list-unstyled mb-2"> <input type="checkbox"
                                                        name="company_id[]" value="<?php echo e($user_company->id); ?>"
                                                        <?php echo e($user_company->company_id ? 'checked' : ''); ?>>
                                                    <?php echo e($user_company->name); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="list-unstyled mb-2"> <input type="checkbox"
                                                        name="company_id[]" value="<?php echo e($company->id); ?>">
                                                    <?php echo e($company->name); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="modules">Profile Image</label>
                                        <input type="file" name="profile" class="dropify">
                                    </div>
                                </div>
                                <div class="row clearfix m-auto float-right">
                                    <button class="btn btn-success save" type="submit" id="save">Save</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- list table end -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-script'); ?>
    <script src="<?php echo e(asset('public/assets/bundles/footable.bundle.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/pages/tables/footable.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/plugins/jquery-validation/jquery.validate.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/plugins/jquery-steps/jquery.steps.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/pages/forms/form-validation.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/plugins/dropify/js/dropify.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/pages/forms/dropify.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/sw.js')); ?>"></script>
    <script>
        function verifyEmail(email, url, token) {
            console.log(email);
            console.log(url);
            console.log(token);
            $.post(url, {
                email: email,
                _token: token
            }, function(data) {
                if (data != "") {
                    $('#email-error').html(data);
                    $('.save').hide();
                }
                $('#email').on('keydown', function(e) {
                    if (e.which == 8 || e.which == 46) {
                        $('#email-error').html('');
                        $('.save').show();
                    }
                });
            });
        }
        $('#confirmPassword').keyup(function() {
            if ($('#password').val() == $('#confirmPassword').val()) {
                $('#message').html('');
                $('.save').show();
            } else {
                $('#message').html('Password Not Match');
                $('.save').hide();
            }
        });
        $('#confirmPassword').on('blur', function() {
            if ($('#confirmPassword').val() == '') {
                $('#message').html('');
                $('.save').show();
            }
        });
        $(".collapse").hide();
        $(".cols").click(function() {
            $(".collapse").slideToggle("slow");
        });
        $("form").submit(function() {
            if ($('#password').val() !== $('#confirmPassword').val()) {
                $('#message').html('Please Confirm the Password');
                return false;

            }
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#module').on('change', function() {
                var module = $('#module').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'post',
                    url: '<?php echo e(url('ModuleGet')); ?>',
                    data: {
                        module: module,
                    },
                    success: function(data) {
                        var child = $('#childModule');
                        var $select = '<div class="col-sm-12" id="childModule">' +
                            '<label for="modules">Child Module</label>' +
                            '<div class="form-group">' +
                            '<select name="route" id="" class="form-control show-tick ms select2" data-placeholder="Select" required>' +
                            '<option value="">-- Select Module --</option>';

                        data.forEach(function(single) {
                            $select += '<option value="' + single.route + '">' + single
                                .module + '</option>';
                        });

                        $select += '</select>' +
                            '</div>' +
                            '</div>';

                        child.html($select);
                    }



                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\app\resources\views/system/userInformation.blade.php ENDPATH**/ ?>