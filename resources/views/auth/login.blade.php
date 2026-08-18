@extends('auth.layouts')

@section('content')
<style>

.navbar {
    display: none;
}
body{
    background:#04120c !important;
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
.row{
    margin:0;
}

</style>

<div class="ig-auth">
    <div class="ig-auth-scene" aria-hidden="true">

        <!-- ============ layer 1: sky, sun, stars ============ -->
        <div class="ig-sky">
            <div class="ig-sun"></div>
            <span class="ig-star" style="top:12%;left:18%;"></span>
            <span class="ig-star" style="top:20%;left:72%;"></span>
            <span class="ig-star" style="top:8%;left:52%;"></span>
            <span class="ig-star" style="top:30%;left:38%;"></span>
            <span class="ig-star" style="top:16%;left:88%;"></span>

            <svg class="ig-cloud ig-cloud-a" viewBox="0 0 120 44" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="30" cy="28" rx="28" ry="14"/><ellipse cx="60" cy="18" rx="22" ry="18"/><ellipse cx="90" cy="26" rx="26" ry="15"/>
            </svg>
            <svg class="ig-cloud ig-cloud-b" viewBox="0 0 120 44" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="30" cy="28" rx="24" ry="12"/><ellipse cx="58" cy="20" rx="20" ry="16"/><ellipse cx="86" cy="27" rx="22" ry="13"/>
            </svg>
            <svg class="ig-cloud ig-cloud-c" viewBox="0 0 120 44" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="26" cy="27" rx="20" ry="11"/><ellipse cx="50" cy="19" rx="18" ry="14"/><ellipse cx="76" cy="26" rx="20" ry="12"/>
            </svg>

            <svg class="ig-bird ig-bird-a" viewBox="0 0 40 20" xmlns="http://www.w3.org/2000/svg">
                <path class="wing-l" d="M20 12 C14 4, 6 3, 0 8 C7 8, 13 10, 20 12Z"/>
                <path class="wing-r" d="M20 12 C26 4, 34 3, 40 8 C33 8, 27 10, 20 12Z"/>
            </svg>
            <svg class="ig-bird ig-bird-b" viewBox="0 0 40 20" xmlns="http://www.w3.org/2000/svg">
                <path class="wing-l" d="M20 12 C14 4, 6 3, 0 8 C7 8, 13 10, 20 12Z"/>
                <path class="wing-r" d="M20 12 C26 4, 34 3, 40 8 C33 8, 27 10, 20 12Z"/>
            </svg>
            <svg class="ig-bird ig-bird-c" viewBox="0 0 40 20" xmlns="http://www.w3.org/2000/svg">
                <path class="wing-l" d="M20 12 C14 4, 6 3, 0 8 C7 8, 13 10, 20 12Z"/>
                <path class="wing-r" d="M20 12 C26 4, 34 3, 40 8 C33 8, 27 10, 20 12Z"/>
            </svg>
        </div>

        <!-- ============ layer 2: skyline / hills ============ -->
        <svg class="ig-hills" viewBox="0 0 1600 300" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path class="hill-far" d="M0,220 C220,150 420,240 680,180 C960,120 1200,220 1600,160 L1600,300 L0,300 Z"/>
            <path class="hill-near" d="M0,260 C260,200 520,270 820,210 C1100,160 1360,250 1600,210 L1600,300 L0,300 Z"/>
            <g class="ig-skyline">
                <rect x="120" y="150" width="26" height="110"/>
                <rect x="160" y="120" width="20" height="140"/>
                <rect x="1360" y="140" width="24" height="120"/>
                <rect x="1400" y="170" width="18" height="90"/>
            </g>
        </svg>

        <!-- ============ layer 3: CNG station structure ============ -->
        <svg class="ig-station" viewBox="0 0 1600 420" preserveAspectRatio="xMidYMax meet" xmlns="http://www.w3.org/2000/svg">
            <!-- canopy -->
            <rect class="canopy-glow" x="560" y="118" width="480" height="10" rx="5"/>
            <rect class="canopy" x="540" y="70" width="520" height="34" rx="10"/>
            <rect class="pillar" x="600" y="104" width="22" height="180" rx="4"/>
            <rect class="pillar" x="980" y="104" width="22" height="180" rx="4"/>
            <rect class="backwall" x="660" y="104" width="280" height="180" rx="6"/>
            <text class="station-sign" x="800" y="60" text-anchor="middle">IGL &#8226; CNG STATION</text>

            <!-- pump island 1 -->
            <g class="pump-unit" transform="translate(700,190)">
                <rect class="pump-body" x="0" y="0" width="64" height="110" rx="10"/>
                <rect class="pump-screen" x="10" y="14" width="44" height="26" rx="4"/>
                <circle class="gauge-ring" cx="32" cy="70" r="20"/>
                <line class="gauge-needle" x1="32" y1="70" x2="32" y2="54"/>
                <circle class="gauge-pin" cx="32" cy="70" r="2.6"/>
                <rect class="pump-hose" x="58" y="30" width="6" height="60" rx="3"/>
            </g>

            <!-- pump island 2 -->
            <g class="pump-unit pump-unit-2" transform="translate(870,190)">
                <rect class="pump-body" x="0" y="0" width="64" height="110" rx="10"/>
                <rect class="pump-screen" x="10" y="14" width="44" height="26" rx="4"/>
                <circle class="gauge-ring" cx="32" cy="70" r="20"/>
                <line class="gauge-needle" x1="32" y1="70" x2="32" y2="54"/>
                <circle class="gauge-pin" cx="32" cy="70" r="2.6"/>
                <rect class="pump-hose" x="-6" y="30" width="6" height="60" rx="3"/>
            </g>

            <!-- flame / burner accents -->
            <g transform="translate(662,320)">
                <g class="flame-unit">
                    <path class="flame-outer" d="M8 0C8 6 2 8 2 14c0 5 3.5 9 8 9s8-4 8-9c0-3-1.5-5-2.7-6.6.2 1.8-.6 3.2-1.9 3.2-1.4 0-1.9-1.2-1.6-2.7C13.4 5 8 4 8 0Z"/>
                    <path class="flame-inner" d="M8 9c0 2.7-2.2 3.4-2.2 6.2A4.2 4.2 0 0 0 10.2 19a4 4 0 0 0 2.2-7.4c-.3.9-1 1.4-1.7 1.1-.7-.3-.9-1.2-.5-2.1.4-.8-.3-1.6-2-2.1Z"/>
                </g>
            </g>
            <g transform="translate(946,326)">
                <g class="flame-unit flame-unit-2">
                    <path class="flame-outer" d="M8 0C8 6 2 8 2 14c0 5 3.5 9 8 9s8-4 8-9c0-3-1.5-5-2.7-6.6.2 1.8-.6 3.2-1.9 3.2-1.4 0-1.9-1.2-1.6-2.7C13.4 5 8 4 8 0Z"/>
                    <path class="flame-inner" d="M8 9c0 2.7-2.2 3.4-2.2 6.2A4.2 4.2 0 0 0 10.2 19a4 4 0 0 0 2.2-7.4c-.3.9-1 1.4-1.7 1.1-.7-.3-.9-1.2-.5-2.1.4-.8-.3-1.6-2-2.1Z"/>
                </g>
            </g>
        </svg>
        <div class="ig-station-glow" style="left:38%; top:63%;"></div>
        <div class="ig-station-glow" style="left:50%; top:63%;"></div>

        <!-- ============ layer 3b: home + kitchen — where the CNG/pipeline
             story arrives. The riser pipe (bottom of this SVG) taps off the
             same horizontal pipeline below and carries the same animated
             flow up into the wall meter and, from there, to the stove. ============ -->
        <svg class="ig-home" viewBox="0 0 300 340" preserveAspectRatio="xMidYMax meet" xmlns="http://www.w3.org/2000/svg">
            <path class="pipe-casing home-riser-casing" d="M56,340 L56,196"/>
            <path class="pipe-flow home-riser-flow" d="M56,340 L56,196"/>
            <rect class="home-meter" x="48" y="170" width="16" height="26" rx="2"/>
            <path class="home-roof" d="M20,90 L150,20 L280,90 Z"/>
            <rect class="home-wall" x="40" y="90" width="220" height="130" rx="6"/>
            <rect class="home-kitchen" x="70" y="120" width="140" height="90" rx="4"/>
            <!-- explicit meter -> stove run, inside the kitchen, so the gas
                 is clearly traced all the way from the wall meter to the
                 burner rather than just implied by the stove being nearby -->
            <path class="pipe-casing home-stove-pipe-casing" d="M64,183 L80,183 L80,169 L112,169"/>
            <path class="pipe-flow home-stove-pipe-flow" d="M64,183 L80,183 L80,169 L112,169"/>
            <rect class="home-counter" x="80" y="178" width="120" height="24" rx="3"/>
            <rect class="home-stovetop" x="112" y="169" width="56" height="9" rx="2"/>
            <circle class="home-burner" cx="140" cy="169" r="11"/>
            <ellipse class="home-pot" cx="140" cy="154" rx="18" ry="11"/>
            <rect class="home-pot-handle" x="118" y="148" width="11" height="4" rx="2"/>
            <rect class="home-pot-handle" x="151" y="148" width="11" height="4" rx="2"/>
            <g class="flame-unit home-flame" transform="translate(132,143)">
                <path class="flame-outer" d="M8 0C8 6 2 8 2 14c0 5 3.5 9 8 9s8-4 8-9c0-3-1.5-5-2.7-6.6.2 1.8-.6 3.2-1.9 3.2-1.4 0-1.9-1.2-1.6-2.7C13.4 5 8 4 8 0Z"/>
                <path class="flame-inner" d="M8 9c0 2.7-2.2 3.4-2.2 6.2A4.2 4.2 0 0 0 10.2 19a4 4 0 0 0 2.2-7.4c-.3.9-1 1.4-1.7 1.1-.7-.3-.9-1.2-.5-2.1.4-.8-.3-1.6-2-2.1Z"/>
            </g>
        </svg>

        <!-- ============ layer 4: pipeline with animated gas flow ============ -->
        <svg class="ig-pipeline" viewBox="0 0 1600 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path class="pipe-casing" d="M0,30 L1600,30"/>
            <path class="pipe-flow" d="M0,30 L1600,30"/>
            <circle class="pipe-flange" cx="120" cy="30" r="7"/>
            <circle class="pipe-flange" cx="420" cy="30" r="7"/>
            <circle class="pipe-flange" cx="1180" cy="30" r="7"/>
            <circle class="pipe-flange" cx="1480" cy="30" r="7"/>
        </svg>

        <!-- ============ layer 5: road + traffic ============ -->
        <div class="ig-road">
            <div class="ig-road-lane"></div>

            <svg class="ig-car ig-car-1" viewBox="0 0 120 52" xmlns="http://www.w3.org/2000/svg">
                <rect class="car-shadow" x="6" y="44" width="104" height="6" rx="3"/>
                <path class="car-body" d="M8 34 C8 24 16 24 24 20 L34 10 C38 6 46 4 56 4 L82 4 C90 4 96 8 100 16 L108 20 C114 22 116 26 116 32 L116 36 C116 39 113 41 110 41 L14 41 C10 41 8 38 8 34Z"/>
                <path class="car-cabin" d="M40 20 L48 9 C50 7 54 6 58 6 L74 6 C79 6 84 9 86 14 L90 20 Z"/>
                <circle class="car-wheel" cx="34" cy="41" r="9"/>
                <circle class="car-wheel" cx="94" cy="41" r="9"/>
                <circle class="car-light" cx="112" cy="27" r="3"/>
            </svg>

            <svg class="ig-car ig-car-2" viewBox="0 0 120 52" xmlns="http://www.w3.org/2000/svg">
                <rect class="car-shadow" x="6" y="44" width="104" height="6" rx="3"/>
                <path class="car-body" d="M8 34 C8 24 16 24 24 20 L34 10 C38 6 46 4 56 4 L82 4 C90 4 96 8 100 16 L108 20 C114 22 116 26 116 32 L116 36 C116 39 113 41 110 41 L14 41 C10 41 8 38 8 34Z"/>
                <path class="car-cabin" d="M40 20 L48 9 C50 7 54 6 58 6 L74 6 C79 6 84 9 86 14 L90 20 Z"/>
                <circle class="car-wheel" cx="34" cy="41" r="9"/>
                <circle class="car-wheel" cx="94" cy="41" r="9"/>
                <circle class="car-light" cx="112" cy="27" r="3"/>
            </svg>

            <svg class="ig-car ig-car-3 ig-scooter" viewBox="0 0 70 46" xmlns="http://www.w3.org/2000/svg">
                <rect class="car-shadow" x="4" y="38" width="62" height="5" rx="2.5"/>
                <path class="car-body" d="M8 34 C6 24 14 22 22 22 L30 14 C33 11 38 10 42 12 L46 22 L58 24 C62 25 64 28 64 32 L64 34 Z"/>
                <circle class="car-wheel" cx="18" cy="34" r="7"/>
                <circle class="car-wheel" cx="56" cy="34" r="7"/>
            </svg>

            <svg class="ig-car ig-truck ig-truck-1" viewBox="0 0 160 60" xmlns="http://www.w3.org/2000/svg">
                <rect class="car-shadow" x="4" y="50" width="150" height="6" rx="3"/>
                <rect class="truck-cargo" x="6" y="14" width="90" height="34" rx="4"/>
                <path class="truck-cab" d="M96 24 L120 24 C126 24 131 28 133 34 L138 46 L96 46 Z"/>
                <rect class="truck-window" x="102" y="28" width="20" height="12" rx="2"/>
                <circle class="car-wheel" cx="30" cy="48" r="10"/>
                <circle class="car-wheel" cx="80" cy="48" r="10"/>
                <circle class="car-wheel" cx="122" cy="48" r="10"/>
                <circle class="car-light" cx="136" cy="38" r="3"/>
            </svg>

            <div class="ig-refuel-glow ig-refuel-glow-1"></div>
            <div class="ig-refuel-glow ig-refuel-glow-2"></div>
        </div>

        <!-- ============ layer 6: floating gas / energy particles ============ -->
        <span class="ig-particle" style="left:58%;"></span>
        <span class="ig-particle" style="left:63%;"></span>
        <span class="ig-particle" style="left:70%;"></span>
        <span class="ig-particle" style="left:75%;"></span>
        <span class="ig-particle" style="left:81%;"></span>

        <div class="ig-scene-fade"></div>
    </div>

    <div class="ig-auth-heading animate__animated animate__fadeInDown">
        <span class="ig-eyebrow">Energy &middot; Technology &middot; Collaboration</span>
        <h2>IGL <span>Social Media</span> CRM</h2>
        <p>A platform which accelerates collaboration</p>
    </div>

    <div class="ig-auth-panel">
        <div class="flip-container">
            <div class="flipper" id="flipper">
                <div class="front">
                    <div class="ig-card animate__animated animate__fadeInUp">
                        <div class="logo">
                            <img src="images/company_logo.png" alt="IGL Social Media CRM logo" class="logo_company">
                        </div>
                        <div class="login">

                            <h2 class="loginh2">Welcome back</h2>
                            <p class="login-sub">Sign in to manage posts, tickets &amp; leads</p>
                            <div class="">
                                <form action="{{ route('authenticate') }}" method="post" id="loginForm">
                                    @csrf
                                    <div class="mb-3 ig-field">
                                        <label for="email" class="form-label">Email Address</label>
                                        <div class="ig-input-group">
                                            <i class="bi bi-envelope ig-input-icon" aria-hidden="true"></i>
                                            <input type="email" class="form-control ig-input-with-icon @error('email') is-invalid @enderror"
                                                id="email" name="email" placeholder="you@company.com" value="{{ old('email') }}" autocomplete="username">
                                        </div>
                                        @if ($errors->has('email'))
                                        <span class="text-danger ig-error"><i class="bi bi-exclamation-circle"></i> {{ $errors->first('email') }}</span>
                                        @endif

                                    </div>
                                    <div class="mb-3 ig-field">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="ig-input-group">
                                            <i class="bi bi-lock ig-input-icon" aria-hidden="true"></i>
                                            <input type="password" class="form-control ig-input-with-icon @error('password') is-invalid @enderror"
                                                placeholder="Enter your password" id="password" name="password" autocomplete="current-password">
                                        </div>
                                        @if ($errors->has('password'))
                                        <span class="text-danger ig-error"><i class="bi bi-exclamation-circle"></i> {{ $errors->first('password') }}</span>
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
                </div>
                <div class="back ">
                    <div class="ig-card">
                        <div class="logo">
                            <img src="images/company_logo.png" alt="IGL Social Media CRM logo" class="logo_company">
                        </div>
                        <div class="login">
                            <h2 class="loginh2">{{ __('Forgot Password') }}</h2>
                            <p class="login-sub">{{ __("We'll email you a reset link") }}</p>
                            <div class="">
                                <form method="POST" action="{{ route('password') }}">
                                    @csrf
                                    <div class="form-group  mb-3 ig-field">
                                        <label for="email"
                                            class="form-label">{{ __('Email Address') }}</label>
                                        <div class="ig-input-group">
                                            <i class="bi bi-envelope ig-input-icon" aria-hidden="true"></i>
                                            <input id="email" type="email"
                                                class="form-control ig-input-with-icon @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autofocus>
                                        </div>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
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

// Purely cosmetic: show a busy state on the login button while the form
// submits. Does not alter validation or submission behaviour.
var loginForm = document.getElementById("loginForm");
if (loginForm) {
    loginForm.addEventListener("submit", function () {
        var submitBtn = loginForm.querySelector('input[type="submit"]');
        if (submitBtn) {
            submitBtn.setAttribute("aria-busy", "true");
            submitBtn.value = "Signing in...";
        }
    });
}
</script>
@endsection
