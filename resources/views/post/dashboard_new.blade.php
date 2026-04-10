@extends('auth.layouts')

@section('content')




<div class="">
    <div class="div_container">

        <div class="">
            <div class="dashboard">
				<?php
				$typeCount = [addUIComponent('DASHBOARD_RECENT_POSTS'),addUIComponent('DASHBOARD_RECENT_TICKETS'),addUIComponent('DASHBOARD_RECENT_LEADS'),addUIComponent('DASHBOARD_RESOLVED_TICKETS')];
				$counts = array_count_values($typeCount);
				$countVal = 0;
				if(isset($counts['READ_WRITE']))
				{
					$countVal = $countVal+$counts['READ_WRITE'];
				}
				if(isset($counts['READ_ONLY']))
				{
					$countVal = $countVal+$counts['READ_ONLY'];
				}
				?>
                <div class="row">
                    <div class="col-md-{{addUIComponent('DASHBOARD_STATICS') == 'HIDDEN'?12:8}}">
                        <div class="states mt-3">
							@if($countVal > 0)
                            <div class="row">
                                <div class="col-md-{{12/$countVal}} col-{{12/($countVal*2)}} {{ addUIComponent('DASHBOARD_RECENT_POSTS') }}">
                                    <a herf="" onclick="location.href='/recentdashboard';">
                                        <div class="states_inner bgone">
                                            <i class="bi bi-mailbox icon1"></i>
                                            <p>Recent Posts</p>
                                            <h6 class="counter" data-target="{{$getSocailRecentData->count()}}">
                                            {{$getSocailRecentData->count()}}</h6>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-{{12/$countVal}} col-{{12/($countVal*2)}} {{ addUIComponent('DASHBOARD_RECENT_TICKETS') }}">
                                    <a herf="" onclick="location.href='/getRecentSocialTicket';">
                                        <div class="states_inner bgtwo">
                                            <i class="bi bi-ticket icon2"></i>
                                            <p>Recent Tickets</p>
                                            <h6 class="counter" data-target="{{$getTicketRecentData->count()}}">
                                                {{$getTicketRecentData->count()}}</h6>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-{{12/$countVal}} col-{{12/($countVal*2)}} {{ addUIComponent('DASHBOARD_RECENT_LEADS') }}">
                                <a herf="" onclick="location.href='/getRecentLeads';">  
                                    <div class="states_inner bgthree">
                                        <i class="bi bi-funnel icon3"></i>
                                        <p>Recent Leads</p>
                                        <h6 class="counter" data-target="{{$getLeadRecentData->count()}}">{{$getLeadRecentData->count()}}</h6>
                                    </div>
                                </a>    
                                </div>
                                <div class="col-md-{{12/$countVal}} col-{{12/($countVal*2)}} {{ addUIComponent('DASHBOARD_RESOLVED_TICKETS') }}">
                                <a herf="" onclick="location.href='/getRecentSocialTicket?status=Resolved';">    
                                    <div class="states_inner bgfour">
                                        <i class="bi bi-flag icon4"></i>
                                        <p>Resolved Tickets</p>
                                        <h6 class="counter" data-target="{{$getTicketGraphResolvedData->count()}}">{{$getTicketGraphResolvedData->count()}}</h6>
                                    </div>
                                </a>    
                                </div>
                            </div>
							@endif
                        </div>
						<?php
				$typeCount = [addUIComponent('DASHBOARD_SOCIAL_POSTS'),addUIComponent('DASHBOARD_SOCIAL_TICKETS')];
				$counts = array_count_values($typeCount);
				$countVal = 0;
				if(isset($counts['READ_WRITE']))
				{
					$countVal = $countVal+$counts['READ_WRITE'];
				}
				if(isset($counts['READ_ONLY']))
				{
					$countVal = $countVal+$counts['READ_ONLY'];
				}
				?>
                        <div class="row">
                            <div class="col-md-{{$countVal>1?6:12}}  {{ addUIComponent('DASHBOARD_SOCIAL_POSTS') }}">
                                <div class="socialticket_mini">
                                    <div class="bgwhite2 heightscroll">
                                        <div class="headingmain headingsec mb-3">
                                            <h6>Social Posts ({{$totalPostCount->count()}})<a href="/dashboard"
                                                    class="seeall">See All</a>
                                            </h6>
                                        </div>
                                        <?php $count = 0; ?>
                                        @if(!empty($getSocailData) && $getSocailData->count())
                                        @foreach($getSocailData as $getSocail)
                                        @if( $count > 4)
                                        <?php continue;?>
                                        @endif
                                        <div class="socialticketinner">

                                            <h6>
                                            @if($getSocail->source == 'Twitter')        
                                               <img src="/images/{{ getSocialIcon($getSocail->source,true) }}" class="newimg">
                                              @else        
                                               <i class="{{ getSocialIcon($getSocail->source) }}"></i>
                                            @endif 
                                             {{$getSocail->socialUser_name}}<span
                                                    class="alertnew">New Post</span></h6>
                                            <p>{!! getUrlinString($getSocail->postMessage)!!} </p>
                                            <p class="dates"><i
                                                    class="bi bi-person-fill"></i>{{$getSocail->getTweet_id}}</p>
                                        </div>
                                        <?php $count++;?>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-{{$countVal>1?6:12}} {{ addUIComponent('DASHBOARD_SOCIAL_TICKETS') }}">
                                <div class="socialticket_mini">
                                    <div class="bgwhite2 heightscroll">
                                        <div class="headingmain headingsec mb-3">
                                            <h6>Social Tickets ({{$totalTicketCount->count()}})
                                                <a href="/getSocialTicket" class="seeall">See All</a>
                                            </h6>
                                        </div>
                                        <?php $count = 0;?>
                                        @if(!empty($getTicketData) && $getTicketData->count())

                                        @foreach($getTicketData as $getSocail)
                                        @if( $count > 4)
                                        <?php continue;?>
                                        @endif
                                        <div class="socialticketinner">
                                            <h6>
                                            @if($getSocail->source == 'Twitter')        
                                               <img src="/images/{{ getSocialIcon($getSocail->source,true) }}" class="newimg">
                                              @else        
                                              <i class="{{ getSocialIcon($getSocail->source) }}"></i>
                                            @endif        
                                                
                                                {{$getSocail->socialUser}}<span
                                                    class="alertnew">New Ticket</span></h6>
                                            <p>{!! getUrlinString($getSocail->postMessage)!!} </p>
                                            <p class="dates"><i class="bi bi-calendar"></i>{{$getSocail->date_Created}}</p>
                                        </div>
                                        <?php $count++;?>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-{{$countVal > 0?4:12}}  {{ addUIComponent('DASHBOARD_STATICS') }}">
                        <div class="bgwhite2 mt-3">
                            <div class="headingmain headingsec mb-3">
                                <div class="row">
                                    <div class="col-md-2">
                                        <h6 class="formh6">Statistics</h6>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="tabscircle2">
                                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{$ticketActive}} {{ addUIComponent('DASHBOARD_TICKETS') }}" id="pills-home-tab"
                                                        data-bs-toggle="pill" data-bs-target="#pills-home" type="button"
                                                        role="tab" aria-controls="pills-home"
                                                        aria-selected="true">Tickets</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{$postActive}} {{ addUIComponent('DASHBOARD_POSTS') }}" id="pills-profile-tab"
                                                        data-bs-toggle="pill" data-bs-target="#pills-profile"
                                                        type="button" role="tab" aria-controls="pills-profile"
                                                        aria-selected="false">Posts</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{$leadActive}} {{ addUIComponent('DASHBOARD_LEADS') }}" id="pills-contact-tab"
                                                        data-bs-toggle="pill" data-bs-target="#pills-contact"
                                                        type="button" role="tab" aria-controls="pills-contact"
                                                        aria-selected="false">Leads</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{$sentimateActive}} {{ addUIComponent('DASHBOARD_SENTIMENTS') }}" id="tab_sentimants" data-bs-toggle="pill" data-bs-target="#sentimants" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Sentiments</button>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>
                                </div>




                            </div>

                            <div class="tab-content" id="pills-tabContent">

                                <div class="tab-pane fade {{$ticketshowActive}}" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                                    <div class="dastpicker">
                                        <form method="GET" action="/countDashboard">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-5 col-5">
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">From</label>
                                                        <input type="date" class="form-control" id="exampleInputEmail1"
                                                            aria-describedby="emailHelp" name="startGraphDate"
                                                            placeholder="From date" value="{{$startGraphDate}}">
                                                            <input type="hidden" class="form-control" id="ticket"
                                                            value="ticket" name="tab_type">    

                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-5">
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">To</label>
                                                        <input type="date" class="form-control" id="exampleInputEmail1"
                                                            aria-describedby="emailHelp" name="endGraphDate"
                                                            placeholder="To date" value="{{$endGraphDate}}"
                                                            max="{{ date('Y-m-d') }}">

                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-2 ps-0">
                                                    <button type="submit" class="btn btn-danger marginmain"><i
                                                            class="bi bi-funnel"></i></button>
                                                </div>
                                            </div>
 
                                        </form>
                                        @if($getTicketGraphNewData->count()>0 || $getTicketGraphPendingData->count()>0 || $getTicketGraphMoveData->count()>0 || $getTicketGraphResolvedData->count()>0 || $getTicketGraphRejectedData->count()>0 || $getTicketGraphDuplicateData->count()>0 || $getTicketGraphAssignedData->count()>0)
                                        <div id="chartContainer" style="height: 250px; width: 90%;"></div>
                                        <div class="canvatextremove"></div>
                                        @else
                                        <h5>No Record Found</h5>
                                        @endif
                                        <div class="bgblack2">
                                            <div class="row">
                                            <div class="col-md-4 col-4 {{ addUIComponent('DASHBOARD_NEW_POST_COUNT') }}">
                                                    <div class="values">
                                                        <h1>{{$getPostNewBoxData1->count()}}</h1>
                                                        <p> Posts</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-4 {{ addUIComponent('DASHBOARD_NEW_TICKET_COUNT') }}">
                                                    <div class="values">
                                                        <h1>{{$getTicketNewBoxData1->count()}}</h1>
                                                        <p> Tickets</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-4 {{ addUIComponent('DASHBOARD_NEW_LEAD_COUNT') }}">
                                                    <div class="values">
                                                        <h1>{{$getLeadNewBoxData1->count()}}</h1>
                                                        <p>Leads</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="tab-pane fade {{$postshowActive}}" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="dastpicker">
                                        <form method="GET" action="/countDashboard">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-5 col-5">
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">From</label>
                                                        <input type="date" class="form-control" id="exampleInputEmail1"
                                                            aria-describedby="emailHelp" name="startPostGraphDate"
                                                            placeholder="From date" value="{{$startPostGraphDate}}"
                                                            max="{{ date('Y-m-d') }}">
                                                            <input type="hidden" class="form-control" id="post"
                                                            value="post" name="tab_type">

                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-5">
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">To</label>
                                                        <input type="date" class="form-control" id="exampleInputEmail1"
                                                            aria-describedby="emailHelp" name="endPostGraphDate"
                                                            placeholder="To date" value="{{$endPostGraphDate}}"
                                                            max="{{ date('Y-m-d') }}">

                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-2 ps-0">
                                                    <button type="submit" class="btn btn-danger marginmain"><i
                                                            class="bi bi-funnel"></i></button>
                                                </div>
                                            </div>

                                        </form>
                                        @if($getPostGraphDuplicateData->count()>0 || $getPostGraphConvertedData->count()>0 || $getPostGraphNewData->count()>0 || $getPostGraphconvertLeadData->count()>0)
                                        <div id="chartContainer1" style="height: 250px; width: 90%;"></div>
                                        <div class="canvatextremove"></div>
                                        @else
                                        <h5>No Record Found</h5>
                                        @endif
                                        <div class="bgblack2">
                                            <div class="row">
                                            <div class="col-md-4 col-4 {{ addUIComponent('DASHBOARD_NEW_POST_COUNT') }}">
                                                    <div class="values">
                                                        <h1>{{$getPostNewBoxData2->count()}}</h1>
                                                        <p> Posts</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-4 {{ addUIComponent('DASHBOARD_NEW_TICKET_COUNT') }}">
                                                    <div class="values">
                                                        <h1>{{$getTicketNewBoxData2->count()}}</h1>
                                                        <p> Tickets</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-4 {{ addUIComponent('DASHBOARD_NEW_LEAD_COUNT') }}">
                                                    <div class="values">
                                                        <h1>{{$getLeadNewBoxData2->count()}}</h1>
                                                        <p>Leads</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                         <div class="tab-pane fade {{$leadshowActive}}" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">


                                      
                                    <div class="dastpicker">
                                        <form method="GET" action="/countDashboard">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-5 col-5">
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">From</label>
                                                        <input type="date" class="form-control" id="exampleInputEmail1"
                                                            aria-describedby="emailHelp" name="startGraphLeadDate"
                                                            placeholder="From date" value="{{$startGraphLeadDate}}"
                                                            max="{{ date('Y-m-d') }}">
                                                            <input type="hidden" class="form-control" id="lead"
                                                            value="lead" name="tab_type">

                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-5">
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">To</label>
                                                        <input type="date" class="form-control" id="exampleInputEmail1"
                                                            aria-describedby="emailHelp" name="endGraphLeadDate"
                                                            placeholder="To date" value="{{$endGraphLeadDate}}"
                                                            max="{{ date('Y-m-d') }}">

                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-2 ps-0">
                                                    <button type="submit" class="btn btn-danger marginmain"><i
                                                            class="bi bi-funnel"></i></button>
                                                </div>
                                            </div>

                                        </form>
                                        @if($getLeadGraphNewData->count()>0 || $getLeadGraphAssignedData->count()>0 || $getLeadGraphInProcessData->count()>0 || $getLeadGraphCovertedData->count()>0 || $getLeadGraphRecycledData->count()>0 || $getLeadGraphDuplicateData->count()>0 || $getLeadGraphDeadData->count()>0)
                                        <div id="chartContainer2" style="height: 250px; width: 90%;"></div>
                                        <div class="canvatextremove"></div>
                                        @else
                                        <h5>No Record Found</h5>
                                        @endif
                                        <div class="bgblack2">
                                            <div class="row">
                                            <div class="col-md-4 col-4 {{ addUIComponent('DASHBOARD_NEW_POST_COUNT') }}">
                                                    <div class="values">
                                                        <h1>{{$getPostNewBoxData3->count()}}</h1>
                                                        <p> Posts</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-4 {{ addUIComponent('DASHBOARD_NEW_TICKET_COUNT') }}">
                                                    <div class="values">
                                                        <h1>{{$getTicketNewBoxData3->count()}}</h1>
                                                        <p> Tickets</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-4 {{ addUIComponent('DASHBOARD_NEW_LEAD_COUNT') }}">
                                                    <div class="values">
                                                        <h1>{{$getLeadNewBoxData3->count()}}</h1>
                                                        <p>Leads</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   </div>

                                  <div class="tab-pane fade {{$sentimateShowActive}}" id="sentimants" role="tabpanel" aria-labelledby="tab_sentimants">

                                       <form method="GET" action="/countDashboard">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-5 col-5">
                                                    <div class="mb-3">
                                                        <label for="startGraphSentimateDate" class="form-label">From</label>
                                                        <input type="date" class="form-control" id="startGraphSentimateDate"
                                                            aria-describedby="emailHelp" name="startGraphSentimateDate"
                                                            placeholder="From date" value="{{$startGraphSentimateDate}}">
                                                            <input type="hidden" class="form-control" id="sentimate"
                                                            value="sentimate" name="tab_type">    

                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-5">
                                                    <div class="mb-3">
                                                        <label for="endGraphSentimateDate" class="form-label">To</label>
                                                        <input type="date" class="form-control" id="endGraphSentimateDate"
                                                            aria-describedby="emailHelp" name="endGraphSentimateDate"
                                                            placeholder="To date" value="{{$endGraphSentimateDate}}"
                                                            max="{{ date('Y-m-d') }}">

                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-2 ps-0">
                                                    <button type="submit" class="btn btn-danger marginmain"><i
                                                            class="bi bi-funnel"></i></button>
                                                </div>
                                            </div>

                                        </form>
                                        @if($getSentimateGraphNagativeData->count()>0 || $getSentimateGraphPositiveData->count()>0 || $getSentimateGraphComplaintData->count()>0 || $getSentimateGraphQueryData->count()>0 || $getSentimateGraphInformationData->count()>0 || $getSentimateGraphSpamData->count()>0)
                                        <div id="chartContainer3" style="height: 250px; width: 90%;"></div>
                                        <div class="canvatextremove"></div>
                                        @else
                                        <h5>No Record Found</h5>
                                        @endif
                                        <div class="bgblack2">
                                            <div class="row">
                                            <div class="col-md-4 col-4 {{ addUIComponent('DASHBOARD_NEW_POST_COUNT') }}">
                                                    <div class="values">
                                                        <h1>{{$getPostNewBoxData4->count()}}</h1>
                                                        <p> Posts</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-4 {{ addUIComponent('DASHBOARD_NEW_TICKET_COUNT') }}">
                                                    <div class="values">
                                                        <h1>{{$getTicketNewBoxData4->count()}}</h1>
                                                        <p> Tickets</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-4 {{ addUIComponent('DASHBOARD_NEW_LEAD_COUNT') }}">
                                                    <div class="values">
                                                        <h1>{{$getLeadNewBoxData4->count()}}</h1>
                                                        <p>Leads</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
  </div>

                            </div>
                            <!-- <div class="bgblack2">
                    <div class="assignedbyticketpost">
                        <div class="headingin">
                            <h4>Assined to Me</h4>
                        </div>
                        <div class="tabsassigned tabscircle">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Social Ticket</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Social Post</button>
                            </li>
                           
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                        <div class="tabsmaininner">
                                        <div class="socialticketinner">

                                <h6><i class="bi bi-twitter"></i> deepesh jangid<span class="alertnew">New Post</span></h6>
                                <p>@bel_technology hello2 </p>
                                <p class="dates"><i class="bi bi-person-fill"></i>1677906711011622912</p>
                                </div>
                                <div class="socialticketinner">

                                <h6><i class="bi bi-twitter"></i> deepesh jangid<span class="alertnew">New Post</span></h6>
                                <p>@bel_technology hello2 </p>
                                <p class="dates"><i class="bi bi-person-fill"></i>1677906711011622912</p>
                                </div>
                                <div class="socialticketinner">

                                <h6><i class="bi bi-twitter"></i> deepesh jangid<span class="alertnew">New Post</span></h6>
                                <p>@bel_technology hello2 </p>
                                <p class="dates"><i class="bi bi-person-fill"></i>1677906711011622912</p>
                                </div>
                                        </div>

                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="tabsmaininner">
                                <div class="socialticketinner">
                                            <h6><i class="bi bi-twitter"></i> deepesh jangid<span class="alertnew">New Ticket</span></h6>
                                            <p>@bel_technology good </p>
                                            <p class="dates"><i class="bi bi-calendar"></i>2023-07-08 08:59:27 AM</p>
                                        </div>
                                        <div class="socialticketinner">
                                            <h6><i class="bi bi-twitter"></i> deepesh jangid<span class="alertnew">New Ticket</span></h6>
                                            <p>@bel_technology good </p>
                                            <p class="dates"><i class="bi bi-calendar"></i>2023-07-08 08:59:27 AM</p>
                                        </div>
                                        <div class="socialticketinner">
                                            <h6><i class="bi bi-twitter"></i> deepesh jangid<span class="alertnew">New Ticket</span></h6>
                                            <p>@bel_technology good </p>
                                            <p class="dates"><i class="bi bi-calendar"></i>2023-07-08 08:59:27 AM</p>
                                        </div>
                                </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div> -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $("#dashboard").addClass("active");

    
    </script>

