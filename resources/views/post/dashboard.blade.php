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
<style>
    .filter_popup{
        max-width: 724px !important;
    }
</style>

<div class="mt-3">
    <div class="div_container">
        <div class="bgwhite2">
            <div class="row mb-4">
                <div class="col-md-6 col-6">
                    <div class="heading_two ">
                        <h2><i class="bi bi-mailbox iconsbg"></i>Social Post </h2>
                    </div>
                </div>
                <div class="col-md-6 col-6">
                    <div class="iconsmenu">
                        <ul class="ms-auto">
                            <li><i class="bi bi-funnel  {{ addUIComponent('SOCIALPOST_FILTER') }}" data-bs-toggle="modal" data-bs-target="#exampleModal"></i></li>
                            <li><i id="refresh-icon" style="cursor: pointer;" class="bi bi-bootstrap-reboot"></i></li>
                            <li id="deleteBtn" class=" {{ addUIComponent('SOCIALPOST_DELETE') }}"><i class="bi bi-trash3"></i> </li>
                            <li><i class="bi bi-list-task  {{ addUIComponent('SOCIALPOST_CHOOSE COLUMNS') }}" data-bs-toggle="modal" data-bs-target="#myModal"></i></li>
                            <li><a href="\createpost" class=" {{ addUIComponent('SOCIALPOST_CREATE_POST') }}"><i class="bi bi-plus-lg"></i></a></li>
                            <div style="clear:both;"></div>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="">
            
            
            <div class="my-2 {{ addUIComponent('SOCIALPOST_TABLE') }} row">
                 <div class="col-md-9">
                 {!! $post->withQueryString()->links() !!}
                 </div>
                 <div class="col-md-3">
                 <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                    Showing {{ $post->firstItem() }} - {{ $post->lastItem() }} of {{ $post->total() }} posts
                 </p>
                </div>
            </div>   
            <table class="table  {{ addUIComponent('SOCIALPOST_TABLE') }}">
                <thead>
                    <tr class="table_width_align">
					@if(!empty($postColumn) && $postColumn->count())
                    @foreach($postColumn as $key => $column)
					@if($column->is_show == 1)
                        <th scope="col">{{$column->column}}</th>
					@endif
					@endforeach
                    @endif
                    <th scope="col">Department</th>
                    </tr>
                </thead>
                <tbody>
				<!-- <pre>
					@php
					
					$fields = [];
					@endphp
				</pre> -->
					@foreach($postColumn as $key => $column)
					@if($column->is_show == 1)
						@php $fields[] = $column->db_field; @endphp
					@endif
					@endforeach
                    @if(!empty($post) && $post->count())
					@php $i = 0; @endphp
                    @foreach($post as $key => $posts)
					
                    <tr>
						@if(!empty($postColumn) && $postColumn->count())
						
						@if(in_array('getTweet_id',$fields))
                        <td class="linebreak" scope="row"> <input class="form-check-input deleteCheck" type="checkbox" 
                                value="{{$posts->id}}" id="{{$posts->id}}">
                            <a  href="{{ addUIComponent('SOCIALPOST_INNER') == 'HIDDEN' ? '#':'/getSocialPostById/'.$posts->getTweet_id}}">{{$posts->getTweet_id}}</a> <i class="bi bi-clipboard copy-button"></i>
                        </td>
						@endif
						
						
						@if(in_array('postMessage',$fields))

                        <td style="table-layout: fixed;"><i class="bi bi-clipboard copy-button"></i> 
						<a style="word-break: break-word;" href="{{ addUIComponent('SOCIALPOST_INNER') == 'HIDDEN' ? '#':'/getSocialPostById/'.$posts->getTweet_id}}">
						{!! getUrlinString($posts->postMessage)!!}</a></td>
						@endif
						
						
                        
						@if(in_array('socialUser_userName',$fields))
							<td class="socialusertd"><i class="bi bi-clipboard copy-button"></i><a  href="/userProfile/{{$posts->socialUser_id}}" href="{{ addUIComponent('SOCIALUSER') == 'HIDDEN' ? '#':'/userProfile/'.$posts->socialUser_id}}">{{$posts->socialUser_name}}({{$posts->socialUser_userName?$posts->socialUser_userName:''}})</a></td>
						
						@endif
						
						@if(in_array('source',$fields))
							
                        <td class="2">{{$posts->source}}</td>
						@endif
						
						
						@if(in_array('postUrl',$fields))
							
							
							
						<td><a class="line_break" href="{{$posts->postUrl}}" target="_blank">{{$posts->postUrl}}</a></td>
						
					
                        
						@endif
						
						@if(in_array('postDate',$fields))
                        <td>{{$posts->istPostDate}}</td>
						@endif
						
						@if(in_array('post_category',$fields))
                        <td>{{$posts->post_category}}</td>
						@endif
						
						@if(in_array('status',$fields))
                        <td>{{$posts->status}}</td>
						@endif
						
						@if(in_array('action',$fields))
                        <td>
                            <div class="editer_file">
                                <div class="dropdown">
                                    <button class="settingicons" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear"></i>
                                    </button>
									
                                    <ul class="dropdown-menu dropdownmenu_innner leftdropdown"
                                        aria-labelledby="dropdownMenuButton1">
                                        <li class="{{ addUIComponent('SOCIALPOST_EDIT_POST') }}"><a class="dropdown-item  {{ addUIComponent('SOCIALPOST_EDIT_POST') }}" href="#"
                                                onclick="location.href='/createpost/{{$posts->id}}';"><i
                                                    class="bi bi-pencil"></i> Edit</a></li>
                                        <li class=" {{ addUIComponent('SOCIALPOST_DELETE') }}"><a class="dropdown-item deleteLink  {{ addUIComponent('SOCIALPOST_DELETE') }}" href="#"
                                        data-bs-toggle="modal" 
                                                    id="{{$posts->id}}"><i
                                                    class="bi bi-trash3" ></i> Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </td>
						@endif
						<td>
                            {{ $posts->department 
                                ? ($departments->where('department_id', $posts->department)->first()->department_name ?? '-') 
                                : '-' 
                            }}
                        </td
						
						@endif
                    </tr>

                    @endforeach
                    @endif
                </tbody>
            </table>
			</div>
            <div class="my-2 {{ addUIComponent('SOCIALPOST_TABLE') }} row">
                 <div class="col-md-9">
                 {!! $post->withQueryString()->links() !!}
                 </div>
                 <div class="col-md-3">
                 <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                    Showing {{ $post->firstItem() }} - {{ $post->lastItem() }} of {{ $post->total() }} posts
                 </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog filter_popup">
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
                     <form method="GET" action="{{ Route::current()->getName()}}">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="mt-3">
                                 <label for="postid" class="form-label">Post ID</label>
                                 <input type="text" class="form-control" id="postid"
                                    placeholder="Post ID" name="postid">
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
                  <div id="menu1" class="container tab-pane fade ">
                     <br>
                     <div class="tabsmain">
                        <form method="GET" action="{{ Route::current()->getName() }}">
                           <div class="row">
								<div class="col-md-6 radiocheckin">
								<label for="text" class="form-label">Filter Type</label>
                                 <div class="form-check p-0 mt-2">
                                    
                                     
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"  name="filterType" value="istPostDate" id="filterType" /> 
                                    <label class="form-check-label" for="filterType">Post Date</label>

								</div>
								<div class="form-check form-check-inline">
									 <input type="radio"  class="form-check-input"   name="filterType" value="responseDate" id="responseDate" /> 
                                    <label class="form-check-label" for="responseDate">Reply Date</label>
                                   
                                 </div>
                              </div>
							  </div>
							  <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                    <label for="text" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="startDate"
                                       placeholder="startDate" name="startDate">
                                    </div>
                                    <div class="col-md-6">
                                    <label for="text" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="endDate"
                                       placeholder="endDate" name="endDate">
                                    </div>
                                </div>

                               
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-3">
                                    <label for="text" class="form-label">Post ID</label>
                                    <input type="text" class="form-control" id="postid"
                                       placeholder="Post ID" name="postid">
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
                                    <label for="url" class="form-label">Post URL</label>
                                    <input type="url" class="form-control" id="url"
                                       placeholder="Post URL" name="url">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-3">
                                    <label for="message" class="form-label">Post Message</label>
                                    <input type="text" class="form-control" id="message"
                                       placeholder="Post Message"name="message">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-3">
                                    <label for="source" class="form-label">Source</label>
                               
									<select class="form-control" placeholder="Source" id="source" name="source">
									<option value="" ></option>
									@foreach(getSource() as $key => $getSources)
                                        <option value="{{$getSources->value}}">{{$getSources->value}}</option>
                                    @endforeach
									
									</select> 
                                 </div>
                              </div>
							  <div class="col-md-6">
                                 <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                               
									<select class="form-control" placeholder="status" id="status" name="status">
									<option value="" ></option>
									<option value="New" >New</option>
									<option value="Pending">Pending</option>
									<option value="Under Process" >Under Process</option>
									<option value="Allocated to IGL Representatives">Allocated to IGL Representatives</option>
									<option value="Unallocated">Unallocated</option>
									<option value="Discarded">Discarded</option>
									<option value="Duplicate">Duplicate</option>

									</select> 
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-3">
                                    <label for="converted" class="form-label">Converted</label>
                                    <input type="text" class="form-control" id="converted" name="converted"
                                       placeholder="converted" >
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" class="form-control" id="category">
									<option value=""  ></option>
									<option value="Feedback Positive"  >Feedback Positive</option>
									<option value="Feedback Negative" >Feedback Negative</option>
									<option value="Complaint"  >Complaint</option>
									<option value="Query">Query</option>
									<option value="Information"   >Information</option>
									<option value="Spam">Spam</option>
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
               </div>
            </div>
         </div>
      </div>
      </form>
   </div>
