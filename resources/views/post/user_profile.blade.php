@extends('auth.layouts')

@section('content')


<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="heading_two ">
                        <h2><i class="bi bi-ticket iconsbg2"></i>Social User </h2>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="editbtns" style="float:right;">
                <div class="iconsmenu2" style="float:none;">
                    </div>
            
                </div>
            </div>
            </div>
           <div class="socialUser">
                <div class="row">
                    <div class="col-md-3">
                        <div class="profileimg">
                        <i class="bi bi-person-circle"></i>
                        <h4>{{$getUser?$getUser->name:''}}<span>{{$getUser?$getUser->user_name:''}}</span></h4>
                            <p>{{$getUser?$getUser->user_id:''}}</p>
                        </div>
                    </div>
                    <div class="col-md-9">
                    <div class="socialticketview">

<div class="row">
                                <div class="col-md-6">
                                    <div class="coulams_form">
                                        <label class="form-label">Date Created	</label>
                                        <p class="peragraph_content">{{$getUser?$getUser->date_modified:''}} By Admin</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="coulams_form">
                                        <label class="form-label">User Name	</label>
                                        <p class="peragraph_content">{{$getUser?$getUser->user_name:''}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="coulams_form">
                                        <label class="form-label">Date Modified</label>
                                        <p class="peragraph_content">{{$getUser?$getUser->date_modified:''}} By Admin</p>
                                    </div>
                                </div>
                             
                             
                               
                            </div>
                            </div>
                    </div>
                </div>
           </div>
     
        </div>
    </div>
</div>
</div>

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

@endsection