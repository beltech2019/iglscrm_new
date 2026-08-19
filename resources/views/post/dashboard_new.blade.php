@extends('auth.layouts')

@section('content')

<style>
/* ==========================================================================
   Dashboard redesign — scoped entirely to .ig-dash2 so nothing here can
   leak into any other page. No shared stylesheet was touched for this.
   ========================================================================== */
.ig-dash2{ --d-border:#e6eaf0; --d-ink-900:#101820; --d-ink-700:#33404b; --d-ink-500:#5c6a76; --d-ink-400:#8994a1; --d-ink-100:#eef1f5; --d-surface:#ffffff; --d-green-600:#0d7a49; --d-green-100:#e3f7ec; --d-danger:#e5484d; --d-danger-100:#fdecec; --d-amber-600:#e7b400; --d-amber-100:#fff6d6; --d-info:#3b6cff; --d-info-100:#eaf0ff; --d-radius:14px; --d-fast:150ms; --d-ease:cubic-bezier(.4,0,.2,1); }

.ig-dash-header-row{ display:flex; justify-content:space-between; align-items:flex-end; gap:16px; flex-wrap:wrap; }
.ig-dash-datefilter{ display:flex; align-items:flex-end; gap:10px; flex-wrap:wrap; margin:0; }
.ig-dash-datefilter-field{ display:flex; flex-direction:column; gap:4px; }
.ig-dash-datefilter-field label{ font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--d-ink-400); margin:0; }
.ig-dash-datefilter-field input{ height:38px; padding:6px 12px; }
.ig-dash-datefilter-btn{ height:38px; padding:0 16px; display:flex; align-items:center; gap:6px; }

