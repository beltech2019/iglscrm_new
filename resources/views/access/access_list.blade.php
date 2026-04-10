@extends('auth.layouts')

@section('content')


    <div class="container-fluid">
        <div class="div_container">
            <div class="bgwhite2">
                <div class="borderalignheading">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="heading_two">
                                <h2><i class="bi bi-ticket iconsbg2"></i>Access</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="busines_details">
                    <div class="moreinfo">
                    <table class="table {{ addUIComponent('DASHBOARD_TICKETS') }}">
                        <thead>
                            <tr>   
                            <th scope="col">Role Name</th>
                            <!-- <th scope="col">Assign To</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        @if(!empty($role) && $role->count())
                          @foreach($role as $key => $roles)
                            <tr>    
                            <td>
                            <div class="editer_file d-flex">
                                <!-- <div class="dropdown">
                                    <button class="settingicons" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear"></i>
                                    </button>
									
                                    <ul class="dropdown-menu dropdownmenu_innner leftdropdown"
                                        aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item {{ addUIComponent('ADMINMANAGEMENT_ACCESS_EDIT') }}" href="#"
                                                ><i
                                                    class="bi bi-pencil"></i> Edit</a></li>
                                        <li><a class="dropdown-item deleteLink {{ addUIComponent('ADMINMANAGEMENT_ACCESS_DELETE') }}"  href="#" 
                                        ><i
                                                    class="bi bi-trash3" ></i> Delete</a></li>
                                    </ul>
                                </div>
                            </div> -->
                            <a class="dropdown-item {{ addUIComponent('ADMINMANAGEMENT_ACCESS_INNER') }}" href="/getRoleWiseComponents/{{$roles->role_id}}">{{$roles->role_name}}</a>     
                            </td>
                            </tr>  
                          @endforeach
                        @endif   
                        </tbody>
                        </table>
                    </div>
                </div>
               
            </div>
        </div>
    </div>




<script>
$("#admin").addClass("active");

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