<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('public/tablogo.png') }}" type="image/x-icon"> <!-- Favicon-->
    <title>{{ config('app.name') }} - @yield('title')</title>
    <link rel="icon" href="/favicon.ico">
    <meta name="description" content="@yield('meta_description', config('app.name'))">
    <meta name="author" content="@yield('meta_author', config('app.name'))">
    @yield('meta')
    {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
    @stack('before-styles')
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    @if (trim($__env->yieldContent('page-style')))
    @yield('page-style')
    @endif
    <!-- Custom Css -->
    <!-- <link rel="stylesheet" href="{{ asset('public/assets/css/style.min.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset('public/assets/css/sw.css') }}"> -->
    @stack('after-styles')

    <style>
        overflow-x:scroll;
overflow-y:scroll;
*{
-ms-overflow-style: none;
margin:0;
padding:0;
border: 1px solid #000;
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
  background-image: url("{{asset('public/assets/images/bg123.png')}}");
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

  text-align: center;
  margin: 0 10px;
  width: 200px;
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
.mmt1{
    /* border: 1px solid #000; */
}
.c8 {
  height: 640px;
  border-radius: 12px;
  background: #DAE3ED;
}
.r8 {
  font-size: 25px;
}
.c9 {
  height: 640px;
  border-radius: 12px;
  background: #00295B;


}
.m3 {
width:70%;
margin-left:15%;
  color: orange;
  text-align: center;
  border-bottom: 2px solid orange;
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
  width: 186px;
  height:60px;
  border-radius: 5px;
  color: white;
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
    }
    .dropdown-menu {
     margin:-287px;
     min-width:91rem;
     height:473px;
     /* margin-top:7%; */
     background-color:#E9EFF5;
    }
    .heading1{
      width:36%;
     height:auto;
     text:center;
     color:#0C76FF;
     border-bottom: 5px solid #0C76FF;
     margin-left:30%;
     /* margin-top:1%; */
    }
    .drop1{
      margin:-382px;
     min-width:91.5rem;
     height:227px;
    }
    .drop2{
     height:293px;
     margin-top: 0.5rem;
     margin:-600px;
    min-width: 91.5rem;
    height: 293px;
    }
  .drop3{
    margin:-870px;
    margin-top:0.5rem;
    width: 91.5rem;
    height:569px;
  }
  .drop4{
    width: 91.5rem;
height: 213px;
margin-top:2%;
border-radius: 10px;
margin:-940px;

  }
    .heading2{
      width:48%;
     height:auto;
     text:center;
     color:#0C76FF;
     border-bottom: 5px solid #0C76FF;
     margin-left:30%;
     /* margin-top:1%; */
    }
    .heading3{
      width:46.5%;
      margin-top:1%;
     height:auto;
     text:center;
     color:#0C76FF;
     border-bottom: 5px solid #0C76FF;
     margin-left:25%;
     /* margin-top:1%; */
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


    </style>
</head>
<body>
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('public/assets/images/loading.png')}}"
                    height="90px" alt="OfDesk"></div>
            <p>Processing...</p>
        </div>
    </div>


    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <section class="">
        <!-- class content -->
        <div class="">
        <!-- block-header -->
            <div class="row">
                <!-- Header content will go here -->
            
 <header >
   <div class="" >
        <nav class=" navbar-expand-lg" style="height:40px">
            <div class="container" id="navmob">
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
<!-- 
                        <li class="nav-item">
                            <a class="nav-link UppernavbarFontClass" style="color:black;" id="nav2" href=""><b>Pakistan</b> : +92 300 1234567</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link  UppernavbarFontClass" style="color:black;" id="nav2" href=""><b>Email</b> : hi@monmatics.com</a>
                        </li>
                                                <li class="nav-item dropdown">
                            <a href="{{url('authentication/login')}}" class="nav-link mx-2 navbarFontClass mr-5 " style="color:  #023C82" id="nav2" role="button" aria-expanded="false">
                                <b> Log In</b>                            </a>
                        </li>
                        
                    </ul>
                  
                </div>
            </div>
            </div>



<div class="container">
<div class="d-block d-lg-none">
<div class="row   mt-2  ">
<div class="col-9">
<ul class=" navbar-nav ml-1 ">
<li class="" ><a href="" class="nav-link"  style="color: black; font-size: large;"><b>Email</b> :hi@monmatics.com</a></li>
</ul>
</div>
<div class="col-3">
<!-- <img src="{{ asset('public/assets/images/🦆 icon _instagram icon_.svg')}}" alt="" >
<img src="{{ asset('public/assets/images/🦆 icon _linked in_.svg')}}" alt="" >
<img src="{{ asset('public/assets/images/🦆 icon _facebook icon_.svg')}}" alt="" > -->
<a href="{{url('authentication/login')}}"  class="nav-link">

    <h4 class="blue c ">LogIn</h4>
</a>
</div>
</div>
</div>
</div>
    



        </nav>


        <!-- <hr> -->          
        <nav class="navbar navbar-expand-lg navbar-light" style="border-top: solid 1px #023C82;border-bottom: solid 1px #023C82">
      <div class="container">
          <a class="navbar-brand" href="">
          <img class="" src="{{ asset('public/assets/images/Logo new 1.svg')}}" alt="" style="height: 30px;" data-pagespeed-url-hash="309413324" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">
          </a>
          <button class="navbar-toggler ms-auto collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="navbar-collapse collapse" id="navbarTogglerDemo01" style="">
<!-- Features li start -->
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item dropdown   megamenu"  >
                      <a class="nav-link dropdown-toggletext-dark     nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"  style="width:100%">
                          Features
                      </a>
                      <div class="dropdown-menu col-lg-6  mmt1" role="menu"  >
                           <h2 class="heading1">Plan your business with monmatics</h2>   
                      <div class="row mt-2 ">
              <div class="col-lg-3   col-md-3 col-sm-3 ">1
             <div class="d-flex ml-5 ">
              <img src="{{ asset('public/assets/images/Accept Payment-1.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Accept Payment</h5>
            </div>
            <div class="d-flex ml-5   mt-3">
              <img src="{{ asset('public/assets/images/Track Projects.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3"   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Track projects</h5>
            </div>
            <div class="d-flex ml-5 mt-3">
              <img src="{{ asset('public/assets/images/Reporting.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3"   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Reporting</h5>
            </div>
            <div class="d-flex ml-5 mt-3">
              <img src="{{ asset('public/assets/images/Analytics.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3"   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Analytics.svg</h5>
            </div>
            </div>
              <div class="col-lg-2">2
            <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Claim expenses-1.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Claim expenses</h5>
            </div>
            <div class="d-flex mt-3">
              <img src="{{ asset('public/assets/images/Bank reconciliation.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3"   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Bank reconciliation</h5>
            </div>
            <div class="d-flex mt-3">
              <img src="{{ asset('public/assets/images/Online invoicing.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3"   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Online invoicing</h5>
            </div>
            <div class="d-flex mt-3">
              <img src="{{ asset('public/assets/images/Sales tax.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3"   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Sales tax</h5>
            </div>
            </div>
            <div class="col-lg-2">3
            <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Bank connection.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Bank connection</h5>
            </div>
            <div class="d-flex mt-3 ">
              <img src="{{ asset('public/assets/images/Manage contacts.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3"   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Manage contacts</h5>
            </div>
            <div class="d-flex mt-3 ">
              <img src="{{ asset('public/assets/images/Multi currency.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3"   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Multi currency</h5>
            </div>
            <div class="d-flex  mt-3">
              <img src="{{ asset('public/assets/images/Accounting (1).svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3"   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Accounting</h5>
            </div>
           </div>
              <div class="col-lg-2">4
             <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Inventory-1.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Inventory</h5>
            </div>
            <div class="d-flex mt-3 ">
              <img src="{{ asset('public/assets/images/Capture data.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3"   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Capture data</h5>
            </div>
            <div class="d-flex mt-3">
              <img src="{{ asset('public/assets/images/Purchase orders.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3"   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Purchase orders</h5>
            </div>
            <div class="d-flex mt-3">
              <img src="{{ asset('public/assets/images/Manage fixed assets.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3"   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Manage fixed assets</h5>
            </div>
              </div> 
              <div class="col-lg-2">5
             <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Pay Bills(1).svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Pay Bills</h5>
            </div>
            <div class="d-flex mt-3">
              <img src="{{ asset('public/assets/images/Pay runs.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3 "   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Pay runs</h5>
            </div>
            <div class="d-flex mt-3">
              <img src="{{ asset('public/assets/images/Files.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3 "   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Files</h5>
            </div>
            <div class="d-flex mt-3 ">
              <img src="{{ asset('public/assets/images/Quotes.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3 "   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Quotes</h5>
            </div>
                  </div>
            <div class="d-flex " style="width:40%;margin-left:27%;">
            <div class="d-flex col-lg-5 mt-3">
              <img src="{{ asset('public/assets/images/Integrate apps.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3 "   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Integrate apps</h5>
            </div>
            <div class="d-flex col-lg-8 mt-3 ">
              <img src="{{ asset('public/assets/images/Monmatics accounting app.svg')}}" alt="" style="" >
              <h5 class="blue mt-4  ml-3 "   style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Monomatics accounting app</h5>
            </div>
          </div>
        </div>
        </div>
         </li>
                  
         <!-- Plans for small Businesses -->

                  <li class="nav-item dropdown paddingInLists">
                      <a class="nav-link dropdown-toggle paddingInLists text-dark" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Plans for small Businesses
                      </a>
                      <div class="dropdown-menu   drop1  mmt1" role="menu"  >
                           <h2 class="heading2">Unleash Your Business Potential with Monmatics</h2>   
                      <div class="row mt-5 ">
                 <div class="col-3">1
             <div class="d-flex ml-5 ">
              <img src="{{ asset('public/assets/images/Benefits.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Benefits</h5>
            </div>
            </div>
            <div class="col-3">2
            <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Data authentication.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Data authentication</h5>
            </div>
           </div>
              <div class="col-2">3
             <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Business recovery.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Business recovery</h5>
            </div>
              </div> 
              <div class="col-3">4
             <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Smart online accounting.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Smart online accounting</h5>
            </div>
            </div>
           </div>
            </div>
             </li>                  
                 

                    <li class="nav-item dropdown paddingInLists">
                      <a class="nav-link dropdown-toggle paddingInLists text-dark" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          For accountants and bookkeepers
                      </a>
                      <div class="dropdown-menu   drop2  mmt1" role="menu"  >
                           <h3 class="heading3" style="font-weight: 700" >Unleash Your Accounting Potential with Monmatics</h3>   
                      <div class="row mt-5 "> 
                 <div class="col-lg-4"  style="margin-left:21%" >
             <div class="d-flex">
              <img src="{{ asset('public/assets/images/Monmatics HQ.svg')}}" alt="" style=""  >
              <h5 class="blue mt-2 ml-3 " style="font-weight: 700;line-height: 22px;letter-spacing: 0em;">Monmatics HQ</h5>
            </div>
            </div>
            <div class="col-lg-4 ">
            <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Monmatics Cashback.svg')}}" alt="" style=""  >
              <h5 class="blue mt-2 ml-3 " style="font-weight: 700;line-height:22px;letter-spacing: 0em;text-align: left;">Monmatics Cashback, Monamtics Ledger</h5>
            </div>
           </div>
           <div class="row mt-5">
           <div class="col-lg-4 " style="margin-left:21%">
             <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Practice manager.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Monmatics Practice Manager</h5>
            </div>
              </div> 
              <div class="col-lg-4 ">
             <div class="d-flex ">
              <img  class="ml-3" src="{{ asset('public/assets/images/Workpapers.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Monmatics Workpapers</h5>
            </div>
            </div>    
           </div>  
           </div>
            </div>

                  </li>
                  
                  <li class="nav-item dropdown ">
                      <a class="nav-link dropdown-toggle paddingInLists text-dark" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Apps
                      </a>

                      <div class="dropdown-menu   drop3  mmt1" role="menu"  >
                      <div class="row mt-2 ">
              <div class="col-lg-3  col-sm- ">1
             <div class="d-flex ml-5 ">
              <img   class="mt-2" src="{{ asset('public/assets/images/Finance.svg')}}" alt="" style=""  >
              <h5 class=" mt-3 ml-2 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;color:#DC7210; width:192.01px;    border-bottom: 2px solid #DC7210;">Finance</h5>
            </div>
            <div class="">
              <!-- <img src="{{ asset('public/assets/images/Track Projects.svg')}}" alt="" style="" > -->
              <p class="mt-2 "  style="letter-spacing: 0em; margin-left:24%;">Accounting  <br>  Invoicing <br> Expenses  <br> Spreadsheet <br> Documents <br> Sign</p>
            </div>
           
            </div>

              <div class="col-lg-3">2
            <div class="d-flex ">
              <img    class="mt-2"  src="{{ asset('public/assets/images/Total Sales.svg')}}" alt="" style=""  >
              <h5 class=" mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left; color:#B91D68; width:192.01px;    border-bottom: 2px solid #B91D68;">Total Sales</h5>
            </div>
            <div class="">
              <!-- <img src="{{ asset('public/assets/images/Bank reconciliation.svg')}}" alt="" style="" > -->
              <p class="mt-2"  style="letter-spacing: 0em; margin-left:15%;">CRM  <br>  Sales <br> Point of sale  <br> Subscriptions <br> Rental <br> Amazon Connectorgn</p>
            </div>
          
            </div>

            <div class="col-lg-3">3
            <div class="d-flex ">
              <img    class="mt-2"  src="{{ asset('public/assets/images/Warehouse.svg')}}" alt="" style=""  >
              <h5 class=" mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left; color:#1A6D30; width:192.01px;    border-bottom: 2px solid #1A6D30;">INVENTORY & MRP</h5>
            </div>

            <div class="">
              <!-- <img src="{{ asset('public/assets/images/Manage contacts.svg')}}" alt="" style="" > -->
              <p class="mt-2"  style="letter-spacing: 0em; margin-left:15%;"> Inventory  <br>  Manufacturing <br> PLM  <br> Purchase <br> Maintenance <br> Quality</p>
            </div>
        
           </div>

           
           <!-- Employees Recuitment Time Off Appraisals Referrals Fleet -->

              <div class="col-lg-3">5
             <div class="d-flex ">
              <img    class="mt-2"   src="{{ asset('public/assets/images/Human Research Program.svg')}}" alt="" style=""  >
              <h5 class=" mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left; color:#00295B; width:192.01px;    border-bottom: 2px solid #00295B;">HUMAN RESOURCE</h5>
            </div>
            

            <div class="">
              <!-- <img src="{{ asset('public/assets/images/Pay runs.svg')}}" alt="" style="" > -->
              <p class="mt-2"  style="letter-spacing: 0em; margin-left:15%;"> Employees  <br>  Recuitment <br> Time OFF   <br> Appraisals <br> Referrals <br> Fleet</p>
            </div>
                  </div>

            <div class=" mt-5 d-flex">
            <div class="col-lg-3 " style="width:30%">
            <div class="d-flex ml-3 ">
              <img    class="mt-2 "  src="{{ asset('public/assets/images/Marketing.svg')}}" alt="" style=""  >
              <h5 class=" mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left; color:#C5221F; width:192.01px;    border-bottom: 2px solid #C5221F;">Marketing</h5>
            </div>

            <div class="">
              <!-- <img src="{{ asset('public/assets/images/Manage contacts.svg')}}" alt="" style="" > -->
              <p class="mt-2"  style="letter-spacing: 0em; margin-left:18%;">  Social Marketing  <br>  Email Marketing  <br> SMS Marketing  <br> Events <br>  Marketing Automation <br> Surveys</p>
            </div>
            </div>
             <div class="col-lg-3">
            <div class="d-flex ">
              <img    class="mt-2 "  src="{{ asset('public/assets/images/Services.svg')}}" alt="" style=""  >
              <h5 class=" mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left; color:#293180; width:192.01px;    border-bottom: 2px solid #293180;">Services</h5>
            </div>

            <div class="">     
              <!-- <img src="{{ asset('public/assets/images/Manage contacts.svg')}}" alt="" style="" > -->
              <p class="mt-2"  style="letter-spacing: 0em; margin-left:12%;">  Project  <br>  Time Shield <br> Filed Service  <br> Helpdesk <br> Planning <br> Appointments</p>
            </div>
           </div>
            <div class="col-lg-3  ">
            <div class="d-flex ">
              <img    class="mt-2"  src="{{ asset('public/assets/images/Brainstorm Skill.svg')}}" alt="" style=""  >
              <h5 class=" mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left; color:#977000; width:192.01px;    border-bottom: 2px solid #977000">Brainstorm Skill</h5>
            </div>
            <div class="">     
              <!-- <img src="{{ asset('public/assets/images/Manage contacts.svg')}}" alt="" style="" > -->
              <p class="mt-2"  style="letter-spacing: 0em; margin-left:15%;">  Discuss  <br>  Approvals <br> IoT  <br> VoIP <br> Knowledge </p>
            </div>
           </div>
  </div> 

        </div>
        </div>
        </li>
                  

        <li class="nav-item dropdown paddingInLists">
                      <a class="nav-link dropdown-toggle paddingInLists text-dark" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                         Support
                      </a>
                      <div class="dropdown-menu   drop4  mmt1" role="menu"  >
                           <h2 class="heading4">Get support to use monmatics</h2>   
                      <div class="row mt-5  " style="width:60%; margin-left:20%">
             
            <div class="col-lg-4">2
            <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Get Support.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Get Support</h5>
            </div>
           </div>
              <div class="col-lg-4">3
             <div class="d-flex  ">
              <img src="{{ asset('public/assets/images/Guide.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Guide</h5>
            </div>
              </div> 
              <div class="col-lg-4">4
             <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Accounting glossary.svg')}}" alt="" style=""  >
              <h5 class="blue mt-3 ml-3 " style=";font-size: 16px;font-weight: 700;line-height: 22px;letter-spacing: 0em;text-align: left;">Accounting glossary</h5>
            </div>
            </div>
           </div>
            </div>
             </li>                  
                 

              </ul>

              <div class="d-flex">
                  <div class="dropdown" style="padding-right:20px;">
                      <button style="border-radius: 12px" class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Try Monmatics
                      </button>
                      <!-- <ul class="dropdown" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="">Individual</a></li>
                        <li><a class="dropdown-item" href="">Business</a></li>
                        <li><a class="dropdown-item" href="">Accountant/Bookkeeper</a></li>
                      </ul> -->
                    </div>
              </div>
          </div>




  
      </div>
  </nav>
</header>



                @yield('content')
            </div>
        </div>
        <!-- <div class="container-fluid">

        </div> -->
        <!--   <div> // Footer content
                <h3>Powered by Solutions Wave</h3>
            </div>  -->
  <!--===--============-=---------------------============================== FOTTER=START----------------------------------------------------------- -->


            <section class="text-center text-md-start  fotr">
    <footer class="container    ">
      <div class="row">
        <div class="col-md-9 mt-5 ">
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

        <div class="col-md-3">
          <div class="row mt-5">

            <div class="col-md-12 mb-md-0 mb-4 mt-5">

              <a href="">
              <img src="{{ asset('public/assets/images/image 1 (1).png')}}" alt="" >
              </a>
              <h6 class="    text-white text-uppercase fw-bold mt-3 " style="margin-bottom:10px;">Contact us</h6>

              <div class="">

                <a href="" class="">
                    <img src="{{ asset('public/assets/images/🦆 icon _instagram icon_.svg')}}" alt="" >
                    <img src="{{ asset('public/assets/images/🦆 icon _linked in_.svg')}}" alt="" >
                    <img src="{{ asset('public/assets/images/🦆 icon _facebook icon_.svg')}}" alt="" >
                    <img src="{{ asset('public/assets/images/🦆 icon _Gmail_.svg')}}" alt="" >
                    <img src="{{ asset('public/assets/images/Frame 50.svg')}}" alt="" >

                  <!-- <img src="./images/Frame 50.svg" alt=""> -->
                </a>

                <div class="ft4  mt-3" style="  border-bottom-style: solid;
  border-bottom-color: white;">
                
                </div>
<div class="mt-3"> 
  <a href="" class="" style="color:white;">
              <img src="{{ asset('public/assets/images/Geography.svg')}}" alt="" >
                  English
                  <img src="{{ asset('public/assets/images/Down Button.svg')}}" alt="" >
                </a></div>
               

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row pb-2">
        <div class="col-md-9 mb-3  te">

          <a href="">
            <p class="text-white" style="margin-right: 20px;border-bottom: 1.5px solid  white;width:auto">Privacy</p>
          </a>
          <a href="">
            <p class="text-white">Terms and conditions</p>
          </a>
        </div>

        <div class="col-md-3    text-white" style="font-size: 12px;">
          <p>
            © 2024 monmatics Inc. All Rights Reserved.
          </p>
        </div>
      </div>
    </footer>
  </section>


            
    </section>
    @yield('modal')
    <!-- Scripts -->
    @stack('before-scripts')

    <script src="{{ asset('public/assets/bundles/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('public/assets/bundles/vendorscripts.bundle.js') }}"></script>
    <script src="{{ asset('public/assets/bundles/mainscripts.bundle.js') }}"></script>

    <script src="{{ asset('public/assets/plugins/fullcalendar/jqueryui.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery-ui.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    @stack('after-scripts')
    @if (trim($__env->yieldContent('page-script')))
    <script>
            var Tawk_API = Tawk_API || {};

    </script>
    @yield('page-script')
    @endif

</body>
</html>
