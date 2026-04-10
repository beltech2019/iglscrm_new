@extends('auth.layouts')

@section('content')
<style>
    .btnplus a.prioritybtn{
        background: #ffd525;
        border: none;
        font-size: 14px;
        color: #000 !important;
        font-weight: 500;
        padding: 8px 17px;
        text-decoration: none;
        border-radius: 4px;
    }
</style>

<form action="/addPostAssignRule" method="POST">
    @csrf
    <div class="container-fluid">
        <div class="div_container">
            <div class="bgwhite2">
                <div class="borderalignheading">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="heading_two">
                                <h2><i class="bi bi-ticket iconsbg2"></i>Post Rule</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="btnplus">
                            <!-- <a href="/postRulePriority" class="{{ addUIComponent('ADMINMANAGEMENT_CREATE_POST_RULE') }} prioritybtn"> <span>Set Priority</span></a> -->
                                <a href="/post_rule" class="{{ addUIComponent('ADMINMANAGEMENT_CREATE_POST_RULE') }}"> <i class="bi bi-plus-lg btnchange"></i></a>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="busines_details">
                    <div class="moreinfo">
                    <table class="table {{ addUIComponent('ADMINMANAGEMENT_POST_RULE_TABLE') }}">
                        <thead>
                            <tr>   
                            <th scope="col">ID</th>
                            <th scope="col">Keyword</th>
                            <th scope="col">Type</th>
                            <th scope="col">Category</th>
                            <th scope="col">Status</th>
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
                                        <li><a class="dropdown-item {{ addUIComponent('ADMINMANAGEMENT_EDIT_RULE') }}" href="/post_rule/{{$rulesLists->ruleid}}"><i
                                                    class="bi bi-pencil"></i> Edit</a></li>
                                        <li><a class="dropdown-item deleteLink {{ addUIComponent('ADMINMANAGEMENT_POST_RULE_DELETE') }}"  href="/deletePostRule/{{$rulesLists->ruleid}}" id=""><i
                                                    class="bi bi-trash3" ></i> Delete</a></li>
                                    </ul>
                                </div>
                                {{$rulesLists->ruleid}}
                            </div>     
                            </td>
                            <td class="line_break">{{$rulesLists->keyword}}</td>
                            
                            <td>{{$rulesLists->type}}</td>
                            <td>{{$rulesLists->category}}</td>
                            
                             <td>{{$rulesLists->status}}</td>
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