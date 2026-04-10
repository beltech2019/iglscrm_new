@extends('auth.layouts')

@section('content')

@if($rules && $rules != "")
	<form method="POST" action="/addUserAssignRule/{{$rules->id}}">
@else
	<?php $rules  = false;?>
	<form method="POST" action="/addUserAssignRule">
@endif 
<form action="/addUserAssignRule" method="POST">
    @csrf
    <div class="container-fluid">
        <div class="div_container">
            <div class="bgwhite2">
                <div class="borderalignheading">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="heading_two">
                                <h2><i class="bi bi-ticket iconsbg2"></i>Business Rule</h2>
                            </div>
                        </div>
                       
                    </div>
                </div>
                <div class="mainrow">
                    <div class="row">
                      <div class="col-md-5">
                        <div class="dastpicker">
                                                                        <div class="row">
                                                <div class="col-md-6 col-5">
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">From <span class="starred">*</span></label>
                                                        <input name="from_date" value="{{$rules?$rules->from_date:''}}" type="date" class="form-control" id="from_date" aria-describedby="emailHelp" placeholder="From date" required>

                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-5">
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">To <span class="starred">*</span></label>
                                                        <input type="date" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="to_date" value="{{$rules?$rules->to_date:''}}" placeholder="To date" required>

                                                    </div>
                                                </div>
                                            
                                            </div>
                        </div>
                      </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Keywords <span class="starred">*</span></label>
                                <textarea name="Keyword" class="form-control" id="exampleFormControlTextarea1"
                                    placeholder="Keywords" rows="3" required>{{$rules?$rules->Keyword:''}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="getTweet_id" class="form-label">Users</label>
                            <select name="user_id" class="form-select" aria-label="Default select example">
                                @if(!empty($user) && $user->count())
                                @foreach($user as $key => $getUsers)
                                <option value="{{$getUsers->id}}" {{$rules && $rules->user_id==$getUsers->id?'selected':''}}>{{$getUsers->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                    </div>
                    <div class="col-md-8">
                        <div class="radiocheckin mt-4">
                            <label for="social_type" class="form-label">Platform</label>
                            <div class="form-check p-0 mt-3">
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="social_type[]" id="twitter"
                                 value="twitter" {{ ($rules && in_array('twitter', $socialtype)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="twitter"><i class="bi bi-twitter"></i>
                                        Twitter</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="social_type[]" id="facebook"
                                        value="facebook" {{ ($rules && in_array('facebook', $socialtype)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="facebook"><i class="bi bi-facebook"></i>
                                        Facebook</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="social_type[]" id="linkedin"
                                        value="linkedin" {{ ($rules && in_array('linkedin', $socialtype)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="linkedin"><i class="bi bi-linkedin"></i>
                                        LinkedIn</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="social_type[]" id="whatsapp"
                                        value="whatsapp" {{ ($rules && in_array('whatsapp', $socialtype)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="whatsapp"><i class="bi bi-whatsapp"></i>
                                        WhatsApp</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="social_type[]" id="instagram"
                                        value="instagram" {{ ($rules && in_array('instagram', $socialtype)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="instagram"><i class="bi bi-instagram"></i>
                                        Instagram</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="radiocheckin mt-4">
                            <label for="assign_type" class="form-label">Type</label>
                            <div class="form-check p-0 mt-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="assign_type" id="message"
                                        value="message"  {{$rules && $rules->assign_type=='message'?'checked':''}}>
                                    <label class="form-check-label" for="message"><i class="bi bi-chat-left-text"></i>
                                        Message</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="assign_type" id="ticket"
                                        value="ticket" {{$rules && $rules->assign_type=='ticket'?'checked':''}}>
                                    <label class="form-check-label" for="ticket"><i class="bi bi-ticket"></i>
                                        Ticket</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="assign_type" id="lead"
                                        value="lead" {{$rules && $rules->assign_type=='lead'?'checked':''}}>
                                    <label class="form-check-label" for="lead"><i class="bi bi-funnel"></i>
                                        Lead</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="radiocheckin mt-4">
                         
                            <div class="form-check p-0 mt-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="enable" id="message"
                                        value="1"  {{$rules && $rules->enable=='1'?'checked':''}}>
                                    <label class="form-check-label" for="message"><i class="bi bi-chat-left-text"></i>
                                    Active  </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="enable" id="ticket"
                                        value="0" {{$rules && $rules->enable=='0'?'checked':''}}>
                                    <label class="form-check-label" for="ticket"><i class="bi bi-ticket"></i>
                                    Inactive </label>
                                </div>
                             
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="buttons_prime">
                            <button type="submit" class="btn btn-danger {{ addUIComponent('ADMINMANAGEMENT_BUSINESS_RULE_SUBMIT') }}">Submit</button>
                            <a type="button" href="/userAssignRuleList" class="btn btn-secondary">Cancel</a>
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