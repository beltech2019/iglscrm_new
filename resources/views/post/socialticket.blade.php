@extends('auth.layouts')

@section('content')

<!-- <div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                @else
                    <div class="alert alert-success">
                        You are logged in!
                    </div>       
                @endif                
            </div>
        </div>
    </div>    
</div> -->


<div class="mt-3">
    <div class="div_container">
        <div class="bgwhite2">
            <div class="row mb-4">
                <div class="col-md-6 col-6">
                    <div class="heading_two ">
                        <h2><i class="bi bi-ticket iconsbg2"></i>Social Tickets </h2>
                    </div>
                </div>
                <div class="col-md-6 col-6">
                    <div class="iconsmenu">
                        <ul class="ms-auto">
                            <li><i class="bi bi-funnel  {{ addUIComponent('SOCIALTICKET_FILTER') }}" data-bs-toggle="modal" data-bs-target="#exampleModal"></i></li>
                            <li><i id="refresh-icon" style="cursor: pointer;" class="bi bi-bootstrap-reboot"></i></li>
                            <li id="deleteBtn" class=" {{ addUIComponent('SOCIALTICKET_DELETE') }}"><i class="bi bi-trash3"></i></li>
                            <li ><i class="bi bi-list-task"></i></li>
                            <li><i class="bi bi-plus-lg "></i></li>
                            <div style="clear:both;"></div>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="my-2 {{ addUIComponent('SOCIALTICKET_TABLE') }} row">
                <div class="col-md-9">
                  {!! $getInfo->withQueryString()->links() !!}
                </div>
                <div class="col-md-3">
                    <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                        Showing {{ $getInfo->firstItem() }} - {{ $getInfo->lastItem() }} of {{ $getInfo->total() }} tickets
                    </p>
                </div>
            </div>
            <div class="">
            <table class="table {{ addUIComponent('SOCIALTICKET_TABLE') }}">
                <thead>
                    <tr>
                        <th scope="col">Num.</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Department</th>
                        <th scope="col">Source</th>
                        <th scope="col">Priority</th>
                        <th scope="col">Status</th>
                        <th scope="col">Final State</th>
                        <th scope="col">Assigned To</th>
                        <th scope="col">Date Created</th>
                        <th scope="col">Modified At</th>
                        <th scope="col">Sap Ticket</th>
                    </tr>
                </thead>
                <tbody>
                @if(!empty($getInfo) && $getInfo->count())
                    @foreach($getInfo as $key => $getInfos)
                    <tr>
                        <td scope="row" class="widthtd ">
                            <span class="d-flex">
                            <input class="form-check-input deleteCheck" type="checkbox" value="{{$getInfos->id}}"
                                id="flexCheckDefault">  
                                <div class="dropdown">   
                                <button class="settingicons" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">   
                                <a href=""><i class="bi bi-gear mainstyle"></i></a>                                </button>
                                <ul class="dropdown-menu dropdownmenu_innner " aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item  {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET') }}" href='/editTicket/{{$getInfos->id}}'><i class="bi bi-pencil"></i> Edit</a></li>
                                    <li><a class="dropdown-item deleteLink  {{ addUIComponent('SOCIALTICKET_DELETE') }}" href="#" id="{{$getInfos->id}}"><i class="bi bi-trash3"></i> Delete</a></li>
                                                                  </ul>
                                </div>
                                <a   href="{{ addUIComponent('SOCIALTICKET_INNER') == 'HIDDEN' ? '#':'/getSocialTicketById/'.$getInfos->id}}"  href="/getSocialTicketById/{{$getInfos->id}}">{{$getInfos->ticket_id}}</a> <i class="bi bi-clipboard copy-button"></i>
                            </span>
                            
                            </td>
                        <td><i class="bi bi-clipboard copy-button"></i> <a    href="{{ addUIComponent('SOCIALTICKET_INNER') == 'HIDDEN' ? '#':'/getSocialTicketById/'.$getInfos->id}}" >{!! getUrlinString($getInfos->postMessage)!!}</a></td>
                        <td><i class="bi bi-clipboard copy-button"></i> <a class="line_break" href="{{ addUIComponent('SOCIALUSER') == 'HIDDEN' ? '#':'/userProfile/'.$getInfos->socialUser_id}}">{{$getInfos->socialUser}}</a></td>
                        
                            <td>{{ $getInfos->assigned_department ?? 'N/A' }}</td>
                        <td><a herf="#" onclick="location.href='/getSocialTicketById/{{$getInfos->id}}';">{{$getInfos->source}}</a></td>
                        <td><a herf="#" onclick="location.href='/getSocialTicketById/{{$getInfos->id}}';">{{$getInfos->priority}}</a></td>
                        <td><a herf="#" onclick="location.href='/getSocialTicketById/{{$getInfos->id}}';">{{$getInfos->status}}</a></td>
                        <td><a herf="#" onclick="location.href='/getSocialTicketById/{{$getInfos->id}}';">{{$getInfos->final_state}}</a></td>
                        <td><a herf="#" onclick="location.href='/getSocialTicketById/{{$getInfos->id}}';">{{$getInfos->name}}</a></td>
                        <td><a herf="#" onclick="location.href='/getSocialTicketById/{{$getInfos->id}}';">{{$getInfos->date_Created}}</a></td>
                        <td><a href="#" onclick="location.href='/getSocialTicketById/{{$getInfos->id}}';">{{$getInfos->updated_at}}</a></td>
                        <td><a herf="#" >{{$getInfos->sapstatus}}</a></td>
                    </tr>
					@endforeach
					@endif
                </tbody>
            </table>
            </div>
			<div class="my-2 {{ addUIComponent('SOCIALTICKET_TABLE') }} row">

                <div class="col-md-9">
                 {!! $getInfo->withQueryString()->links() !!}
                 </div>
                 <div class="col-md-3">
                 <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                    Showing {{ $getInfo->firstItem() }} - {{ $getInfo->lastItem() }} of {{ $getInfo->total() }} tickets
                 </p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

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
                         <li class="nav-item">
                            <a class="nav-link active2" data-bs-toggle="pill" href="#sap">Sap Ticket Filter</a>
                        </li>

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        
                        <div id="home" class="container tab-pane  active"><br>
						<form method="GET" action="{{ Route::current()->getName()}}"> 
                            
                        @if(request()->has('status'))
                            <input type="hidden" class="form-control" id="status" name="status" value="Resolved">
                        @endif
                        @if(request()->has('user_id'))
                            <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{Auth::user()->id}}">
                        @endif
                           <div class="row">


                                <div class="col-md-12">
                                    <div class="mt-3">
                                        <label for="id" class="form-label">Number</label>
                                        <input type="text" class="form-control" id="id"
                                                placeholder="Number" name="id">
                                    </div>
                                </div>
                                <div class="col-md-12 hide">
                                    <div class="form-check marginten">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            My Items
                                        </label>
                                    </div>
                                </div>
                            </div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-danger">Filter</button>
							</div>
							</form>
                        </div>
						<div id="menu1" class="container tab-pane fade "><br>
                            <div class="tabsmain">   
							<form method="GET" action="{{ Route::current()->getName() }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="text" class="form-label">Number</label>
                                            <input type="text" class="form-control" id="id"
                                                placeholder="Number" name="id">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="user" class="form-label">Social User</label>
                                            <input type="text" class="form-control" id="user" placeholder="Social User" name="user">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="priority" class="form-label">Priority</label>
                                            <select class="form-select" name="priority" aria-label="Default select example">
                                              <option selected></option>
                                              <option value="Low">Low</option>
                                              <option value="Medium">Medium</option>
                                              <option value="High">High</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="source" class="form-label">Source</label>
                                            <select name="source" class="form-select" aria-label="Default select example ">
                                               <option selected></option>
                                               @foreach(getSource() as $key => $getSources)
                                                  <option value="{{$getSources->value}}">{{$getSources->value}}</option>
                                               @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="subject" class="form-label">Subject</label>
                                            <textarea class="form-control" id="subject" rows="3" placeholder="subject" name="subject"></textarea>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="final_state" class="form-label">Final State</label>
                                            <select name="final_state" class="form-select" aria-label="Default select example">
                                                <option value="">Final State</option>
                                                <option value="Open" {{ request()->has('final_state') && request('final_state') == 'Open' ? 'selected' : '' }}>Open</option>
                                                <option value="In Process" {{ request()->has('final_state') && request('final_state') == 'In Process' ? 'selected' : '' }}>In Process</option>
                                                @if(loggedUserRole() !== "OTHERUSER")
                                                    <option value="Close" {{ request()->has('final_state') && request('final_state') == 'Close' ? 'selected' : '' }}>Close</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    @if(request()->has('user_id'))
                                        <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{Auth::user()->id}}">
                                    @endif
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="created" class="form-label">Date Created</label>
                                            <input type="date" class="form-control" id="created"
                                                placeholder="Date Created" name="created">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="bipnumber" class="form-label">Bp Number</label>
                                            <input type="text" class="form-control" id="bipnumber" placeholder="Bp Number" name="bipnumber">
                                        </div>
                                    </div>
                                     

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="assignedto" class="form-label">Assigned To </label>
                                            <select class="form-select" name="assignedto" aria-label="Default select example">
                                                @if(!empty($getUser) && $getUser->count())
                                                    <option selected value=""></option>
                                                    @foreach($getUser as $key => $getUsers)
                                                    
                                                    <option value="{{$getUsers->id}}">{{$getUsers->name}}</option>
                                                    @endforeach
                                                @endif   
                                            </select>
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
                        
                        
                        <!--sap-->
                        <div id="sap" class="container tab-pane active2"><br>
                            <form method="GET" action="{{ Route::current()->getName()}}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mt-3">
                                            <label for="id" class="form-label">Sap Ticket Number</label>
                                            <input type="text" class="form-control" id="id" placeholder="Sap Ticket" name="sapticket" value="{{ request()->sapticket ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger">Filter</button>
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
	<form method="post" action="{{ route('deleteAllTicket') }}" id="deleteForm">
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
$("#ticket").addClass("active");
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "64px";
    document.getElementById("main").style.marginLeft = "64px";
}
</script>
<script>
    var deleteId ="";
    $(document).ready(function() {
        $('#refresh-icon').on('click', function() {
            location.reload(); // Reload the page
        });
        $(".deleteCheck").click(function() {
			var id = $(this).val();
            if ($(this).prop('checked')==true){ 
				$("#deleteForm").append("<input type='hidden' name='postid[]' value='"+id+"' id='"+id+"' />" );
			}
			else{
				$("#deleteForm #"+id).remove();
			}
        });
		
		
		
		$("#deleteBtn").click(function() {
            if ($("#deleteForm input").length > 1){ 
				$("#commonDelete").modal("show");
				$("#commonModal").modal("hide");
				$("#commonBtn").hide();
			}
			else{
				$("#msg").text("Please select row");
				$("#commonBtn").hide();
				$("#commonModal").modal("show");
			}
        });
		
		$(".deleteLink").click(function() {
			deleteId = $(this).attr('id');
			$("#commonModal").modal("show");
			$("#msg").text("Are you sure delete it.");
			$("#commonBtn").show();
        });
		
		$("#commonBtn").click(function() {
			location.href= "/deleteTicket/"+deleteId;
        });
		
		
    });

    </script>
@endsection