.ig-dash-stats-row{ display:grid; grid-template-columns:repeat(5, minmax(0,1fr)); gap:14px; margin:18px 0; }
.ig-dstat{ display:flex; align-items:flex-start; gap:12px; background:var(--d-surface); border:1px solid var(--d-border); border-radius:var(--d-radius); padding:16px; text-decoration:none; color:inherit; box-shadow:0 1px 2px rgba(16,24,32,.04); transition:transform var(--d-fast) var(--d-ease), box-shadow var(--d-fast) var(--d-ease), border-color var(--d-fast) var(--d-ease); min-height:96px; }
.ig-dstat:hover{ transform:translateY(-2px); box-shadow:0 8px 18px -8px rgba(16,24,32,.16); border-color:var(--d-ink-100); color:inherit; }
.ig-dstat-icon{ flex:none; width:34px; height:34px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:15px; }
.ig-dstat-icon-posts{ background:var(--d-info-100); color:var(--d-info); }
.ig-dstat-icon-tickets{ background:var(--d-danger-100); color:var(--d-danger); }
.ig-dstat-icon-resolved{ background:var(--d-green-100); color:var(--d-green-600); }
.ig-dstat-icon-totalposts{ background:var(--d-amber-100); color:var(--d-amber-600); }
.ig-dstat-icon-tottickets{ background:var(--d-ink-100); color:var(--d-ink-500); }
.ig-dstat-body{ min-width:0; }
.ig-dstat-body h6{ margin:0; font-size:22px; font-weight:800; letter-spacing:-.4px; color:var(--d-ink-900); line-height:1.15; }
.ig-dstat-body p{ margin:2px 0 0; font-size:12.5px; font-weight:600; color:var(--d-ink-500); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ig-dstat-sub{ display:inline-block; margin-top:4px; font-size:11px; font-weight:600; color:var(--d-ink-400); }

.ig-dash-card{ background:var(--d-surface); border:1px solid var(--d-border); border-radius:var(--d-radius); padding:20px; box-shadow:0 1px 2px rgba(16,24,32,.04); height:100%; box-sizing:border-box; }
.ig-dash-charts-row{ display:grid; grid-template-columns:1.6fr 1fr; gap:16px; margin-bottom:16px; align-items:stretch; }
.ig-dash-social-row{ display:grid; grid-template-columns:1fr 1fr; gap:16px; }

.ig-dash2 .ig-panel-toolbar{ margin-bottom:14px; }

.ig-donut-filter{ margin-bottom:10px; }
.ig-donut-wrap{ display:flex; flex-direction:column; align-items:center; gap:14px; }
.ig-donut-canvas-wrap{ position:relative; width:100%; max-width:230px; margin:0 auto; }
.ig-donut-center{ position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; pointer-events:none; }
.ig-donut-center b{ font-size:24px; font-weight:800; color:var(--d-ink-900); line-height:1.1; }
.ig-donut-center span{ font-size:11.5px; font-weight:600; color:var(--d-ink-400); }
.ig-donut-legend{ width:100%; display:flex; flex-direction:column; gap:9px; }
.ig-donut-legend-row{ display:flex; align-items:center; gap:8px; font-size:13px; color:var(--d-ink-700); }
.ig-donut-dot{ width:9px; height:9px; border-radius:50%; flex:none; }
.ig-donut-legend-row span{ flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.ig-donut-legend-row b{ font-weight:700; color:var(--d-ink-900); }

.ig-overview-chart{ width:100%; }

@media (max-width:1024px){
  .ig-dash-stats-row{ grid-template-columns:repeat(3, minmax(0,1fr)); }
  .ig-dash-charts-row{ grid-template-columns:1fr; }
}
@media (max-width:767px){
  .ig-dash-header-row{ flex-direction:column; align-items:stretch; }
  .ig-dash-datefilter{ width:100%; }
  .ig-dash-datefilter-field{ flex:1 1 40%; }
  .ig-dash-datefilter-btn{ flex:0 0 auto; }
  .ig-dash-stats-row{ grid-template-columns:repeat(2, minmax(0,1fr)); gap:10px; }
  .ig-dash-social-row{ grid-template-columns:1fr; gap:14px; }
  .ig-dash-card{ padding:16px; }
  .ig-dstat{ padding:13px; min-height:84px; }
  .ig-dstat-body h6{ font-size:19px; }
}
@media (max-width:414px){
  .ig-dash-stats-row{ grid-template-columns:1fr; }
  .ig-dash-datefilter-field{ flex:1 1 100%; }
}
@media (prefers-reduced-motion: reduce){
  .ig-dstat{ transition:none; }
}
</style>

<div class="ig-dash2">
    <div class="div_container">

        <div class="ig-page-header ig-animate-in ig-dash-header-row">
            <div>
                <span class="ig-eyebrow ig-eyebrow-light">Control Center</span>
                <h1>Dashboard</h1>
                <p>A live view of posts, tickets and leads flowing through the desk.</p>
            </div>

            <form method="GET" action="/countDashboard" id="igDashDateForm" class="ig-dash-datefilter">
                @csrf
                <div class="ig-dash-datefilter-field">
                    <label for="igDashFrom">From</label>
                    <input type="date" class="form-control" id="igDashFrom" name="startDate" value="{{$startDate}}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="ig-dash-datefilter-field">
                    <label for="igDashTo">To</label>
                    <input type="date" class="form-control" id="igDashTo" name="endDate" value="{{$endDate}}" max="{{ date('Y-m-d') }}">
                </div>
                <button type="submit" class="btn btn-danger ig-dash-datefilter-btn"><i class="bi bi-funnel"></i> Apply</button>

                <input type="hidden" name="startGraphDate" id="igMirrorStartGraphDate">
                <input type="hidden" name="endGraphDate" id="igMirrorEndGraphDate">
                <input type="hidden" name="startPostGraphDate" id="igMirrorStartPostGraphDate">
                <input type="hidden" name="endPostGraphDate" id="igMirrorEndPostGraphDate">
                <input type="hidden" name="startGraphLeadDate" id="igMirrorStartGraphLeadDate">
                <input type="hidden" name="endGraphLeadDate" id="igMirrorEndGraphLeadDate">
                <input type="hidden" name="startGraphSentimateDate" id="igMirrorStartGraphSentimateDate">
                <input type="hidden" name="endGraphSentimateDate" id="igMirrorEndGraphSentimateDate">
            </form>
        </div>

        <div class="ig-dash-stats-row ig-animate-in">
            @if(addUIComponent('DASHBOARD_RECENT_POSTS') != 'HIDDEN')
            <a href="javascript:void(0)" onclick="location.href='/recentdashboard';" class="ig-dstat">
                <span class="ig-dstat-icon ig-dstat-icon-posts"><i class="bi bi-mailbox"></i></span>
                <div class="ig-dstat-body">
                    <h6 class="counter" data-target="{{$getSocailRecentData->count()}}">{{$getSocailRecentData->count()}}</h6>
                    <p>Recent Posts</p>
                    <span class="ig-dstat-sub">Selected range</span>
                </div>
            </a>
            @endif
            @if(addUIComponent('DASHBOARD_RECENT_TICKETS') != 'HIDDEN')
            <a href="javascript:void(0)" onclick="location.href='/getRecentSocialTicket';" class="ig-dstat">
                <span class="ig-dstat-icon ig-dstat-icon-tickets"><i class="bi bi-ticket"></i></span>
                <div class="ig-dstat-body">
                    <h6 class="counter" data-target="{{$getTicketRecentData->count()}}">{{$getTicketRecentData->count()}}</h6>
                    <p>Recent Tickets</p>
                    <span class="ig-dstat-sub">Selected range</span>
                </div>
            </a>
            @endif
            @if(addUIComponent('DASHBOARD_RESOLVED_TICKETS') != 'HIDDEN')
            <a href="javascript:void(0)" onclick="location.href='/getRecentSocialTicket?status=Resolved';" class="ig-dstat">
                <span class="ig-dstat-icon ig-dstat-icon-resolved"><i class="bi bi-flag"></i></span>
                <div class="ig-dstat-body">
                    <h6 class="counter" data-target="{{$getTicketGraphResolvedData->count()}}">{{$getTicketGraphResolvedData->count()}}</h6>
                    <p>Resolved Tickets</p>
                    <span class="ig-dstat-sub">Selected range</span>
                </div>
            </a>
            @endif
            <a href="javascript:void(0)" onclick="location.href='/dashboard';" class="ig-dstat">
                <span class="ig-dstat-icon ig-dstat-icon-totalposts"><i class="bi bi-mailbox"></i></span>
                <div class="ig-dstat-body">
                    <h6 class="counter" data-target="{{$totalPostCount->count()}}">{{$totalPostCount->count()}}</h6>
                    <p>Total Posts</p>
                    <span class="ig-dstat-sub">All Time</span>
                </div>
            </a>
            <a href="javascript:void(0)" onclick="location.href='/getSocialTicket';" class="ig-dstat">
                <span class="ig-dstat-icon ig-dstat-icon-tottickets"><i class="bi bi-ticket"></i></span>
                <div class="ig-dstat-body">
                    <h6 class="counter" data-target="{{$totalTicketCount->count()}}">{{$totalTicketCount->count()}}</h6>
                    <p>Total Tickets</p>
                    <span class="ig-dstat-sub">All Time</span>
                </div>
            </a>
        </div>

        <div class="ig-dash-charts-row ig-animate-in">
            <div class="ig-dash-card ig-panel">
                <div class="ig-panel-toolbar">
                    <h6 class="formh6 ig-panel-title"><i class="bi bi-graph-up-arrow"></i> Statistics Overview</h6>
                </div>

                @if($getSocailGraphData->count() > 0 || $getTicketGraphData->count() > 0)
                <div class="ig-chart-shell">
                    <div id="chartContainerOverview" class="ig-overview-chart" style="height: 300px;"></div>
                </div>
                @else
                <div class="ig-empty-state ig-empty-state-sm">
                    <i class="bi bi-bar-chart"></i>
                    <h6>No record found</h6>
                    <p>Try a different date range to see activity here.</p>
                </div>
                @endif
            </div>

            <div class="ig-dash-card ig-panel ig-donut-card">
                <div class="ig-panel-toolbar">
                    <h6 class="formh6 ig-panel-title"><i class="bi bi-pie-chart"></i> Category Breakdown</h6>
                    <div class="tabscircle2">
                        <ul class="nav nav-pills" id="ig-breakdown-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="ig-tab-sentiment" data-bs-toggle="pill" data-bs-target="#ig-pane-sentiment" type="button" role="tab" aria-controls="ig-pane-sentiment" aria-selected="true">Sentiment</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="ig-tab-tickets" data-bs-toggle="pill" data-bs-target="#ig-pane-tickets" type="button" role="tab" aria-controls="ig-pane-tickets" aria-selected="false">Tickets</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="ig-tab-posts" data-bs-toggle="pill" data-bs-target="#ig-pane-posts" type="button" role="tab" aria-controls="ig-pane-posts" aria-selected="false">Posts</button>
                            </li>
                        </ul>
                    </div>
                </div>

                <?php
                    $sentiTotal = $getSentimateGraphNagativeData->count() + $getSentimateGraphPositiveData->count() + $getSentimateGraphComplaintData->count() + $getSentimateGraphQueryData->count() + $getSentimateGraphInformationData->count() + $getSentimateGraphSpamData->count();
                    $sentiSlices = [
                        ['label' => 'Feedback Negative', 'count' => $getSentimateGraphNagativeData->count(), 'color' => '#0a5c39'],
                        ['label' => 'Feedback Positive', 'count' => $getSentimateGraphPositiveData->count(), 'color' => '#ffd525'],
                        ['label' => 'Complaint', 'count' => $getSentimateGraphComplaintData->count(), 'color' => '#0f9457'],
                        ['label' => 'Query', 'count' => $getSentimateGraphQueryData->count(), 'color' => '#e7b400'],
                        ['label' => 'Information', 'count' => $getSentimateGraphInformationData->count(), 'color' => '#3b6cff'],
                        ['label' => 'Spam', 'count' => $getSentimateGraphSpamData->count(), 'color' => '#0d7a49'],
                    ];

                    // Tickets and Posts status breakdowns — this is the data that
                    // used to render as pie charts in the old Statistics Overview
                    // tabs. That data is still fetched by the controller every
                    // request (getTicketGraph*Data / getPostGraph*Data, bound to
                    // the same startGraphDate/startPostGraphDate the single
                    // dashboard-wide date filter already drives); it just wasn't
                    // being rendered anywhere after Statistics Overview became the
                    // Posts/Tickets trend chart. Bringing it back here, not there.
                    $ticketTotal = $getTicketGraphNewData->count() + $getTicketGraphPendingData->count() + $getTicketGraphMoveData->count() + $getTicketGraphResolvedData->count() + $getTicketGraphRejectedData->count() + $getTicketGraphDuplicateData->count() + $getTicketGraphAssignedData->count();
                    $ticketSlices = [
                        ['label' => 'New', 'count' => $getTicketGraphNewData->count(), 'color' => '#0f9457'],
                        ['label' => 'Pending With Team', 'count' => $getTicketGraphPendingData->count(), 'color' => '#ffd525'],
                        ['label' => 'Move To Internal Team', 'count' => $getTicketGraphMoveData->count(), 'color' => '#3b6cff'],
                        ['label' => 'Resolved', 'count' => $getTicketGraphResolvedData->count(), 'color' => '#0a5c39'],
                        ['label' => 'Rejected', 'count' => $getTicketGraphRejectedData->count(), 'color' => '#e5484d'],
                        ['label' => 'Duplicate', 'count' => $getTicketGraphDuplicateData->count(), 'color' => '#e7b400'],
                        ['label' => 'Assigned', 'count' => $getTicketGraphAssignedData->count(), 'color' => '#5c6a76'],
                    ];

                    $postTotal = $getPostGraphNewData->count() + $getPostGraphDuplicateData->count() + $getPostGraphconvertLeadData->count() + $getPostGraphConvertedData->count();
                    $postSlices = [
                        ['label' => 'New', 'count' => $getPostGraphNewData->count(), 'color' => '#0f9457'],
                        ['label' => 'Duplicate', 'count' => $getPostGraphDuplicateData->count(), 'color' => '#e7b400'],
                        ['label' => 'Converted To Lead', 'count' => $getPostGraphconvertLeadData->count(), 'color' => '#3b6cff'],
                        ['label' => 'Converted To Ticket', 'count' => $getPostGraphConvertedData->count(), 'color' => '#0a5c39'],
                    ];
                ?>

                <div class="tab-content" id="ig-breakdown-tabContent">
                    <div class="tab-pane fade show active" id="ig-pane-sentiment" role="tabpanel" aria-labelledby="ig-tab-sentiment">
                        @if($sentiTotal > 0)
                        <div class="ig-donut-wrap">
                            <div class="ig-donut-canvas-wrap">
                                <div id="chartContainer3" style="height:230px;"></div>
                                <div class="ig-donut-center">
                                    <b>{{$sentiTotal}}</b>
                                    <span>Total</span>
                                </div>
                            </div>
                            <div class="ig-donut-legend">
                                @foreach($sentiSlices as $slice)
                                <div class="ig-donut-legend-row">
                                    <span class="ig-donut-dot" style="background:{{$slice['color']}}"></span>
                                    <span>{{$slice['label']}}</span>
                                    <b>{{ round(($slice['count']/$sentiTotal)*100) }}%</b>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="ig-empty-state ig-empty-state-sm">
                            <i class="bi bi-pie-chart"></i>
                            <h6>No record found</h6>
                            <p>Try a different date range to see category activity here.</p>
                        </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="ig-pane-tickets" role="tabpanel" aria-labelledby="ig-tab-tickets">
                        @if($ticketTotal > 0)
                        <div class="ig-donut-wrap">
                            <div class="ig-donut-canvas-wrap">
                                <div id="chartContainerTicketBreakdown" style="height:230px;"></div>
                                <div class="ig-donut-center">
                                    <b>{{$ticketTotal}}</b>
                                    <span>Total</span>
                                </div>
                            </div>
                            <div class="ig-donut-legend">
                                @foreach($ticketSlices as $slice)
                                <div class="ig-donut-legend-row">
                                    <span class="ig-donut-dot" style="background:{{$slice['color']}}"></span>
                                    <span>{{$slice['label']}}</span>
                                    <b>{{ round(($slice['count']/$ticketTotal)*100) }}%</b>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="ig-empty-state ig-empty-state-sm">
                            <i class="bi bi-pie-chart"></i>
                            <h6>No record found</h6>
                            <p>Try a different date range to see ticket activity here.</p>
                        </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="ig-pane-posts" role="tabpanel" aria-labelledby="ig-tab-posts">
                        @if($postTotal > 0)
                        <div class="ig-donut-wrap">
                            <div class="ig-donut-canvas-wrap">
                                <div id="chartContainerPostBreakdown" style="height:230px;"></div>
                                <div class="ig-donut-center">
                                    <b>{{$postTotal}}</b>
                                    <span>Total</span>
                                </div>
                            </div>
                            <div class="ig-donut-legend">
                                @foreach($postSlices as $slice)
                                <div class="ig-donut-legend-row">
                                    <span class="ig-donut-dot" style="background:{{$slice['color']}}"></span>
                                    <span>{{$slice['label']}}</span>
                                    <b>{{ round(($slice['count']/$postTotal)*100) }}%</b>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="ig-empty-state ig-empty-state-sm">
                            <i class="bi bi-pie-chart"></i>
                            <h6>No record found</h6>
                            <p>Try a different date range to see post activity here.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="ig-dash-social-row ig-animate-in">
            <div class="socialticket_mini {{ addUIComponent('DASHBOARD_SOCIAL_POSTS') }}">
                <div class="bgwhite2 heightscroll ig-panel">
                    <div class="headingmain headingsec mb-3">
                        <h6><i class="bi bi-mailbox ig-panel-icon"></i> Social Posts <span class="ig-count-pill">{{$totalPostCount->count()}}</span><a href="/dashboard"
                                class="seeall">See All</a>
                        </h6>
                    </div>
                    <?php $count = 0; ?>
                    @if(!empty($getSocailData) && $getSocailData->count())
                    @foreach($getSocailData as $getSocail)
                    @if( $count > 4)
                    <?php continue;?>
                    @endif
                    <div class="socialticketinner" style="--ig-i:{{ $count }}">

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
                    @else
                    <div class="ig-panel-empty">
                        <i class="bi bi-mailbox"></i>
                        <h6>No recent posts</h6>
                        <p>New social posts will appear here as they come in.</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="socialticket_mini {{ addUIComponent('DASHBOARD_SOCIAL_TICKETS') }}">
                <div class="bgwhite2 heightscroll ig-panel">
                    <div class="headingmain headingsec mb-3">
                        <h6><i class="bi bi-ticket ig-panel-icon"></i> Social Tickets <span class="ig-count-pill">{{$totalTicketCount->count()}}</span>
                            <a href="/getSocialTicket" class="seeall">See All</a>
                        </h6>
                    </div>
                    <?php $count = 0;?>
                    @if(!empty($getTicketData) && $getTicketData->count())

                    @foreach($getTicketData as $getSocail)
                    @if( $count > 4)
                    <?php continue;?>
                    @endif
                    <div class="socialticketinner" style="--ig-i:{{ $count }}">
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
                    @else
                    <div class="ig-panel-empty">
                        <i class="bi bi-ticket"></i>
                        <h6>No recent tickets</h6>
                        <p>New social tickets will appear here as they come in.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<script>
$("#dashboard").addClass("active");
</script>

<script>

// Presentation-only chart theming — registers an on-brand colour set for
// the existing CanvasJS charts below. This changes how the same data
// points are painted (colours/background/tooltip chrome), never what data
// is fetched, computed or plotted.
if (window.CanvasJS && typeof CanvasJS.addColorSet === "function") {
    CanvasJS.addColorSet("igEnergyPalette", [
        "#0f9457", "#ffd525", "#0a5c39", "#e7b400",
        "#3b6cff", "#0d7a49", "#f4b400", "#5c6a76"
    ]);
}
var igChartTheme = {
    theme: "light2",
    backgroundColor: "transparent",
    colorSet: "igEnergyPalette",
    toolTip: { cornerRadius: 8, fontSize: 13 }
};

function chart3()
{
    var chart33 = new CanvasJS.Chart("chartContainer3", {
        animationEnabled: true,
        ...igChartTheme,

        data: [{
            type: "doughnut",
            innerRadius: "62%",
            startAngle: 240,
            yValueFormatString: "##0",
            indexLabel: "",
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

// Category Breakdown — Tickets / Posts status tabs. Same status-count data
// the controller has always computed for the selected range (getTicketGraph*
// / getPostGraph*, bound to startGraphDate/startPostGraphDate, which the
// single dashboard date filter already drives); just rendering it again as
// a doughnut, the way the old pre-redesign tabs did.
function ticketBreakdownChart()
{
    var container = document.getElementById('chartContainerTicketBreakdown');
    if (!container) return;
    var chartTb = new CanvasJS.Chart("chartContainerTicketBreakdown", {
        animationEnabled: true,
        ...igChartTheme,
        data: [{
            type: "doughnut",
            innerRadius: "62%",
            startAngle: 240,
            yValueFormatString: "##0",
            indexLabel: "",
            dataPoints: [
                { y: {{$getTicketGraphNewData->count()}}, label: "New" },
                { y: {{$getTicketGraphPendingData->count()}}, label: "Pending With Team" },
                { y: {{$getTicketGraphMoveData->count()}}, label: "Move To Internal Team" },
                { y: {{$getTicketGraphResolvedData->count()}}, label: "Resolved" },
                { y: {{$getTicketGraphRejectedData->count()}}, label: "Rejected" },
                { y: {{$getTicketGraphDuplicateData->count()}}, label: "Duplicate" },
                { y: {{$getTicketGraphAssignedData->count()}}, label: "Assigned" },
            ]
        }]
    });
    chartTb.render();
}

function postBreakdownChart()
{
    var container = document.getElementById('chartContainerPostBreakdown');
    if (!container) return;
    var chartPb = new CanvasJS.Chart("chartContainerPostBreakdown", {
        animationEnabled: true,
        ...igChartTheme,
        data: [{
            type: "doughnut",
            innerRadius: "62%",
            startAngle: 240,
            yValueFormatString: "##0",
            indexLabel: "",
            dataPoints: [
                { y: {{$getPostGraphNewData->count()}}, label: "New" },
                { y: {{$getPostGraphDuplicateData->count()}}, label: "Duplicate" },
                { y: {{$getPostGraphconvertLeadData->count()}}, label: "Converted To Lead" },
                { y: {{$getPostGraphConvertedData->count()}}, label: "Converted To Ticket" },
            ]
        }]
    });
    chartPb.render();
}

// Statistics Overview — real line/area trend built from the same raw
// records the app already fetches for the selected range (getSocailGraphData
// / getTicketGraphData, both bounded by startGraphDate/endGraphDate, which
// the single dashboard date filter now drives). No new query: this only
// buckets the already-fetched records by day, client-side.
var igPostDates = {!! json_encode($getSocailGraphData->pluck('istPostDate')) !!};
var igTicketDates = {!! json_encode($getTicketGraphData->pluck('date_Created')) !!};

function overviewChart()
{
    var fromEl = document.getElementById('igDashFrom');
    var toEl = document.getElementById('igDashTo');
    var container = document.getElementById('chartContainerOverview');
    if (!container || !fromEl || !fromEl.value || !toEl || !toEl.value) return;

    function dayKey(raw) {
        return String(raw).substring(0, 10);
    }
    function countByDay(list) {
        var map = {};
        list.forEach(function (raw) {
            var k = dayKey(raw);
            map[k] = (map[k] || 0) + 1;
        });
        return map;
    }
    var postCounts = countByDay(igPostDates);
    var ticketCounts = countByDay(igTicketDates);

    var cursor = new Date(fromEl.value + 'T00:00:00');
    var end = new Date(toEl.value + 'T00:00:00');
    var days = [];
    var guard = 0;
    while (cursor <= end && guard < 400) {
        var y = cursor.getFullYear(), m = ('0' + (cursor.getMonth() + 1)).slice(-2), d = ('0' + cursor.getDate()).slice(-2);
        days.push(y + '-' + m + '-' + d);
        cursor.setDate(cursor.getDate() + 1);
        guard++;
    }
    function fmtLabel(key) {
        var d = new Date(key + 'T00:00:00');
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return ('0' + d.getDate()).slice(-2) + ' ' + months[d.getMonth()];
    }

    var postPoints = days.map(function (k) { return { label: fmtLabel(k), y: postCounts[k] || 0 }; });
    var ticketPoints = days.map(function (k) { return { label: fmtLabel(k), y: ticketCounts[k] || 0 }; });

    var chartOv = new CanvasJS.Chart("chartContainerOverview", {
        animationEnabled: true,
        ...igChartTheme,
        axisX: { labelFontSize: 11, gridColor: "#eef1f5", interval: Math.ceil(days.length / 10) || 1 },
        axisY: { gridColor: "#eef1f5", labelFontSize: 11, includeZero: true },
        toolTip: { shared: true, cornerRadius: 8, fontSize: 13 },
        legend: { fontSize: 12, verticalAlign: "top", horizontalAlign: "right" },
        data: [
            { type: "splineArea", name: "Posts", showInLegend: true, fillOpacity: .15, markerSize: 0, dataPoints: postPoints },
            { type: "spline", name: "Tickets", showInLegend: true, markerSize: 0, dataPoints: ticketPoints }
        ]
    });
    chartOv.render();
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

    setTimeout(() => { overviewChart(); chart3(); }, 200);
}

// Category Breakdown tab switching. CanvasJS can't reliably measure a
// display:none container, so each Tickets/Posts doughnut is only rendered
// the first time its tab is actually shown (same pattern the old pre-redesign
// tab system used) rather than at page load alongside chart3().
var igTicketBreakdownRendered = false;
var igPostBreakdownRendered = false;
var igTabTickets = document.getElementById('ig-tab-tickets');
var igTabPosts = document.getElementById('ig-tab-posts');
if (igTabTickets) {
    igTabTickets.addEventListener('shown.bs.tab', function () {
        if (!igTicketBreakdownRendered) {
            igTicketBreakdownRendered = true;
            setTimeout(ticketBreakdownChart, 50);
        }
    });
}
if (igTabPosts) {
    igTabPosts.addEventListener('shown.bs.tab', function () {
        if (!igPostBreakdownRendered) {
            igPostBreakdownRendered = true;
            setTimeout(postBreakdownChart, 50);
        }
    });
}

// Single dashboard-wide date filter: mirror the chosen From/To into every
// section-specific date pair the controller already understands, so one
// submit updates every date-dependent part of the dashboard in one request.
// No new backend query — this only reuses the existing four override blocks
// that already exist in DashboardController::countDashboard.
document.getElementById('igDashDateForm').addEventListener('submit', function () {
    var from = document.getElementById('igDashFrom').value;
    var to = document.getElementById('igDashTo').value;
    ['igMirrorStartGraphDate', 'igMirrorStartPostGraphDate', 'igMirrorStartGraphLeadDate', 'igMirrorStartGraphSentimateDate'].forEach(function (id) {
        document.getElementById(id).value = from;
    });
    ['igMirrorEndGraphDate', 'igMirrorEndPostGraphDate', 'igMirrorEndGraphLeadDate', 'igMirrorEndGraphSentimateDate'].forEach(function (id) {
        document.getElementById(id).value = to;
    });
});

</script>

    @endsection
