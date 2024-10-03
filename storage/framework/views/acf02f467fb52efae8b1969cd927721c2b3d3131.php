<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" href="<?php echo e(asset('public/tablogo.png')); ?>" type="image/x-icon"> <!-- Favicon-->
    <title><?php echo e(config('app.name')); ?> - <?php echo $__env->yieldContent('title'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', config('app.name')); ?>">
    <meta name="author" content="<?php echo $__env->yieldContent('meta_author', config('app.name')); ?>">
    <?php echo $__env->yieldContent('meta'); ?>
    
    <?php echo $__env->yieldPushContent('before-styles'); ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  
    
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <?php if(trim($__env->yieldContent('page-style'))): ?>
    <?php echo $__env->yieldContent('page-style'); ?>
    <?php endif; ?>
    <!-- Custom Css -->
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/css/style.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/css/sw.css')); ?>">
    <?php echo $__env->yieldPushContent('after-styles'); ?>

    <style>
        /* Styles for light theme */
        .light-theme {
            background-color: #f5f5f5;
            color: #333;
        }

        /* Styles for dark theme */
        .dark-theme {
            background-color: #333;
            color: #f5f5f5;
        }
        a{
            text-decoration: none
        }
        .dt-buttons{
            gap: 6px;
        }
        .btn-icon{
            float: inline-end;
        }
    </style>



</head>
<?php
$setting = !empty($_GET['theme']) ? $_GET['theme'] : '';
$theme = 'theme-blush';
$menu = '';
if ($setting == 'p') {
    $theme = 'theme-purple';
} elseif ($setting == 'b') {
    $theme = 'theme-blue';
} elseif ($setting == 'g') {
    $theme = 'theme-green';
} elseif ($setting == 'o') {
    $theme = 'theme-orange';
} elseif ($setting == 'bl') {
    $theme = 'theme-cyan';
} else {
    $theme = 'theme-blush';
}

if (Request::segment(2) === 'rtl') {
    $theme .= ' rtl';
}
?>


<body class="ls-toggle-menu" >



    
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img class="zmdi-hc-spin" src="<?php echo e(asset('public/assets/images/loading.png')); ?>"
                    height="90px" alt="OfDesk"></div>
            <p>Processing...</p>
        </div>
    </div>
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <?php echo $__env->make('layout.navbarright', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('layout.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('layout.rightsidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="content">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2><?php echo $__env->yieldContent('title'); ?></h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard.index')); ?>"><i
                                    class="zmdi zmdi-home"></i> Monmatics</a></li>
                        <?php if(trim($__env->yieldContent('parentPageTitle'))): ?>
                        <li class="breadcrumb-item"><?php echo $__env->yieldContent('parentPageTitle'); ?></li>
                        <?php endif; ?>
                        <?php if(trim($__env->yieldContent('title'))): ?>
                        <li class="breadcrumb-item active"><?php echo $__env->yieldContent('title'); ?></li>
                        <?php endif; ?>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i
                            class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i
                            class="zmdi zmdi-arrow-right"></i></button>
                </div>
            </div>
        </div>
        <div class="container-fluid">

            <?php echo $__env->yieldContent('content'); ?>
        </div>
        <!--   <div>
                <h3>Powered by Solutions Wave</h3>
            </div>  -->
    </section>
    <?php echo $__env->yieldContent('modal'); ?>
    <!-- Scripts -->
    <?php echo $__env->yieldPushContent('before-scripts'); ?>

    <script src="<?php echo e(asset('public/assets/bundles/libscripts.bundle.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/bundles/vendorscripts.bundle.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/bundles/mainscripts.bundle.js')); ?>"></script>

    <script src="<?php echo e(asset('public/assets/plugins/fullcalendar/jqueryui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/jquery-ui.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('after-scripts'); ?>
    <?php if(trim($__env->yieldContent('page-script'))): ?>
    <script>
            var Tawk_API = Tawk_API || {};

    </script>
    <?php echo $__env->yieldContent('page-script'); ?>
    <?php endif; ?>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\app\resources\views/layout/master.blade.php ENDPATH**/ ?>