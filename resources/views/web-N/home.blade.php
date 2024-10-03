@extends('web.master')
@section('page-style')
<script>


</script>
@stop
@section('content')

<div class="row clearfix">
    <div class="im1">
    <div class="container ">
      <div class="row im">
        <div class="col-md-6">
          <h1 class="mmt">Accounting software to help <br>you keep track of your finances</h1>
          <p class="text-white ">The only platform you'll ever need to run your <br>
            business: integrated apps, kept simple,
            and
            <br>
            adored by users.
          </p>
          <a href="">
            <button type="button" class="bu   mt-3 buttonUnleash ">Try monmatics</button>
          </a>
        </div>
        <div class="col-md-6 d-md-block  d-none  mt-5   ">
          <div>
            <img class="img-fluid" src="{{ asset('public/assets/images/Main screen video 1.svg')}}" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <!-- --------------------------------------------     section-2   ------------------------------------------------------------------------------>
  <section>
      <div class="   container  mt-5   mb-5    ">
      <h2 class="text-center  mb-5"> <span class="blue">Features</span> for elevating your business</h2>


      <div class="row">
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
   
      <section>
        <div class="container">
        <h2 class="text-center  mt-5 "> <span class="blue  "> Apps</span> for your business needs</h2>
        <div class="row">
          <div class="col-md-4 mb-5">
            <h6 class=" text-center mb-5 mt-5  pt-2 blue ">BOOST YOUR SALES</h6>
            <div class="  Apps-image-container  d-flex  justify-content-around ">

              <div class="Apps-image-item">
                <img src="{{ asset('public/assets/images/CRM.svg')}}" alt="">
                <div class="ml-3 mt-3">CRM</div>
              </div>
              <div>
                <img  src="{{ asset('public/assets/images/POS.svg')}}" alt="">
                <div class="ml-3 mt-3">POS</div>
              </div>
              <div>
                <img  src="{{ asset('public/assets/images/Sales.svg')}}" alt="">
                <div class="ml-3 mt-3 ">SALES</di>
                </div>
              </div>
            </div>
          </div>



          <div class="col-md-4  mb-5 ">
            <h6 class="blue   text-center mb-5 mt-5">INTERGRATE YOUR SERVICES</h6>
            <div class="d-flex  r3  mt-2">
              <div>
                <img src="{{ asset('public/assets/images/Project.svg')}}" >
                <div class="ml-1  mt-3">Project</div>
              </div>
              <div>
                <img src="{{ asset('public/assets/images/Timesheet.svg')}}"  alt="">
                <div class=" mt-3">Timesheet</div>
              </div>
              <div>
                <img class="" src="{{ asset('public/assets/images/HelpDesk.svg')}}"  alt="">
                <div class="ml-1 mt-3 ">HelpDesk</div>
              </div>
            </div>
          </div>




          <div class="col-md-4  text-center mb-5 mt-5 ">
            <h6 class="blue  b2">STREAMLINE YOUR OPERATIONS</h6>
            <div class="d-flex  r3  mt-5">
              <div>
                <img src="{{ asset('public/assets/images/Inventory.svg')}}" alt="">
                <div class="ml-1  mt-3">Inventory</div>
              </div>

              <div>
                <img src="{{ asset('public/assets/images/MRP.svg')}}" alt="">
                <h6 class="ml-3 mt-3">MRP</h6>
              </div>
              <div>
                <img class="" src="{{ asset('public/assets/images/Purchase.svg')}}" alt="">
                <h6 class="ml-1 mt-3 ">Purchase</h6>
              </div>
            </div>
          </div>
        </div>




        <div class="row    d-flex justify-content-center ">
          
        <div class=" col-md-4 mb-5">
            <h6 class="blue   text-center mb-5 mt-5  b2">MANAGE YOUR FINANCES</h6>
            <div class="d-flex    mt-5  justify-content-evenly">
              <div>
                <img src="{{ asset('public/assets/images/Invoicing.svg')}}">
                <div class="ml-1  mt-3">Inventory</div>
              </div>
              <div>
                <img src="{{ asset('public/assets/images/Accounting.svg')}}" alt="">
                <div class=" mt-3">Monmatics.sh</div>
              </div>

            </div>
          </div>


          <div class=" col-md-4 mb-5">
            <h6 class="blue   text-center mb-5 mt-5  b2">CUSTOMIZE AND DEVELOP</h6>
            <div class="d-flex    mt-5  justify-content-evenly">
              <div>
                <img src="{{ asset('public/assets/images/Inventory.svg')}}">
                <div class="ml-1  mt-3">Inventory</div>
              </div>
              <div>
                <img src="{{ asset('public/assets/images/monmatics.sh.svg')}}" alt="">
                <div class=" mt-3">Monmatics.sh</div>
              </div>

            </div>
          </div>
        </div>

        <div class="row  center-button">
          <div class="col-md-4 offset-md-5 mt-4 mb-5">
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
    <div class="container-fluid    ">
      <section class="mb-5">
        <div class="container-fluid p-4 r6">
          <div class="row ">
            <div class="col-sm-12 col-md-7     col-lg-6    ss">
              <div class="pt-5 pb-3 ">
                <h1 class="c4 blue  mt-3">The CRM platform adored by millions</h1>
                <p class="c5 ">Monmatics integrates your sales, marketing, and support
                  teams by letting the platform handle the work so they
                  can focus on increasing productivity, growing your
                  business, and engaging customers in critical moments.</p>
                <p class="c5 ">With Monmatics' seamless integration, your company gains a comprehensive view of
                  customer interactions, streamlining workflows and enabling data-driven decisions. By automating
                  repetitive
                  tasks and providing real-time insights, Monmatics empowers your teams to deliver exceptional customer
                  experiences, boost revenue, and achieve sustainable business growth.</p>
              </div>
            </div>
            <div class="r7 col-md-5 pt-5    d-md-block  d-none   col-6 pb-3 -image   ">
            <img src="{{ asset('public/assets/images/Frame 77.svg')}}" alt="">
            </div>
          </div>
        </div>
      </section>
    </div>
  </section>


    <!--------------------------------- <section>5</section> ------------------->

    <section>
    <div class="container  mb-5 c7    ">
      <div class="row">

        <div class="col-md-5 pr-3  offset-md-1  mt-1  c8 ">
          <div class="row ">
            <div class="col-md-12 ">
              <h3 class="text-center  blue  r8 mt-2 ">Access Anytime</h3>
              <p class="p-4 text-center">Track your business on the move and have confidence in your figures no matter
                where
                you are</p>
                <div class="col-12">
                <!-- <h6  class="m11 text-center" style=" color: orange; width:43%; border-bottom: 2px solid orange;">Monmatics Accounting app</h6> -->
                <h6 class="text-center  " style="color:#dc7210;border-bottom: 2px solid #dc7210; width:61%;margin-left:15%">Monmatics Accounting app</h6>
                </div>
             
              <div class="text-center  ">
              <img src="{{ asset('public/assets/images/WhatsApp Image 2023-07-06 at 10.41 1.svg')}}" alt="">
              </div>
            </div>
          </div>
        </div>


        <div class=" col-md-5   pr-3 c9 mt-1  offset-md-1">
          <div class="row ">
            <div class="col-12 ">
              <h3 class="text-white  text-center mt-4">For accountants and bookkeepers</h3>
               <p class="text-center  text-white p-3">With Monmatics accounting software, you can keep your practise one
                step
                ahead of the competition.</p>
                <h6 class="m3 text-center">Monmatics for Accountants and Bookkeepers</h6>

               <div class="text-center ">
              <img src="{{ asset('public/assets/images/Rectangle 34.svg')}}" alt=""  style="border:1px solid black" >
              </div>
              
            </div>
          </div>
        </div>





      </div>
    </div>
  </section>






  <!-- ------------------------------------<section>6</section>-------------------------------------- -->

  <section>
    <div class="container-fluid    UnleashGradiant    ">
      <div class="container  ">
        <div class="row">
          <div class="col-md-7 pt-5">
            <h1 class="blue unleashTest">Unleash automation's power,<br> nurturing your every need</h1>
          </div>
          <div class="col-md-5 p-5">
            <button class="buttonUnleash mt-4"> Try monmatics </button>
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