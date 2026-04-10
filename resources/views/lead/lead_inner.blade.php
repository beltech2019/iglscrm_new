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
        <div class="bgwhite2 pb-0">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="heading_two ">
                        <h2><i class="bi bi-ticket iconsbg2"></i>Leads</h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="editbtns" style="float:right;">
                        <div class="iconsmenu2" style="float:none;">
                            <ul class="ms-auto">
                                <li>
                                    <form method="POST" action="/addFavourite?type=tb_leads&type_id={{$leadData->id}}">   
                                    @csrf    
                                       <div class="icon-container" > 
                                         <button class="btn" type="submit"><i class="bi bi-star" style="{{ $getFavourite && $getFavourite->status=='1' ? 'background: #ffd525;' : '' }}"></i></button>
                                       </div>
                                    </form>
                                </li>
                                <li>
                                    <div class="dropdown">
                                        <a class="btn btn-secondary btncustom dropdown-toggle" href="#" role="button"
                                            id="dropdownMenuLinkmain" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-pencil-square" style="background:none;padding:0;"></i> Action
                                        </a>

                                        <ul class="dropdown-menu dropdownmenu_innner"
                                            aria-labelledby="dropdownMenuLinkmain">
                                            <li class="{{ addUIComponent('LEAD_DUPLICATE') }}"><a class="dropdown-item" href="/createUpdateLead/{{$leadData->id}}?status=Duplicate"><i class="bi bi-layers"></i>
                                                    Duplicate</a></li>
                                            <li class="{{ addUIComponent('LEAD_DELETE') }}"><a class="dropdown-item deleteLink"  id="{{$leadData->id}}" href="#" ><i class="bi bi-trash3"></i>
                                                    Delete</a></li>
                                            <li class="{{ addUIComponent('LEAD_EDIT_LEAD') }}"><a class="dropdown-item"  href="/createLead/{{$leadData->id}}"><i class="bi bi-pencil"></i> Edit
                                                    </a></li>
                                            <li class="{{ addUIComponent('LEAD_CONVERT_SOCIALTICKET') }}"><a class="dropdown-item" href="/generateTicketFromLead/{{$leadData->id}}"><i
                                                        class="bi bi-view-stacked"></i>
                                                    Convert to ticket</a></li>
                                            <!-- <li><a class="dropdown-item {{ addUIComponent('LEAD_MANAGE_SUBSCRIPTION') }}" href=""><i
                                                        class="bi bi-view-stacked"></i>
                                                    Manage Subscription</a></li>
                                            <li><a class="dropdown-item {{ addUIComponent('LEAD_DOWNLOAD_VCARD') }}" href=""><i
                                                        class="bi bi-view-stacked"></i>
                                                    Download VCard</a></li>
                                            <li><a class="dropdown-item {{ addUIComponent('LEAD_CREATE_LEAD') }}" href="/createLead"><i
                                                        class="bi bi-view-stacked"></i>
                                                    Create Lead</a></li>                         -->
                                            <li class="{{ addUIComponent('LEAD_VIEW_CHANGE_LOG') }}"><a class="dropdown-item" href="/tweetLogList/{{$leadData->id}}/Lead"><i
                                                        class="bi bi-view-stacked"></i>
                                                    View Change Log</a></li>
                                            <li class="{{ addUIComponent('LEAD_REPLY') }}"><a class="dropdown-item" href="/leadReply/{{$leadData->id}}"><i
                                                        class="bi bi-view-stacked"></i>
                                                    Reply</a></li> 
                                        </ul>
                                    </div>

                                </li>

                                <div style="clear:both;"></div>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <div class="tabspost filter">
                <ul class="steps">
                    <li>
                        <p class="steps_inner">1</p>
                        <p>New</p>
                    </li>
                    @php $count = 2; @endphp
						@if(!empty($getSocialLog))
							
						<?php foreach($getSocialLog as $dataVal):?>
						<li>
							<p  class="steps_inner {{$dataVal->new_value}}">{{$count}}
								</p>
							<p>{{$dataVal->new_value}}</p>
						</li>
					@php $count++ @endphp
					@endforeach
					@endif
                 
                </ul>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div class="">

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="tabsone">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="divone bgwhite2">
                                    <div class="tabscircle">
                                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="postonetab" data-bs-toggle="pill"
                                                    data-bs-target="#postone" type="button" role="tab"
                                                    aria-controls="postone" aria-selected="true">Overview</button>
                                            </li>
                                        
                                            
                                        </ul>
                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="postone" role="tabpanel"
                                                aria-labelledby="postonetab">
                                                <div class="maincontent">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">First Name</label>
                                                                <p class="peragraph_content">{{$leadData->first_name}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Last Name</label>
                                                                <p class="peragraph_content">{{$leadData->last_name}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Type</label>
                                                                <p class="peragraph_content">{{$leadData->type}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Office Phone</label>
                                                                <p class="peragraph_content">{{$leadData->office_phone}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Title</label>
                                                                <p class="peragraph_content">{{$leadData->title}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Mobile</label>
                                                                <p class="peragraph_content">{{$leadData->mobile}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Department</label>
                                                                <p class="peragraph_content">{{$leadData->department}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Customer Name</label>
                                                                <p class="peragraph_content">{{$leadData->customer_name}}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Status</label>
                                                                <p class="peragraph_content">{{$leadData->status}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">BP Number</label>
                                                                <p class="peragraph_content">{{$leadData->bp_number}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mb-4">
           
                <label for="socialUser_name" class="form-label">Document <span  class="starred">*</span></label>
                 
                @if(count($attacheddata)>0)
                                                <ul>
                                                   @foreach($attacheddata as $attacheddatas)
                                                     <?php $filename = pathinfo($attacheddatas->fileName)['basename']; ?>
                                                     <a target="blank" href="{{$attacheddatas->fileUrl}}"><li>{{$filename}}</li></a>
                                                   @endforeach    
                                                </ul>
                                                @endif
                </div>
                                                        <div class="col-md-12">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Approval Status</label>
                                                                <p class="peragraph_content">{{$leadData->approvel_status}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Primary Address</label>
                                                                <p class="peragraph_content">{{$leadData->primary_address}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Other Address</label>
                                                                <p class="peragraph_content">{{$leadData->other_address}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Email Address</label>
                                                                <p class="peragraph_content">{{$leadData->email_address}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Converted</label>
                                                                <input class="form-check-input" type="checkbox" value="1" id="converted" {{$leadData->converted?'checked':''}}>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Description</label>
                                                                <p class="peragraph_content">{!! getUrlinString($leadData->description)!!}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Resolution</label>
                                                                <p class="peragraph_content">{{$leadData->resolution}}
                                                                </p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                       
                                        
                                        <div class="tab-pane fade" id="postthree" role="tabpanel"
                                            aria-labelledby="postthreetab">3</div>
                                    </div>
                                    </div>
                                </div>
                                
                           
                        </div>
                        <div class="col-md-5 {{ addUIComponent('LEAD_ACTIVITIES') }}">
                            <div class="bgwhite2 tabscircle heightscroll2">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-profile" type="button" role="tab"
                                            aria-controls="pills-profile" aria-selected="true">
                                            Activities</button>
                                    </li>
                                    <!--<li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-contact" type="button" role="tab"
                                            aria-controls="pills-contact" aria-selected="false">Past
                                            Activities</button>
                                    </li>-->
                                </ul>
                                <div class="tab-content" id="pills-tabContent">

                                    <div class="tab-pane active show fade" id="pills-profile" role="tabpanel"
                                        aria-labelledby="pills-profile-tab">

											<form method="POST" action="/createUpdateActivity">
                                            @csrf
											<div class="row">
											 <div class="mb-3 col-md-12">
                                            
											<textarea class="form-control" name="text" id="history_Text" rows="2"  placeholder="Type Here" required></textarea>
									        </div>
											<div class="mb-3 col-md-12 text-end">
											<input type="hidden" name="created_by" value="{{loggedUserId()}}"/> 
											<input type="hidden" name="type" value="Lead"/> 
											<input type="hidden" name="post_id" value="{{$leadData->id}}"/> 
											<button type="submit" class="btn btn-danger {{ addUIComponent('LEAD_ACTIVITIES') }}">Save</button>
											</div>
											</div>
										</form>
										@if(!empty($getActivity))
										
										
											@foreach($getActivity as $activity)
											<div class="mb-1 row">
                                                <div class="col-md-1 iconsmenu">
                                                    <ul class="ms-auto">

                                                        <li class="mt-2"><i class="bi bi-envelope"></i></li>

                                                        <div style="clear:both;"></div>
                                                    </ul>

                                                </div>
                                                <div class="col-md-11">
                                                    <div class="content_past_activity ms-2">
													 <p class="namehistory">{{ $activity->name}} <span class="datehistory">{{ $activity->created_at}}</span></p>
                                                        <p class="texthistory">{{ $activity->text}}</p>
                                                       
                                                    </div>

                                                </div>
                                                </div>
                                          
                                               
	
                                               
												@endforeach
												
                                            
										@else
                                        <div class="maincontent2">
                                            <img src="/images/folder.png" class="nodata">
                                            <h6>No data Found</h6>
                                           
                                        </div>
										@endif
                                    </div>
                                    <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                        aria-labelledby="pills-contact-tab">
                                        <div class="pastactivity">
                                            <div class="row">
                                                <div class="col-md-2 iconsmenu">
                                                    <ul class="ms-auto iconswidthalign">

                                                        <li><i class="bi bi-envelope"></i></li>

                                                        <div style="clear:both;"></div>
                                                    </ul>

                                                </div>
                                                <div class="col-md-9">
                                                    <div class="content_past_activity">
                                                        <h6>Lorem Ipsum is simply dummy text.</h6>
                                                        <p>typesetting industry. Lorem Ipsum has been the industry's
                                                            standard dummy text ever since the 1500s,</p>
                                                    </div>

                                                </div>
                                                <div class="col-md-1">
                                                    <div class="dropdown">
                                                        <button class=" settingicons" type="button"
                                                            id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdownmenu_innner "
                                                            aria-labelledby="dropdownMenuButton1">
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-eye"></i> View </a></li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-pencil"></i> Edit </a></li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-trash3"></i> Delete</a></li>
                                                        </ul>
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 iconsmenu">
                                                    <ul class="ms-auto iconswidthalign">

                                                        <li><i class="bi bi-envelope"></i></li>

                                                        <div style="clear:both;"></div>
                                                    </ul>

                                                </div>
                                                <div class="col-md-9">
                                                    <div class="content_past_activity">
                                                        <h6>Lorem Ipsum is simply dummy text.</h6>
                                                        <p>typesetting industry. Lorem Ipsum has been the industry's
                                                            standard dummy text ever since the 1500s,</p>
                                                    </div>

                                                </div>
                                                <div class="col-md-1">
                                                    <div class="dropdown">
                                                        <button class=" settingicons" type="button"
                                                            id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdownmenu_innner "
                                                            aria-labelledby="dropdownMenuButton1">
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-eye"></i> View </a></li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-pencil"></i> Edit </a></li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-trash3"></i> Delete</a></li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 iconsmenu">
                                                    <ul class="ms-auto iconswidthalign">

                                                        <li><i class="bi bi-envelope"></i></li>

                                                        <div style="clear:both;"></div>
                                                    </ul>

                                                </div>
                                                <div class="col-md-9">
                                                    <div class="content_past_activity">
                                                        <h6>Lorem Ipsum is simply dummy text.</h6>
                                                        <p>typesetting industry. Lorem Ipsum has been the industry's
                                                            standard dummy text ever since the 1500s,</p>
                                                    </div>

                                                </div>
                                                <div class="col-md-1">
                                                    <div class="dropdown">
                                                        <button class=" settingicons" type="button"
                                                            id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdownmenu_innner "
                                                            aria-labelledby="dropdownMenuButton1">
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-eye"></i> View </a></li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-pencil"></i> Edit </a></li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-trash3"></i> Delete</a></li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 iconsmenu">
                                                    <ul class="ms-auto iconswidthalign">

                                                        <li><i class="bi bi-envelope"></i></li>

                                                        <div style="clear:both;"></div>
                                                    </ul>

                                                </div>
                                                <div class="col-md-9">
                                                    <div class="content_past_activity">
                                                        <h6>Lorem Ipsum is simply dummy text.</h6>
                                                        <p>typesetting industry. Lorem Ipsum has been the industry's
                                                            standard dummy text ever since the 1500s,</p>
                                                    </div>

                                                </div>
                                                <div class="col-md-1">

                                                    <div class="dropdown">
                                                        <button class=" settingicons" type="button"
                                                            id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdownmenu_innner "
                                                            aria-labelledby="dropdownMenuButton1">
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-eye"></i> View </a></li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-pencil"></i> Edit </a></li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-trash3"></i> Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 iconsmenu">
                                                    <ul class="ms-auto iconswidthalign">

                                                        <li><i class="bi bi-envelope"></i></li>

                                                        <div style="clear:both;"></div>
                                                    </ul>

                                                </div>
                                                <div class="col-md-9">
                                                    <div class="content_past_activity">
                                                        <h6>Lorem Ipsum is simply dummy text.</h6>
                                                        <p>typesetting industry. Lorem Ipsum has been the industry's
                                                            standard dummy text ever since the 1500s,</p>
                                                    </div>

                                                </div>
                                                <div class="col-md-1">
                                                    <div class="dropdown">
                                                        <button class=" settingicons" type="button"
                                                            id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdownmenu_innner "
                                                            aria-labelledby="dropdownMenuButton1">
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-eye"></i> View </a></li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-pencil"></i> Edit </a></li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-trash3"></i> Delete</a></li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bgwhite2">
		
		@if($reply)
		<table class="table {{ addUIComponent('LEAD_REPLY_TABLE') }}">
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
    <td scope="row">{{$leadData->id}}</td>
    <td>{!! getUrlinString($leadData?$leadData->description:'')!!}</td>
      <td>Text</td>
      <td></td>
      <td>{{$leadData->created_at}}</td>
    </tr>
  </tbody>
</table>
		@endif
		
		
    </div>
                        </div>
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

            function openDelMod(id) {
                
            }
            </script>
<script>
    var deleteId ="";
    $(document).ready(function() {
		
		$(".deleteLink").click(function() {
        deleteId = $(this).attr('id');
        $("#commonModal").modal("show");
        $("#commonBtn").show();
        $("#msg").text("Are you sure delete it.");
        });

        $("#commonBtn").click(function() {
         location.href = "/deleteLead/" + deleteId;
        });
		
		
    });

    </script>
    
@endsection