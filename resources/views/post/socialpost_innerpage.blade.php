<style>
    .texthistory{
        word-break:break-all;
    }
</style>
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
<?php
$convertedData = ($getSocial->converted || $getSocial->convertLead) ? "Yes" : "No";
$ticketIds = [];
$leadIds = [];

foreach ($socialTickets as $ticket) {
    $ticketIds[] = "<a target='_blank' href='/getSocialTicketById/{$ticket->id}'>{$ticket->ticket_id}</a>";
}

foreach ($leads as $lead) {
    $leadIds[] = "<a target='_blank' href='/getLeadById/{$lead->id}'>{$lead->leadId}</a>";
}

$ticketIdsStr = implode(', ', $ticketIds);
$leadIdsStr = implode(', ', $leadIds);
?>

<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2">
            <div class="row">
                <div class="col-md-6">
                    <div class="heading_two ">
                        <h2><i class="bi bi-ticket iconsbg2"></i>Social Post <span class="spantext">{{$getSocial->source}}  @  {{$getSocial->socialUser_userName}}</span></h2>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="editbtns" style="float:right;">
                <div class="iconsmenu2" style="float:none">
                        <ul class="ms-auto" >
                            <li>
                            <form method="POST" action="/addFavourite?type=tb_gettweet&type_id={{$getSocial->id}}">   
                             @csrf    
                            <div class="icon-container secondstar" style="{{ $getFavourite && $getFavourite->status=='1' ? 'background: #ffd525;' : '' }}"> 
                            <button class="btn  {{ addUIComponent('SOCIALPOST_FAVOURITE') }}" type="submit"><i class="bi bi-star"></i></button>
                            </div>
                            </form>
                            </li>
                            <li>
                            <div class="dropdown">
  <a class="btn btn-secondary btncustom dropdown-toggle" href="#" role="button" id="dropdownMenuLinkmain" data-bs-toggle="dropdown" aria-expanded="false">
  <i class="bi bi-pencil-square" style="background:none;padding:0;"></i> Action
  </a>

  <ul class="dropdown-menu dropdownmenu_innner" aria-labelledby="dropdownMenuLinkmain">
    <li class="{{ addUIComponent('SOCIALPOST_DUPLICATE') }}"><a class="dropdown-item" href="/manualAddSocailPost/{{$getSocial->id}}?status=Duplicate"><i class="bi bi-layers"></i> Duplicate</a></li>
    <li class="{{ addUIComponent('SOCIALPOST_DELETE') }}"><a class="dropdown-item deleteLink" id="{{$getSocial->id}}" href="#"><i class="bi bi-trash3"></i> Delete</a></li>
    <li class="{{ addUIComponent('SOCIALPOST_EDIT_POST') }}"><a class="dropdown-item"  href="/createpost/{{$getSocial->id}}"><i class="bi bi-pencil"></i> Edit</a></li>
    <li class="{{ addUIComponent('SOCIALPOST_FIND_DUPLICATE') }}"><a class="dropdown-item" href="/dashboard?message={{$getSocial->postMessage}}"><i class="bi bi-search"></i> Find Duplicate</a></li>
    <li class="{{ addUIComponent('SOCIALPOST_CONVERT_SOCIAL_MEDIA_TICKET') }}"><a class="dropdown-item" href="/generateTicket/{{$getSocial->id}}"><i class="bi bi-ticket"></i> Convert Social Media Ticket</a></li>
    <li class="{{ addUIComponent('SOCIALPOST_CONVERT_LEAD') }}"><a class="dropdown-item" href="/generateLead/{{$getSocial->getTweet_id}}"><i class="bi bi-view-list" ></i> Convert Lead</a></li>
    <li class="{{ addUIComponent('SOCIALPOST_VIEW_CHANGE_LOG') }}"><a class="dropdown-item" href="/tweetLogList/{{$getSocial->id}}/post"><i class="bi bi-view-stacked"></i> View Change Log</a></li>
    <li class="{{ addUIComponent('SOCIALPOST_REPLY') }}"><a class="dropdown-item" href="/postreply/{{$getSocial->id}}"><i class="bi bi-view-stacked"></i>Reply</a></li>  
