@extends('auth.layouts')

@section('content')




<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="heading_two ">
                        <h2><i class="bi bi-mailbox iconsbg"></i>Configuration</h2>
                    </div>
                </div>
                <div class="col-md-6">
				<div class="iconsmenu">
                        <ul class="ms-auto">
                            
                            <div style="clear:both;"></div>
                        </ul>
                    </div>
                     <a href="/user-list"></a>
                </div>
            </div>
         
					<div class="width50">
					@if(!empty($source) && $source->count() > 0)
					<form action="{{ route('socialPlatform') }}" method="post">
					@csrf
					@foreach($source as $key => $config)	
                    <div class="form-check p-0 mt-3">
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="status[]" id="twitter{{$key}}"
                             value="{{$config->value}}" {{ $config && $config->status == 1 ? 'checked' : '' }}>
                        <input type="hidden" name="id[]" value="{{$config->id}}">
                        <label class="form-check-label" for="twitter">{{$config->value}}</label>
                        </div>
                    </div>            
                    @endforeach
					
                    <div class="col-md-12 buttons_prime text-start">
				
                <input type="submit" class=" btn btn-danger dangeriline {{ addUIComponent('ADMINMANAGEMENT_CONFIGURATION_SUBMIT') }}" name="submit" />
				</div>
					</form>
					@endif
</div>
				
        </div>
    </div>
</div>
</div>

<script>
	$("#admin").addClass("active");
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "64px";
        document.getElementById("main").style.marginLeft = "64px";
    }

$(document).ready(function(){
	if( $("#mySidenav").hasClass("openbar"))
	{
		$(".bgfooter").addClass("toggle_footer");
	}
  $(".sidenav-menu").click(function(){
	$(".bgfooter").toggleClass("toggle_footer");
  });
});


</script>

@endsection