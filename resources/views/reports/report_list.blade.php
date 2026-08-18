@extends('auth.layouts')

@section('content')


    <div class="container-fluid">
        <div class="div_container">
            <div class="ig-page-header">
                <div>
                    <span class="ig-eyebrow-light">Reporting</span>
                    <h1><i class="bi bi-file-earmark-bar-graph"></i> Manual Reports</h1>
                    <p>Custom reports built and shared by your team.</p>
                </div>
                <a class="btn btn-danger ig-btn-add {{ addUIComponent('REPORTS_CREATE_REPORTS') }}" href="/createReport"><i class="bi bi-plus-lg"></i> New Report</a>
            </div>
            <div class="bgwhite2 ig-panel">
                <div class="busines_details">
                    <div class="moreinfo table-responsive">
                    <table class="table ig-table {{ addUIComponent('REPORTS_TABLE') }}" data-ig-tabletools>
                        <thead>
                            <tr>   
                            <th scope="col">Report Id</th>
                            <th scope="col">Name</th>
                            <!-- <th scope="col">Assign To</th> -->
                            <th scope="col">Module</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Created Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!empty($reportList) && $reportList->count())
                          @foreach($reportList as $key => $reportLists)
                            <tr>    
                            <td>
                            <div class="editer_file d-flex">
                                <div class="dropdown">
                                    <button class="settingicons" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear"></i>
                                    </button>
									
                                    <ul class="dropdown-menu dropdownmenu_innner leftdropdown"
                                        aria-labelledby="dropdownMenuButton1">
                                       <li><a class="dropdown-item" href="/editreport/{{$reportLists->report_id}}"
                                                ><i
                                                    class="bi bi-pencil"></i> Edit</a></li>
										<li>
										<a class="dropdown-item {{ addUIComponent('DASHBOARD_TICKETS') }}" href="/showReport/{{$reportLists->report_id}}?download=true"><i   class="bi bi-download" ></i> Download</a>
										</li>
                                        <li><a class="dropdown-item deleteLink {{ addUIComponent('REPORTS_DELETE') }}" id="{{$reportLists->report_id}}"  href="#" 
                                        ><i  class="bi bi-trash3" ></i> Delete</a></li>
                                    </ul>
                                </div>
                                <a class="{{ addUIComponent('REPORTS_INNER') }}" href="/showReport/{{$reportLists->report_id}}">{{$reportLists->report_id}}</a>
                            </div>     
                            </td>
                            <td>{{$reportLists->report_name}}</td>
                            <!-- <td>{{$reportLists->assigned_to}}</td> -->
                            <td>@php $module=changeByKey($reportLists->module_name); @endphp {{$module}}</td>
                            <td>{{$reportLists->name}}</td>
                            <td>{{$reportLists->updated_at}}</td>
                            </tr>
                          @endforeach
                        @else
                          <tr class="ig-empty-row">
                            <td colspan="5">
                                <div class="ig-empty-state">
                                    <i class="bi bi-file-earmark-bar-graph"></i>
                                    <h6>No reports yet</h6>
                                    <p>Reports you create will show up here.</p>
                                </div>
                            </td>
                          </tr>
                        @endif
                        </tbody>
                        </table>
                    </div>
                    <div class="my-2 d-flex  {{ addUIComponent('REPORTS_TABLE') }} ig-table-toolbar">
                        {!! $reportList->withQueryString()->links() !!}
                  </div>
                </div>

            </div>
        </div>
    </div>



<script>
$("#admin").addClass("active");
var deleteId = '';
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "64px";
    document.getElementById("main").style.marginLeft = "64px";
}
$(".deleteLink").click(function() {
        deleteId = $(this).attr('id');
        $("#commonModal").modal("show");
        $("#commonBtn").show();
        $("#msg").text("Are you sure delete it.");
    });

    $(document).on('click',"#commonBtn",function() {
        location.href = "/deleteReport/" + deleteId;
    });
</script>

@endsection