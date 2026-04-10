@extends('auth.layouts')

@section('content')
<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2 widthsmall">
        @if($template && $template != "")
			<form method="POST" action="/storeTemplate/{{$template->id}}">
		@else
			<?php $template  = false;?>
			<form method="POST" action="/storeTemplate">
		@endif 
        @csrf
        <div class="form-group">
                <label for="template_name">Template Name</label>
                <input type="text" name="template_name" value="{{$template?$template->template_name:''}}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="template_content">Template Content</label>
                <textarea name="template_content" class="form-control" rows="5" required>{{$template?$template->template_content:''}}</textarea>
            </div>

            <!-- <div class="form-group">
                <label for="template_code">Template Code</label>
                <textarea name="template_code" class="form-control" rows="10" required>{{$template?$template->template_code:''}}</textarea>
            </div> -->
            <br>
            <div class="buttons_prime">
            <button type="submit" class="btn btn-danger" id="sv">Submit</button>
            <a type="button" href="/getTemplateList" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
<script>
    
   $("#admin").addClass("active");
   </script>
    @endsection