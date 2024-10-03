<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" href="<?php echo e(asset('public/tablogo.png')); ?>" type="image/x-icon"> <!-- Favicon-->
    <title><?php echo e(config('app.name')); ?> - <?php echo $__env->yieldContent('title'); ?></title>
    <link rel="icon" href="/favicon.ico">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', config('app.name')); ?>">
    <meta name="author" content="<?php echo $__env->yieldContent('meta_author', config('app.name')); ?>">
    <?php echo $__env->yieldContent('meta'); ?>
    
    <?php echo $__env->yieldPushContent('before-styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('public/assets/plugins/bootstrap/css/bootstrap.min.css')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->

    <!-- <script type="text/javascript" defer="defer" src="https://odoocdn.com/web/assets/1/f7cc3e4/web.assets_frontend_minimal.min.js" onerror="__odooAssetError=1"></script>
    <script type="text/javascript" defer="defer" onerror="__odooAssetError=1" src="https://odoocdn.com/web/assets/1/18ef698/web.assets_frontend_lazy.min.js"></script>
     -->

    <?php if(trim($__env->yieldContent('page-style'))): ?>
    <?php echo $__env->yieldContent('page-style'); ?>
    <?php endif; ?>
    <!-- Custom Css -->
    <!-- <link rel="stylesheet" href="<?php echo e(asset('public/assets/css/style.min.css')); ?>"> -->
    <!-- <link rel="stylesheet" href="<?php echo e(asset('public/assets/css/sw.css')); ?>"> -->
    <?php echo $__env->yieldPushContent('after-styles'); ?>

    <style>
        overflow-x:scroll;
overflow-y:scroll;
*{
-ms-overflow-style: none;
margin:0;
padding:0;
}
::-webkit-scrollbar {
display: none;
}

.page-loader-wrapper {
    z-index: 99999999;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background: #eee;
    overflow: hidden;
    text-align: center
}

.page-loader-wrapper p {
    font-size: 13px;
    font-weight: 700;
    color: #777;
    margin-top: 10px
}

.page-loader-wrapper .loader {
    position: relative;
    top: calc(40% - 30px)
}
.zmdi-hc-spin {
    -webkit-animation: zmdi-spin 1.5s infinite linear;
    animation: zmdi-spin 1.5s infinite linear
}

.zmdi-hc-spin-reverse {
    -webkit-animation: zmdi-spin-reverse 1.5s infinite linear;
    animation: zmdi-spin-reverse 1.5s infinite linear
}
.mmt{
   color:white;
}
.im1 {
  height:550px;
  background-image: url("<?php echo e(asset('public/assets/images/bg123.png')); ?>");
  background-repeat: round;
}
.im h1 {
  color: white;
  margin-top: 8%;
  font-weight: bolder;
  font-size: 28px;
}

.im p {
  color: white;
  font-weight: 200;
  margin-top: 6%;
}
.bu{
  background-color: orange;
  border-radius: 5px;
  width: 139px;
  height: 43px;
  color: white;
  font-family: sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 21px;
  letter-spacing: 0em;
  text-align: center;
  border: none;
  cursor: pointer;
}
.blue {
  color: #023C82;
}
.image-container {
  display: flex;
  justify-content: center;
  justify-content: space-evenly;
  flex-wrap: wrap;
}

.image-item {
cursor: pointer;
  text-align: center;
  margin: 0 10px;
  width: 200px;
  transition: all 0.2s ease-in-out;
}
.image-item:hover{
  transform: scale(1.1);

}

 .scl{
  transition: all 0.2s ease-in-out;
 }
 .scl:hover{
  transform: scale(1.1);

 }

.m {
     width:150px;
  color: orange;
  border-bottom: 2px solid orange;
  margin-left:20px;
}
.r3 {
  justify-content: space-evenly;
}

.c8 {
  height: 667px;
  border-radius: 12px;
  background: #DAE3ED;
}
.r8 {
  font-size: 25px;
}
.c9 {
  height:667px;
  border-radius: 12px;
  background: #00295B;


}
.m3 {
width:100%;
/* margin-left:15%; */
  color: orange;
  /* text-align: center; */
  /* border-bottom: 2px solid orange; */
}

.m4 {
  width: 100%;
  height: 320px;
}
.m11 {
  width:50%;
  margin-left: 30%;

}
.UnleashGradiant {
  background-image: linear-gradient(to bottom, #CDE2FB, #f5f0f0, #CDE2FB);
  background-position: top;
  background-repeat: no-repeat;
  z-index: 2;

}
.buttonUnleash {
  background-color: #DC7210;
  width:186px;
  height:60px;
  /* border-radius: 5px; */
  color: white;
  border:none;
}

.buttonUnleash:hover {
  cursor: pointer;
}
.unleashTest {
  font-family: 'Open Sans', sans-serif;
  font-weight: 700;
  font-size: 35px;
  line-height: 47.66px;
}
.fotr {
  width: 100%;
  height: auto;
  background: #194882;

}

.te{ 
  display: flex; 
}
.mmt1{
    border: 1px solid #000;
}
.ic {
  border: 1px solid #0000003f;
/* border-radius: 5px; */
font-size: 19px;
margin-top:-10%;
margin-left:80%;
padding: 0px 5px;
position: relative;
}
.ic span{
    height:10px;
    width:20px
}
.content h1{
    width:1000px;
}
.full-width-content {
      margin-top: 20px;
      padding: 20px;
      background-color: #eaeaea;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      box-sizing: border-box;
      position: relative;
    }
    .dropdown-menu {
     /* margin:-287px; */
     /* min-width:91rem; */
     height:auto;
     /* margin-top:7%; */
     background-color:#E9EFF5;
    }
    .drop{   
      border-radius: 15px;
background: #F0F0F0;
    }
    .heading1{
      width:100%;
      text-align: center;
     color:#0C76FF;
    }
    
.clr{
  color:#023C82;
}

  
    .heading4{
      width:31%;
      margin-top:1%;
     height:auto;
     text:center;
     color:#0C76FF;
     border-bottom: 5px solid #0C76FF;
     margin-left:32%;
     /* margin-top:1%; */
    }
    
    .mmt1{
      border: 1px solid #000;
    }
    a:link {
  text-decoration: none;
}

 a:visited {
  text-decoration: none;
}

 a:hover {
  text-decoration: none;
}

a:active {
  text-decoration: none;
}

* {
  font-family: "Source Sans Pro", "Roboto", Arial, sans-serif;
}

@keyframes  fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
.dropdown-menu.show {
  -webkit-animation: fadeIn 0.3s alternate;
  /* Safari 4.0 - 8.0 */
  animation: fadeIn 0.3s alternate;
}

.nav-item.dropdown.dropdown-mega {
  position: static;
}
.nav-item.dropdown.dropdown-mega .dropdown-menu {
  width: 100%;
  top: auto;

}

.navbar-toggler {
  border: none;
  padding: 0;
  outline: none;
}
.navbar-toggler:focus {
  box-shadow: none;
}
.navbar-toggler .hamburger-toggle {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 50px;
  z-index: 11;
  float: right;
}
.navbar-toggler .hamburger-toggle .hamburger {
  position: absolute;
  transform: translate(-50%, -50%) rotate(0deg);
  left: 50%;
  top: 50%;
  width: 50%;
  height: 50%;
  pointer-events: none;
}
.navbar-toggler .hamburger-toggle .hamburger span {
  width: 100%;
  height: 4px;
  position: absolute;
  background: #333;
  border-radius: 2px;
  z-index: 1;
  transition: transform 0.2s cubic-bezier(0.77, 0.2, 0.05, 1),
   background 0.2s cubic-bezier(0.77, 0.2, 0.05, 1), all 0.2s ease-in-out;
  left: 0px;
}
.navbar-toggler .hamburger-toggle .hamburger span:first-child {
  top: 10%;
  transform-origin: 50% 50%;
  transform: translate(0% -50%) !important;
}
.navbar-toggler .hamburger-toggle .hamburger span:nth-child(2) {
  top: 50%;
  transform: translate(0, -50%);
}
.navbar-toggler .hamburger-toggle .hamburger span:last-child {
  left: 0px;
  top: auto;
  bottom: 10%;
  transform-origin: 50% 50%;
}
.navbar-toggler .hamburger-toggle .hamburger.active span {
  position: absolute;
  margin: 0;
}
.navbar-toggler .hamburger-toggle .hamburger.active span:first-child {
  top: 45%;
  transform: rotate(45deg);
}
.navbar-toggler .hamburger-toggle .hamburger.active span:nth-child(2) {
  left: 50%;
  width: 0px;
}
.navbar-toggler .hamburger-toggle .hamburger.active span:last-child {
  top: 45%;
  transform: rotate(-45deg);
}

.icons {
  display: inline-flex;
  margin-left: auto;
}
.icons a {
  transition: all 0.2s ease-in-out;
  padding: 0.2rem 0.4rem;
  color: #ccc !important;
  text-decoration: none;
}
.icons a:hover {
  color: white;
  text-shadow: 0 0 30px white;
}
.custom-maxWidth {
  max-width: 1400px;
  margin:0 auto;
}
    </style>
</head>
<body>
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img class="zmdi-hc-spin" src="<?php echo e(asset('public/assets/images/loading.png')); ?>"
                    height="90px" alt="OfDesk"></div>
            <p>Processing...</p>
        </div>
    </div>
       
                <!-- Header content will go here -->
            
 <header>
   <div class="custom-maxWidth  " >
        <nav class=" navbar-expand-lg  container-fluid   " style="height:40px">
            <div class="container-fluid" id="navmob">
                <div class="row">
                    <div class=" col-5 ">
                        <div class="   collapse navbar-collapse" >
                            <ul class="navbar-nav  ">
                                <li class="nav-item  " style="color:#B1B0B0;  margin-top:3%">
                                    <span><b>Let the platform lead the way</b></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                   <div class=" col-7  collapse navbar-collapse" id="nav2" >
                
                    <ul class="navbar-nav ms-auto">

                        <li class="nav-item">
                            <!-- <a href="tel:+92 300 1234567" class="nav-link UppernavbarFontClass" style="color:black;" id="nav2" href=""><b>Pakistan</b> : +92 300 1234567</a> -->
                        </li>
                        <li class="nav-item">
                            <a href="mailto: hi@monmatics.com"  class="nav-link  UppernavbarFontClass" style="color:black;" id="nav2" href=""><b><img class="" src="<?php echo e(asset('public/assets/icon/email.png')); ?>"
                    height="30px" alt=""></b> : hi@monmatics.com</a>
                        </li>
                           <li class="nav-item dropdown">
                            <a href="<?php echo e(url('authentication/login')); ?>" class="nav-link mx-2 navbarFontClass  " style="color:  #023C82" id="nav2" role="button" aria-expanded="false">
                                <b>LogIn</b> 
                              </a>
                        </li>
                        
                    </ul>
                  </div>
                  
                </div>
            </div>

            <div class="container-fluid  ">
              <div class="d-block d-lg-none">
                <div class="row   mt-2  ">
                  <div class="col-6">
                    <ul class=" navbar-nav ">
                      <li class="" ><a href="" class="nav-link"  style="color: black; font-size: large;"><b>Email</b>:hi@monmatics.com</a></li>
                    </ul>
                  </div>
                  <div class="col-6 " style=" text-align: right">
                  <a href="<?php echo e(url('authentication/login')); ?>"  class="">
                    <h4 class="blue mt-2">LogIn</h4>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </nav>
          
      </div>


  <nav class="navbar navbar-expand-lg navbar-light   custom-maxWidth  border-top ">
  <a class="navbar-brand" href="/">
          <img class="" src="<?php echo e(asset('public/assets/images/Logo new 1.svg')); ?>" alt="" style="" data-pagespeed-url-hash="309413324" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">
          </a>      
  <button class="navbar-toggler collapsed   " type="button" data-bs-toggle="collapse" data-bs-target="#navbar-content">
        <div class="hamburger-toggle">
          <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </button>
    <div class="container-fluid ">
      <div class="collapse navbar-collapse" id="navbar-content">
        <ul class="navbar-nav mr-auto mb-2 mb-lg-0    ">

           <!-- feature li          -->
        
           <li class="nav-item dropdown dropdown-mega position-static">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">Features</a>
            
            <div class="dropdown-menu shadow">
              <div class="mega-content px-4">
                <div class="heading1">
                  <h2 class=""> <span style="     border-bottom: 4px solid #0C76FF;">Plan your business with monmatics</span></h2> 

                </div>
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <div class="d-flex  ">
                        <img src="<?php echo e(asset('public/assets/images/Accept Payment-1.svg')); ?>" alt=""   >
                        <h6 class="blue mt-3 ml-2 " style="letter-spacing: 0em;text-align: left;">Accept Payment</h6>
                      </div>
                      <div class="d-flex  mt-3">
                        <img src="<?php echo e(asset('public/assets/images/Track Projects.svg')); ?>" alt="" >
                        <h6 class="blue mt-4  ml-3"   style="letter-spacing: 0em;text-align: left;">Track projects</h6>
                      </div>

                      <div class="d-flex  mt-3">
              <img src="<?php echo e(asset('public/assets/images/Reporting.svg')); ?>" alt="" style="" >
              <h6 class="blue mt-4  ml-3"   style="letter-spacing: 0em;text-align: left;">Reporting</h6>
            </div>

            <div class="d-flex  mt-3">
              <img src="<?php echo e(asset('public/assets/images/Analytics.svg')); ?>" alt="" style="" >
              <h6 class="blue mt-4  ml-3"   style="letter-spacing: 0em;text-align: left;">Analytics.svg</h6>
            </div>
   

                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>Card</h5> -->
                     

                <div class="d-flex ">
              <img src="<?php echo e(asset('public/assets/images/Claim expenses-1.svg')); ?>" alt="" style=""  >
              <h6 class="blue mt-3  ml-2 " >Claim expenses</h6>
            </div>
            <div class="d-flex mt-3">
              <img src="<?php echo e(asset('public/assets/images/Bank reconciliation.svg')); ?>" alt="" style="" >
              <h6 class="blue mt-3  ml-2"   style="">Bank reconciliation</h6>
            </div>

            <div class="d-flex mt-3">
              <img src="<?php echo e(asset('public/assets/images/Online invoicing.svg')); ?>" alt="" style="" >
              <h6 class="blue mt-3  ml-2"   style="letter-spacing: 0em;text-align: left;">Online invoicing</h6>
            </div>
            <div class="d-flex mt-3">
              <img src="<?php echo e(asset('public/assets/images/Sales tax.svg')); ?>" alt="" style="" >
              <h6 class="blue mt-3  ml-2"   style="letter-spacing: 0em;text-align: left;">Sales tax</h6>
            </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>About CodeHim</h5> -->

                      <div class="d-flex ">
              <img src="<?php echo e(asset('public/assets/images/Bank connection.svg')); ?>" alt="" style=""  >
              <h6 class="blue mt-3 ml-3 " >Bank connection</h6>
            </div>
            <div class="d-flex mt-3 ">
              <img src="<?php echo e(asset('public/assets/images/Manage contacts.svg')); ?>" alt="" style="" >
              <h6 class="blue mt-3  ml-3"  >Manage contacts</h6>
            </div>
            <div class="d-flex mt-3 ">
              <img src="<?php echo e(asset('public/assets/images/Multi currency.svg')); ?>" alt="" style="" >
              <h6 class="blue mt-3  ml-3"  >Multi currency</h6>
            </div>
            <div class="d-flex  mt-3">
              <img src="<?php echo e(asset('public/assets/images/Accounting (1).svg')); ?>" alt="" style="" >
              <h6 class="blue mt-3  ml-3"   >Accounting</h6>
            </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 py-4">
                      <!-- <h5>Damn, so many</h5> -->
                     
                      <div class="d-flex ">
              <img src="<?php echo e(asset('public/assets/images/Inventory-1.svg')); ?>" alt="" style=""  >
              <h6 class="blue mt-3 ml-3 " >Inventory</h6>
            </div>
            <div class="d-flex mt-3 ">
              <img src="<?php echo e(asset('public/assets/images/Capture data.svg')); ?>" alt="" style="" >
              <h6 class="blue mt-4  ml-3"  >Capture data</h6>
            </div>
            <div class="d-flex mt-3">
              <img src="<?php echo e(asset('public/assets/images/Purchase orders.svg')); ?>" alt="" style="" >
              <h6 class="blue mt-4  ml-3"   >Purchase orders</h6>
            </div>
            <div class="d-flex mt-3">
              <img src="<?php echo e(asset('public/assets/images/Manage fixed assets.svg')); ?>" alt="" style="" >
              <h6 class="blue mt-4  ml-3"  >Manage fixed assets</h6>
            </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </li>

          <!-- plans your li -->
          <li class="nav-item dropdown dropdown-mega position-static">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">Plans For small businesses</a>
            
            <div class="dropdown-menu shadow">
              <div class="mega-content px-4">
              <h3 class="heading1"> <span style="border-bottom: 4px solid #0C76FF;">Unleash Your Business Potential with Monmatics</span></h3>                 <div class="container-fluid">
                  <div class="row">
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <div class="d-flex ">
                        <img src="<?php echo e(asset('public/assets/images/Benefits.svg')); ?>" alt=""   >
                        <h6 class="blue mt-3 ml-2 " style="letter-spacing: 0em;text-align: left;">Benefits</h6>
                      </div>
                      
                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>Card</h5> -->
                     

                      <div class="d-flex ">
              <img src="<?php echo e(asset('public/assets/images/Data authentication.svg')); ?>" alt="" style=""  >
              <h6 class="blue mt-3 ml-2 " >Data authentication</h6>
            </div>
            

                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>About CodeHim</h5> -->

                      <div class="d-flex ">
              <img src="<?php echo e(asset('public/assets/images/Business recovery.svg')); ?>" alt="" style=""  >
              <h6 class="blue mt-3 ml-2" >Business recovery</h6>
            </div>
           
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 py-4">
                      <!-- <h5>Damn, so many</h5> -->
                     
                      <div class="d-flex ">
              <img src="<?php echo e(asset('public/assets/images/Smart online accounting.svg')); ?>" alt="" style=""  >
              <h6 class="blue mt-3 ml-2 ">Smart online accounting</h6>
            </div>
            
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </li>

          <!-- For accountants and bookkeepers -->
          <li class="nav-item dropdown dropdown-mega position-static">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">For accountants and bookkeepers</a>
            
            <div class="dropdown-menu shadow">
              <div class="mega-content px-2">
              <h3 class="heading1" > <span style="border-bottom: 4px solid #0C76FF;">Unleash Your Accounting Potential with Monmatics</span></h3>    
                 <div class="container">
                  <div class="row">
                   


                    <div class="col-lg-6 col-sm-6    col-md-6   py-4">
                     
    
                <div class="d-flex " >
              <img src="<?php echo e(asset('public/assets/images/Monmatics HQ.svg')); ?>" alt=""   >
              <h6 class="blue mt-3 ml-2 " >Monmatics HQ</h6>
            </div>
             
            <div class="d-flex  mt-4" >
              <img src="<?php echo e(asset('public/assets/images/Practice manager.svg')); ?>" alt=""  >
              <h6 class="blue mt-3 ml-2 " >Monmatics Practice Manager </h6>
            </div>


                    </div>

                 


                    <div class="col-lg-6 col-sm-12 col-md-6 py-4  ">
                     
                <div class="d-flex ">
              <img src="<?php echo e(asset('public/assets/images/Monmatics Cashback.svg')); ?>" alt=""  >
              <h6 class="blue mt-3 ml-2" >Monmatics Cashback, Monamtics Ledger</h6>
            </div>
           

            <div class="d-flex mt-4 ">
              <img src="<?php echo e(asset('public/assets/images/Workpapers.svg')); ?>" alt=""  >
              <h6 class="blue mt-3 ml-2" >Monmatics Workpapers</h6>
            </div>


                   


                  </div>
                </div>
              </div>
            </div>
          </li>

          <!-- Apps -->
          <li class="nav-item dropdown dropdown-mega position-static">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">Apps</a>
            
            <div class="dropdown-menu shadow">
              <div class="mega-content px-4">
                
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12 col-sm-4 col-md-3 py-4">

                      <div class="d-flex ">
                        <img src="<?php echo e(asset('public/assets/images/Finance.svg')); ?>" alt=""   >
                        <h6 class=" mt-2 ml-2 " style="color:#DC7210; width:152.01px;    border-bottom: 2px solid #DC7210;">Finance</h6>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;">Accounting  <br>  Invoicing <br> Expenses  <br> Spreadsheet <br> Documents <br> Sign</p>
                   </div>





                   <div class="d-flex  ">
                        <img src="<?php echo e(asset('public/assets/images/Marketing.svg')); ?>" alt=""   >
                        <h6 class=" mt-2 ml-2 " style="color:#C5221F; width:152.01px;    border-bottom: 2px solid #C5221F">Marketing</h6>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;"> Social Marketing  <br>  Email Marketing  <br> SMS Marketing  <br> Events <br>  Marketing Automation <br> Surveys</p>
                   </div>

                  
                  



              

                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>Card</h5> -->
                     

                    



                   <div class="d-flex  ">
                        <img src="<?php echo e(asset('public/assets/images/Total Sales.svg')); ?>" alt=""   >
                        <h6 class=" mt-2 ml-2 " style="color:#B91D68; width:152.01px;    border-bottom: 2px solid #B91D68;"> Sales</h6>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;">CRM  <br>  Sales <br> Point of sale  <br> Subscriptions <br> Rental <br> Amazon Connectorgn</p>
                   </div>


   

                   <div class="d-flex  ">
                        <img src="<?php echo e(asset('public/assets/images/Services.svg')); ?>" alt=""   >
                        <p class=" mt-2 ml-2 " style="color:#293180; width:152.01px;    border-bottom: 2px solid #293180;">Services</p>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;"> Project  <br>  Time Shield <br> Filed Service  <br> Helpdesk <br> Planning <br> Appointments</p>
                   </div>

          
          
                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>About CodeHim</h5> -->

                 
                    


                   <div class="d-flex  ">
                        <img src="<?php echo e(asset('public/assets/images/Warehouse.svg')); ?>" alt=""   >
                        <h6 class=" mt-2 ml-2 " style="color:#1A6D30; width:152.01px;    border-bottom: 2px solid #1A6D30;">INVENTORY & MRP</h6>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;">  Inventory  <br>  Manufacturing <br> PLM  <br> Purchase <br> Maintenance <br> Quality</p>
                   </div>


                   <div class="d-flex  ">
                        <img src="<?php echo e(asset('public/assets/images/Brainstorm Skill.svg')); ?>" alt=""   >
                        <h6 class=" mt-2 ml-2 " style="color:#977000; width:152.01px;    border-bottom: 2px solid #977000;">PRODUCTIVITY</h6>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;">   Discuss  <br>  Approvals <br> IoT  <br> VoIP <br> Knowledge</p>
                   </div>




                    </div>
                    <div class="col-12 col-sm-12 col-md-3 py-4">
                      <!-- <h5>Damn, so many</h5> -->
                     
           


                      <div class="d-flex  ">
                        <img src="<?php echo e(asset('public/assets/images/Human Research Program.svg')); ?>" alt=""   >
                        <h6 class=" mt-2 ml-2 " style="color:#00295B; width:162.01px;    border-bottom: 2px solid #00295B;">HUMAN RESOURCE</h6>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;">  Employees  <br>  Recuitment <br> Time OFF   <br> Appraisals <br> Referrals <br> Fleet</p>
                   </div>



          



                    </div>
                  </div>
                </div>
              </div>
            </div>
          </li>



          <!-- Support -->
          <li class="nav-item dropdown dropdown-mega position-static">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">Support</a>
            
            <div class="dropdown-menu shadow">
              <div class="mega-content px-4">
              <h3 class="heading1"> <span style="border-bottom: 4px solid #0C76FF;">Get support to use monmatics</span></h3>                 <div class="container-fluid">
                  <div class="row ">




                  <div class="col-lg-2 col-sm-4 col-md-3 py-4  ">
                      <!-- <h5>Card</h5> -->
                     

                      
            

                    </div>


                
                    <div class="col-12 col-sm-4 col-md-3 py-4 ">
                      <!-- <h5>Card</h5> -->
                     

                      <div class="d-flex ">
              <img src="<?php echo e(asset('public/assets/images/Get Support.svg')); ?>" alt="" style=""  >
              <h6 class="blue mt-3 ml-2 " >Get Support</h6>
            </div>
            

                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>About CodeHim</h5> -->

                      <div class="d-flex ">
              <img src="<?php echo e(asset('public/assets/images/Guide.svg')); ?>" alt="" style=""  >
              <h6 class="blue mt-3 ml-2" >Guide</h6>
            </div>
           
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 py-4 ">
                      <!-- <h5>Damn, so many</h5> -->
                     
                      <div class="d-flex ">
              <img src="<?php echo e(asset('public/assets/images/Accounting glossary.svg')); ?>" alt="" style=""  >
              <h6 class="blue mt-3 ml-2 ">Accounting glossary</h6>
            </div>
            
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </li>


        </ul>
        <div class="btn-group" style="float: inline-end;">
  <button class=" btn-sm rounded-3    " data-bs-toggle="dropdown" aria-expanded="false"  style="border:1px solid #163C69; color:#163C69 ">
  Try monmatics
  </button>
  <ul class="dropdown-menu   drop  dropdown-menu-end">
    <li class="d-flex">
    <img src="<?php echo e(asset('public/assets/images/Male User.svg')); ?>" alt="" style="margin-left:4%"  > 
    <a class="dropdown-item clr" href="<?php echo e(route('individual.login')); ?>">Individual</a></li>
    <li class="d-flex">
    <img src="<?php echo e(asset('public/assets/images/Business.svg')); ?>" alt=""  style="margin-left:4%"   > 
    <a class="dropdown-item clr" href="<?php echo e(route('business.login')); ?>">Business</a></li>
    <li class="d-flex">
    <img src="<?php echo e(asset('public/assets/images/General Ledger.svg')); ?>" alt=""  style="margin-left:4%"   > 
    <a class="dropdown-item clr" href="<?php echo e(route('bookkeeper.login')); ?>">Accountant/Bookkeeper</a></li>
  </ul>
</div>
   
      </div>
    </div>
  </nav>
</header>
<body>
<section class="content">
<div class="row">
      <?php echo $__env->yieldContent('content'); ?>
</div>
</section>

            
        
  <!--===--============-=---------------------============================== FOTTER=START----------------------------------------------------------- -->


<section class="text-center text-md-start  fotr     ">
    <footer class="container    ">
      <div class="row ">
        <div class="col-md-8 mt-5 ">
          <div class="row">
            <div class="col-md-3 mb-4">
              <h6 class=" text-white text-uppercase fw-bold mx-auto mt-5">Community</h6>
              <hr class="mb-2 mt-0 d-inline-block mx-auto">
              <p class="text-white">Coming soon</p>
            </div>


            <div class="col-md-3 mb-4">
              <h6 class=" text-white text-uppercase fw-bold mx-auto mt-5"> Knowledge Base</h6>
              <hr class="mb-2 mt-0 d-inline-block mx-auto">
              <p class="text-white">Find a Accountant <br> Find a Partner <br> Become a Partner <br></p>
            </div>

            <!-- ft3 -->

            <div class="col-md-3 mb-4 ">
              <h6 class=" text-white text-uppercase fw-bold mx-auto mt-5 ">Industry</h6>
              <hr class="mb-2 mt-0 d-inline-block mx-auto">
              <p class="text-white">Automotive <br> Food and Beverage <br> Aerospace <br> Electronics <br> Education
                <br> Healthcare <br> Tourism <br>
              </p>
            </div>

            <div class="col-md-3 mb-4">
              <h6 class="text-white text-uppercase fw-bold mx-auto mt-5 ">About Us</h6>
              <hr class="mb-2 mt-0 d-inline-block mx-auto">
              <p class="text-white ">Our company <br> Jobs <br> Podcasts <br> Customers <br> Security</p>
            </div>

          </div>
        </div>

        <!-- ft2 -->

        <div class="col-md-4 ">
          <div class="row mt-5">

            <div class="col-md-12 mb-md-0 mb-4 mt-5">

              <a href="">
              <img src="<?php echo e(asset('public/assets/images/image 1 (1).png')); ?>" alt="" >
              </a>
              <h6 class="    text-white text-uppercase fw-bold mt-3 " style="margin-bottom:10px;">Contact us</h6>

              <div class=" col-md-12">
                <a href="https://www.instagram.com/monmatics.solutions_wave?igsh=eHBoNzRmNmw4ajN6">
                  <img src="<?php echo e(asset('public/assets/images/🦆 icon _instagram icon_.svg')); ?>" alt="" >
                </a>
                    <a href="https://www.linkedin.com/company/monmatics-com">
                      <img src="<?php echo e(asset('public/assets/images/🦆 icon _linked in_.svg')); ?>" alt="" >
                    </a>
                    <a href="https://www.facebook.com/monmatics">
                      <img src="<?php echo e(asset('public/assets/images/🦆 icon _facebook icon_.svg')); ?>" alt="" ></a>
                    <img src="<?php echo e(asset('public/assets/images/🦆 icon _Gmail_.svg')); ?>" alt="" >
                    <img class="" src="<?php echo e(asset('public/assets/images/Frame 50.svg')); ?>" alt="" >

                  <!-- <img src="./images/Frame 50.svg" alt=""> -->
              

                <div class="ft4  mt-3" style="  border-bottom-style: solid;
  border-bottom-color: white;">
                
                </div>
<div class="mt-3"> 
  <a href="" class="" style="color:white;">
              <img src="<?php echo e(asset('public/assets/images/Geography.svg')); ?>" alt="" >
                  English
                  <img src="<?php echo e(asset('public/assets/images/Down Button.svg')); ?>" alt="" >
                </a></div>
               

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row pb-2">
        <div class="col-md-8  mb-3  te">

          <a href="">
            <p class="text-white" style="margin-right: 20px;border-bottom: 1.5px solid  white;width:auto">Privacy</p>
          </a>
          <a href="">
            <p class="text-white">Terms and conditions</p>
          </a>
        </div>

        <div class="col-md-4 text-white" style="font-size: 15px;">
          <p>
            © 2024 monmatics Inc. All Rights Reserved.
          </p>
        </div>
      </div>
    </footer>
  </section>


            
  
    <?php echo $__env->yieldContent('modal'); ?>
    <!-- Scripts -->
    <?php echo $__env->yieldPushContent('before-scripts'); ?>

    <script src="<?php echo e(asset('public/assets/bundles/libscripts.bundle.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/bundles/vendorscripts.bundle.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/bundles/mainscripts.bundle.js')); ?>"></script>

    <script src="<?php echo e(asset('public/assets/plugins/fullcalendar/jqueryui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/jquery-ui.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <?php echo $__env->yieldPushContent('after-scripts'); ?>
    <?php if(trim($__env->yieldContent('page-script'))): ?>
    <script>
            var Tawk_API = Tawk_API || {};

    </script>
    <?php echo $__env->yieldContent('page-script'); ?>
    <?php endif; ?>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\app\resources\views/web/master.blade.php ENDPATH**/ ?>