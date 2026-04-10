<!DOCTYPE html>
<html lang="en">
@guest
@php

$roleInfo = "";
@endphp

@else
@php

$loggedUser = \Auth::user();
$roleInfo = \App\Models\Role::find($loggedUser->role);
@endphp

@endif

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart CRM</title>
    <link rel="icon" type="image/x-icon" href="/images/fav-icon.png">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css">

    <link href="/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/css/style.css" rel="stylesheet" />
    <link href="/css/responsive.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>

</head>
<style>
    .downloadbtn{
        font-size: 10px;
    }
</style>
<body>
    <div id="main">
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"> <span style="font-size:30px;cursor:pointer" onclick="openNav()"><img
                            src="/images/menu.png" class="menuicons"></span>
                    <img src="/images/fav-logo.png" class="logo_nav mt-0 hidedesktop"></a>

                <form class="me-auto postioninner d-flex {{ addUIComponent('GLOBALSEARCH') }}" action="/globalSearch" method="GET">
                    <input class="form-control formsearch  me-2" type="search" minlength="4" name="search"
                        value="{{request()->get('search')}}" placeholder="Search" aria-label="Search" required>
                    <button class="btn btn-secondary btnhoverser"><i class="bi bi-search"></i></button>
                    <select name="type" id="type" class="form-select innerinput" style="width: 92px;">
                        <option value="ALL" {{request()->get('type') && request()->get('type')=='ALL'?'selected':''}}>
                            All</option>
                        <option value="Post" {{request()->get('type') && request()->get('type')=='Post'?'selected':''}}>
                            Post</option>
                        <option value="Ticket"
                            {{request()->get('type') && request()->get('type')=='Ticket'?'selected':''}}>Ticket</option>
                        <option class="{{ addUIComponent('DASHBOARD_LEADS') }}" value="Lead" {{request()->get('type') && request()->get('type')=='Lead'?'selected':''}}>
                            Lead</option>
                    </select>

                </form>
                <div class="" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto ">
                        @guest
                        <li class="nav-item">
                            <a class="nav-link navbarmenu {{ (request()->is('login')) ? 'active' : '' }}"
                                href="{{ route('login') }}">Login</a>
                        </li>

                        @else

                        <li class="nav-item">
                            <a class="nav-link">
                                <div class="icon-container" type="button" data-bs-toggle="offcanvas"
                                    data-bs-target="#start_wise">
                                    <button type="button" class="btn btn-danger buttonset"><i
                                            class="bi bi-star me-2 "></i> <span
                                            class="hidemobile">Favourite</span></button>
                                </div>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link">
                                <div class="icon-container me-3" type="button" data-bs-toggle="offcanvas"
                                    data-bs-target="#demo">

                                    <button class="btn btn-danger buttonset">
                                        <i class="bi bi-list-task"></i><span class="hidemobile">Assigned to Me</span>
                                    </button>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link navbarmenu dropdown-toggle alignprofilecon" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle profileiconss"></i><span class="hidemobile">
                                    {{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdownmenu_innner">
                                <li><a class="dropdown-item" href="{{ route('password.change') }}">Change Password</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                    </form>

                                </li>
                            </ul>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div id="mySidenav" class="sidenav">
            <div class="">
                <img src="/images/fav-logo.png" class="logo_nav">
                <div class="menu">
                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                    <a href="/countDashboard" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard"
                        class=" {{ addUIComponent('DASHBOARD') }}" id="dashboard"><i class="bi bi-columns-gap"></i>
                        Dashboard</a>
                    <a href="/dashboard" id="post" data-bs-toggle="tooltip" data-bs-placement="right"
                        title="Social Post" class=" {{ addUIComponent('SOCIALPOST') }}"><i class="bi bi-mailbox"></i>
                        Social Post</a>
                    <a href="/getSocialTicket" id="ticket" data-bs-toggle="tooltip" data-bs-placement="right"
                        title="Social Ticket" class=" {{ addUIComponent('SOCIALTICKET') }}"><i class="bi bi-ticket"></i>
                        Social Ticket</a>
                    <a href="/getLeads" data-bs-toggle="tooltip" data-bs-placement="right" title="Leads"
                        class=" {{ addUIComponent('LEAD') }}" id="lead"><i class="bi bi-funnel"></i> Leads</a>
                    <a href="{{ route('adminManagement') }}" data-bs-toggle="tooltip" data-bs-placement="right"
                        title="Admin Management" id="admin" class=" {{ addUIComponent('ADMINMANAGEMENT') }}"><i
                            class="bi bi-person-workspace"></i> Admin Management</a>
                    <a href="/getSocialPostReport" data-bs-toggle="tooltip" data-bs-placement="right"
                        title="Reports" id="PredefineReports" class=" {{ addUIComponent('PREDEFINE_REPORT') }}"><i class="bi bi-file-bar-graph"></i>
                        Reports</a>

                </div>
            </div>
        </div>
        <div class="container-fluid ">
            @if(Session::has('success'))
            <div class="row">
                <p class="alert newdesign {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success') }}
                </p>
            </div>
            @elseif(Session::has('message'))
            <div class="row">
                <p class="alert newdesign {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}
                </p>
            </div>
            @endif
            @yield('content')
        </div>

        <x-template-component />
        <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        $(document).ready(function() {
            $(".alert").delay(4000).slideUp(200, function() {
                $(this).alert('close');
            });
        });
        </script>
        <div class="offcanvas offcanvas-end" id="demo">
            <div class="offcanvas-header ">
                <h1 class="offcanvas-title">Assigned to Me</h1>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <div class="tabsmaininner2">

                </div>
                <div class="tabsmaininner2">
                    <div class="headingin2">
                        @guest
                        @else
                        <div class="headingmain headingsec mb-3">
                            <h4 style="display: flex; justify-content: space-between; align-items: center;">
                                Social Ticket
                                <div style="display: flex; gap: 10px;">
                                    <a href="/getSocialTicket?user_id={{ Auth::user()->id }}" class="seeall">See All</a>
                                    <form method="GET" action="/getSocialTicket/{{ Auth::user()->id }}">
                                        <button type="submit" class="btn btn-danger downloadbtn" name="download" value="download">
                                            <i class="bi bi-download"></i> <!-- Bootstrap Icons Download Icon -->
                                        </button>
                                    </form>
                                </div>
                            </h4>
                        </div>
                        @endguest
                    </div>
                    @if(!empty($assignedToMe) && count($assignedToMe))
                    @foreach($assignedToMe as $key => $assignedToMes)
                    <div class="socialticketinner">
                        <h6>
                            @if($assignedToMes->source == 'Twitter')
                            <img src="/images/{{ getSocialIcon($assignedToMes->source,true) }}" class="newimg">
                            @else
                            <i class="{{ getSocialIcon($assignedToMes->source) }}"></i>
                            @endif
                            {{$assignedToMes->socialUser}}<span class="alertnew2">{{$assignedToMes->status}}</span>
                        </h6>
                        <p>{{$assignedToMes->postMessage}}</p>
                        <p class="dates"><i class="bi bi-person-fill"></i>{{$assignedToMes->getTweet_id}}</p>
                    </div>
                    @endforeach
                    @endif

                </div>
            </div>
        </div>
        <div class="offcanvas offcanvas-end" id="start_wise">
            <div class="offcanvas-header">
                <h1 class="offcanvas-title">Favourite<span class="ms-3"><a href="/getFavourite" class="seeall">See
                            All</a></span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                @if(!empty($favouriteToMe) && count($favouriteToMe))
                @foreach($favouriteToMe as $key => $favouriteToMes)
                <div class="socialticketinner">
                    <h6>
                        @if($favouriteToMes->source == 'Twitter')
                        <img src="/images/{{getSocialIcon($favouriteToMes->ticketSource,true) }}" class="newimg">
                        @else
                        <i
                            class="{{ getSocialIcon(!is_null($favouriteToMes->source) ? $favouriteToMes->source : (!is_null($favouriteToMes->leadSource) ? $favouriteToMes->leadSource : $favouriteToMes->ticketSource)) }}"></i>
                        @endif
                        {{$favouriteToMes && $favouriteToMes->type == 'tb_leads' ? $favouriteToMes->first_name . ' ' . $favouriteToMes->last_name : ($favouriteToMes->type == 'tb_gettweet' ? $favouriteToMes->socialUser_name : explode('(', $favouriteToMes->socialUser)[0])
    }}
                    </h6>
                    <p>{{$favouriteToMes && $favouriteToMes->type == 'tb_leads' ? $favouriteToMes->description : ($favouriteToMes->type == 'tb_gettweet' ? $favouriteToMes->postMessage : $favouriteToMes->ticketPostMessage)}}
                    </p>
                    <p class="dates"><i
                            class="bi bi-person-fill"></i>{{$favouriteToMes && $favouriteToMes->type == 'tb_leads' ? $favouriteToMes->leadGetTweet_id : ($favouriteToMes->type == 'tb_gettweet' ? $favouriteToMes->getTweet_id : $favouriteToMes->ticketGetTweet_id)}}
                    </p>
                </div>
                @endforeach
                @endif
            </div>
        </div>
</body>

</html>
<script>
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "64px";
    document.getElementById("main").style.marginLeft = "64px";
}
</script>
<div class="modal" id="commonModal" tabindex="-1">
    <div class="modal-dialog widthdialoge">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="delete_post_ticket">
                    <img src="/images/wired-outline-185-trash-bin (2).gif" class="deleteimg">

                    <h3 id="msg"></h3>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" id="commonBtn" style="display:none"><i
                        class="bi bi-trash3"></i> Delete</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".copy-button").click(function() {
            const copyText = $(this).siblings("a").text();
            const tempInput = $("<input>");
            $("body").append(tempInput);
            tempInput.val(copyText).select();
            document.execCommand("copy");
            tempInput.remove();

            // Change the icon to 'bi-clipboard-right' after copying
            $(this).removeClass("bi-clipboard").addClass("bi-clipboard-check");

            // You can add a delay and then change it back to the original icon if needed
            setTimeout(function() {
                $(this).removeClass("bi-clipboard-check").addClass("bi-clipboard");
            }.bind(this), 2000); // Change back after 2 seconds (adjust the time as needed)
        });
    });
</script>