<script>

function chart3()
{
    
    var chart33 = new CanvasJS.Chart("chartContainer3", {
        animationEnabled: true,

        data: [{
            type: "pie",
            startAngle: 240,
            width:200,
            height:200,
            yValueFormatString: "##0",
            indexLabel: "{label} {y}",
            dataPoints: [
                {
                    y: {{$getSentimateGraphNagativeData->count()}},
                    label: "Feedback Nagative"
                },
                {
                    y: {{$getSentimateGraphPositiveData->count()}},
                    label: "Feedback Positive"
                },
                {
                    y: {{$getSentimateGraphComplaintData->count()}},
                    label: "Complaint"
                },
                {
                    y: {{$getSentimateGraphQueryData->count()}},
                    label: "Query"
                },
                {
                    y: {{$getSentimateGraphInformationData->count()}},
                    label: "Information"
                },
                {
                    y: {{$getSentimateGraphSpamData->count()}},
                    label: "Spam"
                },
            ]
        }]
    });
    
    chart33.render();
}

function chart2()
{
    
    var chart22 = new CanvasJS.Chart("chartContainer1", {
        animationEnabled: true,

        data: [{
            type: "pie",
            startAngle: 240,
            width:200,
            height:200,
            yValueFormatString: "##0",
            indexLabel: "{label} {y}",
            dataPoints: [
                {
                    y: {{$getPostGraphDuplicateData->count()}},
                    label: "Duplicate"
                },
                {
                    y: {{$getPostGraphNewData->count()}},
                    label: "New"
                },
                {
                    y: {{$getPostGraphconvertLeadData->count()}},
                    label: "Converted To Lead"
                },
                {
                    y: {{$getPostGraphConvertedData->count()}},
                    label: "Converted To Ticket"
                },
            ]
        }]
    });
    
    chart22.render();
}

