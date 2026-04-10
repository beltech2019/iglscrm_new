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
                                    aria-selected="true" onclick="window.location.href='/getadminupdatebyReport';">Update</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile"
                                    aria-selected="false" onclick="window.location.href='/getadmincreatebyReport';">Create</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="lead-tab" data-bs-toggle="tab" data-bs-target="#lead"
                                    type="button" role="tab" aria-controls="lead"
                                    aria-selected="false" onclick="window.location.href='/getadmindeletebyReport';">Delete</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-2 mx-1" id="myTabContent">
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row my-2">
                                    <div class="col-md-12">
                                        <div class="heading_two ">
                                            <h2>Delete</h2>
                                        </div>
                                    </div>
                                </div>
                                <form action="/getadmindeletebyReport" method="GET">
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
                                        <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Users</label>
                                            <select class="form-control" placeholder="Source" name="adminuser" id="searchSelect" required>
                                               <option value="" disabled selected></option>
                                            @if(!empty($userlist) && $userlist->count())
                                              @foreach($userlist as $getUsers)
                                              <option value="{{$getUsers->id}}"{{$user && $user == $getUsers->id?'selected':''}}>{{$getUsers->name}}</option>
                                              @endforeach
                                            @endif    
                                            </select>  
                                        </div>
                                    </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-danger marginmain"><i class="bi bi-funnel"></i></button>
                                            <!-- <button type="submit" class="btn btn-danger mt-4 float-end" name="download" value="download"><i class="bi bi-download"></i></button> -->
                                        </div>
                                    </div>
                                </form>
                                        <div class="my-2 row">
                                            <div class="col-md-9">
                                                {!! $getInfo->withQueryString()->links() !!}
                                            </div>
                                            <div class="col-md-3">
                                                <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                                                    Showing {{ $getInfo->firstItem() }} - {{ $getInfo->lastItem() }} of {{ $getInfo->total() }} admin delete
                                                </p>
                                            </div>
                                        </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pb-1">
                                            <table class="table tickettable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Sr No</th>
                                                        <th scope="col">Deleted By</th>
                                                        <th scope="col">Deleted Time</th>
                                                        <th scope="col">Table</th>
                                                        <th scope="col">Table Id</th>
                                                        <th scope="col">Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $serialNumber = $getInfo->firstItem();
                                                @endphp

                                                @foreach ($getInfo as $info)
                                                    <tr>
                                                        <td scope="row">{{ $serialNumber++ }}</td>
                                                        <td>{{ $info->name }}</td>
                                                        <td>{{ $info->change_date }}</td>
                                                        <td>{{ $info->table_name }}</td>
                                                        <td>{{ $info->field_id }}</td>
                                                        <td>{{ reportfordescription($info->field_id,$info->table_name,$info->operation) }}</td>
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
                                                    Showing {{ $getInfo->firstItem() }} - {{ $getInfo->lastItem() }} of {{ $getInfo->total() }} admin delete
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