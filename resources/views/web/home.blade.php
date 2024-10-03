@extends('web.master')
@section('page-style')
<script>


</script>
@stop
@section('content')


<div class="im1  ">
    <div class="container  ">
      <div class="  row  custom-maxWidthim    ">
        <div class="col-md-7   ">
          <h1 class="mmt mt-5 ">Accounting software to help <br>you keep track of your finances</h1>
          <p class="text-white">The only platform you'll ever need to run your <br>
            business: integrated apps, kept simple,
            and<br>adored by users.
          </p>
          <a href="">
            <button type="button" class=" rounded  mt-3 buttonUnleash ">Try monmatics</button>
          </a>
        </div>

        <div class=" col-md-5  d-md-block  d-none  mt-5  " >
            <img class="" src="{{ asset('public/assets/images/Main screen video 1.svg')}}" alt="" style="width:98%">
        </div>

        <!-- d-flex  justify-content-evenly -->


        
      </div>
    </div>
  </div>


  <!-- --------------------------------------------     section-2   ------------------------------------------------------------------------------>
  <section>
      <div class="   container-fluid  mt-5   mb-5    ">
      <h2 class="text-center  mb-5"> <span class="blue">Features</span> for elevating your business</h2>


      <div class="row  custom-maxWidth">
        <div class="col-12  mb-5">
          <div class="image-container d-flex">


            <div class="image-item">
              <img src="{{ asset('public/assets/images/Accept Payment.svg')}}" alt="">
              <h5 class="blue mt-3">Accept Payment</h5>
            </div>

            <div class="image-item">
              <img src="{{ asset('public/assets/images/Claim expenses.svg')}}" alt="">
              <h5 class="blue mt-3">Claim expenses</h5>
            </div>

            <div class="image-item">
              <img src="{{ asset('public/assets/images/Bank connections.svg')}}" alt="">
              <h5 class="blue mt-3">Bank connections</h5>
            </div>

            <div class="image-item">
              <img src="{{ asset('public/assets/images/Pay bills.svg')}}" alt="">
              <h5 class="blue mt-3 ml-4">Pay bills</h5>
            </div>

            <div class="image-item">
              <img src="{{ asset('public/assets/images/Ellipse 7.svg')}}" alt="">
              <h5 class="blue  m mt-3">
                See all features
              </h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    </section>

    <!-- -------------------------------------------<section-3 -start>=------------------------------ -->
   
      <section >
        <div class="container-fluid" style="background-color:  #F0F0F0;" >
        <h2 class="text-center pt-5"> <span class="blue"> Apps</span> for your business needs</h2>
        <div class="row  custom-maxWidth">
          <div class="col-md-4 ">
            <h6 class=" text-center mb-3  mt-5  pt-2 blue  fw-bold ">BOOST YOUR SALES</h6>
            <div class="  Apps-image-container  d-flex  justify-content-evenly  ">

              <div class="Apps-image-item  scl">
                <img   src="{{ asset('public/assets/images/CRM.svg')}}" alt="">
                <div class="ml-3 mt-1">CRM</div>
              </div>
           
                <div class="scl">
                <img  src="{{ asset('public/assets/images/POS.svg')}}" alt="">
                <div class="ml-3 mt-1">POS</div>
                </div>
           
           
                <div class="scl">
                <img  src="{{ asset('public/assets/images/Sales.svg')}}" alt="">
                <div class="ml-3 mt-1">SALES</di> 
                </div>
                
                
              </div>
            </div>
          </div>



          <div class="col-md-4  mb-5 ">
            <h6 class="blue   text-center mb-3 mt-5 fw-bold ">INTERGRATE YOUR SERVICES</h6>
            <div class="d-flex  r3  mt-2">
              <div class="scl">
                <img src="{{ asset('public/assets/images/Project.svg')}}" >
                <div class="ml-1  mt-2">Project</div>
              </div>
              <div class="scl">
                <img src="{{ asset('public/assets/images/Timesheet.svg')}}"  alt="">
                <div class=" mt-2">Timesheet</div>
              </div>
              <div class="scl">
                <img class="" src="{{ asset('public/assets/images/HelpDesk.svg')}}"  alt="">
                <div class="ml-1 mt-2 ">HelpDesk</div>
              </div>
            </div>
          </div>




          <div class="col-md-4  mb-3  mt-5 ">
            <h6 class="blue  b2  text-center fw-bold">STREAMLINE YOUR OPERATIONS</h6>
            <div class="d-flex  r3  mt-3">
              <div class="scl">
                <img src="{{ asset('public/assets/images/Inventory.svg')}}" alt="">
                <div class="ml-1  mt-2">Inventory</div>
              </div>

              <div class="scl">
                <img src="{{ asset('public/assets/images/MRP.svg')}}" alt="">
                <h6 class="ml-3 mt-2">MRP</h6>
              </div>
              <div class="scl">
                <img class="" src="{{ asset('public/assets/images/Purchase.svg')}}" alt="">
                <h6 class="ml-1 mt-2 ">Purchase</h6>
              </div>
            </div>
          </div>
        </div>




        <div class="row    d-flex justify-content-center ">
          
        <div class=" col-md-4 mb-3">
            <h6 class="blue   text-center mb-3 mt-4  b2  fw-bold">MANAGE YOUR FINANCES</h6>
            <div class="d-flex    mt-3  justify-content-evenly">
              <div class="scl">
                <img src="{{ asset('public/assets/images/Invoicing.svg')}}">
                <div class="ml-1  mt-1">Inventory</div>
              </div>
              <div class="scl">
                <img src="{{ asset('public/assets/images/Accountingg.svg')}}" alt="">
                <div class=" mt-1">Accounting</div>
              </div>

            </div>
          </div>


          <div class=" col-md-4 mb-3">
            <h6 class="blue   text-center mb-3 mt-4  b2  fw-bold">CUSTOMIZE AND DEVELOP</h6>
            <div class="d-flex    mt-3  justify-content-evenly">
              <div class="scl">
                <img src="{{ asset('public/assets/images/Studio.svg')}}">
                <div class="ml-1  mt-1">Studio</div>
              </div>
              <div class="scl">
                <img src="{{ asset('public/assets/images/monmatics.sh.svg')}}" alt="">
                <div class=" mt-1">Monmatics.sh</div>
              </div>

            </div>
          </div>
        </div>

        <div class="row  center-button">
          <div class="col-md-4 offset-md-5 mt-4 mb-5 p-3">
            <a class="btn bu" href="">
              <span class="text-light">
                See all apps
              </span>
            </a>
          </div>
        </div>
        <a class=" mt-3"></a>
        </div>
      </section>



          <!-- ---------------------------------------------------------------section-4=------------------------------------------------------------------------------- -->


  <section>
        <div class="container  ">
          <div class="row">
            <div class="  col-md-7   col-lg-7  ">
              <div class=" " style="margin-top:20%">
                <h3 class=" blue  " style="font-family: Open Sans;font-size: 35px;font-weight: 700; ">The CRM platform adored by millions</h3>
                <p class=" " style="font-family: Open Sans;font-size: 20px;">Monmatics integrates your sales, marketing, and support
                  teams by letting the platform handle the work so they
                  can focus on increasing productivity, growing your
                  business, and engaging customers in critical moments.</p>
              </div>
               </div>
            <div class="   col-md-5 col-lg-5  d-md-block  d-none  ">
            <img src="{{ asset('public/assets/images/Frame 77.svg')}}"  class="mt-5" style="width:100%"  alt="">
            </div>

          </div>
        </div>
  </section>


    <!--------------------------------- <section>5</section> ------------------->
   



  <section>
    <div class="container ">
      <div class="row d-flex justify-content-evenly mt-5 mb-5 ">
        <!-- <div class="col-1"></div> -->
        <div class="  mt-1  c8  col-lg-5 col-md-5   ">
        <h3 class="text-center  blue  r8 mt-5 ">Access Anytime</h3>
        <p class="p-2  text-center">Track your business on the move and have confidence in your figures no matter where you are</p>
        <h6 class="m3 text-center"  >  <span style="border-bottom: 3px solid orange;">Monmatics Accounting app</span></h6>
        <div class="text-center  " style="margin-top:25px">
        <img src="{{ asset('public/assets/images/WhatsApp Image 2023-07-06 at 10.41 1.svg')}}" alt="">
        </div>
      </div>
        <div class=" c9 mt-1 col-lg-5  col-md-5 ">
        <h3 class="text-white  text-center mt-5">For accountants and bookkeepers</h3>
                <p class="text-center  text-white p-2">With Monmatics accounting software, you can keep your practise one step ahead of the competition.</p>
                <div class="m3 ">
                  <h6 class="text-center"> <span style=" border-bottom: 2px solid orange; "> Monmatics for Accountants and Bookkeepers</span></h6>
                </div>
                <div>
                  <div class="text-center">

                    <img class="ml-4" src="{{ asset('public/assets/images/Rectangle 34.svg')}}" alt="" style="width: 80%">
                  </div>
      </div>
      </div>
    </div>
  </section>
  <!-- ------------------------------------<section>6</section>-------------------------------------- -->

  <section class="">
    <div class="UnleashGradiant ">
      <div class="container-fluid">
        <div class="row custom-maxWidth">
          <div class="  col-md-7  col-sm-8">
            <h1 class="blue unleashTest mt-4 offset-2" >Unleash automation's power,<br> nurturing your every need</h1>
          </div>
          <div class="col-md-5 col-sm-4 p-5">
            <button class="buttonUnleash mt-3"> Try monmatics </button>
          </div>
        </div>
      </div>
    </div>
  </section>



  



@stop
@section('page-script')
<script src="{{asset('public/assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('public/assets/js/pages/forms/dropify.js')}}"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>

</script>
@stop