@extends('auth.layouts')

@section('content')


<form action="/addUserAssignRule" method="POST">
    @csrf
    <div class="container-fluid">
        <div class="div_container">
            <div class="bgwhite2">
                <div class="borderalignheading">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="heading_two">
                                <h2><i class="bi bi-ticket iconsbg2"></i>Business Info</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="btnplus">
                                <a href="/busines_rule" class="{{ addUIComponent('ADMINMANAGEMENT_CREATE_BUSINESS_RULE') }}"> <i class="bi bi-plus-lg btnchange"></i></a>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="busines_details">
                    <div class="moreinfo">
                    <table class="table {{ addUIComponent('ADMINMANAGEMENT_BUSINESS_INFO_TABLE') }}">
                        <thead>
                            <tr>   
                            <th scope="col">User</th>
                            <th scope="col">Keyword</th>
                            <th scope="col">social Type</th>
                            <th scope="col">Assign Type</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!empty($rulesList) && $rulesList->count())
                          @foreach($rulesList as $key => $rulesLists)
                            <tr>    
                            <td>
                            <div class="editer_file d-flex">
                                <div class="dropdown">
                                    <button class="settingicons" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear"></i>
                                    </button>
									
                                    <ul class="dropdown-menu dropdownmenu_innner leftdropdown"
                                        aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item {{ addUIComponent('ADMINMANAGEMENT_EDIT_BUSINESS_RULE') }}" href="#"
                                                onclick="location.href='/busines_rule/{{$rulesLists->id}}';"><i
                                                    class="bi bi-pencil"></i> Edit</a></li>
                                        <li><a class="dropdown-item deleteLink {{ addUIComponent('ADMINMANAGEMENT_BUSINESS_RULE_DELETE') }}"  href="#" 
                                        onclick="location.href='/deleteRule/{{$rulesLists->id}}';" id=""><i
                                                    class="bi bi-trash3" ></i> Delete</a></li>
                                    </ul>
                                </div>
                                {{$rulesLists->name}}
                            </div>     
                            </td>
                            <td class="line_break">{{$rulesLists->Keyword}}</td>
                            <td>
                                <div class="socialtypemain">
                                    <ul>
                                        <li class="line_break">{{$rulesLists->social_type}}</li>
                                    </ul>
                                </div>
                            </td>
                            <td>{{$rulesLists->assign_type}}</td>
                            <?php $enable = $rulesLists->enable;
                             $status = ($enable == "1") ? 'Active' : 'InActive';?>
                             <td><?php echo $status; ?></td>
                            <td>{{$rulesLists->from_date}} to {{$rulesLists->to_date}}</td>
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
</form>



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