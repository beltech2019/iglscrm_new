@extends('auth.layouts')

@section('content')

@if($rules && $rules != "")
	<form method="POST" action="/addPostAssignRule/{{$rules->ruleid}}">
@else
	<?php $rules  = false;?>
	<form method="POST" action="/addPostAssignRule">
@endif 
<form action="/addPostAssignRule" method="POST">
    @csrf
    <div class="container-fluid">
        <div class="div_container">
            <div class="bgwhite2">
                <div class="borderalignheading">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="heading_two">
                                <h2><i class="bi bi-ticket iconsbg2"></i>Post Rule</h2>
                            </div>
                        </div>
                       
                    </div>
                </div>
                <div class="mainrow">
                   
                      
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="keyword" class="form-label">Keywords <span class="starred">*</span></label>
                                <textarea name="keyword" class="form-control" id="keyword"
                                    placeholder="Keywords" rows="3" required>{{$rules?$rules->keyword:old('keyword')}}</textarea>
                            </div>
                        </div>
                       
						<div class="col-md-8">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" class="form-control" id="category"  required>
									<option value="Feedback Positive" {{$rules && $rules->category=='Feedback Positive'?'selected':''}} >Feedback Positive</option>
									<option value="Feedback Negative"  {{$rules && $rules->category=='Feedback Negative'?'selected':''}} >Feedback Negative</option>
									<option value="Complaint"  {{$rules && $rules->category=='Complaint'?'selected':''}} >Complaint</option>
									<option value="Query"  {{$rules && $rules->category=='Query'?'selected':''}} >Query</option>
									<option value="Information"  {{$rules && $rules->category=='Information'?'selected':''}} >Information</option>
									<option value="Spam"  {{$rules && $rules->category=='Spam'?'selected':''}} >Spam</option>
								</select>
                            </div>
                        </div>
                    
                    <div class="col-md-8">
                        <div class="radiocheckin mt-4">
                            <label for="" class="form-label">Type</label>
                            <div class="form-check p-0 mt-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type[]" id="post"
                                        value="post"  {{$rules && $rules->type=='post'?'checked':''}}>
                                    <label class="form-check-label" for="post"><i class="bi bi-chat-left-text"></i>
                                        Post</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type[]" id="ticket"
                                        value="ticket" {{$rules && $rules->type=='ticket'?'checked':''}}>
                                    <label class="form-check-label" for="ticket"><i class="bi bi-ticket"></i>
                                        Ticket</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type[]" id="lead"
                                        value="lead" {{$rules && $rules->type=='lead'?'checked':''}}>
                                    <label class="form-check-label" for="lead"><i class="bi bi-funnel"></i>
                                        Lead</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="radiocheckin mt-4">
                          <label for="" class="form-label">Status</label>
                            <div class="form-check p-0 mt-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="message"
                                        value="Active"  {{$rules && $rules->status=='Active'?'checked':''}}>
                                    <label class="form-check-label" for="message"><i class="bi bi-chat-left-text"></i>
                                    Active  </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="ticket"
                                        value="Inactive" {{$rules && $rules->status=='Inactive'?'checked':''}}>
                                    <label class="form-check-label" for="ticket"><i class="bi bi-ticket"></i>
                                    Inactive </label>
                                </div>
                             
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="buttons_prime">
                            <button type="submit" class="btn btn-danger {{ addUIComponent('ADMINMANAGEMENT_POST_RULE_SUBMIT') }}">Submit</button>
                            <a type="button" href="/postAssignRuleList" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>



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
</script>

@endsection