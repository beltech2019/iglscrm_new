@extends('auth.layouts')

@section('content')
<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2 widthsmall">
            <div class="formscoulam">
			@if($postData && $postData != "")
				<form method="POST" action="/manualAddSocailPost/{{$postData->id}}">
			@else
				<?php $postData  = false;?>
				<form method="POST" action="/manualAddSocailPost">
			@endif
                
                    @csrf
                    <div class="row">
                    <div class="col-md-12">
                    <div class="heading_two ">
                        <h2><i class="bi bi-ticket iconsbg2"></i>Create User</h2>
                    </div>
                </div>
             
            </div>
           <div class="formscoulam">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Consumer Key</label>
                        <input class="form-control" placeholder="Consumer Key " id="consumer_key" name="consumer_key">
                                    
                              </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="consumer_secret" class="form-label">Consumer Secret</label>
                        <input type="text" class="form-control" id="consumer_secret" name="consumer_secret" placeholder="Consumer Secret " value="{{$postData?$postData->consumer_secret:''}}">
                    </div>
                </div>
              
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="user_access" class="form-label">User Access Token</label>
                        <input type="text" class="form-control" id="user_access" name="user_access" placeholder="User Access Token" value="{{$postData?$postData->user_access:''}}" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="user_token" class="form-label">User Token Secret</label>
                        <input type="text" class="form-control" id="user_token" name="user_token" placeholder="User Token"  value="{{$postData?$postData->user_token:''}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="bearer_token" class="form-label">Bearer Token</label>
                        <input type="text" class="form-control" id="bearer_token" name="bearer_token" placeholder="Bearer Token" value="{{$postData?$postData->bearer_token:''}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="user_post" class="form-label">User Post Days</label>
                        <div style="clear:both;"></div>
                        <input type="text" class="form-control controlicons" name="user_post" id="user_post" placeholder="User Post" value="{{$postData?$postData->user_post:''}}">
                        
                        <!-- <div class="socialicons">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bi bi-hand-index-thumb"></i></a>
                        <i class="bi bi-trash3"></i>
                        <div style="clear:both;"></div>
                        </div> -->
                   
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="userAccounnt_no" class="form-label">User Accounnt No</label>
                        <input type="number" class="form-control" id="userAccounnt_no" name="userAccounnt_no" placeholder="UserAccounntNo" value="{{$postData?$postData->userAccounnt_no:''}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="Twitter_id" class="form-label">Twitter Id</label>
                        <input type="number" class="form-control" id="Twitter_id" name="Twitter_id" placeholder="TwitterId" value="{{$postData?$postData->Twitter_id:''}}">
                    </div>
                </div>
            </div>
        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="buttons_prime">
                            <button type="submit" class="btn btn-danger">Save</button>
                            <a type="button" href="/dashboard" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
  

 




 




     