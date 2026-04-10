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
                        <h2><i class="bi bi-ticket iconsbg2"></i>Social Ticket <span class="spantext">{{$getsocial->source}}  @  {{$getsocial->socialUser_userName}}
                                </span></h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="editbtns" style="float:right;">
                        <div class="iconsmenu2" style="float:none;">
                            <ul class="ms-auto">
                                <li>
                                  <form method="POST" action="/addFavourite?type=tb_socialticket&type_id={{$getsocial->id}}">   
                                  @csrf    
                                 <div class="icon-container secondstar" style="{{ $getFavourite && $getFavourite->status=='1' ? 'background: #ffd525;' : '' }}"> 
                                 <button class="btn  {{ addUIComponent('SOCIALTICKET_FAVOURITE') }}" type="submit"><i class="bi bi-star"></i></button>
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
                                            <li class="{{ addUIComponent('SOCIALTICKET_DUPLICATE') }}"><a class="dropdown-item" href="/markDuplicate/{{$getsocial->id}}?status=Duplicate"><i class="bi bi-layers"></i>
                                                    Duplicate</a></li>
                                            <li class="{{ addUIComponent('SOCIALTICKET_DELETE') }}"><a class="dropdown-item deleteLink" id="{{$getsocial->id}}" href="#" ><i class="bi bi-trash3"></i>
                                                    Delete</a></li>
													  <li class="{{ addUIComponent('SOCIALTICKET_EDIT_SOCIAL_TICKET') }}"><a class="dropdown-item"  href="/editTicket/{{$getsocial->id}}"><i class="bi bi-pencil"></i> Edit</a></li>
                                            <li class="{{ addUIComponent('SOCIALTICKET_FIND_DUPLICATE') }}"><a class="dropdown-item" href="/getSocialTicket?subject={{$getsocial->postMessage}}"><i class="bi bi-search"></i> Find
                                                    Duplicate</a></li>
                                            
                                            <li class="{{ addUIComponent('SOCIALTICKET_CONVERT_LEAD') }}"><a class="dropdown-item"  href="/generateLead/{{$getsocial->getTweet_id}}"><i class="bi bi-view-list"></i> Convert
                                                    Lead</a></li>
                                            <li class="{{ addUIComponent('SOCIALTICKET_VIEW_CHANGE_LOG') }}"><a class="dropdown-item" href="/tweetLogList/{{$getsocial->id}}/ticket"><i
                                                        class="bi bi-view-stacked"></i>
                                                    View Change Log</a></li>
                                            <li class="{{ addUIComponent('SOCIALTICKET_REPLY') }}"><a class="dropdown-item" href="/ticketReply/{{$getsocial->id}}"><i
                                                        class="bi bi-view-stacked"></i>
                                                    Reply</a></li>       
                                            @if($getsocial->assigned_to!=null)        
                                            <li class="{{ addUIComponent('SOCIALTICKET_SAP_TICKET_CREATE') }}">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ticketModal">
                                                    <i class="bi bi-view-stacked"></i> Sap Ticket Create
                                                </a>
                                            </li>
                                            @endif        
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
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link  {{ addUIComponent('SOCIALTICKET_TICKET_UPDATES') }}" id="posttwotab" data-bs-toggle="pill"
                                                    data-bs-target="#posttwo" type="button" role="tab"
                                                    aria-controls="posttwo" aria-selected="false">Ticket
                                                    Updates</button>
                                            </li>
                                            
                                        </ul>
                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="postone" role="tabpanel"
                                                aria-labelledby="postonetab">
                                                <div class="maincontent">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Case Number</label>
                                                                <p class="peragraph_content">{{$getsocial->ticket_id}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Priority</label>
                                                                <p class="peragraph_content">{{$getsocial->priority}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Final State</label>
                                                                <p class="peragraph_content">{{$getsocial->final_state}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Status</label>
                                                                <p class="peragraph_content">{{$getsocial->status}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Type</label>
                                                                <p class="peragraph_content">{{$getsocial->type}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Customer Name</label>
                                                                <p class="peragraph_content">{{$getsocial->socialUser}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Subject</label>
                                                                <p class="peragraph_content">{{$getsocial->postMessage}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Suggestions</label>
                                                                <p class="peragraph_content">{{$getsocial->suggestion}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 {{ addUIComponent('SOCIALTICKET_SAP_TICKET_STATUS') }}">
                                                            <div class="row">
                                                                <label class="form-label">CRM Status</label>
                                                                <div class="d-flex">
                                                                    @if(count($saptickets) > 0)
                                                                        @foreach($saptickets as $sapticket)
                                                                            <a href="#" 
                                                                            class="me-2"
                                                                            data-bs-toggle="modal" 
                                                                            data-bs-target="#ticketstatusModal"
                                                                            data-sap-object-id="{{ $sapticket->id }}">
                                                                                {{ $sapticket->sap_object_id }}
                                                                            </a>
                                                                        @endforeach
                                                                    @else
                                                                        <span>NA</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12  mb-3">
                                                <div class="documentType coulams_form">
                                                <label class="form-label">Documents</label>

                                                @if(count($attacheddata)>0)
                                                <ul>
                                                   @foreach($attacheddata as $attacheddatas)
                                                     <?php $filename = pathinfo($attacheddatas->fileName)['basename']; ?>
                                                     <a target="blank" href="{{$attacheddatas->fileUrl}}"><li>{{$filename}}</li></a>
                                                   @endforeach    
                                                </ul>
                                                @endif
                                                </div>
                                            </div>
                                                        <div class="col-md-12">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Description</label>
                                                                <p class="peragraph_content">{{$getsocial->description}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="coulams_form">
                                                                <label class="form-label">Post Url</label>
                                                                <p class="peragraph_content"><a target="blank" href="{{$getsocial->postUrl}}">{{$getsocial->postUrl}}</a>
                                                                </p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                       
                                        <div class="tab-pane fade" id="posttwo" role="tabpanel"
                                            aria-labelledby="posttwotab">
                                            <div class="tabs2main">
                                                <div class="headingmain2">
                                                    <h6>Case Update Threaded</h6>
                                                </div>
                                                <form method="POST" action="/updateTicketBtText/{{$getsocial->id}}">
                                                    @csrf
                                                    <div class="textareamain">
                                                        <div class="mb-3">
                                                            <label for="additional_Text"
                                                                class="form-label">Additional Text <div class="templateicons">
						<a href="#" class="btn btn-link" onClick="openTemplate('#additional_Text')"><i class="bi bi-envelope"></i></a>
						<div style="clear:both;"></div>
						</div></label>
                                                            <textarea class="form-control" name="additional_Text"
                                                                id="additional_Text" rows="3"
                                                                placeholder="Additional Text">{{$getsocial->additional_Text}}</textarea>
                                                            <div class="form-check mt-3">
                                                                <input class="form-check-input" type="checkbox" value="1"  {{$getsocial->internalUpdate?'checked':''}}  id="internalUpdate" name="internalUpdate">
                                                                <label class="form-check-label" for="flexCheckDefault">
                                                                    Internal Update
                                                                </label>
															
                                                            </div>
                                                            <div class="button-additional mt-3">
                                                                <button type="submit"
                                                                    class="btn btn-danger">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="postthree" role="tabpanel"
                                            aria-labelledby="postthreetab">3</div>
                                    </div>
                                    </div>
                                </div>
                                
                           
                        </div>
                        <div class="col-md-5  {{ addUIComponent('SOCIALTICKET_ACTIVITIES') }}">
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
											<input type="hidden" name="type" value="TICKET"/> 
											<input type="hidden" name="post_id" value="{{$getsocial->id}}"/> 
											<button type="submit" class="btn btn-danger  {{ addUIComponent('SOCIALTICKET_ACTIVITIES') }}">Save</button>
											</div>
											</div>
										</form>
										@if(!empty($getActivity))
										
										
											@foreach($getActivity as $activity)
											<div class="mb-2 row">
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
		<table class="table  {{ addUIComponent('SOCIALTICKET_REPLY_TABLE') }}">
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
      <td>{!! getUrlinString($replys->tweeter_text) !!}</td>
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
    <td scope="row">{{$getsocial?$getsocial->getTweet_id:''}}</td>
    <td>{!! getUrlinString($getsocial?$getsocial->postMessage:'')!!}</td>
      <td>Text</td>
      <td></td>
      <td>{{$getsocial?$getsocial->created_at:''}}</td>
    </tr>
  </tbody>
</table>
		@endif
		
		
    </div>
                        </div>
                    </div>
                </div>
            </div>
<!-- Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="ticketForm" action="/createSapTicket/{{$getsocial->id}}" method="POST">
        @csrf    
            <div class="modal-content">
                <div class="modal-header" style="background-color: #ffd525;">
                    <h5 class="modal-title" id="ticketModalLabel">Create SAP Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="optionDropdown" class="form-label">Catalog Type</label>
                        <select class="form-select" id="optionDropdown" name="ct" required>
                            <option value="">Select Catalog Type</option>
                            @foreach(getCodeOptions() as $option)
                            <option value="{{ $option->catalog_type }}">
                                {{ 
                                    $option->catalog_type." ($option->catlog_type_desc)"
                                }}
                            </option>

                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="suboptionContainer" style="display:none;">
                        <label for="suboptionDropdown" class="form-label">Code Group</label>
                        <select class="form-select" id="suboptionDropdown" name="cg" required></select>
                    </div>
                    <div class="mb-3" id="textboxContainer" style="display:none;">
                        <label for="textbox" class="form-label">BP No.</label>
                        <input type="text" class="form-control" id="textbox" name="bpno" value="{{$getsocial->bipNumber}}" required>
                    </div>
                    <div class="mb-3" id="descriptionbox" style="display:none;">
                        <label for="textbox" class="form-label">Description.</label>
                        <input type="text" class="form-control" id="descriptiontextbox" name="description" value="SOCIAL CRM-Social media" required disabled>
                        <input type="hidden" class="form-control" id="descriptiontextbox" name="description" value="SOCIAL CRM-Social media" required>
                    </div>
                    <div class="mb-3" id="notesbox">
                        <label for="textbox" class="form-label">Notes.</label>
                        <input type="text" class="form-control" id="notesboxtextbox" name="notes" value="" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submitButton" class="btn btn-primary" style="display:none;">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="ticketstatusModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #ffd525;">
                    <h5 class="modal-title" id="ticketModalLabel">SAP Ticket Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="ticketStatusContent">Loading...</div>
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

            function openDelMod(id) {
                
            }
            </script>
<script>
    var deleteId ="";
    $(document).ready(function() {
		
		$(".deleteLink").click(function() {
			deleteId = $(this).attr('id');
			$("#commonModal").modal("show");
			$("#msg").text("Are you sure delete it.");
        });
		
		$("#commonBtn").click(function() {
			location.href= "/deleteTicket/"+deleteId;
        });
		
		
    });

    </script>
        <script>
    $(document).ready(function () {
        $('#optionDropdown').change(function () {
            var selectedOption = $(this).val();
            if (selectedOption) {
                $('#suboptionContainer').show();
                $.ajax({
                    url: '/get-suboptions/' + selectedOption,
                    type: 'GET',
                    success: function (data) {
                        var suboptions = data.suboptions;
                        $('#suboptionDropdown').empty().append('<option value="">Select Code Group</option>');
                        $.each(suboptions, function (key, value) {
                            $('#suboptionDropdown').append('<option value="' + value.code_group + '">' + value.group_text + '('+value.code+')</option>');
                        });
                    }
                });
            } else {
                $('#notesbox').hide();
                $('#descriptionbox').hide();
                $('#suboptionContainer').hide();
                $('#textboxContainer').hide();
                $('#submitButton').hide();
            }
        });

        $('#suboptionDropdown').change(function () {
            var selectedSubOption = $(this).val();
            if (selectedSubOption) {
                $('#textboxContainer').show();
                @if($getsocial->bipNumber != null)
                $('#descriptionbox').show();
                @endif
            } 
            // else {
            //     $('#notesbox').hide();
            //     $('#descriptionbox').hide();
            //     $('#textboxContainer').hide();
            //     $('#submitButton').hide();
            // }
        });

        $('#textbox').on('input', function () {
            var textValue = $(this).val();
            if (textValue.length > 9) {
                $('#descriptionbox').show();
            }
            // else {
            //     $('#notesbox').hide();
            //     $('#descriptionbox').hide();
            //     $('#submitButton').hide();
            // }
        });

        $('#descriptiontextbox').on('input', function () {
            var textValue = $(this).val();
            if (textValue) {
                $('#notesbox').show();
            }
            // else {
            //     $('#descriptionbox').hide();
            //     $('#submitButton').hide();
            // }
        });

        $('#notesboxtextbox').on('input', function () {
            var textValue = $(this).val();
            if (textValue) {
                $('#submitButton').show();
            }
            // else {
            //     $('#notesbox').hide();
            //     $('#submitButton').hide();
            // }
        });
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var ticketModal = document.getElementById('ticketstatusModal');
        ticketModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var sapObjectId = button.getAttribute('data-sap-object-id'); // Extract info from data-* attributes
            
            var contentDiv = ticketModal.querySelector('#ticketStatusContent');
            contentDiv.innerHTML = '<div id="ticketStatusContent">Loading...</div>';
            // You can use AJAX to fetch data here, for example:
            fetch(`/${sapObjectId}/getSapTicketStatus`)
                .then(response => response.text())
                .then(data => {
                    contentDiv.innerHTML = data; // Update modal content
                })
                .catch(error => {
                    contentDiv.innerHTML = 'Error loading data';
                    console.error('Error:', error);
                });
        });
    });
</script>
  @endsection