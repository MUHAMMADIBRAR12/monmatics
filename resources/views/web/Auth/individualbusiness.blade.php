@extends('web.master')
@section('page-style')
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
@stop

@section('content')
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

                                <h4 class="text text-center "><b>Business Detail</b></h4>
                                <h6  class="text-center">Already have an account? <a href="{{url('authentication/login')}}">

                                <span class="btn-login">Log In</span>

                                </a> </h6>

                            </div>
                            <form name=""  method="" class="form">
                                <div class="input-control form-group">
                                    <label for="lname" class="fw-bold">Company Name:</label>
                                    <input type="text" name="last_name" id="lname" class="input-fieldd form-control"
                                        placeholder="">
                                </div>
                                <div class="input-control form-group">
                                    <label for="Phone" class="fw-bold">Company Phone:</label>
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
                                    <label for="address" class="fw-bold">Address:</label>
                                    <input type="text" name="address" id="address" class="input-fieldd form-control"
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
                                 </div>
                                    <div class="row">
                                    <div class="col-md-8">
                                    <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image" class="custom-file-input" id="exampleInputFile">
                                        <label class="custom-file-label text-primary" for="exampleInputFile">Upload logo</label>
                                    </div>
                                </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-control form-group">
                                        <a class="btn btn-primary" href="{{ url('company') }}" role="button">Next</a>
                                        </div>
                            </form>
                        </section>

            </div>
        </main>
    </div>
    </div>
@stop