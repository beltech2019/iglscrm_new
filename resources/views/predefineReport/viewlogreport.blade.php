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
                                    aria-selected="false" onclick="window.location.href='/getSocialPostReport';">Post</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab"
                                    type="button" role="tab" aria-controls="profile"
                                    aria-selected="false" onclick="window.location.href='/getSocialTicketReport';">Ticket</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="log-tab" data-bs-toggle="tab"
                                    data-bs-target="#log" type="button" role="tab" aria-controls="log"
                                    aria-selected="true">View Log</button>
                            </li>

                            <li class="nav-item {{ addUIComponent('DASHBOARD_LEADS') }}" role="presentation">
                                <button class="nav-link" id="lead-tab" data-bs-toggle="tab" data-bs-target="#lead"
                                    type="button" role="tab" aria-controls="lead"
                                    aria-selected="false" onclick="window.location.href='/getLeadsReport';">Lead</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-2 mx-1" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="log-tab">
                                <div class="row my-2">
                                    <div class="col-md-12">
                                        <div class="heading_two ">
                                            <h2>View Log</h2>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ url('/getViewLogReport') }}" method="GET">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="change_by" class="form-label">Changed By</label>
                                                <select name="change_by" class="form-select" id="change_by">
                                                    <option value="">All Users</option>
                                                        @foreach ($userMap as $name => $display)
                                                    <option value="{{ $name }}" {{ (string) $selectedUser === (string) $name ? 'selected' : '' }}>
                                                        {{ $display }}
                                                    </option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="start" class="form-label">From</label>
                                                <input type="date" class="form-control" name="start" id="start" value="{{ $start }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="end" class="form-label">To</label>
                                                <input type="date" class="form-control" name="end" id="end" value="{{ $end }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3 d-flex justify-content-between">
                                            <button type="submit" class="btn btn-danger mb-3" title="Filter">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button type="submit" name="download" value="download" class="btn btn-danger mb-3" title="Download CSV">
                                                <i class="bi bi-download"></i>
                                            </button>
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
                                                        <th></th>
                                                        <th></th>
                                                        <th>Field</th>
                                                        <th>New Value</th>
                                                        <th>Old Value</th>
                                                        <th>Changed By</th>
                                                        <th>Change Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $grouped = $posts->groupBy('post_id');
                                                    @endphp

                                                    @forelse($grouped as $postId => $logs)
                                                        <tr class="table-primary">
                                                            <td colspan="7">
                                                                <strong>
                                                                    Post ID: {{ $postId }}  |  Ticket ID: {{ $logs->first()->ticket_id ?? 'N/A' }}
                                                                </strong>
                                                            </td>
                                                        </tr>

                                                        @foreach($logs as $log)
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ changeByKey($log->field) }}</td>
                                                                <td>{{ $log->new_value }}</td>
                                                                <td>{{ $log->old_value }}</td>
                                                                <td>{{ $userMap[$log->change_by] ?? $log->change_by }}</td>
                                                                <td>{{ $log->change_date }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @empty
                                                        <tr>
                                                            <td colspan="7">No logs found.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="my-2 row">
                                            <div class="col-md-9">
                                                {!! $posts->withQueryString()->links() !!}
                                            </div>
                                            <div class="col-md-3">
                                                <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                                                    Showing {{ $posts->firstItem() }} - {{ $posts->lastItem() }} of {{ $posts->total() }} logs
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