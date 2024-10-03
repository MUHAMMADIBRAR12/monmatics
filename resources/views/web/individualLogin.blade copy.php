<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <!-- <link rel="stylesheet" href="https://monmatics.com/pro/web_assets/style.css"> -->
        <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <title>indvidiual</title>
<style>
    .bgc{
  background-image:url('{{asset('public/assets/images/Background.svg')}}');
  background-repeat:100% 100%;
  
}
.bg{
    background: linear-gradient(180deg, #C6D5E8 0%, rgba(255, 255, 255, 0) 100%);
    border: 1px solid #D9D9D9;
    border-radius:10px;
}


.wrapper {

max-width:32rem;
width: 100%;
margin: 2rem auto;
margin-top: 80px;
padding: 2rem 2.5rem;
border: 2px solid #000;
outline: none;
border-radius: 2.25rem;
color:black;
background: #f2f5f8;
}

.form {
/* width:300px; */
/* height: 520px; */
width: 100%;
height:450px;
margin-top: 2rem
}

.input-control-sirname {
display: flex;
align-items: center;
margin-bottom: 1.25rem
}

.input-sirname{
font-family: inherit;
font-size: 1rem;
font-weight: 400;
line-height: inherit;
width:40%;
height: auto;
padding: .3rem 1.25rem;
border: none;
outline: none;
border-radius: 2rem;
color:black;
/* border-bottom: 1px solid #ccc; */
background:white;
background: linear-gradient(180deg, #C6D5E8 0%, rgba(255, 255, 255, 0) 100%);
border: 1px solid #D9D9D9;
border-radius:10px;

}
.input-control {
display: flex;
align-items: center;
justify-content: space-between;
margin-bottom: 1.25rem
}


.input-field {
font-family: inherit;
font-size: 1rem;
font-weight: 400;
line-height: inherit;
width: 80%;
height: auto;
padding: .3rem 1.25rem;
border: none;
outline: none;
border-radius: 2rem;
color:black;
/* border-bottom: 1px solid #ccc; */
background:white;
background: linear-gradient(180deg, #C6D5E8 0%, rgba(255, 255, 255, 0) 100%);
border: 1px solid #D9D9D9;
border-radius:10px;
}
.input-fieldd{
font-family: inherit;
font-size: 1rem;
font-weight: 400;
line-height: inherit;
width: 80%;
height: 27px;;
padding: .3rem 1.25rem;
border: none;
outline: none;
/* border-radius: 2rem; */
color:black;
border-bottom: 1px solid #ccc;
/* border: 1px solid #000; */
background:#F2F5F8;
}
.input-state{
font-family: inherit;
font-size: 1rem;
font-weight: 400;
line-height: inherit;
width:70%;
height: auto;
padding: .3rem 1.15rem;
border: none;
outline: none;
border-radius: 2rem;
color:black;
margin-left:17%;
/* border-bottom: 1px solid #ccc; */
background:white;
background: linear-gradient(180deg, #C6D5E8 0%, rgba(255, 255, 255, 0) 100%);
border: 1px solid #D9D9D9;
border-radius:10px;
}
.input-submit {
font-family: inherit;
font-size: 1rem;
font-weight: 500;
line-height: inherit;
cursor: pointer;
min-width: 40%;
height: auto;
padding: .65rem 1.25rem;
border: none;
outline: none;
border-radius: 2rem;
color:white;
background:orange;
}
.btn-login{
color:orange;
}
a{
text-decoration: none;
}
</style>
</head>
<body>         
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
                            <a href="mailto: hi@monmatics.com"  class="nav-link  UppernavbarFontClass" style="color:black;" id="nav2" href=""><b> <img src="{{ asset('public/assets/images/🦆 icon _Gmail_.svg')}}" alt="" ></b> : hi@monmatics.com</a>
                        </li>
                           <li class="nav-item dropdown">
                            <a href="{{url('authentication/login')}}" class="nav-link mx-2 navbarFontClass  " style="color:  #023C82" id="nav2" role="button" aria-expanded="false">
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
                  <a href="{{url('authentication/login')}}"  class="">
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
          <img class="" src="{{ asset('public/assets/images/Logo new 1.svg')}}" alt="" style="" data-pagespeed-url-hash="309413324" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">
          </a>      
  <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-content">
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
                        <img src="{{ asset('public/assets/images/Accept Payment-1.svg')}}" alt=""   >
                        <h6 class="blue mt-3 ml-2 " style="letter-spacing: 0em;text-align: left;">Accept Payment</h6>
                      </div>
                      <div class="d-flex  mt-3">
                        <img src="{{ asset('public/assets/images/Track Projects.svg')}}" alt="" >
                        <h6 class="blue mt-4  ml-3"   style="letter-spacing: 0em;text-align: left;">Track projects</h6>
                      </div>

                      <div class="d-flex  mt-3">
              <img src="{{ asset('public/assets/images/Reporting.svg')}}" alt="" style="" >
              <h6 class="blue mt-4  ml-3"   style="letter-spacing: 0em;text-align: left;">Reporting</h6>
            </div>

            <div class="d-flex  mt-3">
              <img src="{{ asset('public/assets/images/Analytics.svg')}}" alt="" style="" >
              <h6 class="blue mt-4  ml-3"   style="letter-spacing: 0em;text-align: left;">Analytics.svg</h6>
            </div>
   

                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>Card</h5> -->
                     

                <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Claim expenses-1.svg')}}" alt="" style=""  >
              <h6 class="blue mt-3  ml-2 " >Claim expenses</h6>
            </div>
            <div class="d-flex mt-3">
              <img src="{{ asset('public/assets/images/Bank reconciliation.svg')}}" alt="" style="" >
              <h6 class="blue mt-3  ml-2"   style="">Bank reconciliation</h6>
            </div>

            <div class="d-flex mt-3">
              <img src="{{ asset('public/assets/images/Online invoicing.svg')}}" alt="" style="" >
              <h6 class="blue mt-3  ml-2"   style="letter-spacing: 0em;text-align: left;">Online invoicing</h6>
            </div>
            <div class="d-flex mt-3">
              <img src="{{ asset('public/assets/images/Sales tax.svg')}}" alt="" style="" >
              <h6 class="blue mt-3  ml-2"   style="letter-spacing: 0em;text-align: left;">Sales tax</h6>
            </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>About CodeHim</h5> -->

                      <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Bank connection.svg')}}" alt="" style=""  >
              <h6 class="blue mt-3 ml-3 " >Bank connection</h6>
            </div>
            <div class="d-flex mt-3 ">
              <img src="{{ asset('public/assets/images/Manage contacts.svg')}}" alt="" style="" >
              <h6 class="blue mt-3  ml-3"  >Manage contacts</h6>
            </div>
            <div class="d-flex mt-3 ">
              <img src="{{ asset('public/assets/images/Multi currency.svg')}}" alt="" style="" >
              <h6 class="blue mt-3  ml-3"  >Multi currency</h6>
            </div>
            <div class="d-flex  mt-3">
              <img src="{{ asset('public/assets/images/Accounting (1).svg')}}" alt="" style="" >
              <h6 class="blue mt-3  ml-3"   >Accounting</h6>
            </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 py-4">
                      <!-- <h5>Damn, so many</h5> -->
                     
                      <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Inventory-1.svg')}}" alt="" style=""  >
              <h6 class="blue mt-3 ml-3 " >Inventory</h6>
            </div>
            <div class="d-flex mt-3 ">
              <img src="{{ asset('public/assets/images/Capture data.svg')}}" alt="" style="" >
              <h6 class="blue mt-4  ml-3"  >Capture data</h6>
            </div>
            <div class="d-flex mt-3">
              <img src="{{ asset('public/assets/images/Purchase orders.svg')}}" alt="" style="" >
              <h6 class="blue mt-4  ml-3"   >Purchase orders</h6>
            </div>
            <div class="d-flex mt-3">
              <img src="{{ asset('public/assets/images/Manage fixed assets.svg')}}" alt="" style="" >
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
                        <img src="{{ asset('public/assets/images/Benefits.svg')}}" alt=""   >
                        <h6 class="blue mt-3 ml-2 " style="letter-spacing: 0em;text-align: left;">Benefits</h6>
                      </div>
                      
                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>Card</h5> -->
                     

                      <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Data authentication.svg')}}" alt="" style=""  >
              <h6 class="blue mt-3 ml-2 " >Data authentication</h6>
            </div>
            

                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>About CodeHim</h5> -->

                      <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Business recovery.svg')}}" alt="" style=""  >
              <h6 class="blue mt-3 ml-2" >Business recovery</h6>
            </div>
           
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 py-4">
                      <!-- <h5>Damn, so many</h5> -->
                     
                      <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Smart online accounting.svg')}}" alt="" style=""  >
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
              <img src="{{ asset('public/assets/images/Monmatics HQ.svg')}}" alt=""   >
              <h6 class="blue mt-3 ml-2 " >Monmatics HQ</h6>
            </div>
             
            <div class="d-flex  mt-4" >
              <img src="{{ asset('public/assets/images/Practice manager.svg')}}" alt=""  >
              <h6 class="blue mt-3 ml-2 " >Monmatics Practice Manager </h6>
            </div>


                    </div>

                 


                    <div class="col-lg-6 col-sm-12 col-md-6 py-4  ">
                     
                <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Monmatics Cashback.svg')}}" alt=""  >
              <h6 class="blue mt-3 ml-2" >Monmatics Cashback, Monamtics Ledger</h6>
            </div>
           

            <div class="d-flex mt-4 ">
              <img src="{{ asset('public/assets/images/Workpapers.svg')}}" alt=""  >
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
                        <img src="{{ asset('public/assets/images/Finance.svg')}}" alt=""   >
                        <h6 class=" mt-2 ml-2 " style="color:#DC7210; width:152.01px;    border-bottom: 2px solid #DC7210;">Finance</h6>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;">Accounting  <br>  Invoicing <br> Expenses  <br> Spreadsheet <br> Documents <br> Sign</p>
                   </div>





                   <div class="d-flex  ">
                        <img src="{{ asset('public/assets/images/Marketing.svg')}}" alt=""   >
                        <h6 class=" mt-2 ml-2 " style="color:#C5221F; width:152.01px;    border-bottom: 2px solid #C5221F">Marketing</h6>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;"> Social Marketing  <br>  Email Marketing  <br> SMS Marketing  <br> Events <br>  Marketing Automation <br> Surveys</p>
                   </div>

                  
                  



              

                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>Card</h5> -->
                     

                    



                   <div class="d-flex  ">
                        <img src="{{ asset('public/assets/images/Total Sales.svg')}}" alt=""   >
                        <h6 class=" mt-2 ml-2 " style="color:#B91D68; width:152.01px;    border-bottom: 2px solid #B91D68;"> Sales</h6>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;">CRM  <br>  Sales <br> Point of sale  <br> Subscriptions <br> Rental <br> Amazon Connectorgn</p>
                   </div>


   

                   <div class="d-flex  ">
                        <img src="{{ asset('public/assets/images/Services.svg')}}" alt=""   >
                        <p class=" mt-2 ml-2 " style="color:#293180; width:152.01px;    border-bottom: 2px solid #293180;">Services</p>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;"> Project  <br>  Time Shield <br> Filed Service  <br> Helpdesk <br> Planning <br> Appointments</p>
                   </div>

          
          
                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>About CodeHim</h5> -->

                 
                    


                   <div class="d-flex  ">
                        <img src="{{ asset('public/assets/images/Warehouse.svg')}}" alt=""   >
                        <h6 class=" mt-2 ml-2 " style="color:#1A6D30; width:152.01px;    border-bottom: 2px solid #1A6D30;">INVENTORY & MRP</h6>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;">  Inventory  <br>  Manufacturing <br> PLM  <br> Purchase <br> Maintenance <br> Quality</p>
                   </div>


                   <div class="d-flex  ">
                        <img src="{{ asset('public/assets/images/Brainstorm Skill.svg')}}" alt=""   >
                        <h6 class=" mt-2 ml-2 " style="color:#977000; width:152.01px;    border-bottom: 2px solid #977000;">PRODUCTIVITY</h6>
                      </div>

                   <div class=""> 
                   <p class="mt-2 "  style="letter-spacing: 0em; margin-left:14%;">   Discuss  <br>  Approvals <br> IoT  <br> VoIP <br> Knowledge</p>
                   </div>




                    </div>
                    <div class="col-12 col-sm-12 col-md-3 py-4">
                      <!-- <h5>Damn, so many</h5> -->
                     
           


                      <div class="d-flex  ">
                        <img src="{{ asset('public/assets/images/Human Research Program.svg')}}" alt=""   >
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
              <img src="{{ asset('public/assets/images/Get Support.svg')}}" alt="" style=""  >
              <h6 class="blue mt-3 ml-2 " >Get Support</h6>
            </div>
            

                    </div>
                    <div class="col-12 col-sm-4 col-md-3 py-4">
                      <!-- <h5>About CodeHim</h5> -->

                      <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Guide.svg')}}" alt="" style=""  >
              <h6 class="blue mt-3 ml-2" >Guide</h6>
            </div>
           
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 py-4 ">
                      <!-- <h5>Damn, so many</h5> -->
                     
                      <div class="d-flex ">
              <img src="{{ asset('public/assets/images/Accounting glossary.svg')}}" alt="" style=""  >
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
    <img src="{{ asset('public/assets/images/Male User.svg')}}" alt="" style="margin-left:4%"  > 
    <a class="dropdown-item clr" href="{{ route('individual.login') }}">Individual</a></li>
    <li class="d-flex">
    <img src="{{ asset('public/assets/images/Business.svg')}}" alt=""  style="margin-left:4%"   > 
    <a class="dropdown-item clr" href="{{ route('business.login') }}">Business</a></li>
    <li class="d-flex">
    <img src="{{ asset('public/assets/images/General Ledger.svg')}}" alt=""  style="margin-left:4%"   > 
    <a class="dropdown-item clr" href="{{ route('bookkeeper.login') }}">Accountant/Bookkeeper</a></li>
  </ul>
</div>
   


      </div>
    </div>
  </nav>
</header>
<div class="bgc">
        <img src="" alt="">
    <div class="container-fluid loginBackground pb-5">

        <main class="main">
            <div class="container">
            <section class="wrapper">
                            <div class="">
                                <div class="text-center">
                                    <a class="navbar-brand" href="">
                                        <img class="" src="{{ asset('public/assets/images/image 1 (1).png')}}" alt="" >
                                    </a>
                                </div>

                                <h4 class="text text-center "><b>Create Your Account</b></h4>
                                <h6  class="text-center">Already have an account? <a href="{{url('authentication/login')}}">

                                <span class="btn-login">Log In</span>

                                </a> </h6>

                            </div>
                            <form name=""  method="" class="form">

                                <div class="input-control  position-relative ">
                                    <label for="Sir Name" class=" "><b>Sir Name:</b></label>
                                    <select name="sir_name" class="input-sirname position-absolute  form-control" style="left:20%">
                                        <option value="">Select</option>
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                    </select>
                                </div>

                                <div class="input-control ">
                                    <label for="fname" class="fw-bold">First Name:</label>
                                    <input type="text" name="first_name" id="fname" class="input-fieldd"
                                        placeholder="">
                                </div>
                                <div class="input-control form-group">
                                    <label for="lname" class="fw-bold">Last Name:</label>
                                    <input type="text" name="last_name" id="lname" class="input-fieldd form-control"
                                        placeholder="">
                                </div>
                                <div class="input-control form-group">
                                    <label for="Phone" class="fw-bold">Phone:</label>
                                    <input type="phone" name="phone" id="phone" class="input-fieldd form-control"
                                        placeholder="">
                                </div>
                                <script>
                                    const input = document.querySelector("#phone");
                                    window.intlTelInput(input, {
                                        initialCountry: "us",
                                        strictMode: true,
                                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@20.3.0/build/js/utils.js",
                                    });
                                </script>
                                <div class="input-control form-group">
                                    <label for="email" class="fw-bold">Email:</label>
                                    <input type="email" name="email" id="email" class="input-fieldd form-control"
                                        placeholder="">
                                </div>

                                <div class="input-control form-group">
                                    <label for="Country" class="input-label "><b>Country:</b></label>
                                    <select name="country" class="input-field form-control ">
                                        <option value="">Select a country</option>
                                        <option value="USA">United States</option>
                                        <option value="UK">United Kingdom</option>
                                        <option value="Canada">Canada</option>
                                        <!-- Add more countries as needed -->
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="input-control form-group">
                                            <label for="State" class=" "><b>State:</b></label>
                                            <select name="state" class="input-state  form-control ">
                                                <option value="">Select a State</option>
                                                <option value="USA">United States</option>
                                                <option value="UK">United Kingdom</option>
                                                <option value="Canada">Canada</option>
                                                <!-- Add more countries as needed -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-control form-group">
                                            <label for="Zip" class=" fw-bold">Zip:</label>
                                            <input type="text" name="zip" id="zip" class="input-fieldd "
                                                placeholder="">
                                        </div>
                                    </div>
                                    <div class="input-control text-center" >
                                        <a href="{{ url('individualbusiness/login') }}">Create Account</a>
                                        <!-- <input type="submit" class="input-submit orange" value="Create Account" style="    margin: auto;"> -->
                                    </div>
                                </div>
                            </form>
                        </section>

            </div>
        </main>

        <div class="text-center">
            <h5 class="text-white">© 2024 Monmatics Inc. All Rights Reserved.<p></p>
            </h5>
        </div>

    </div>
    </div>
</body>
</html>