</ul>
</div>

                            </li>

                            <div style="clear:both;"></div>
                        </ul>
                    </div>
            
                </div>
            </div>
            <div class="socialticketview">

            <div class="row">
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Post ID	</label>
                                                    <p class="peragraph_content">{{$getSocial->getTweet_id}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Post message	</label>
                                                    <p class="peragraph_content">{{$getSocial->postMessage}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Social User	</label>
                                                    <p class="peragraph_content">{{$getSocial->socialUser_name}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Source</label>
                                                    <p class="peragraph_content">{{$getSocial->source}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Post Url	</label>
                                                    <p class="peragraph_content"><a target="_blank" href="{{$getSocial->postUrl}}">{{$getSocial->postUrl}}</a>	</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Post Date</label>
                                                    <p class="peragraph_content">{{$getSocial->istPostDate}}
</p>
                                                </div>
                                            </div>
                                         
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Category</label>
                                                    <p class="peragraph_content">{{$getSocial->post_category}}</p>
                                                </div>
                                            </div>
											<div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Reply Days</label>
                                                    <p class="peragraph_content">{{getDaysByDate($getSocial->responseDate==null?$getSocial->istPostDate:$getSocial->responseDate)}}</p>
                                                </div>
                                            </div>
											<div class="col-md-6">
                                                <div class="coulams_form {{ addUIComponent('SOCIALPOST_TICKET_LEAD_REF') }}">
                                                    <label class="form-label">Converted</label>
                                                    <p class="peragraph_content"><?= $convertedData ?></p>
                                                    <p class="peragraph_content">Tickets: <?= $ticketIdsStr ?></p>
                                                    <p class="peragraph_content {{ addUIComponent('DASHBOARD_LEADS') }}">Leads: <?= $leadIdsStr ?></p>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Status</label>
                                                    <p class="peragraph_content">{{$getSocial->status}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
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
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Aging</label>
                                                    <p class="peragraph_content">{{getDaysByDate($getSocial->istPostDate)}}</p>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Reason</label>
                                                    <p class="peragraph_content">
                                                        @if(!empty($getActivity))
										
                                                        @foreach($getActivity as $activity)
                                                        <div class="mb-2 row">
                                                            <div class="col-md-11 mt-2">
                                                                <div class="content_past_activity">
                                                                    <p class="namehistory" style="width:50%">{{ $activity->name}} <span class="datehistory">{{ $activity->created_at}}</span></p>
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
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">BP Number</label>
                                                    <p class="peragraph_content">{{$getSocial->bp_number}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        
                                        </div>
                                        </div>

        <div class="bgwhite2">
		
		
@if($reply)
<table class="table  {{ addUIComponent('SOCIALPOST_REPLY_TABLE') }}">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Message</th>
            <th scope="col">Type</th>
            <th scope="col">Url</th>
            <th scope="col">Post Date</th>
            <th scope="col">Reply By</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody class="accordion" id="accordionExample">
        @foreach($reply as $replys)
        <tr>
            <td scope="row">{{$replys-> tweeter_id}}</td>
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
            <td scope="row">{{$getSocial->getTweet_id}}</td>
            <td>{!! getUrlinString($getSocial?$getSocial->postMessage:'') !!}</td>
            <td>Text</td>
            <td></td>
            <td>{{$getSocial?$getSocial->created_at:''}}</td>
        </tr>
    </tbody>
</table>
@endif
		
		
    </div>

</div>
</div>

<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
      
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
         <div class="successfull">
                <img src="/images/success.avif" class="imgsuccess">
                <h4>Lead Created Successfully!</h4>
               
            </div>
      </div>

      <!-- Modal footer -->


    </div>
  </div>
<script>
var  deleteId = 0;
$(document).ready(function()
{
	$(".deleteLink").click(function() {
		deleteId = $(this).attr('id');
		$("#commonModal").modal("show");
		$("#msg").text("Are you sure delete it.");
	});

	$("#commonBtn").click(function() {
		location.href= "/deletePost/"+deleteId;
	});
});
		
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


@endsection