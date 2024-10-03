<!-- Left Sidebar -->

<?php
$user = Auth()->user();
?>

<?php if(Session::get('multi_currency') == 1): ?>
<style>
    .multicurrency {
        display: block !important;
    }
</style>
<?php else: ?>
<style>
    .multicurrency {
        display: none !important;
    }
</style>
<?php endif; ?>



<aside id="leftsidebar" class="sidebar">

    <div class="navbar-brand">
        <button class="btn-menu ls-toggle-btn" type="button"><i class="zmdi zmdi-menu"></i></button>
        <a href="<?php echo e(route('dashboard.index')); ?>"><img src="<?php echo e(asset('public/assets/images/logo2.png')); ?>"
                alt="OfDesk"><span class="m-l-10"></span></a>
    </div>
    <div class="menu">
        <ul class="list">
            <li>
                <summary>
                    <div class="user-info"
                        onclick="window.location.href = '<?php echo e(url('Admin/Users/Create/' . $user->id . '/p')); ?>';">
                        <div class="image"><a href="#"><img src="<?php echo e(url('display/' . $user->id)); ?>" alt="User"></a>
                        </div>
                        <div class="detail">
                            <h4><?php echo e($user->firstName); ?>&nbsp<?php echo e($user->lastName); ?></h4>
                            <small><?php echo e($user->role); ?> </small>
                        </div>
                    </div>
                </summary>
            </li>
            <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="<?php echo e(Request::segment(1) === 'app' ? 'active open' : null); ?>">
                <a href="<?php echo e($module->route); ?>" class="menu-toggle"><i class="zmdi <?php echo e($module->image); ?>"></i>
                    <span><?php echo e($module->module); ?></span></a>
                    <?php if($module->has_child != 0): ?>
                    <ul class="ml-menu ">
                        <?php $__currentLoopData = $module->has_child; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="<?php echo e(Request::is($child->route) ? 'active' : ''); ?>"><a href="<?php echo e(url($child->route)); ?>"><?php echo e($child->module); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        
                    </ul>
                    <?php endif; ?>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <a href="#Blog" class="menu-toggle"><i class="zmdi zmdi-blogger"></i> <span>Blog</span></a>
                    <ul class="ml-menu">
                        <li class="<?php echo e(Request::segment(2) === 'dashboard' ? 'active' : null); ?>"><a href="<?php echo e(route('blog.dashboard')); ?>">Dashboard</a></li>
                        <li class="<?php echo e(Request::segment(2) === 'new-post' ? 'active' : null); ?>"><a href="<?php echo e(route('blog.posts')); ?>">Blog Posts</a></li>
                        
                        
                        
                        <li class="<?php echo e(Request::segment(2) === 'detail' ? 'active' : null); ?>"><a href="<?php echo e(route('blog.category')); ?>">Category</a></li>
    
                    </ul>
                </li>
            <li style="height:70px" class="<?php echo e(Request::segment(1) === 'app' ? 'active open' : null); ?> ">
                &nbsp;&nbsp;
            </li>

     
            <!--

            <li class="<?php echo e(Request::segment(1) === 'form' ? 'active open' : null); ?>">
                <a href="#Form" class="menu-toggle"><i class="zmdi zmdi-assignment"></i><span>System</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'basic' ? 'active' : null); ?>"><a href="<?php echo e(route('form.basic')); ?>">Basic Form</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'advanced' ? 'active' : null); ?>"><a href="<?php echo e(route('form.advanced')); ?>">Advanced Form</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'examples' ? 'active' : null); ?>"><a href="<?php echo e(route('form.examples')); ?>">Form Examples</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'validation' ? 'active' : null); ?>"><a href="<?php echo e(route('form.validation')); ?>">Form Validation</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'wizard' ? 'active' : null); ?>"><a href="<?php echo e(route('form.wizard')); ?>">Form Wizard</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'editors' ? 'active' : null); ?>"><a href="<?php echo e(route('form.editors')); ?>">Editors</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'upload' ? 'active' : null); ?>"><a href="<?php echo e(route('form.upload')); ?>">File Upload</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'summernote' ? 'active' : null); ?>"><a href="<?php echo e(route('form.summernote')); ?>">Summernote</a></li>
                </ul>
            </li>

            <li class="<?php echo e(Request::segment(1) === 'dashboard' ? 'active open' : null); ?>"><a href="<?php echo e(route('dashboard.index')); ?>"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a></li>

            <li class="<?php echo e(Request::segment(1) === 'my-profile' ? 'active open' : null); ?>"><a href="<?php echo e(route('profile.my-profile')); ?>"><i class="zmdi zmdi-account"></i><span>My Profile</span></a></li>
            <li class="<?php echo e(Request::segment(1) === 'app' ? 'active open' : null); ?>">
                <a href="#App" class="menu-toggle"><i class="zmdi zmdi-apps"></i> <span>Accounts</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'inbox' ? 'active' : null); ?>"><a href="<?php echo e(route('app.inbox')); ?>">Chart of Accounts</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'chat' ? 'active' : null); ?>"><a href="<?php echo e(route('app.chat')); ?>">Chat</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'calendar' ? 'active' : null); ?>"><a href="<?php echo e(route('Crm/Calendar')); ?>">Calendar</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'contact-list' ? 'active' : null); ?>"><a href="<?php echo e(route('app.contact-list')); ?>">Contact list</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'contact-list' ? 'active open' : null); ?>">
                        <a href="#App" class="menu-toggle"><i class="zmdi zmdi-apps"></i> <span>App2</span></a>
                        <ul class="ml-menu">
                            <li class="<?php echo e(Request::segment(2) === 'contact-list' ? 'active' : null); ?>"><a href="<?php echo e(route('app.contact-list')); ?>">Contact list3</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="<?php echo e(Request::segment(1) === 'project' ? 'active open' : null); ?>">
                <a href="#Project" class="menu-toggle"><i class="zmdi zmdi-assignment"></i> <span>Inventory</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'project-list' ? 'active' : null); ?>"><a href="<?php echo e(route('project.project-list')); ?>">Project List</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'taskboard' ? 'active' : null); ?>"><a href="<?php echo e(route('project.taskboard')); ?>">Taskboard</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'ticket-list' ? 'active' : null); ?>"><a href="<?php echo e(route('project.ticket-list')); ?>">Ticket List</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'ticket-detail' ? 'active' : null); ?>"><a href="<?php echo e(route('project.ticket-detail')); ?>">Ticket Detail</a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::segment(1) === 'file-manager' ? 'active open' : null); ?>">
                <a href="#FileManager" class="menu-toggle"><i class="zmdi zmdi-folder"></i> <span>Purchase</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'all' ? 'active' : null); ?>"><a href="<?php echo e(route('file-manager.all')); ?>">All</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'documents' ? 'active' : null); ?>"><a href="<?php echo e(route('file-manager.documents')); ?>">Documents</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'media' ? 'active' : null); ?>"><a href="<?php echo e(route('file-manager.media')); ?>">Media</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'image' ? 'active' : null); ?>"><a href="<?php echo e(route('file-manager.image')); ?>">Images</a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::segment(1) === 'blog' ? 'active open' : null); ?>">
                <a href="#Blog" class="menu-toggle"><i class="zmdi zmdi-blogger"></i> <span>Sales</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'dashboard' ? 'active' : null); ?>"><a href="<?php echo e(route('blog.dashboard')); ?>">Dashboard</a></li>

                    <li class="<?php echo e(Request::segment(2) === 'list' ? 'active' : null); ?>"><a href="<?php echo e(route('blog.list')); ?>">List View</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'grid' ? 'active' : null); ?>"><a href="<?php echo e(route('blog.grid')); ?>">Grid View</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'detail' ? 'active' : null); ?>"><a href="<?php echo e(route('blog.detail')); ?>">Blog Details</a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::segment(1) === 'ecommerce' ? 'active open' : null); ?>">
                <a href="#Ecommerce" class="menu-toggle"><i class="zmdi zmdi-shopping-cart"></i> <span>CRM</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'dashboard' ? 'active' : null); ?>"><a href="<?php echo e(route('ecommerce.dashboard')); ?>">Dashboard</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'product' ? 'active' : null); ?>"><a href="<?php echo e(route('ecommerce.product')); ?>">Product</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'product-list' ? 'active' : null); ?>"><a href="<?php echo e(route('ecommerce.product-list')); ?>">Product List</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'product-detail' ? 'active' : null); ?>"><a href="<?php echo e(route('ecommerce.product-detail')); ?>">Product Details</a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::segment(1) === 'components' ? 'active open' : null); ?>">
                <a href="#Components" class="menu-toggle"><i class="zmdi zmdi-swap-alt"></i> <span>Human Resource</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'ui' ? 'active' : null); ?>"><a href="<?php echo e(route('components.ui')); ?>">Aero UI KIT</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'alerts' ? 'active' : null); ?>"><a href="<?php echo e(route('components.alerts')); ?>">Alerts</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'collapse' ? 'active' : null); ?>"><a href="<?php echo e(route('components.collapse')); ?>">Collapse</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'colors' ? 'active' : null); ?>"><a href="<?php echo e(route('components.colors')); ?>">Colors</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'dialogs' ? 'active' : null); ?>"><a href="<?php echo e(route('components.dialogs')); ?>">Dialogs</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'list' ? 'active' : null); ?>"><a href="<?php echo e(route('components.list')); ?>">List Group</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'media' ? 'active' : null); ?>"><a href="<?php echo e(route('components.media')); ?>">Media Object</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'modals' ? 'active' : null); ?>"><a href="<?php echo e(route('components.modals')); ?>">Modals</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'notifications' ? 'active' : null); ?>"><a href="<?php echo e(route('components.notifications')); ?>">Notifications</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'progressbars' ? 'active' : null); ?>"><a href="<?php echo e(route('components.progressbars')); ?>">Progress Bars</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'range' ? 'active' : null); ?>"><a href="<?php echo e(route('components.range')); ?>">Range Sliders</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'sortable' ? 'active' : null); ?>"><a href="<?php echo e(route('components.sortable')); ?>">Sortable & Nestable</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'tabs' ? 'active' : null); ?>"><a href="<?php echo e(route('components.tabs')); ?>">Tabs</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'waves' ? 'active' : null); ?>"><a href="<?php echo e(route('components.waves')); ?>">Waves</a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::segment(1) === 'icons' ? 'active open' : null); ?>">
                <a href="#Icons" class="menu-toggle"><i class="zmdi zmdi-flower"></i> <span>Payroll</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'material' ? 'active' : null); ?>"><a href="<?php echo e(route('icons.material')); ?>">Material</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'themify' ? 'active' : null); ?>"><a href="<?php echo e(route('icons.themify')); ?>">Themify</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'weather' ? 'active' : null); ?>"><a href="<?php echo e(route('icons.weather')); ?>">Weather</a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::segment(1) === 'form' ? 'active open' : null); ?>">
                <a href="#Form" class="menu-toggle"><i class="zmdi zmdi-assignment"></i><span>System</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'basic' ? 'active' : null); ?>"><a href="<?php echo e(route('form.basic')); ?>">Basic Form</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'advanced' ? 'active' : null); ?>"><a href="<?php echo e(route('form.advanced')); ?>">Advanced Form</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'examples' ? 'active' : null); ?>"><a href="<?php echo e(route('form.examples')); ?>">Form Examples</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'validation' ? 'active' : null); ?>"><a href="<?php echo e(route('form.validation')); ?>">Form Validation</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'wizard' ? 'active' : null); ?>"><a href="<?php echo e(route('form.wizard')); ?>">Form Wizard</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'editors' ? 'active' : null); ?>"><a href="<?php echo e(route('form.editors')); ?>">Editors</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'upload' ? 'active' : null); ?>"><a href="<?php echo e(route('form.upload')); ?>">File Upload</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'summernote' ? 'active' : null); ?>"><a href="<?php echo e(route('form.summernote')); ?>">Summernote</a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::segment(1) === 'tables' ? 'active open' : null); ?>">
                <a href="#Tables" class="menu-toggle"><i class="zmdi zmdi-grid"></i><span>Tables</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'normal' ? 'active' : null); ?>"><a href="<?php echo e(route('tables.normal')); ?>">Normal Tables</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'datatable' ? 'active' : null); ?>"><a href="<?php echo e(route('tables.datatable')); ?>">Jquery Datatables</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'editable' ? 'active' : null); ?>"><a href="<?php echo e(route('tables.editable')); ?>">Editable Tables</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'footable' ? 'active' : null); ?>"><a href="<?php echo e(route('tables.footable')); ?>">Foo Tables</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'color' ? 'active' : null); ?>"><a href="<?php echo e(route('tables.color')); ?>">Tables Color</a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::segment(1) === 'chart' ? 'active open' : null); ?>">
                <a href="#Chart" class="menu-toggle"><i class="zmdi zmdi-chart"></i><span>Charts</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'echarts' ? 'active' : null); ?>"><a href="<?php echo e(route('chart.echarts')); ?>">E Chart</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'c3' ? 'active' : null); ?>"><a href="<?php echo e(route('chart.c3')); ?>">C3 Chart</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'morris' ? 'active' : null); ?>"><a href="<?php echo e(route('chart.morris')); ?>">Morris</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'flot' ? 'active' : null); ?>"><a href="<?php echo e(route('chart.flot')); ?>">Flot</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'chartjs' ? 'active' : null); ?>"><a href="<?php echo e(route('chart.chartjs')); ?>">ChartJS</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'sparkline' ? 'active' : null); ?>"><a href="<?php echo e(route('chart.sparkline')); ?>">Sparkline</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'knob' ? 'active' : null); ?>"><a href="<?php echo e(route('chart.knob')); ?>">Jquery Knob</a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::segment(1) === 'widgets' ? 'active open' : null); ?>">
                <a href="#Widgets" class="menu-toggle"><i class="zmdi zmdi-delicious"></i><span>Widgets</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'app' ? 'active' : null); ?>"><a href="<?php echo e(route('widgets.app')); ?>">Apps Widgets</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'data' ? 'active' : null); ?>"><a href="<?php echo e(route('widgets.data')); ?>">Data Widgets</a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::segment(1) === 'authentication' ? 'active open' : null); ?>">
                <a href="#Authentication" class="menu-toggle"><i class="zmdi zmdi-lock"></i><span>Authentication</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'login' ? 'active' : null); ?>"><a href="<?php echo e(route('authentication.login')); ?>">Sign In</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'register' ? 'active' : null); ?>"><a href="<?php echo e(route('authentication.register')); ?>">Sign Up</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'lockscreen' ? 'active' : null); ?>"><a href="<?php echo e(route('authentication.lockscreen')); ?>">Locked Screen</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'forgot' ? 'active' : null); ?>"><a href="<?php echo e(route('authentication.forgot')); ?>">Forgot Password</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'page404' ? 'active' : null); ?>"><a href="<?php echo e(route('authentication.page404')); ?>">Page 404</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'page500' ? 'active' : null); ?>"><a href="<?php echo e(route('authentication.page500')); ?>">Page 500</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'offline' ? 'active' : null); ?>"><a href="<?php echo e(route('authentication.offline')); ?>">Page Offline</a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::segment(1) === 'pages' ? 'active open open_top' : null); ?>">
                <a href="#Pages" class="menu-toggle"><i class="zmdi zmdi-copy"></i><span>Sample Pages</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'blank' ? 'active' : null); ?>"><a href="<?php echo e(route('pages.blank')); ?>">Blank Page</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'gallery' ? 'active' : null); ?>"><a href="<?php echo e(route('pages.gallery')); ?>">Image Gallery</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'invoices1' ? 'active' : null); ?>"><a href="<?php echo e(route('pages.invoices1')); ?>">Invoices</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'invoices2' ? 'active' : null); ?>"><a href="<?php echo e(route('pages.invoices2')); ?>">Invoices List</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'pricing' ? 'active' : null); ?>"><a href="<?php echo e(route('pages.pricing')); ?>">Pricing</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'profile' ? 'active' : null); ?>"><a href="<?php echo e(route('pages.profile')); ?>">Profile</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'search' ? 'active' : null); ?>"><a href="<?php echo e(route('pages.search')); ?>">Search Results</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'timeline' ? 'active' : null); ?>"><a href="<?php echo e(route('pages.timeline')); ?>">Timeline</a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::segment(1) === 'map' ? 'active open open_top' : null); ?>">
                <a href="#Map" class="menu-toggle"><i class="zmdi zmdi-map"></i><span>Maps</span></a>
                <ul class="ml-menu">
                    <li class="<?php echo e(Request::segment(2) === 'yandex' ? 'active' : null); ?>"><a href="<?php echo e(route('map.yandex')); ?>">YandexMap</a></li>
                    <li class="<?php echo e(Request::segment(2) === 'jvector' ? 'active' : null); ?>"><a href="<?php echo e(route('map.jvector')); ?>">jVectorMap</a></li>
                </ul>
            </li>
            -->
        </ul>
        
        
<script>
     $(document).ready(function() {
    var currentUrl = window.location.href;

    $(".sidebar li").each(function() {
        var link = $(this).find("a").attr("href");

        if (link === currentUrl) {
            $(this).addClass("active").parents("li").addClass("active open").removeClass("treeview");
            $(this).parents("ul").slideDown();
        }
    });
});

</script>
    </div>
</aside><?php /**PATH C:\xampp\htdocs\app\resources\views/layout/sidebar.blade.php ENDPATH**/ ?>