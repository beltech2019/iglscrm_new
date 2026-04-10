@extends('auth.layouts')

@section('content')
<style>
    .filter_popup{
        max-width: 724px !important;
    }
</style>

<div class="mt-3">
    <div class="div_container">
        <div class="bgwhite2">
            <div class="row mb-4">
                <div class="col-md-6 col-6">
                    <div class="heading_two ">
                        <h2><i class="bi bi-mailbox iconsbg"></i>Search </h2>
                    </div>
                </div>
            </div>
            <div class="my-2 row">

@if($search)
<div class="col-md-9">
{!! $search->withQueryString()->links() !!}
 </div>
 <div class="col-md-3">
 <p style="margin-top: 8px;text-align: right;" class="pagination-info">
    Showing {{ $search->firstItem() }} - {{ $search->lastItem() }} of {{ $search->total() }} search item
 </p>
</div>
@endif
</div>
            <div class="">
            <table class="table">
                <thead>
                    <tr class="table_width_align">
                    <th scope="col">ID</th>
                    <th scope="col">Message</th>
                    <th scope="col">Name</th>
                    <th scope="col">Social User</th>
                    <th scope="col">Type</th>
                    </tr>
                </thead>
                <tbody>
				<!-- <pre>
					@php
					
					$fields = [];
					@endphp
				</pre> -->
					 @if($search)
                    @foreach($search as $key => $searchdata)
                    <tr>
                        <td>@if($searchdata->type =='post')<a href="/getSocialPostById/{{$searchdata->getTweet_id}}">{{$searchdata->getTweet_id}}</a>
						@elseif($searchdata->type =='ticket')<a href="/getSocialTicketById/{{$searchdata->id}}">{{$searchdata->getTweet_id}}</a>
						@else <a href="/getLeadById/{{$searchdata->id}}">{{$searchdata->getTweet_id}}</a>@endif</td>
                        <td>{{$searchdata->postMessage}}</td>
                        <td>{{$searchdata->name}}</td>
                        <td>{{$searchdata->socialUser}}</td>
                        <td>{{$searchdata->type}}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
			</div>
            <div class="my-2 row">

                @if($search)
                <div class="col-md-9">
                {!! $search->withQueryString()->links() !!}
                 </div>
                 <div class="col-md-3">
                 <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                    Showing {{ $search->firstItem() }} - {{ $search->lastItem() }} of {{ $search->total() }} search item
                 </p>
                </div>
				@endif
            </div>
        </div>
    </div>
</div>

 

    <script>
	$("#dashboard").addClass("active");
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "64px";
        document.getElementById("main").style.marginLeft = "64px";
    }
    </script>
    <script>
    var deleteId ="";
    $(document).ready(function() {
        $(document).on ('click',".hideBox,.display",function() {
			if($(this).hasClass('display'))
			{
				var id = $(this).find('input').val();
				var text = $(this).find('button').text();
				$(this).remove();
				$("#hiddenvalue").prepend('<li class="hideBox"> <input type="hidden" name="columnHide[]" value="'+id+'" /> <button class="custombtn2 " type="button">'+text+'</button></li>' );
			}
			else{
				var id = $(this).find('input').val();
				var text = $(this).find('button').text();
				$(this).remove();
				$("#displayedvalue").append('<li class="display"> <input type="hidden" name="column[]" value="'+id+'" /> <button class="custombtn" type="button">'+text+'</button></li>' );
			}
        });

        $(".deleteCheck").click(function() {
			var id = $(this).val();
            if ($(this).prop('checked')==true){ 
				$("#deleteFormDiv").append("<input type='hidden' name='postid[]' value='"+id+"' id='"+id+"' />" );
			}
			else{
				$("#deleteFormDiv #"+id).remove();
			}
        });
		
		
		
		$("#deleteBtn").click(function() {
			
            if ($("#deleteFormDiv input").length > 0){ 
				$("#commonDelete").modal("show");
				$("#commonModal").modal("hide");
				$("#commonBtn").hide();
			}
			else{
				$("#msg").text("Please select row");
				$("#commonBtn").hide();
				$("#commonModal").modal("show");
			}
        });
		
		$(".deleteLink").click(function() {
			deleteId = $(this).attr('id');
			$("#commonModal").modal("show");
			$("#msg").text("Are you sure delete it.");
        });
		
		$("#commonBtn").click(function() {
			location.href= "/deletePost/"+deleteId;
        });
		
		
    });
	</script>
	@php
	$count = 0;
	@endphp
	@if(!empty($postColumn) && $postColumn->count())
	@foreach($postColumn as $key => $column)
	@if($column->is_show == 0)
		<script> $("table tr td").eq({{$count}}).remove();</script>
	@endif
		@php
	$count ++;	@endphp

		@endforeach
	@endif

    @endsection