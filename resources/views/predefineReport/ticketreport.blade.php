@extends('auth.layouts')

@section('content')
<style>
.tabscircle2 .nav-tabs .nav-link {
    color: #000;
    padding: 6px 14px !important;
    color: #5a5a5a;
    padding: 6px 14px;
    background: transparent;
    border: 0;
    font-size: 12px;
    border-radius: 70px;
    width: 120px;
}

.tabscircle2 .nav-item .nav-link.active {
    border-color: #dee2e6;
    padding: 5px 14px !important;
}

.tabscircle2 .nav-tabs {
    border-bottom: 1px solid #dee2e6;
    width: fit-content;
    background: #f3f3f3;
    padding: 5px 16px !important;
    border-radius: 70px;
}
</style>
<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="heading_two ">
                        <h2><i class="bi bi-file-earmark-text iconsbg2"></i>Reports</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tabscircle2">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true" onclick="window.location.href='/getSocialPostReport';">Post</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile"
                                    aria-selected="false" onclick="window.location.href='/getSocialTicketReport';">Ticket</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="log-tab"
                                    type="button" role="tab" aria-controls="log"
                                    aria-selected="false" onclick="window.location.href='/getViewLogReport';">View Log</button>
                            </li>
                            <li class="nav-item {{ addUIComponent('DASHBOARD_LEADS') }}" role="presentation">
                                <button class="nav-link" id="lead-tab" data-bs-toggle="tab" data-bs-target="#lead"
                                    type="button" role="tab" aria-controls="lead"
                                    aria-selected="false" onclick="window.location.href='/getLeadsReport';">Lead</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-2 mx-1" id="myTabContent">
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row my-2">
                                    <div class="col-md-12">
                                        <div class="heading_two ">
                                            <h2>Social Ticket</h2>
                                        </div>
                                    </div>
                                </div>
                                <form action="/getSocialTicketReport" method="GET">
                                @csrf
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="start" class="form-label">From</label>
                                                <input type="date" class="form-control" name="start" placeholder="From date" value="{{$start}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="end" class="form-label">To</label>
                                                <input type="date" class="form-control" name="end" placeholder="To date" value="{{$end}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-danger marginmain"><i class="bi bi-funnel"></i></button>
                                            <button type="submit" class="btn btn-danger mt-4 float-end" name="download" value="download"><i class="bi bi-download"></i></button>
                                        </div>
                                    </div>
                                </form>
                                        <div class="my-2 row">
                                            <div class="col-md-9">
                                                {!! $getInfo->withQueryString()->links() !!}
                                            </div>
                                            <div class="col-md-3">
                                                <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                                                    Showing {{ $getInfo->firstItem() }} - {{ $getInfo->lastItem() }} of {{ $getInfo->total() }} tickets
                                                </p>
                                            </div>
                                        </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pb-1">
                                            <table class="table tickettable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Post ID</th>
                                                        <th scope="col">Ticket ID</th>
                                                        <th scope="col">Post message</th>
                                                        <th scope="col">Customer Name</th>
                                                        <th scope="col">Source</th>
                                                        <th scope="col">Priority</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Assigned To</th>
                                                        <th scope="col">Creation Date</th>
                                                        <th scope="col">Activity</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($getInfo as $info)
                                                    <tr>
                                                        <td scope="row">{{ $info->getTweet_id }}</td>
                                                        <td scope="row">{{ $info->ticket_id }}</td>
                                                        <td>{{ $info->postMessage }}</td>
                                                        <td>{{ $info->socialUser }}</td>
                                                        <td>{{ $info->source }}</td>
                                                        <td>{{ $info->priority }}</td>
                                                        <td>{{ $info->status }}</td>
                                                        <td>{{ $info->name }}</td>
                                                        <td>{{ $info->date_Created }}</td>
                                                        @if (!empty($info->activies))
                                                        <td>
                                                            @foreach($info->activies as $activity)
                                                                <span>{{ $activity->name }}({{ $activity->created_at }})  : {{ $activity->text }} </span></br>
                                                                
                                                            @endforeach
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="my-2 row">
                                            <div class="col-md-9">
                                                {!! $getInfo->withQueryString()->links() !!}
                                            </div>
                                            <div class="col-md-3">
                                                <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                                                    Showing {{ $getInfo->firstItem() }} - {{ $getInfo->lastItem() }} of {{ $getInfo->total() }} tickets
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
            $("#PredefineReports").addClass("active");  
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