</div>
    
<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Choose Columns</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
			<form method="POST" action="{{ route('showHideColumn') }}">
			@csrf
            <div class="modal-body">
                <div class="coulams">
                    <div class="headingmamin mb-4">
                        <h6>Displayed</h6>

                    </div>
                    <div class="displayedvalue">
                        <ul id="displayedvalue">
						@if(!empty($postColumn) && $postColumn->count())
						@foreach($postColumn as $key => $column)
						@if($column->is_show == 1)
							<li class="display"><input type="hidden" name="column[]" value="{{$column->id}}" /><button class="custombtn" type="button">{{$column->column}}</button></li>
						@endif
							@endforeach
						
						@endif
                            
                            
                        </ul>
						<div style="clear:both;"></div>
                    </div>
                    <div class="headingmamin mb-4">
                        <h6>Hidden</h6>

                    </div>
                    <div class="hiddenvalue">
                        <ul id="hiddenvalue">
                            @if(!empty($postColumn) && $postColumn->count())
							@foreach($postColumn as $key => $column)
							@if($column->is_show == 0)
								<li class="hideBox"> <input type="hidden" name="columnHide[]" value="{{$column->id}}" /> <button class="custombtn2 " type="button">{{$column->column}}</button></li>
							@endif
							@endforeach
							@endif
                            

                        </ul>
						<div style="clear:both;"></div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Save Changes</button>
            </div>
			</form>
        </div>
    </div>
    </div>
	
