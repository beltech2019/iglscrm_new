@extends('auth.layouts')

@section('content')


<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="heading_two ">
                        <h2><i class="bi bi-ticket iconsbg2"></i>Change Log </h2>
                    </div>
                </div>
            </div>
			<!--
            <div class="data_alert">
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle"></i>
                            <div class="ms-4 fontmain">
                            Fields audited in this module: Post Message.Assigned User ID post ID.Converted Post URL.

                            </div>
                        </div>
                    </div>-->
        <div class="formscoulam">
        <table class="table">
		  <thead>
			<tr>
			  <th scope="col">Field</th>
			  <th scope="col">New Value</th>
			  <th scope="col">Old Value</th>
			  <th scope="col">Changed By</th>
			  <th scope="col">Change Date</th>
			</tr>
		  </thead>
		  <tbody>
		  @if(!empty($getSocialLog))
		  @foreach($getSocialLog as $log)
		<tr id="tr-{{$log->log_id}}">
		  <td>{{changeByKey($log->field)}}</td>
		  <td>{{$log->new_value}}</td>
		  <td>{{$log->old_value}}</td>
		  <td>{{$log->change_by}}</td>
		  <td>{{$log->change_date}}</td>
		</tr>
	@endforeach
	@endif
    
  </tbody>
</table>
        </div>
        </div>
    </div>
</div>
</div>

<script>
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