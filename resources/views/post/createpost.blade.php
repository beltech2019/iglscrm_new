<style>
  .reasonhistory .accordion-button:not(.collapsed) {
    color: #000000 !important;
    background-color: #ffffff !important;
    box-shadow: none !important;
}
.accordion-button:focus {
    z-index: 3;
    border-color: transparent !important;
    outline: 0;
    box-shadow: none !important;
}
</style>
@extends('auth.layouts')

@section('content')
<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2 widthsmall">
					<div class="formscoulam">
					@if($postData && $postData != "")
						<form method="POST" action="/manualAddSocailPost/{{$postData->id}}" enctype="multipart/form-data">
					@else
						<?php $postData  = false;?>
						<form method="POST" action="/manualAddSocailPost" enctype="multipart/form-data">
					@endif
                
                    @csrf
                    <div class="row">
                    <div class="col-md-12">
                    <div class="heading_two ">
                        <h2><i class="bi bi-ticket iconsbg2"></i>{{$create_post}} </h2>
                    </div>
                </div>
             
            </div>
           <div class="formscoulam">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1 " class="form-label">Source</label>
                        <select class="form-control {{$postData? 'readonly':''}} " placeholder="Source" id="source" name="source">
                          @foreach(getSource() as $key => $getSources)
                            <option value="{{$getSources->value}}" {{$postData && $postData->source==$getSources->value?'selected':''}}>{{$getSources->value}}</option>
                          @endforeach   
                        </select>              
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="getTweet_id" class="form-label">Post ID <span  class="starred">*</span></label>
                        <input type="text" class="form-control" id="getTweet_id" name="getTweet_id" placeholder="Post ID" value="{{$postData?$postData->getTweet_id:''}}" required />
                    </div>
                </div>
              
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="mobile_no" class="form-label">Mobile Number</label>
                        <input type="number" class="form-control" id="mobile_no" name="mobile_no" placeholder="Mobile Number" value="{{$postData?$postData->mobile_no:''}}"  />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="bp_number" class="form-label">BP Number</label>
                        <input type="number" class="form-control" id="bp_number" name="bp_number" placeholder="BP Number" value="{{$postData?$postData->bp_number:''}}"  />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="mobile_no" class="form-label">User Id <span  class="starred">*</span></label>
                        <input type="number" class="form-control" id="socialUser_id" name="socialUser_id" placeholder="User Id" value="{{$postData?$postData->socialUser_id:''}}" required/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email_id" placeholder="Email"  value="{{$postData?$postData->email_id:''}}"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="istPostDate" class="form-label">Post Date</label>
                        <input type="text" class="form-control" id="istPostDate" name="istPostDate" placeholder="Post Date" value="{{$formattedDate?$formattedDate:''}}" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                <div class="mb-3">
                 <label for="post_category" class="form-label">Category <span  class="starred">*</span></label>
                 <select name="post_category" class="form-control" id="post_category"  required>
									<option value=""  ></option>
									<option value="Feedback Positive" {{$postData && $postData->post_category=='Feedback Positive'?'selected':''}}>Feedback Positive</option>
									<option value="Feedback Negative" {{$postData && $postData->post_category=='Feedback Negative'?'selected':''}}>Feedback Negative</option>
									<option value="Complaint" {{$postData && $postData->post_category=='Complaint'?'selected':''}}>Complaint</option>
									<option value="Query" {{$postData && $postData->post_category=='Query'?'selected':''}}>Query</option>
									<option value="Information" {{$postData && $postData->post_category=='Information'?'selected':''}}>Information</option>
									<option value="Spam" {{$postData && $postData->post_category=='Spam'?'selected':''}}>Spam</option>
								</select>
                </div>
                </div>
				
				
                <div class="col-md-4">
                <div class="mb-3">
                 <label for="status" class="form-label">Status</label>
                 <select name="status" class="form-control" id="status"  required>
					<option value="New" {{$postData && $postData->status=='New'?'selected':''}}>New</option>
					<option value="Pending" {{$postData && $postData->status=='Pending'?'selected':''}}>Pending</option>
					<option value="Under Process" {{$postData && $postData->status=='Under Process'?'selected':''}}>Under Process</option>
					<option value="Allocated to IGL Representatives" {{$postData && $postData->status=='Allocated to IGL Representatives'?'selected':''}}>Allocated to IGL Representatives</option>
					<option value="Unallocated" {{$postData && $postData->status=='Unallocated'?'selected':''}}>Unallocated</option>
					<option value="Discarded" {{$postData && $postData->status=='Discarded'?'selected':''}}>Discarded</option>
					<option value="Duplicate" {{$postData && $postData->status=='Duplicate'?'selected':''}}>Duplicate</option>
				</select>
                </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="socialUser_name" class="form-label">Social User <span  class="starred">*</span></label>
                        <div style="clear:both;"></div>
                        <input type="text" class="form-control controlicons" name="socialUser_name" id="socialUser_name" placeholder="Social User" value="{{$postData?$postData->socialUser_name:''}}" required />
                        
                        <div class="socialicons">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bi bi-hand-index-thumb"></i></a>
                        <a type="button" id="socialUserClear"><i class="bi bi-trash3"></i></a>
                        <div style="clear:both;"></div>
                        </div>
                   
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="department" class="form-label">Department</label>
                    <select name="department" id="department" class="form-control">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->department_id }}"
                                {{ isset($postData) && isset($postData->department) && $postData->department == $department->department_id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-4">
                <div class="mb-3">
                <label for="socialUser_name" class="form-label">Upload Document <span  class="starred">*</span></label>
                  <input class="form-control" type="file" id="media" name="media" accept=".pdf, .jpg, .jpeg, .xlsx, .mp4, .docx, .png, .doc">
                </div>
                @if(count($attacheddata)>0)
                <div class="documentType coulams_form">

                  <ul>
                  @foreach($attacheddata as $attacheddatas)
                   <?php $filename = pathinfo($attacheddatas->fileName)['basename']; ?>
                      <li>{{$filename}} <a href="/deleteattachment/{{$postData->getTweet_id}}"><i class="bi bi-x-lg"></i></a></li>
                  @endforeach    
                  </ul>
                </div>
                @endif                                
                </div> 
                <div class="col-md-6">
                  <div class="mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Reason </label><div id="charCount" class="text-muted mt-1 float-end" style="font-size:12px"> 249/249</div>
                      <textarea class="form-control" id="reason" name="reason_text" rows="3" placeholder="Reason" oninput="updateCounter()" maxlength="249"></textarea>
                  </div>
              </div>
                <div class="col-md-6">
                      <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Post Message  <span  class="starred">*</span>
                          <!-- <div class="templateicons">
                                <a href="#" class="btn btn-link" onClick="openTemplate('#postMessage')"><i class="bi bi-envelope"></i></a>
                                <div style="clear:both;"></div>
                                </div> -->
						            </label>
						
                        <textarea class="form-control {{$postData?'READ_ONLY':''}}" id="postMessage" name="postMessage" rows="3" placeholder="Post Message" required>{{$postData?$postData->postMessage:''}}</textarea>
                    </div> 
                  </div>
                  @if($getActivity && $getActivity->count()>0)
                  <div class="accordion reasonhistory" id="accordionExample" style="width:50%">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          Reason History
                        </button>
                      </h2>
                      <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <div class="col-md-12">
                            <div class="col-mb-3">
                              @foreach($getActivity as $activity)
                                <div class="mb-2 row">
                                  <div class="col-md-12">
                                    <div class="content_past_activity ms-2">
                                      <p class="namehistory">{{ $activity->name}} <span class="datehistory">{{ $activity->created_at}}</span></p>
                                      <p class="texthistory">{{ $activity->text}}</p>
                                    </div>
                                  </div>
                                </div>
                              @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- accordion end -->
                  @endif
                                 
                    </div>
                    <div class="col-md-12">
                        <div class="buttons_prime">
                            <button type="submit" class="btn btn-danger {{ addUIComponent('SOCIALPOST_SAVE') }}">Save</button>
                            <a type="button" href="/dashboard" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
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
        @if($postData && $postData != "")
				  <form method="GET" action="/createpost/{{$postData->id}}">
				@else
				  <?php $postData  = false;?>
				  <form method="GET" action="/createpost">
				@endif
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
<script>
$("#post").addClass("active");
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
        $('#socialUser_name').val('');
    });
    $("#socialUserTable tr").click(function() {
      var tdValue = $(this).find("td:first").text();
      var tdValue2 = $(this).find("td:eq(1)").text();
      $("#socialUser_id").val(tdValue2);
      $("#socialUser_name").val(tdValue);
      $("#myModal").modal("hide");
    });
    <?php if($modal):?>
    if(<?php echo $modal?>){
      $("#myModal").modal("show");
    }
	<?php endif?>
  });
  function updateCounter() {
        var maxLength = 249;
        var currentLength = document.getElementById('reason').value.length;
        var charactersLeft = maxLength - currentLength;

        // Display the remaining characters count
        document.getElementById('charCount').innerText = charactersLeft +'/249 ';
    }
</script>

@endsection