<div class="modal" id="commonDelete" tabindex="-1">
  <div class="modal-dialog widthdialoge">
  <form method="post" action="{{ route('deleteAll') }}" >
    <div class="modal-content">
	
	@csrf
      <div class="modal-header">

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="deleteFormDiv">
      <div class="delete_post_ticket">
      <img src="/images/wired-outline-185-trash-bin (2).gif" class="deleteimg">

          <h3 id="textMsg">Are you sure delete it.</h3>
        </div>
      </div>
      <div class="modal-footer" style="justify-content: center;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
	  
    </div>
	</form>
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
    </script>
    <script>
    var deleteId ="";
    $(document).ready(function() {
        $('#refresh-icon').on('click', function() {
            location.reload(); // Reload the page
        });
        $(document).on ('click',".hideBox,.display",function() {
			if($(this).hasClass('display'))
			{
				var id = $(this).find('input').val();
				var text = $(this).find('button').text();
				$(this).remove();
				$("#hiddenvalue").prepend('<li class="hideBox"> <input type="hidden" name="columnHide[]" value="'+id+'" /> <button class="custombtn2 " type="button">'+text+'</button></li>' );
			}
			else{
				var id = $(this).find('input').val();
				var text = $(this).find('button').text();
				$(this).remove();
				$("#displayedvalue").append('<li class="display"> <input type="hidden" name="column[]" value="'+id+'" /> <button class="custombtn" type="button">'+text+'</button></li>' );
			}
        });

        $(".deleteCheck").click(function() {
			var id = $(this).val();
            if ($(this).prop('checked')==true){ 
				$("#deleteFormDiv").append("<input type='hidden' name='postid[]' value='"+id+"' id='"+id+"' />" );
			}
			else{
				$("#deleteFormDiv #"+id).remove();
			}
        });
		
		
		
		$("#deleteBtn").click(function() {
			
            if ($("#deleteFormDiv input").length > 0){ 
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
            $("#commonBtn").show();
			$("#commonModal").modal("show");
			$("#msg").text("Are you sure delete it.");
        });
		
		$("#commonBtn").click(function() {
			location.href= "/deletePost/"+deleteId;
        });
		
		
    });
    const startDate = document.getElementById("startDate");
    const endDate = document.getElementById("endDate");
    const filterTypeRadios = document.querySelectorAll('input[name="filterType"]');

    // Function to set the required attribute for date fields and radio buttons
    const setRequiredAttributes = () => {
        if (startDate.value || endDate.value) {
            startDate.required = true;
            endDate.required = true;
            filterTypeRadios.forEach(radio => {
                radio.required = true;
            });
        } else {
            startDate.required = false;
            endDate.required = false;
            filterTypeRadios.forEach(radio => {
                radio.required = false;
            });
        }
    };

    startDate.addEventListener("input", setRequiredAttributes);
    endDate.addEventListener("input", setRequiredAttributes);
</script>

	@php
	$count = 0;
	@endphp
	@if(!empty($postColumn) && $postColumn->count())
	@foreach($postColumn as $key => $column)
	@if($column->is_show == 0)
		<script> $("table tr td").eq({{$count}}).remove();</script>
	@endif
		@php
	$count ++;	@endphp

		@endforeach
	@endif

    @endsection