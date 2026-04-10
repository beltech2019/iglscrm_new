@extends('auth.layouts')

@section('content')

<div class="mt-3">
    <div class="div_container">
        <div class="bgwhite2">
            <div class="row mb-4">
                <div class="col-md-6 col-6">
                    <div class="heading_two ">
                        <h2><i class="bi bi-mailbox iconsbg"></i>LEADS </h2>
                    </div>
                </div>
                <div class="col-md-6 col-6">

                    <div class="iconsmenu">
                        <ul class="ms-auto">
                            <li><i class="bi bi-funnel {{ addUIComponent('LEAD_FILTER') }}" data-bs-toggle="modal" data-bs-target="#exampleModal"></i></li>
                            <li><i id="refresh-icon" style="cursor: pointer;" class="bi bi-bootstrap-reboot"></i></li>
                            <li id="deleteBtn" class="{{ addUIComponent('LEAD_DELETE') }}"><i class="bi bi-trash3"></i></li>
                            <li><i class="bi bi-list-task"></i></li>
                            <li><a href="/createLead" class="{{ addUIComponent('LEAD_CREATE_LEAD') }}"><i class="bi bi-plus-lg"></i></a></li>

                            <div style="clear:both;"></div>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="my-2 d-flex  {{ addUIComponent('LEAD_TABLE') }} row">
            <div class="col-md-9">
                 {!! $lead->withQueryString()->links() !!}
                 </div>
                 <div class="col-md-3">
                 <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                    Showing {{ $lead->firstItem() }} - {{ $lead->lastItem() }} of {{ $lead->total() }} lead
                 </p>
            </div>
            </div>
            <div class="table-responsive">
            <table class="table {{ addUIComponent('LEAD_TABLE') }}">
                <thead>
                    <tr>
                        <th scope="col"> Date Created</th>
                        <th scope="col">Lead Number</th>
                        <th scope="col">Description</th>
                        <th scope="col">Name</th>
                        <th scope="col">Lead Source</th>
                        <th scope="col">User</th>
                        <th scope="col">Status</th>
                        <th scope="col"></th>
                </thead>
                <tbody>
                    @if(!empty($lead) && $lead->count())
                    @foreach($lead as $key => $leads)
                    <tr>

                        <td scope="row"> 
                        <span class="d-flex">
                        <input class="form-check-input deleteCheck" type="checkbox"
                                value="{{$leads->id}}" id="{{$leads->id}}">
                            <div class="dropdown">
                                <button class="settingicons" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <a href=""><i class="bi bi-gear mainstyle"></i></a> </button>
                                <ul class="dropdown-menu dropdownmenu_innner " aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item {{ addUIComponent('LEAD_EDIT_LEAD') }}" href='/createLead/{{$leads->id}}'><i
                                                class="bi bi-pencil"></i> Edit</a></li>
                                    <li><a class="dropdown-item deleteLink {{ addUIComponent('LEAD_DELETE') }}" href="#" id="{{$leads->id}}"><i
                                                class="bi bi-trash3"></i> Delete</a></li>
                                </ul>
                            </div>{{$leads->created_date}}
                            </span>   
                        </td>
                        <td class="socialusertd" ><i class="bi bi-clipboard copy-button"></i> <a class="" href="{{ addUIComponent('LEAD_INNER') == 'HIDDEN' ? '#':'/getLeadById/'.$leads->id}}" >{{$leads->leadId}}</a></td>
                        <td>{!! getUrlinString($leads->description)!!}</td>
                        <td class="socialusertd" ><i class="bi bi-clipboard copy-button"></i> <a   href="{{ addUIComponent('SOCIALUSER') == 'HIDDEN' ? '#':'/userProfile/'.$leads->socialUser_id}}" >{{$leads->first_name}} {{$leads->last_name}}</a></td>
                        <td>{{$leads->lead_source}}</td>
                        <td>{{$leads->leadBy}}</td>
                        <td>{{$leads->status}}</td>
                        <td>
                            <div class="editer_file">

                                <i class="bi bi-info" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal_{{$leads->leadId}}"></i>

                                <div class="modal fade" id="exampleModal_{{$leads->leadId}}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Additional Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-striped table-hover">
                                                    <tr>
                                                        <td>Name :
                                                       </td>
                                                       <td>{{$leads->first_name}}
                                                       </td>
                                                        <td>{{$leads->last_name}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Status :</td>
                                                        <td>{{$leads->status}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Created Date :</td>
                                                        <td>{{$leads->created_date}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>


                    @endforeach
                    @endif
                </tbody>
            </table>
            </div>

            <div class="my-2 d-flex  {{ addUIComponent('LEAD_TABLE') }} row">
            <div class="col-md-9">
                 {!! $lead->withQueryString()->links() !!}
                 </div>
                 <div class="col-md-3">
                 <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                    Showing {{ $lead->firstItem() }} - {{ $lead->lastItem() }} of {{ $lead->total() }} lead
                 </p>
            </div>
            </div>
        </div>
    </div>
</div>
</div>



<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="filter">
                    <!-- Nav pills -->
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="pill" href="#home">Quick Filter </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="pill" href="#menu1">Advanced Filter</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="home" class="container tab-pane  active">
                            <br>
                            <form method="GET" action="{{ Route::current()->getName() }}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mt-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="text" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="postid"
                                                        placeholder="Name" name="name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 hide">
                                        <div class="form-check marginten">
                                            <input class="form-check-input" type="checkbox" value="1" id="favorite">
                                            <label class="form-check-label" for="favorite">
                                                favorite
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger">Filter</button>
                                </div>
                            </form>
                        </div>
                        <div id="menu1" class="container tab-pane fade ">
                            <br>
                            <div class="tabsmain">
                                <form method="GET" action="{{ Route::current()->getName() }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="first_name" class="form-label">First Name</label>
                                                <input type="text" class="form-control" id="first_name" placeholder="First Name"
                                                    name="first_name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="last_name" class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="last_name" placeholder="Name"
                                                    name="last_name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select class="form-select" aria-label="Default select example" id="status" name="status">
                                                <option selected></option> 
                                                   <option value="New">New</option>
                                                   <option value="Assigned">Assigned</option>
                                                   <option value="In Process">In Process</option>
                                                   <option value="Converted">Converted</option>
                                                   <option value="Recycled">Recycled</option>
                                                   <option value="Dead">Dead</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="lead_source" class="form-label">Lead Source</label>
                                                <select class="form-select" aria-label="Default select example" id="lead_source" name="lead_source">
                                                   <option selected></option>
                                                   <option value="Other">Other</option>
                                                   @foreach(getSource() as $key => $getSources)
                                                     <option value="{{$getSources->value}}">{{$getSources->value}}</option>
                                                   @endforeach
                                                   <option value="Portal">Portal</option>
                                                   <option value="Call">Call</option>
                                                   <option value="Inbounced Email">Inbounced Email</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="created_date" class="form-label">Date Created</label>
                                                <input type="date" class="form-control" id="created_date"
                                                    placeholder="Date" name="created_date">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Filter</button>
								<button type="submit" class="btn btn-danger" name="download" value="download">Download</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>


<div class="modal" id="commonDelete" tabindex="-1">
    <div class="modal-dialog widthdialoge">
        <div class="modal-content">
            <form method="post" action="{{ route('deleteAllLead') }}" id="deleteForm">
                @csrf
                <div class="modal-header">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete_post_ticket">
                        <img src="/images/wired-outline-185-trash-bin (2).gif" class="deleteimg">

                        <h3 id="textMsg">Are you sure delete it.</h3>
                    </div>
                </div>
                <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$("#lead").addClass("active");    
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "64px";
    document.getElementById("main").style.marginLeft = "64px";
}
var deleteId = "";
$(document).ready(function() {
    $(document).on('click', ".hideBox,.display", function() {
        if ($(this).hasClass('display')) {
            var id = $(this).find('input').val();
            var text = $(this).find('button').text();
            $(this).remove();
            $("#hiddenvalue").prepend(
                '<li class="hideBox"> <input type="hidden" name="columnHide[]" value="' + id +
                '" /> <button class="custombtn2 " type="button">' + text + '</button></li>');
        } else {
            var id = $(this).find('input').val();
            var text = $(this).find('button').text();
            $(this).remove();
            $("#displayedvalue").append(
                '<li class="display"> <input type="hidden" name="column[]" value="' + id +
                '" /> <button class="custombtn" type="button">' + text + '</button></li>');
        }
    });

    $(".deleteCheck").click(function() {
        var id = $(this).val();
        if ($(this).prop('checked') == true) {
            $("#deleteForm").prepend("<input type='hidden' name='postid[]' value='" + id + "' id='" +
                id + "' />");
        } else {
            $("#deleteForm #" + id).remove();
        }
    });



    $("#deleteBtn").click(function() {
        if ($("#deleteForm input").length > 1) {
            $("#commonDelete").modal("show");
			$("#commonModal").modal("hide");
			$("#commonBtn").hide();
        } else {
            $("#msg").text("Please select row");
            $("#commonBtn").hide();
            $("#commonModal").modal("show");
        }
    });

    $(".deleteLink").click(function() {
        deleteId = $(this).attr('id');
        $("#commonModal").modal("show");
        $("#commonBtn").show();
        $("#msg").text("Are you sure delete it.");
    });

    $("#commonBtn").click(function() {
        location.href = "/deleteLead/" + deleteId;
    });
    $('#refresh-icon').on('click', function() {
            location.reload(); // Reload the page
        });

});
</script>

@endsection