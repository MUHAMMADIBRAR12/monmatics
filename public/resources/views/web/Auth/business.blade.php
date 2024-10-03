<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Monmatics</title>
    <link rel="stylesheet" href="{{ asset('/web_assets/style.css') }}">
    <style>
        .orange{color: #DC7210;}.blue{color:#163C69}
        .navvv{
            background: #163C69;
        }
        .loginBackground{
         background: #fff;
         height: 100%;
        }
        /* .header {
            font-size: 18px;
        } */

        .navbarFontClass {
            font-size: 18px;
            padding-left: 20px;
        }
        :root {
             --color-white: #fff;
             --color-light: #f1f5f9;
             --color-black: #121212;
             --color-night: #001632;
             --color-red: #f44336;
             --color-orange: #DC7210;
             --color-red: #f44336;
             --color-blue: #163C69;
             --color-gray: #80868b;
             --color-grayish: #dadce0;
             --shadow-normal: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
             --shadow-medium: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
             --shadow-large: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }


         a, button {
             font-family: inherit;
             /* font-size: inherit; */
             line-height: inherit;
             cursor: pointer;
             border: none;
             outline: none;
             background: none;
             text-decoration: none;
         }

         .container {
             display: flex;
             justify-content: left;
             align-items: center;
             /* max-width: 80rem;
             min-height: 100vh; */
             width: 100%;
             padding: 0 2rem;
             margin: 0 auto;
         }
         .text {
             font-family: inherit;
             line-height: inherit;
             text-transform: unset;
             text-rendering: optimizeLegibility;
         }
         .text-large {
             font-size: 2rem;
             font-weight: 600;
             color: var(--color-black);
         }
         .text-normal {
             font-size: 1rem;
             font-weight: 400;
             color: var(--color-black);
         }
         .text-links {
             font-size: 1rem;
             font-weight: 400;
             color: var(--color-blue);
         }
         .text-links:hover {
             text-decoration: underline;
         }
         .main .wrapper {
             max-width: 34rem;
             width: 100%;
             margin: 2rem auto;
             margin-top: 80px;
             padding: 2rem 2.5rem;
             border: none;
             outline: none;
             border-radius: 2.25rem;
             color: var(--color-black);
             background: #668FC9;
             box-shadow: var(--shadow-large);
         }
         .main .wrapper .form {
             width: 100%;
             height: auto;
             margin-top: 2rem;
         }
         .main .wrapper .form .input-control {
             display: flex;
             align-items: center;
             justify-content: space-between;
             margin-bottom: 1.25rem;
         }
         .main .wrapper .form .input-field {
             font-family: inherit;
             font-size: 1rem;
             font-weight: 400;
             line-height: inherit;
             width: 80%;
             height: auto;
             padding: 0.75rem 1.25rem;
             border: none;
             outline: none;
             border-radius: 2rem;
             color: var(--color-black);
             background: var(--color-light);
             text-transform: unset;
             text-rendering: optimizeLegibility;
         }
         .main .wrapper .form .input-submit {
    font-family: inherit;
    font-size: 0.8rem; /* Adjust the font size as needed */
    font-weight: 500;
    line-height: inherit;
    cursor: pointer;
    width: auto; /* Remove the min-width property */
    height: auto;
    padding: 0.5rem 1rem; /* Adjust the padding as needed */
    border: none;
    outline: none;
    border-radius: 2rem;
    color: var(--color-white);
    background: var(--color-orange);
    box-shadow: var(--shadow-medium);
    text-transform: capitalize;
    text-rendering: optimizeLegibility;
    float: right; /* Add this property to align the button to the right */
}

         .main .wrapper .striped {
             display: flex;
             flex-direction: row;
             justify-content: center;
             align-items: center;
             margin: 1rem 0;
         }
         .main .wrapper .striped-line {
             flex: auto;
             flex-basis: auto;
             border: none;
             outline: none;
             height: 2px;
             background: var(--color-grayish);
         }
         .main .wrapper .striped-text {
             font-family: inherit;
             font-size: 1rem;
             font-weight: 500;
             line-height: inherit;
             color: var(--color-black);
             margin: 0 1rem;
         }
         .main .wrapper .method-control {
             margin-bottom: 1rem;
         }
         .main .wrapper .method-action {
             font-family: inherit;
             font-size:;
             font-weight: 500;
             line-height: inherit;
             display: flex;
             justify-content: center;
             align-items: center;
             width: 100%;
             height: auto;
             padding: 0.35rem 1.25rem;
             outline: none;
             border: 2px solid var(--color-grayish);
             border-radius: 2rem;
             color: var(--color-black);
             background: var(--color-white);
             text-transform: capitalize;
             text-rendering: optimizeLegibility;
             transition: all 0.35s ease;
         }
         .main .wrapper .method-action:hover {
             background: var(--color-light);
         }
         @media (max-width: 767px) {
            .hidden-mobile {
                display: none;
            }
            .container-fluid{font-size: 10px;}
            .main .wrapper .form .input-field {
             font-family: inherit;
             font-size: 10px;
             font-weight: 400;
             line-height: inherit;
             width: 80%;
             height: auto;
             padding: 0.75rem 1.25rem;
             border: none;
             outline: none;
             border-radius: 2rem;
             color: var(--color-black);
             background: var(--color-light);
             text-transform: unset;
             text-rendering: optimizeLegibility;
         }
        }


    </style>
  </head>
  <body>
        {{-- @include('web.layouts.header') --}}
        <nav class="navbar navbar-expand-lg  ">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{url('/')}}">
                    <img class="" src="{{asset('public/assets/web_assets/images/home/Back.svg')}}" alt="" style="height: 40px; margin-left: 30px;
                    "></a>
            </div>
        </nav>

        <div class="container-fluid loginBackground pb-5" >
           <div class="row">
            <div class="col-md-6">
                <img class="hidden-mobile" src="{{asset('public/assets/web_assets/images/Background image.svg')}}" alt="" style="margin-top: 10px; margin-left: 70px">
            </div>
            <div class="col-md-6">
                <main class="main">
                    <div class="container">
                        <section class="wrapper">
                            <div class="heading">
                                <div class="text-center">
                                    <a class="navbar-brand" href="{{url('/')}}">
                                        <img class="" src="{{asset('public/assets/web_assets/images/image 1.svg')}}" alt="" style="height: 30px;">
                                    </a>
                                </div>
                                <h4 class="text text-center"><b>Create Your Account</b></h4>
                                {{-- <p class="text text-normal text-center">Already have an account?<span><a href="#" class="text text-links"> <span class="orange">Log In</span></a></span></p> --}}
                            </div>
                            <form name="signin" class="form" action="{{ route('create_business') }}" method="POST">
                                @csrf
                                <div class="input-control form-group">
                                    <label for="bname" class="input-label">Business name</label>
                                    <input type="text" name="business_name" id="bname" class="input-field form-control" placeholder="Business name">
                                </div>
                                <div class="input-control form-group">
                                    <label for="btype" class="input-label">Business type</label>
                                    <input type="text" name="business_type" id="btype" class="input-field form-control" placeholder="Business type">
                                </div>
                                <div class="input-control form-group">
                                    <label for="no_of_users" class="input-label">Number of users</label>
                                    <input type="phone" name="number_of_users" id="no_of_users" class="input-field form-control" placeholder="Number of users">
                                </div>
                                <div class="input-control form-group">
                                    <label for="Country" class="input-label">Country</label>
                                    <select name="country" class="input-field form-control">
                                        <option value="">Select a country</option>
                                        <option value="USA">United States</option>
                                        <option value="UK">United Kingdom</option>
                                        <option value="Canada">Canada</option>
                                        <!-- Add more countries as needed -->
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-control form-group">
                                            <label for="State" class="input-label">State</label>
                                            <select name="state" class="input-field form-control">
                                                <option value="">Select a State</option>
                                                <option value="USA">United States</option>
                                                <option value="UK">United Kingdom</option>
                                                <option value="Canada">Canada</option>
                                                <!-- Add more countries as needed -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-control form-group">
                                            <label for="Zip" class="input-label">Zip</label>
                                            <input type="text" name="zip" id="zip" class="input-field form-control" placeholder="Zip">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="input-submit orange" >
                                                <img src="{{ asset('public/assets/web_assets/images/home/Forward Button.svg') }}" style="width: 30px; height: 30px;" alt="Forward Button">
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <p> Already have an account? <span class="orange">Log In</span></p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>
                </main>
            </div>

           </div>
            <div class="container">
                <div class="text-left">
                    <h5 class="text-dark" >© 2023 Monmatics Inc. All Rights Reserved.</p>
                </div>
            </div>
        </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
