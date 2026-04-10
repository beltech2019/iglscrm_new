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
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">Post</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab"
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
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row my-2">
                                    <div class="col-md-12">
                                        <div class="heading_two ">
                                            <h2>Social Post</h2>
                                        </div>
                                    </div>
                                </div>
                                <form action="/getSocialPostReport" method="GET">
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
                                                {!! $posts->withQueryString()->links() !!}
                                            </div>
                                            <div class="col-md-3">
                                                <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                                                    Showing {{ $posts->firstItem() }} - {{ $posts->lastItem() }} of {{ $posts->total() }} posts
                                                </p>
                                            </div>
                                        </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="">
                                            <table class="table tickettable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Post ID</th>
                                                        <th scope="col">Post message</th>
                                                        <th scope="col">Social User</th>
                                                        <th scope="col">Source</th>
                                                        <th scope="col">Post Url</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Post Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                               
                                                    @foreach ($posts as $post)
                                                    <tr>
                                                        <td scope="row">{{ $post->getTweet_id }}</td>
                                                        <td>{{ $post->postMessage }}</td>
                                                        <td>{{ $post->socialUser_name }}</td>
                                                        <td>{{ $post->source }}</td>
                                                        <td>{{ $post->postUrl }}</td>
                                                        <td>{{ $post->status }}</td>
                                                        <td>{{ $post->istPostDate }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="my-2 row">
                                            <div class="col-md-9">
                                                {!! $posts->withQueryString()->links() !!}
                                            </div>
                                            <div class="col-md-3">
                                                <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                                                    Showing {{ $posts->firstItem() }} - {{ $posts->lastItem() }} of {{ $posts->total() }} posts
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