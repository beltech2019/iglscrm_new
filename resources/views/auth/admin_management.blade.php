@extends('auth.layouts')

@section('content')
<style>
body{
    background:#fff !important;
}
.states_inner{
    box-shadow:none !important;
}
.states a{
   text-decoration:none !important;
}
    </style>



    <div class="div_container">

        <div class="">
            <div class="dashboard">

                <div class="row">
                    <div class="col-md-12">
                        <div class="states mt-3">
                            <div class="row">
                                <div class="col-md-3 col-6">
                                    <a href="/user-list" class="{{ addUIComponent('ADMINMANAGEMENT_USERS') }}">
                                        <div class="states_inner admin_states">
                                        <p >
                                            Users</p>
                                             <img src="/images/img1.svg" class="img-fluid"> 
                                         
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="/userAssignRuleList" class="{{ addUIComponent('ADMINMANAGEMENT_BUSINESS_RULES') }}">
                                        <div class="states_inner admin_states">
                                            
                                        <p>
                                                Business Rules</p>
                                     <img src="/images/img2.svg" class="img-fluid"> 

                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                <a href="/update-config" class="{{ addUIComponent('ADMINMANAGEMENT_CONFIGURATION') }}">  
                                    <div class="states_inner admin_states">
                                        
                                    <p >Application Configuration</p>
                                 <img src="/images/img3.svg" class="img-fluid">

                                    </div>
                                </a>    
                                </div>
								<div class="col-md-3 col-6">
                                <a href="/postAssignRuleList" class="{{ addUIComponent('ADMINMANAGEMENT_POST_RULE') }}">  
                                    <div class="states_inner admin_states">
                                        
                                    <p >Post Rule</p>
                                 <img src="/images/img4.svg" class="img-fluid">

                                    </div>
                                </a>    
                                </div>

                                <div class="col-md-3 col-6">
                                <a href="/accessRole" class=" {{ addUIComponent('ADMINMANAGEMENT_ACCESS') }}">  
                                    <div class="states_inner admin_states">
                                        
                                    <p >Access</p>
                                 <img src="/images/img44.svg" class="img-fluid">

                                    </div>
                                </a>    
                                </div>

                                <div class="col-md-3 col-6">
                                <a href="/socialPlatform" class=" {{ addUIComponent('ADMINMANAGEMENT_ACCESS') }}">  
                                    <div class="states_inner admin_states">
                                        
                                    <p >Social Platform Configuration</p>
                                 <img src="/images/img55.svg" class="img-fluid">

                                    </div>
                                </a>    
                                </div>
                                 <div class="col-md-3 col-6">
                                     <a href="/getTemplateList" class=" {{ addUIComponent('ADMINMANAGEMENT_TEMPLATE') }}">  
                                    <div class="states_inner admin_states">
                                        
                                    <p >Templates</p>
                                 <img src="/images/img55.svg" class="img-fluid">

                                    </div>
                                </a>    
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="/getReportList" class="{{ addUIComponent('ADMINMANAGEMENT_REPORTS') }}">
                                        <div class="states_inner admin_states">
                                            
                                        <p>
                                               Manual Reports</p>
                                     <img src="/images/img2.svg" class="img-fluid"> 

                                        </div>
                                    </a>
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
    
   $("#admin").addClass("active");
   </script>

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