function chart1()
{
    var chart = new CanvasJS.Chart("chartContainer2", {
        animationEnabled: true,

        data: [{
            type: "pie",
            width:200,
            height:200,
            startAngle: 240,
            yValueFormatString: "##0",
            indexLabel: "{label} {y}",
            dataPoints: [{
                    y: {{$getLeadGraphNewData->count()}},
                    label: "New"
                },
                {
                    y: {{$getLeadGraphAssignedData->count()}},
                    label: "Assigned"
                },
                {
                    y: {{$getLeadGraphInProcessData->count()}},
                    label: "In Process"
                },
                {
                    y: {{$getLeadGraphCovertedData->count()}},
                    label: "Converted"
                },
                {
                    y: {{$getLeadGraphRecycledData->count()}},
                    label: "Recycled"
                },
                {
                    y: {{$getLeadGraphDuplicateData->count()}},
                    label: "Duplicate"
                },
                {
                    y: {{$getLeadGraphDeadData->count()}},
                    label: "Dead"
                },

            ]
        }]
    });
    chart.render();
}
function chart ()
{
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,

        data: [{
            type: "pie",
            startAngle: 240,
            width:200,
            height:200,
            yValueFormatString: "##0",
            indexLabel: "{label} {y}",
            dataPoints: [{
                    y: {{$getTicketGraphNewData->count()}},
                    label: "New"
                },
                {
                    y: {{$getTicketGraphPendingData->count()}},
                    label: "Pending With Team"
                },
                {
                    y: {{$getTicketGraphMoveData->count()}},
                    label: "Move To Internal Team"
                },
                {
                    y: {{$getTicketGraphResolvedData->count()}},
                    label: "Resolved"
                },
                {
                    y: {{$getTicketGraphRejectedData->count()}},
                    label: "Rejected"
                },
                {
                    y: {{$getTicketGraphDuplicateData->count()}},
                    label: "Duplicate"
                },
                {
                    y: {{$getTicketGraphAssignedData->count()}},
                    label: "Assigned"
                },
            ]
        }]
    });
    chart.render();
}
window.onload = function() { 
   
    const counters = document.querySelectorAll(".counter");

    counters.forEach((counter) => {
    counter.innerText = "0";
    const updateCounter = () => {
        const target = +counter.getAttribute("data-target");
        const count = +counter.innerText;
        const increment = target / 200;
        if (count < target) {
        counter.innerText = `${Math.ceil(count + increment)}`;
        setTimeout(updateCounter, 1);
        } else counter.innerText = target;
    };
    updateCounter();
    });

}

function initializeCharts(tabId) {
    if (tabId == 'pills-profile') {
        setTimeout(() => { chart2() }, 200);
    } else if (tabId == 'pills-contact') {
        setTimeout(() => { chart1() }, 200);
    } else if (tabId == 'sentimants') {
        setTimeout(() => { chart3() }, 200);
    } else {
        setTimeout(() => { chart() }, 200);
    }
}

$(document).ready(function(){
  $("#pills-home-tab, #pills-profile-tab, #pills-contact-tab,#tab_sentimants").click(function(){
        var id = $(this).attr('id');
        if(id == 'pills-profile-tab')
        {
            setTimeout(() => {  chart2()  }, 200);
        }
        else if(id == 'pills-contact-tab')
        {
            setTimeout(() => {  chart1()  }, 200);
        }
        else if(id == 'tab_sentimants')
        {
            setTimeout(() => {  chart3()  }, 200);
        }
        else{
            setTimeout(() => {  chart()  }, 200);
        }
        
  });

  var classShowElement = $('.show.active');
    if (classShowElement.length > 0) {
        var id = classShowElement.attr('id');
        initializeCharts(id);
    }
//   chart();
//    chart2();
//    chart1();
//    chart3();
});

</script>
    @endsection