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
        <div class="bgwhite2">
            <div class="row">
                <div class="col-md-6">
                    <div class="heading_two ">
                        <h2><i class="bi bi-ticket iconsbg2"></i>Report</h2>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="editbtns" style="float:right;">
                <a class="" href="/showReport/{{$report->report_id}}?download=true"><i   class="bi bi-download" ></i> Download</a>
                <div class="iconsmenu2" style="float:none">
                        
                    </div>
            
                </div>
            </div>
            <div class="socialticketview">

            <div class="row">
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Report Id	</label>
                                                    <p class="peragraph_content">{{$report->report_id}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Name</label>
                                                    <p class="peragraph_content">{{$report->report_name}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Assign To</label>
                                                    <p class="peragraph_content">{{$report->assigned_to}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Module</label>
                                                    <p class="peragraph_content">@php $module=changeByKey($report->module_name); @endphp {{$module}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Created By</label>
                                                    <p class="peragraph_content">{{$report->name}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coulams_form">
                                                    <label class="form-label">Created Date</label>
                                                    <p class="peragraph_content">{{$report->created_at}}</p>
                                                </div>
                                            </div> 
                                        </div>
                                        </div>
        </div>

        <div class="bgwhite2">
	
        <div class="my-2 d-flex row">
        <div class="col-md-8">
            {!! $data->withQueryString()->links() !!}
        </div>
        <div class="col-md-4">
            <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                Showing {{ $data->firstItem() }} - {{ $data->lastItem() }} of {{ $data->total() }} report item
            </p>
        </div>
    </div>
		<table class="table {{ addUIComponent('REPORTS_INNER_TABLE') }}">
  <thead>
    <tr>
      @if(!empty($field) && $field->count())
       @foreach($field as $key => $fields)  
        <th scope="col">{{$fields->field_label}}</th>
       @endforeach
      @endif
    </tr>
    </thead>
    <tbody>
    @if(!empty($data) && $data->count())
       @foreach($data as $key => $datas)  
        <tr>
         @if(!empty($field) && $field->count())
           @foreach($field as $key => $fields)  
             <td class="line_break">{{ $datas->{$fields->field_key} }}</td>
           @endforeach
         @endif
        </tr>
       @endforeach
    @endif
  

  </tbody>
</table>
		
		
    </div>
    <div class="my-2 d-flex row">
        <div class="col-md-8">
            {!! $data->withQueryString()->links() !!}
        </div>
        <div class="col-md-4">
            <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                Showing {{ $data->firstItem() }} - {{ $data->lastItem() }} of {{ $data->total() }} report item
            </p>
        </div>
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
		
$("#admin").addClass("active");
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