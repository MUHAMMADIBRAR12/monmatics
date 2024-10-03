<?php $__env->startSection('title', 'Login'); ?>
<?php $__env->startSection('content'); ?>

<style>
    .header .logo{
        width: 160px !important;
    }
    .im1{

    }
    .im1 {
  height:600px;
  background-image: url("<?php echo e(asset('public/assets/images/backend-1234.png')); ?>");
  background-repeat: round;
  margin-top:5px;
 

}
.body {
  height:65%;
  /* height: auto; */
  width:72%;
  /* border: 3px solid #f1f1f1; */
  background-color: white;
  border-radius: 20px;
  /* margin-left: 10%;  */
  margin-top: 1%;
}
.input{

width:80%;
margin: auto;
}
.input-group{
  margin-top: 10%;
  margin-left:20%;
}
.psd{
    display: flex;
    justify-content:space-between;
    /* border: 1px solid #000; */
    width:100%;
    margin-left:20%;

}
.btn{
    margin-left:"20px";
}

</style>

<div class="">
    <div class="im1">
    <div class=" col-lg-6  ">
          <h1 class="text-white ml-5">Welcome back!</h1>
          <p class="text-white ml-5">Log In to your account to keep connected with us</p>
        </div>
    <div class="col-lg-5   col-md-8   col-sm-12"  style="height:60px">

        <form class="card auth_form ml-5" method="POST" action="authenticate" style="  border-radius: 20px;" >
             <!-- <?php echo e(@csrf_field()); ?> -->
            <div class="header">
                <!-- <img class="logo" src="<?php echo e(asset('public/assets/images/logo2.png')); ?>" alt=""> -->
                <h1 class="blue mt-5">Log in</h1>
                <?php if(Session::has('message')): ?>
	            <div class="text-danger"><?php echo e(Session::get('message')); ?></div>
                <?php endif; ?>
            </div>
            <div class="body">
                <div class="input-group mb-3">
                    <input type="email" name='email' class="form-control" placeholder="Email" required>
                    <div class="input-group-append">
                        <!-- <span class="input-group-text"><i class="zmdi zmdi-account-circle"></i></span> -->
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name='password' class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <!-- <span class="input-group-text"><i class="zmdi zmdi-lock"></i></span> -->
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="d-flex  psd">
                    <div class="checkbox">
                        <input id="remember_me" name="remember" type="checkbox">
                        <label for="remember_me">Remember Me</label>
                    </div>
                   
              </div>
                    <div class="input-group mb-3   btn">
                        <button type="submit" class="btn  rounded-pill btn-warning" style="width: 150px">
                            <?php echo e(__('Signin')); ?>

                        </button>
                        <div class=" mt-1">
                <a class="" href="">Forgot password.?</a>
              </div>
                    </div>
                </form>

            </div>
        </form>
    </div>
</div>
</div>
</div>
    <!-- <div class="col-lg-8 col-sm-12">
        <div class="card">
            <img src="<?php echo e(asset('public/assets/images/signin.svg')); ?>" alt="Log In" />
        </div>
    </div>
</div> -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('web.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\app\resources\views/authentication/login.blade.php ENDPATH**/ ?>