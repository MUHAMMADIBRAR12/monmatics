
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://monmatics.com/pro/web_assets/style.css">

<style>
        
        .navvv {
            background-image:url('{{asset('public/assets/images/Untitled.png')}}');
            background-repeat: round;

        }
        .navbarFontClass {
            font-size: 18px;
            padding-left: 20px
        }       
       .wrapper {
            max-width: 34rem;
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
            width: 100%;
            height: auto;
            margin-top: 2rem
        }

     .input-control-sirname {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem
        }

     .input-control {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem
        }

        .input-field-sirname {
            font-family: inherit;
            font-size: 1rem;
            font-weight: 400;
            line-height: inherit;
            width: 30%;
            height: auto;
            padding: .75rem 1.25rem;
            border: none;
            outline: none;
            border-radius: 2rem;
            color: black;
            background: white;
        }

       .input-field {
            font-family: inherit;
            font-size: 1rem;
            font-weight: 400;
            line-height: inherit;
            width: 80%;
            height: auto;
            padding: .75rem 1.25rem;
            border: none;
            outline: none;
            border-radius: 2rem;
            color:black;
            background:white;
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

 .striped {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            margin: 1rem 0
        }

      .striped-line {
            flex: auto;
            flex-basis: auto;
            border: none;
            outline: none;
            height: 2px;
        }

        .main .wrapper .striped-text {
            font-family: inherit;
            font-size: 1rem;
            font-weight: 500;
            line-height: inherit;
            color:black;
            margin: 0 1rem
        }

        .method-control {
            margin-bottom: 1rem
        }

       .method-action {
            font-family: inherit;
            font-weight: 500;
            line-height: inherit;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: auto;
            padding: .35rem 1.25rem;
            outline: none;
            border-radius: 2rem;
            color:black;
            background:white;
            text-transform: capitalize;
            text-rendering: optimizeLegibility;
            transition: all .35s ease
        }

       
    </style>
     <nav class="navbar navbar-expand-lg  ">
        <div class="container-fluid">
            <a class="navbar-brand" href="">
                <img class="" src="./images-5/Back Arrow-1.svg" alt="" style="height: 40px; margin-left: 30px;
                    ">
            </a>
        </div>
    </nav>

    <div class="container-fluid  ">
        <div class="row">

            <div class="col-md-6">
                <main class="main">
                    <div class="container">
                        <section class="wrapper">
                            <div class="heading">
                                <div class="text-center">
                                    <a class="navbar-brand" href="">
                                        <img class="" src="{{ asset('public/assets/images/image 1 (1).png')}}" alt="" style="height: 30px;">
                                    </a>
                                </div>
                                <h4 class="text text-center"><b>Create Your Account</b></h4>

                            </div>
                            <form name="signin" action="" method="post" class="form">

                                <input type="hidden" name="" value="">
                                <div class="input-control form-group">
                                    <label for="Sir Name" class="input-label "><b>Sir Name</b></label>
                                    <select name="sir_name" class="input-field form-control">
                                        <option value="">Select</option>
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                    </select>
                                </div>

                                <div class="input-control form-group">
                                    <label for="fname" class="input-label">First Name</label>
                                    <input type="text" name="first_name" id="fname" class="input-field form-control"
                                        placeholder="First Name">
                                </div>
                                <div class="input-control form-group">
                                    <label for="lname" class="input-label">Last Name</label>
                                    <input type="text" name="last_name" id="lname" class="input-field form-control"
                                        placeholder="Last Name">
                                </div>
                                <div class="input-control form-group">
                                    <label for="Phone" class="input-label">Phone</label>
                                    <input type="phone" name="phone" id="phone" class="input-field form-control"
                                        placeholder="Phone">
                                </div>
                                <div class="input-control form-group">
                                    <label for="email" class="input-label">Email</label>
                                    <input type="email" name="email" id="email" class="input-field form-control"
                                        placeholder="Email Address">
                                </div>

                                <div class="input-control form-group">
                                    <label for="Country" class="input-label "><b>Country</b></label>
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
                                            <label for="State" class="input-label "><b>State</b></label>
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
                                            <input type="text" name="zip" id="zip" class="input-field form-control"
                                                placeholder="Zip">
                                        </div>
                                    </div>
                                    <div class="input-control">
                                        <input type="submit" class="input-submit orange" value="Create Account">
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>
                </main>
            </div>
            <div class="col-md-5   d-none d-md-block -mobile navvv">

            </div>
        </div>
        <div class="container">
            <div class="text-left">
                <h5 class="text-dark">© 2024 Monmatics Inc. All Rights Reserved.<p></p>
                </h5>
            </div>
        </div>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
