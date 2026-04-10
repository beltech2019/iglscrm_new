@extends('auth.layouts')

@section('content')
<style>
.spinner-border{
    position:fixed;
    top:50%;
    left:50%;
    z-index: 9999999999;

}

.bgspiinner:before{
    content: '';
    position: fixed;
    background: #00000042;
    height: 100%;
    width: 100%;
    top: 0px;
    left: 0;
    z-index: 999999;
    max-height: max-content;
}
    </style>

<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2 widthsmall">
            <div class="formscoulam">
			@if($postData && $postData != "")
				<form method="POST" action="/replyTweetId/{{$postData->getTweet_id}}" enctype="multipart/form-data" onsubmit="showLoader()">
			@endif             
                    @csrf
                    <div class="row">
                    <div class="col-md-12">
                    <div class="heading_two ">
                        <h2><i class="bi bi-ticket iconsbg2"></i>Ticket Reply </h2>
                    </div>
                </div>
             
            </div>
           <div class="formscoulam">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label readonly">Source</label>
                        <input type="text" class="form-control readonly" id="lead_source" name="source" placeholder="Source" value="{{$postData?$postData->source:''}}" readonly>            
                              </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="getTweet_id" class="form-label">Post ID</label>
                        <input type="text" class="form-control" id="getTweet_id" name="getTweet_id" placeholder="Post ID" value="{{$postData?$postData->getTweet_id:''}}">
                    </div>
                </div>
              
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <input type="text" class="form-control" id="priority" name="priority" placeholder="Priority" value="{{$postData?$postData->priority:''}}" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="status" class="form-control" id="status" name="status" placeholder="Status"  value="{{$postData?$postData->status:''}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <input type="number" class="form-control" id="mobile_no" name="mobile_no" placeholder="Mobile Number" value="{{$postData?$postData->mobile_no:''}}" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="socialUser" class="form-label">Social User</label>
                        <div style="clear:both;"></div>
                        <input type="text" class="form-control" name="socialUser" id="socialUser" placeholder="Social User" value="{{$postData?$postData->socialUser:''}}">
                </div>
            </div>
            <div class="col-md-4" id="upload_document">
                    <div class="mb-3">
                        <label for="media" class="form-label">Upload Document</label>
                        <input type="file" class="form-control" id="media" name="media" accept=".mp4,.jpg,.jpeg,.png,.gif">
                    </div>
                </div>
            <div class="col-md-12">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Post Message <div class="templateicons">
						<a href="#" class="btn btn-link" onClick="openTemplate('#postMessage')"><i class="bi bi-envelope"></i></a>
						<div style="clear:both;"></div>
						</div></label>
                        <textarea class="form-control {{ addUIComponent('SOCIALTICKET_REPLY_TEMPLATE') }}" id="postMessage" name="postMessage" rows="3" placeholder="Post Message"></textarea>
                    </div>
                    @if($postData && $postData->source=="Twitter")
                    <div class="form-check mt-3 hide">
                        <input class="form-check-input" type="checkbox" value="1"  id="replyondm" name="replyondm">
                        <label class="form-check-label" for="replyondm">Reply On DM</label>									
                    </div>
                    @endif
                </div>
        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="buttons_prime">
                            <button type="submit" class="btn btn-danger">Send</button>
                            <a type="button" href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="bgwhite2">
        @if($reply)
		<table class="table">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Message</th>
      <th scope="col">Type</th>
      <th scope="col">Url</th>
      <th scope="col">Post Date</th>
      <th scope="col">Reply By</th>
    </tr>
  </thead>
  <tbody>
  @foreach($reply as $replys)
    <tr>
    <td scope="row">{{$replys->	tweeter_id}}</td>
      <td>{!! getUrlinString($replys->tweeter_text)!!}</td>
      <td>{{$replys->media_type}}</td>
      <td><a href="{{$replys->url}}" target="_blank">{{$replys->url}}</a></td>
      <td>{{$replys->created_at}}</td>
      <td>{{$replys->name}}</td>
    </tr>
   @endforeach
   @if(count($dmData)>0)
        <tr class="hide">
            <td scope="row">DM Data</td>
            <td></td>
            <td></td>
            <td></td>
            <td class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                <tr id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                    data-bs-parent="#accordionExample">
                    <td colspan="6">
                        <table class="table accordion-body">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Message</th>
                                    <th>Sender</th>
                                    <th>Created Time</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($dmData as $dmDatas)
                                <tr>
                                    <td>{{$dmDatas-> message_id}}</td>
                                    <td>{{$dmDatas-> message}}</td>
                                    <td>{{$dmDatas-> sender_name}}</td>
                                    <td>{{$dmDatas-> message_time}}</td>
                                </tr>         
                            @endforeach    
                            </tbody>
                        </table>
                    </td>
                </tr>
            </td>
        </tr>
        @endif
   <tr>
    <td scope="row">{{$postData?$postData->getTweet_id:''}}</td>
    <td>{!! getUrlinString($postData?$postData->postMessage:'')!!}</td>
      <td>Text</td>
      <td></td>
      <td>{{$postData?$postData->created_at:''}}</td>
    </tr>
  </tbody>
</table>
		@endif
    </div>
    </div>
</div>

<div class="bgspiinner hide" id="loader">
<div class="spinner-border text-primary " role="status">
  <span class="visually-hidden">Loading...</span>
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

function showLoader() {
    document.getElementById("loader").classList.remove("hide"); // Apply blur effect to the container
    disableFormElements(true); // Disable form elements (buttons, inputs, etc.)
}


var replyOnDMCheckbox = document.getElementById("replyondm");
  var postMessageTextarea = document.getElementById("upload_document");

  replyOnDMCheckbox.addEventListener("change", function () {
    if (replyOnDMCheckbox.checked) {
      postMessageTextarea.style.display = "none";
    } else {
      postMessageTextarea.style.display = "block";
    }
  });


function search() {
  var input, select, filterInput, filterSelect, table, tr, td, i, txtValue;
  input = document.getElementById("searchInput");
  select = document.getElementById("searchSelect");
  filterInput = input.value.toUpperCase();
  filterSelect = select.value.toUpperCase();
  table = document.getElementById("socialUserTable");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0]; // Index 0 for the first column
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().includes(filterInput) && txtValue.toUpperCase().includes(filterSelect)) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
 
</script>

@endsection