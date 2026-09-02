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
                                            
                                            @if($getsocial->assigned_to != null && count($salesforceCases) == 0)
                                            <li>
                                                <a class="dropdown-item"
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#salesforceTicketModal">
                                                    <i class="bi bi-cloud"></i> Salesforce Case Create
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
                                                                <div class="d-flex" style="display: none !important;">
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

                                                               <div class="d-flex">
                                                                    @if(count($salesforceCases) > 0)

                                                                        @foreach($salesforceCases as $salesforceCase)

                                                                            <a href="#"
                                                                            class="me-2"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#salesforceCaseStatusModal"
                                                                            data-salesforce-case-id="{{ $salesforceCase->salesforce_case_id }}">

                                                                                {{ $salesforceCase->case_number ?? $salesforceCase->salesforce_case_id }}

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
		<div class="table-responsive">
		<table class="table ig-table {{ addUIComponent('SOCIALTICKET_REPLY_TABLE') }}">
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
                        <div class="table-responsive">
                        <table class="table ig-table accordion-body">
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
                        </div>
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
</div>
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

    
<!-- Salesforce Create Case Modal -->
<div class="modal fade" id="salesforceTicketModal" tabindex="-1"
     aria-labelledby="salesforceTicketModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <form id="salesforceTicketForm"
              action="/createSalesforceCase/{{$getsocial->id}}"
              method="POST">

            @csrf

            <div class="modal-content">

                <div class="modal-header" style="background-color: #ffd525;">
                    <h5 class="modal-title" id="salesforceTicketModalLabel">
                        Create Salesforce Case
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <!-- Case Type -->
                    <div class="mb-3">
                        <label class="form-label">Case Type</label>

                        <select class="form-select"
                                name="case_type"
                                id="salesforceCaseType"
                                required>

                            <option value="">Select Case Type</option>

                            <option value="domestic">
                                Domestic Ticket
                            </option>

                            <option value="ic">
                                I&C Ticket
                            </option>

                        </select>
                    </div>

                    <!-- Ticket Group -->
                    <div class="mb-3">
                        <label class="form-label">Ticket Group</label>

                        <select class="form-select" name="ticket_group" id="ticket_group" required>
                            <option value="">Select Ticket Group</option>

                            <option value="Enquiry Query">Enquiry Query</option>
                            <option value="Enquiry Non-Query">Enquiry Service Ticket</option>

                            <option value="Customer Query">Customer Query</option>
                            <option value="Customer Non-Query">Customer Service Ticket</option>

                            <option value="Non-Registered Customer Query">
                                Non-Registered Customer Query
                            </option>

                            <option value="Non-Registered Customer Non-Query">
                                Non-Registered Customer Service Ticket
                            </option>
                        </select>
                    </div>


                    <!-- Ticket Type -->
                    <div class="mb-3">
                        <label class="form-label">Ticket Type</label>

                        <select class="form-select" name="ticket_type" id="ticket_type" required disabled>
                            <option value="">Select Ticket Type</option>
                        </select>
                    </div>


                    <!-- Ticket Category -->
                    <div class="mb-3">
                        <label class="form-label">Ticket Category</label>

                        <select class="form-select"
                                name="ticket_category"
                                id="ticket_category"
                                required
                                disabled>

                            <option value="">Select Category</option>
                        </select>
                    </div>

                    <!-- Subject -->
                    <div class="mb-3">
                        <label class="form-label">Subject</label>

                        <input type="text"
                               class="form-control"
                               name="subject"
                               value="{{ $getsocial->postMessage }}"
                               required>
                    </div>

                    <!-- Comments -->
                    <div class="mb-3">
                        <label class="form-label">Comments</label>

                        <textarea class="form-control"
                                  name="comments"
                                  rows="3">{{ $getsocial->description }}</textarea>
                    </div>


                    <hr>

                    <!-- Customer Type -->
                    <div class="mb-3">
                        <label class="form-label">Customer Type</label>

                        <select class="form-select"
                                name="customer_type"
                                id="salesforceCustomerType"
                                required>

                            <option value="">Select Customer Type</option>

                            <option value="registered">
                                Registered Customer
                            </option>

                            <option value="unregistered">
                                Unregistered Customer
                            </option>

                        </select>
                    </div>

                    <!-- Registered Customer -->
                    <div class="mb-3"
                         id="salesforceAccountContainer"
                         style="display:none;">

                        <label class="form-label">Account ID</label>

                        <input type="text"
                               class="form-control"
                               name="account_id"
                               placeholder="Salesforce Account ID">
                    </div>

                    <!-- Unregistered Customer -->
                    <div id="salesforceUnregisteredContainer"
                         style="display:none;">

                        <div class="mb-3">

                            <label class="form-label">
                                Customer Name
                            </label>

                            <input type="text"
                                   class="form-control"
                                   name="customer_name"
                                   value="{{ $getsocial->socialUser }}">
                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Mobile Number
                            </label>

                            <input type="text"
                                   class="form-control"
                                   name="mobile">
                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Address Master
                            </label>

                            <select class="form-select"
                                    name="address_master_id">

                                <option value="">
                                    Select Address
                                </option>

                                @foreach(\App\Models\SalesforceAddressMaster::orderBy('name')->get() as $address)

                                    <option value="{{ $address->salesforce_id }}">

                                        {{ $address->name }}
                                        -
                                        {{ $address->area }}
                                        -
                                        {{ $address->city }}
                                        -
                                        {{ $address->pincode }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Create Salesforce Case
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<!-- Salesforce Case Status Modal -->
<div class="modal fade" id="salesforceCaseStatusModal" tabindex="-1"
     aria-labelledby="salesforceCaseStatusModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="salesforceCaseStatusModalLabel">
                    Salesforce Case Status
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>

            </div>

            <div class="modal-body">

                <div id="salesforceCaseStatusContent">
                    Loading...
                </div>

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

<script>
document.addEventListener('DOMContentLoaded', function () {

    const ticketGroup = document.getElementById('ticket_group');
    const ticketType = document.getElementById('ticket_type');
    const ticketCategory = document.getElementById('ticket_category');


    /*
     * Salesforce dependent picklist mapping
     *
     * Group:
     * 0 = Enquiry Query
     * 1 = Enquiry Non-Query
     * 2 = Customer Query
     * 3 = Customer Non-Query
     * 4 = Non-Registered Customer Query
     * 5 = Non-Registered Customer Non-Query
     */

    const groupIndex = {
        "Enquiry Query": 0,
        "Enquiry Non-Query": 1,
        "Customer Query": 2,
        "Customer Non-Query": 3,
        "Non-Registered Customer Query": 4,
        "Non-Registered Customer Non-Query": 5
    };


    /*
     * Ticket Type -> Salesforce validFor Group indexes
     */

    const ticketTypes = [
        {
            value: "Modification/Shifting",
            validFor: [3]
        },
        {
            value: "EMI",
            validFor: [0, 2, 4]
        },
        {
            value: "General Info",
            validFor: [0, 2, 4]
        },
        {
            value: "App / Web Login Related",
            validFor: [0, 2, 4]
        },
        {
            value: "Billing & Consumption",
            validFor: [0, 2, 4]
        },
        {
            value: "E-Bill Required",
            validFor: [0, 2, 4]
        },
        {
            value: "Estimated Bills",
            validFor: [0, 2, 4]
        },
        {
            value: "Final Bill & NOC",
            validFor: [0, 2, 4]
        },
        {
            value: "KYC / Ownership / NAC",
            validFor: [0, 2, 4]
        },
        {
            value: "Meter Reading",
            validFor: [0, 2, 4]
        },
        {
            value: "Payment",
            validFor: [0, 2, 4]
        },
        {
            value: "Security Deposit",
            validFor: [0, 2, 4]
        },
        {
            value: "Service Request Procedure & Charges",
            validFor: [0, 2, 4]
        },
        {
            value: "New Connection Request Procedure",
            validFor: [0, 2, 4]
        },
        {
            value: "Leakage",
            validFor: [3, 5]
        },
        {
            value: "No Gas",
            validFor: [3]
        },
        {
            value: "Meter Not Working",
            validFor: [3]
        },
        {
            value: "Refund",
            validFor: [3, 5]
        },
        {
            value: "Arrears in Billing",
            validFor: [3]
        },
        {
            value: "Meter Not Available on Site",
            validFor: [3]
        },
        {
            value: "Temporary Disconnection",
            validFor: [3]
        },
        {
            value: "Permanent Disconnection",
            validFor: [3]
        },
        {
            value: "Low Line Pressure",
            validFor: [3]
        },
        {
            value: "BP Master Data Correction",
            validFor: [3]
        },
        {
            value: "EVC Not Working",
            validFor: [3]
        },
        {
            value: "Meter Not Found",
            validFor: [3]
        },
        {
            value: "Restoration",
            validFor: [3]
        },
        {
            value: "Defaulter Restoration",
            validFor: [1, 3, 5]
        },
        {
            value: "IT",
            validFor: [1, 3, 5]
        },
        {
            value: "Billing Errors",
            validFor: [1, 3, 5]
        },
        {
            value: "Service Quality",
            validFor: [1, 3, 5]
        }
    ];


    /*
     * Ticket Type -> Ticket Category
     */

    const categoryMap = {

        "Modification/Shifting": ["Service Request"],

        "EMI": ["Query"],
        "General Info": ["Query"],
        "App / Web Login Related": ["Query"],
        "Billing & Consumption": ["Query"],
        "E-Bill Required": ["Query"],
        "Estimated Bills": ["Query"],
        "Final Bill & NOC": ["Query"],
        "KYC / Ownership / NAC": ["Query"],
        "Meter Reading": ["Query"],
        "Payment": ["Query"],
        "Security Deposit": ["Query"],
        "Service Request Procedure & Charges": ["Query"],
        "New Connection Request Procedure": ["Query"],

        "Leakage": ["Emergency"],
        "No Gas": ["Emergency"],

        "Meter Not Working": ["Complaint"],
        "Arrears in Billing": ["Complaint"],
        "Low Line Pressure": ["Complaint"],
        "EVC Not Working": ["Complaint"],
        "Meter Not Found": ["Complaint"],

        "Refund": ["Service Request"],
        "Temporary Disconnection": ["Service Request"],
        "Permanent Disconnection": ["Service Request"],
        "BP Master Data Correction": ["Service Request"],
        "Restoration": ["Service Request"],
        "Defaulter Restoration": ["Service Request"],

        "Meter Not Available on Site": ["Internal"],
        "IT": ["Internal"],
        "Billing Errors": ["Internal"],
        "Service Quality": ["Internal"]
    };


    /*
     * When Ticket Group changes
     */

    ticketGroup.addEventListener('change', function () {

        const selectedGroup = this.value;

        ticketType.innerHTML =
            '<option value="">Select Ticket Type</option>';

        ticketCategory.innerHTML =
            '<option value="">Select Category</option>';

        ticketCategory.disabled = true;

        if (!selectedGroup) {
            ticketType.disabled = true;
            return;
        }

        const selectedIndex = groupIndex[selectedGroup];

        ticketTypes.forEach(function (type) {

            if (type.validFor.includes(selectedIndex)) {

                const option = document.createElement('option');

                option.value = type.value;
                option.textContent = type.value;

                ticketType.appendChild(option);
            }
        });

        ticketType.disabled = false;
    });


    /*
     * When Ticket Type changes
     */

    ticketType.addEventListener('change', function () {

        const selectedType = this.value;

        ticketCategory.innerHTML =
            '<option value="">Select Category</option>';

        if (!selectedType) {
            ticketCategory.disabled = true;
            return;
        }

        const categories = categoryMap[selectedType] || [];

        categories.forEach(function (category) {

            const option = document.createElement('option');

            option.value = category;
            option.textContent = category;

            ticketCategory.appendChild(option);
        });

        ticketCategory.disabled = categories.length === 0;
    });

});
</script>

<script>
$(document).ready(function () {

    $('#salesforceCustomerType').change(function () {

        var customerType = $(this).val();

        if (customerType === 'registered') {

            $('#salesforceAccountContainer').show();
            $('#salesforceUnregisteredContainer').hide();

        } else if (customerType === 'unregistered') {

            $('#salesforceAccountContainer').hide();
            $('#salesforceUnregisteredContainer').show();

        } else {

            $('#salesforceAccountContainer').hide();
            $('#salesforceUnregisteredContainer').hide();

        }

    });

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var salesforceModal =
        document.getElementById('salesforceCaseStatusModal');

    if (!salesforceModal) {
        return;
    }

    salesforceModal.addEventListener('show.bs.modal', function (event) {

        var button = event.relatedTarget;

        var salesforceCaseId =
            button.getAttribute('data-salesforce-case-id');

        var contentDiv =
            salesforceModal.querySelector('#salesforceCaseStatusContent');

        contentDiv.innerHTML = 'Loading...';

        fetch('/' + salesforceCaseId + '/getSalesforceCaseStatus')
            .then(response => {

                if (!response.ok) {
                    throw new Error('Failed to load Salesforce Case status');
                }

                return response.text();
            })
            .then(data => {

                contentDiv.innerHTML = data;

            })
            .catch(error => {

                contentDiv.innerHTML =
                    '<div class="alert alert-danger">' +
                    'Error loading Salesforce Case status' +
                    '</div>';

                console.error('Salesforce Error:', error);
            });

    });

});
</script>
  @endsection