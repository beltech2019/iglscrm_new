@extends('auth.layouts')

@section('content')
<style>

.navbar {
    display: none;
}
body{
    background:#fff !important;
}
#main{
    margin-left: 0 !important;
}
.sidenav{
    display:none !important;
}
.container-fluid{
    padding:0;
}
.col-md-8{
    padding:0;

}
.row{
    margin:0;
}

</style>
<div class="mobile_480_bg">
<div class="row ">
    <div class="col-md-8">
        <div class="login_image">
            <div class="heaidng animate__animated animate__fadeInDownBig">
                <h2>IGL <span>Social Media</span> CRM</h2>
                <p>A platform which accelerates collaboration</p>
            </div>
            <div class="images_manage">
                <img src="images/sun.png" class="img1 hidemobile animate__animated animate__fadeInDown">
                <img src="images/cloud1.png" class="img2 hidemobile animate__animated animate__fadeInDown">
                <img src="images/cloud2.png" class="img3 hidemobile animate__animated animate__fadeInDown">
                <img src="images/cloud3.png" class="img4 hidemobile animate__animated animate__fadeInDown">
                <img src="images/gas_pump.png" class="img5 hidemobile animate__animated animate__fadeInRight">
                <img src="images/ca-unscreen.gif" class="img6 car">
                <img src="images/rabit3.gif" class="img18 car9">
                <img src="images/road.png" class="img7 hidemobile animate__animated animate__fadeInUp">
                <img src="images/tree2.gif" class="img9 hidemobile animate__animated animate__fadeInUp">
                <img src="images/town.png" class="img8 hidemobile animate__animated animate__fadeInUp">
                <img src="images/car2.gif" class="img10 car2">
                <img src="images/car4.gif" class="img11 car3">
                <img src="images/bike2.gif" class="img12 car4">
                <img src="images/bird.gif" class="img13 car7">
                <img src="images/plane.gif" class="img15 car5">
                <img src="images/bird2.gif" class="img14 car6">
            </div>
        </div>
    </div>
    <div class="col-md-4 position_for_mobile">
        <div class="flip-container">
            <div class="flipper" id="flipper">
                <div class="front">
                <div class="logo">
                            <img src="images/company_logo.png" alt="logo" class="logo_company">
                        </div>
                    <div class="login">
                       
                        <h2 class="loginh2">Login</h2>
                        <div class="">
                            <form action="{{ route('authenticate') }}" method="post">
                                @csrf
                                <div class="mb-3 ">
                                    <label for="email" class="form-label">Email Address</label>

                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Email Address" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif

                                </div>
                                <div class="mb-3 ">
                                    <label for="password" class="form-label">Password</label>

                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Password" id="password" name="password">
                                    @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif

                                </div>
                                
                                <div class="mb-3 ">
                                    <input type="submit" class=" btn btn-primary" value="Login">
                                </div>
                                <div class="mb-3 row">
                                    <p class="text-center fontforget_passowrd">Forget Password?<a href="#" class="flipbutton" id="loginButton">
                                        Click Here</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="back ">
                    <div class="logo">
                        <img src="images/company_logo.png" alt="logo" class="logo_company">
                    </div>
                    <div class="login">
                        <h2 class="loginh2">{{ __('Forgot Password') }}</h2>
                        <div class="">
                            <form method="POST" action="{{ route('password') }}">
                                @csrf
                                <div class="form-group  mb-3">
                                    <label for="email"
                                        class="form-label">{{ __('Email Address') }}</label>
                                    <div class=" ">
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group  mb-0">
                                    <div class="">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Send Password Reset Link') }}
                                        </button>
                                    </div>
                                </div>
                                <p class="text-center fontforget_passowrd mt-4">Back to Login<a href="#" class="flipbutton" id="registerButton">
                                    Click Here</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
var loginButton = document.getElementById("loginButton");
var registerButton = document.getElementById("registerButton");

loginButton.onclick = function() {
    document.querySelector("#flipper").classList.toggle("flip");
}

registerButton.onclick = function() {
    document.querySelector("#flipper").classList.toggle("flip");
}
</script>
@endsection