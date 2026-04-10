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


<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2 widthsmall">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="heading_two ">
                        <h2><i class="bi bi-ticket iconsbg2"></i>Edit Social Ticket </h2>
                        <P class="twitmsg">{{$getSocialTicket->subject}}.</P>
                    </div>
                </div>
            </div>
      <form action="/updateTicket/{{$id}}" enctype="multipart/form-data" method="POST">
       @csrf     
        <div class="formscoulam">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3 {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET_EXCEPT_CHANGE_USER') }}">
                        <label for="exampleFormControlInput1" class="form-label">Number</label>
                        <input type="email" readonly class="form-control" id="exampleFormControlInput1" placeholder="Number" value="{{$getSocialTicket->id}}" name="id" required>
                              </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET_EXCEPT_CHANGE_USER') }}">
                        <label for="exampleFormControlInput1" class="form-label">Social User <span class="starred">*</span></label>
                        <div style="clear:both;"></div>
                        <input type="text" name="socialUser" value="{{$getSocialTicket->socialUser}}" class="form-control controlicons" id="socialUser" placeholder="Social User" required>
                        <div class="socialicons">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bi bi-hand-index-thumb"></i></a>
                        <a type="button" id="socialUserClear"><i class="bi bi-trash3"></i></a>
                        <div style="clear:both;"></div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET_EXCEPT_CHANGE_USER') }}">
                        <label for="exampleFormControlInput1" class="form-label">Type</label>
                        <select class="form-select" name="type" aria-label="Default select example" required>
                            <option value="Complaint" {{$getSocialTicket && $getSocialTicket->type=='Complaint'?'selected':''}}>Complaint</option>
                            <option value="Emergency" {{$getSocialTicket && $getSocialTicket->type=='Emergency'?'selected':''}}>Emergency</option>
							<option value="Request Service" {{$getSocialTicket && $getSocialTicket->type=='Service Request'?'selected':''}}>Service Request </option>
                            <option value="Other Query" {{$getSocialTicket && $getSocialTicket->type=='Other Query'?'selected':''}}>Other Query</option>
                            </select>
                    </div>
                </div>
              
                <div class="col-md-4">
                    <div class="mb-3 {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET_EXCEPT_CHANGE_USER') }}">
                        <label for="exampleFormControlInput1" class="form-label">Priority</label>
                        <select class="form-select" name="priority" aria-label="Default select example"  required>
                            <option value="Low" {{$getSocialTicket && $getSocialTicket->priority=='Low'?'selected':''}}>Low</option>
                            <option value="Medium" {{$getSocialTicket && $getSocialTicket->priority=='Medium'?'selected':''}}>Medium</option>
                            <option value="High" {{$getSocialTicket && $getSocialTicket->priority=='High'?'selected':''}}>High</option>
                            </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET_EXCEPT_CHANGE_USER') }}">
                        <label for="suggestion" class="form-label">Suggestions</label>
                        <input type="text" value="{{$getSocialTicket->suggestion}}" name="suggestion" class="form-control" id="exampleFormControlInput1" placeholder="Suggestions"  >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET_EXCEPT_CHANGE_USER') }}">
                        <label for="subject" class="form-label">Subject <span class="starred">*</span></label>
                        <input type="text" name="subject" value="{{$getSocialTicket->subject}}" class="form-control" id="subject" placeholder="Subject" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Final State</label>
                        <select name="final_state" class="form-select" aria-label="Default select example" required>
                            <option value="Open" {{$getSocialTicket && $getSocialTicket->final_state=='Open'?'selected':''}}>Open</option>
							<option value="In Process" {{$getSocialTicket && $getSocialTicket->final_state=='In Process'?'selected':''}}>In Process</option>
                            @if(loggedUserRole()!=="OTHERUSER" || $getSocialTicket->final_state=='Close')<option value="Close" {{$getSocialTicket && $getSocialTicket->final_state=='Close'?'selected':''}}>Close</option>@endif
                            
                            </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3 {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET_EXCEPT_CHANGE_USER') }}">
                        <label for="exampleFormControlInput1" class="form-label">Status <span class="starred">*</span></label>
                        <select name="status" class="form-select" aria-label="Default select example">
                            <option value="New" {{$getSocialTicket && $getSocialTicket->status=='New'?'selected':''}}>New</option>
                            <!-- <option value="Assigned" {{$getSocialTicket && $getSocialTicket->status=='Assigned'?'selected':''}}>Assigned</option> -->
							<!-- <option value="" ></option> -->
                            <option value="Pending with team" {{$getSocialTicket && $getSocialTicket->status=='Pending with team'?'selected':''}}>Pending with team</option>
                            <option value="Move to internal Team" {{$getSocialTicket && $getSocialTicket->status=='Move to internal Team'?'selected':''}}>Move to internal Team</option>
                            <option value="Resolved" {{$getSocialTicket && $getSocialTicket->status=='Resolved'?'selected':''}}>Resolved</option>
                            <option value="Rejected" {{$getSocialTicket && $getSocialTicket->status=='Rejected'?'selected':''}}>Rejected</option>
                            <!-- <option value="Recived" {{$getSocialTicket && $getSocialTicket->status=='Recieved'?'selected':''}}>Received</option> -->
                            <!-- <option value="Close" {{$getSocialTicket && $getSocialTicket->status=='Close'?'selected':''}}>Close</option> -->
                            <option value="Duplicate" {{$getSocialTicket && $getSocialTicket->status=='Duplicate'?'selected':''}}>Duplicate</option>
                            </select>
                    </div>
                </div>
                  
                <div class="col-md-4">
                    <div class="mb-3 {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET_EXCEPT_CHANGE_USER') }}">
                        <label for="exampleFormControlInput1" class="form-label">Source</label>
                        <select name="source" class="form-select readonly" aria-label="Default select example " readonly>
                            <option selected value=""></option>
                              @foreach(getSource() as $key => $getSources)
                                <option value="{{$getSources->value}}" {{$getSocialTicket && $getSocialTicket->source==$getSources->value?'selected':''}}>{{$getSources->value}}</option>
                              @endforeach
                            <option value="Portal"{{$getSocialTicket && $getSocialTicket->source=='Portal'?'selected':''}}>Portal</option>
                            <option value="Call"{{$getSocialTicket && $getSocialTicket->source=='Call'?'selected':''}}>Call</option>
							<option value="Other" {{$getSocialTicket && $getSocialTicket->source=='Other'?'selected':''}}>Other</option>
                            <option value="Inbounced Email"{{$getSocialTicket && $getSocialTicket->source=='Inbounced Email'?'selected':''}}>Inbounced Email</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET_EXCEPT_CHANGE_USER') }}">
                        <label for="exampleFormControlInput1" class="form-label">Sub Source</label>
                        <select class="form-select" aria-label="Default select example" disabled>
                            <option selected value=""></option>
                            </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET_EXCEPT_CHANGE_USER') }}">
                        <label for="exampleFormControlInput1" class="form-label">BP Number</label>
                        <input type="text" value="{{$getSocialTicket->bipNumber}}" name="bipNumber" class="form-control" id="exampleFormControlInput1" placeholder="BP Number">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Assigned To <span class="starred">*</span></label>
                        <select class="form-select" name="assignedto" aria-label="Default select example" required>
                          @if(!empty($getUser) && $getUser->count())
							  <option selected value=""></option>
                            @foreach($getUser as $key => $getUsers)
							
                              <option value="{{$getUsers->id}}" <?php  echo $getSocialTicket && $getUsers->id == $getSocialTicket->assigned_to?'selected':''?>>{{$getUsers->name}}</option>
                            @endforeach
                          @endif   
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                <div class="">
                <label for="socialUser_name" class="form-label">Upload Document <span  class="starred">*</span></label>
                  <input class="form-control" type="file" id="media" name="media" accept=".pdf, .jpg, .jpeg, .xlsx, .mp4, .docx, .png, .doc">
                </div>
                @if(count($attacheddata)>0)
                <div class="documentType coulams_form">

                  <ul>
                  @foreach($attacheddata as $attacheddatas)
                   <?php $filename = pathinfo($attacheddatas->fileName)['basename']; ?>
                      <li>{{$filename}} <a href="/deleteattachmentfromticket/{{$getSocialTicket->id}}"><i class="bi bi-x-lg"></i></a></li>
                  @endforeach    
                  </ul>
                </div>
                @endif
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Description <span class="starred">*</span></label>
                        <textarea name="description" value="{{$getSocialTicket->description}}"  class="form-control" id="description" rows="3" placeholder="Description" required>{{$getSocialTicket->description}}</textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3 {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET_EXCEPT_CHANGE_USER') }}">
                        <label for="exampleFormControlInput1" class="form-label">Resolution 
                    </label>
                        <textarea  value="{{$getSocialTicket->resolution}}" name="resolution" class="form-control" id="resolution" rows="3" placeholder="Resolution" >{{$getSocialTicket->resolution}}</textarea>
						
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3 {{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET_EXCEPT_CHANGE_USER') }}">
                        <label for="exampleFormControlInput1" class="form-label">Additional Text </label>
						
                        <textarea name="additional_Text" value="{{$getSocialTicket->additional_Text}}" class="form-control" id="additional_Text" rows="3" placeholder="Additional Text" >{{$getSocialTicket->additional_Text}}</textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="buttons_prime">
                      <a href="/tweetLogList/{{$getSocialTicket->id}}/ticket" class="btn btn-danger  {{ addUIComponent('SOCIALTICKET_VIEW_CHANGE_LOG') }}">View Change Log </a>
                    <button type="submit" class="btn btn-danger  {{ addUIComponent('SOCIALTICKET_SAVE') }}">Save</button>
                    <a href="{{ url()->previous() }}" type="button" class="btn btn-secondary">Cancel</button></a>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </form> 
    </div>
</div>
</div>


<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog modalwidth">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Search social User</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="modalforms">
        <form action="/editTicket/{{$id}}" method="GET">
        @csrf
        <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Name</label>
                                            <input type="text" class="form-control" name="user" id="searchInput" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Assigned to</label>
                                            <select class="form-control" placeholder="Source" name="assignedto" id="searchSelect">
                                               <option value="" disabled selected>Select your option</option>
                                            @if(!empty($getUser) && $getUser->count())
                                              @foreach($getUser as $key => $getUsers)
                                                 <option value="{{$getUsers->name}}">{{$getUsers->name}}</option>
                                              @endforeach
                                            @endif    
                                            </select>  
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                        <button type="submit" class="btn btn-danger">Search</button>
                                        <button type="button" id="clearButton" class="btn btn-secondary marginalign">Clear</button>
                                        </div>
                                    </div>
                                    </div>
                                    </form>
            </div>
            <div class="socialuser mt-4">
            <div class="headingmain">
                <h5>Social User</h5>
            </div>
            <table class="table">
  <thead>
    <tr>
   
      <th scope="col">Name</th>
      <th scope="col">Assigned to</th>
      <th scope="col">Date Created</th>
    </tr>
  </thead>
  <tbody id="socialUserTable">
  @if(!empty($getSocialUser) && $getSocialUser->count())
   @foreach($getSocialUser as $key => $SocialUser)
    <tr>
      <td>{{$SocialUser->socialUser_name}}({{$SocialUser->socialUser_userName}})</td>
      <td class="hide">{{$SocialUser->socialUser_id}}</td>
      <td>{{$SocialUser->assigned_to}}</td>
      <td>{{$SocialUser->postDate}}</td>
    </tr> 
    @endforeach
   @endif  
  </tbody>
</table>
<div class="my-2">

                
		{!! $getSocialUser->withQueryString()->links() !!}
	</div>
</div>
</div>
    
     

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

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

function search() {
  var input, select, filterInput, filterSelect, table, tr, tdName, tdAssignedTo, i, txtValueName, txtValueAssignedTo;
  input = document.getElementById("searchInput");
  select = document.getElementById("searchSelect");
  filterInput = input.value.toUpperCase();
  filterSelect = select.value.toUpperCase();
  table = document.getElementById("socialUserTable");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    tdName = tr[i].getElementsByTagName("td")[0]; 
    tdAssignedTo = tr[i].getElementsByTagName("td")[1]; 
    if (tdName && tdAssignedTo) {
      txtValueName = tdName.textContent || tdName.innerText;
      txtValueAssignedTo = tdAssignedTo.textContent || tdAssignedTo.innerText;
      if (
        txtValueName.toUpperCase().includes(filterInput) &&
        txtValueAssignedTo.toUpperCase().includes(filterSelect)
      ) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

  const selectField = document.getElementById('searchSelect');
  const inputField = document.getElementById('searchInput');
  const clearButton = document.getElementById('clearButton');
  clearButton.addEventListener('click', function() {
    inputField.value = '';
    selectField.selectedIndex = -1;
  }); 


  $(document).ready(function() {
    $('#socialUserClear').on('click', function() {
        $('#socialUser').val('');
    });
    $("#socialUserTable tr").click(function() {
      var tdValue = $(this).find("td:first").text();
      $("#socialUser").val(tdValue);
      $("#myModal").modal("hide");
    });
	<?php if($modal):?>
    if(<?php echo $modal?>){
      $("#myModal").modal("show");
    }
	<?php endif?>
  });


</script>
<style>

select.readonly {
  opacity: 0.8;
  pointer-events: none;
}
</style>
@endsection