@extends('auth.layouts')

@section('content')
<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2 widthsmall">
        @if($leadData && $leadData != "")
			<form method="POST" action="/createUpdateLead/{{$leadData->id}}" enctype="multipart/form-data">
		@else
			<?php $leadData  = false;?>
			<form method="POST" action="/createUpdateLead" enctype="multipart/form-data">
		@endif 
        @csrf
            <div class="formscoulam">

                <div class="row">
                    <div class="col-md-12">
                        <div class="heading_two ">
                            <h2><i class="bi bi-ticket iconsbg2"></i>{{$create_lead}}</h2>
                        </div>
                    </div>

                </div>
                <div class="formscoulam">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-3 {{$create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                <label for="" class="form-label"></label>   

                                    <select class="form-select" aria-label="Default select example" name="greeting_first_name"
                                    id="greeting_first_name" required>
										<option value="Mr." {{$leadData && $leadData->greeting_first_name=='Mr.'?'selected':''}}>Mr.</option>
										<option value="Ms." {{$leadData && $leadData->greeting_first_name=='Ms.'?'selected':''}}>Ms.</option>
										<option value="Mrs." {{$leadData && $leadData->greeting_first_name=='Mrs.'?'selected':''}}>Mrs.</option>
										<option value="Miss" {{$leadData && $leadData->greeting_first_name=='Miss.'?'selected':''}}>Miss</option>
										<option value="Dr." {{$leadData && $leadData->greeting_first_name=='Dr.'?'selected':''}}>Dr.</option>
										<option value="Prof." {{$leadData && $leadData->greeting_first_name=='Prof.'?'selected':''}}>Prof.</option>
									</select>
                                    </div>
                                    <div class="col-md-9">
                                    <div class="mb-3 {{$create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">         
                                <label for="greeting_first_name" class="form-label">First Name <span class="starred">*</span></label>   
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                    placeholder="First Name" value="{{$leadData?$leadData->first_name:''}}" {{$create_lead=='Create Lead'?'required':'readonly'}}>
                                    </div>
                                    </div>
                                </div>
                              

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                <label for="last_name" class="form-label">Last Name <span class="starred">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    placeholder="Last Name" value="{{$leadData?$leadData->last_name:''}}" {{$create_lead=='Create Lead'?'required':'readonly'}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                <label for="socialUser_id" class="form-label">User Id <span class="starred">*</span></label>
                                <input type="text" class="form-control" id="socialUser_id" name="socialUser_id"
                                    placeholder="User Id" value="{{$leadData?$leadData->socialUser_id:''}}" {{$create_lead=='Create Lead'?'required':'readonly'}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" aria-label="Default select example" name="type" id="type" required>
                                    <option value="Hot" {{$leadData && $leadData->type=='Hot'?'selected':''}}>Hot</option>
                                    <option value="Warm" {{$leadData && $leadData->type=='Warm'?'selected':''}}>Warm</option>
                                    <option value="Cold" {{$leadData && $leadData->type=='Cold'?'selected':''}}>Cold</option>
                              
                                </select>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                <label for="office_phone" class="form-label">Office Phone</label>
                                <input type="number" class="form-control" id="office_phone" name="office_phone"
                                    placeholder="Office Phone" value="{{$leadData?$leadData->office_phone:''}}" >
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                <label for="title" class="form-label">Title <span class="starred">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{$leadData?$leadData->title:''}}" required>
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input type="number" class="form-control" id="mobile" name="mobile"
                                    placeholder="Mobile" value="{{$leadData?$leadData->mobile:''}}" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="department" name="department"
                                    placeholder="department" value="{{$leadData?$leadData->department:''}}" >
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                    placeholder="Customer Name" value="{{$leadData?$leadData->customer_name:''}}" >
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                <label for="website" class="form-label">Website</label>
                                <input type="text" class="form-control" id="website" name="website"
                                    placeholder="Website" value="{{$leadData?$leadData->website:''}}" >
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                <label for="status" class="form-label">Status <span class="starred">*</span></label>
                                <select class="form-select" aria-label="Default select example" name="status"
                                    id="status" required>
                                    <option value="New" {{$leadData && $leadData->status=='New'?'selected':''}}>New</option>
                                    <option value="Assigned" {{$leadData && $leadData->status=='Assigned'?'selected':''}}>Assigned</option>
                                    <option value="In Process" {{$leadData && $leadData->status=='In Process'?'selected':''}}>In Process</option>
                                    <option value="Converted" {{$leadData && $leadData->status=='Converted'?'selected':''}}>Converted</option>
                                    <option value="Recycled" {{$leadData && $leadData->status=='Recycled'?'selected':''}}>Recycled</option>
                                    <option value="Dead" {{$leadData && $leadData->status=='Dead'?'selected':''}}>Dead</option>
                                    <option value="Duplicate" {{$leadData && $leadData->status=='Duplicate'?'selected':''}}>Duplicate</option>
                                   
                                </select>

                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                <label for="postDate" class="form-label">Approval Status</label>
                                <select class="form-select" aria-label="Default select example" name="approval_status"
                                    id="approval_status" >
									 <option value="" ></option>

                                    <option value="In Review" {{$leadData && $leadData->approval_status=='In Review'?'selected':''}}>In Review</option>
									
                                    <option value="Qualified" {{$leadData && $leadData->approval_status=='Qualified'?'selected':''}}>Qualified</option>
                                    <option value="Not Qualified" {{$leadData && $leadData->approval_status=='Not Qualified'?'selected':''}}>Not Qualified</option>
        
                                </select>

                            </div>
                        </div>
                        <div class=" address_neeew">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="address_primary">

                                        <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                            <label for="exampleFormControlInput1" class="form-label">Primary Address</label>
                                            <textarea class="form-control" id="primary_address" name="primary_address"
                                                rows="3" placeholder="Primary Address" >{{$leadData?$leadData->primary_address:''}}</textarea>
                                        </div>

                                        <div class=" row">
                                            <div class=" col-md-6">
                                                <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                                    <label for="primary_city" class="form-label">City
                                                    </label>
                                                    <input type="text" class="form-control" id="primary_city" name="primary_city"
                                                        placeholder="City" value="{{$leadData?$leadData->primary_city:''}}" >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class=" mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                                    <label for="primary_state" class="form-label">State </label>
                                                    <input type="text" class="form-control" id="primary_state" name="primary_state"
                                                        placeholder="State" value="{{$leadData?$leadData->primary_state:''}}" >
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                                <div class=" mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                                    <label for="primary_postal_code" class="form-label">Postal   Code</label>
                                                    <input type="text" class="form-control" id="primary_postal_code"
                                                        name="primary_postal_code" placeholder="Postal
                                                            Code" value="{{$leadData?$leadData->primary_postal_code:''}}" >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class=" mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                                    <label for="postDate" class="form-label">Country</label>
                                                    <input type="text" class="form-control" id="primary_country" name="primary_country"
                                                        placeholder="Country" value="{{$leadData?$leadData->primary_country:''}}" >
                                                </div>
                                            </div>

                                        </div>





                                    </div>
                                </div>
                                <div class=" col-md-6">
                                    <div class="address_primary">

                                        <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                            <label for="exampleFormControlInput1" class="form-label">Other
                                                Address</label>
                                            <textarea class="form-control" id="other_address" name="other_address"
                                                rows="3" placeholder="Other Address">{{$leadData?$leadData->other_address:''}}</textarea>
                                        </div>
                                        <div class=" row">
                                            <div class=" col-md-6">
                                                <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                                    <label for="other_city" class="form-label">City
                                                    </label>
                                                    <input type="text" class="form-control" id="other_city" name="other_city"
                                                        placeholder="City" value="{{$leadData?$leadData->other_city:''}}" >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class=" mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                                    <label for="other_state" class="form-label">State</label>
                                                    <input type="text" class="form-control" id="other_state" name="other_state"
                                                        placeholder="State" value="{{$leadData?$leadData->other_state:''}}" >
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                                <div class=" mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                                    <label for="other_postal_code" class="form-label">Postal
                                                        Code</label>
                                                    <input type="text" class="form-control" id="other_postal_code"
                                                        name="other_postal_code" placeholder="Postal
                                                            Code" value="{{$leadData?$leadData->other_postal_code:''}}" >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class=" mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                                    <label for="other_country" class="form-label">Country</label>
                                                    <input type="text" class="form-control" id="other_country" name="other_country"
                                                        placeholder="Country" value="{{$leadData?$leadData->other_country:''}}" >
                                                </div>
                                            </div>

                                        </div>
                                        <div class=" form-check {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                                            <input class="form-check-input" type="checkbox" value="" id="copy_address">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Copy Address from left
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
       

            <div class=" col-md-4">
                <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                    <label for="email_address" class="form-label">Email Address</label>
                    <div style="clear:both;"></div>
                    <input type="email" class="form-control controlicons" name="email_address" id="email_address"
                        placeholder="Email Address" value="{{$leadData?$leadData->email_address:''}}" >

                    <!--<div class="socialicons">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bi bi-dash"></i></a>
                        <i class="bi bi-gear"></i>
                        <div style="clear:both;"></div>
                    </div>
					-->
					<div style="clear:both;"></div>
                </div>
            </div>
            <div class=" form-check mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                <input class="form-check-input" type="checkbox" value="1" id="converted" name="converted">
                <label class="form-check-label" for="flexCheckDefault">
                    Converted
                </label>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                        <label for="description" class="form-label">Description <span class="starred">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                            placeholder="Description" required>{{$leadData?$leadData->description:''}}</textarea>
                    </div>
                </div>
                <div class="col-md-12">

                    <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                        <label for="resolution" class="form-label">Resolution</label>
                        <textarea class="form-control" id="resolution" name="resolution" rows="3"
                            placeholder="Resolution">{{$leadData?$leadData->resolution:''}}</textarea>
                    </div>
                </div>
                <div class=" col-md-6">
                    <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                        <label for="fax" class="form-label">Fax</label>
                        <input type="text" class="form-control" id="fax" name="fax"
                         placeholder="Fax" value="{{$leadData?$leadData->fax:''}}" >
                    </div>
                </div>
                <div class=" col-md-6">
                    <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                        <label for="bp_number" class="form-label">BP Number</label>
                        <input type="text" class="form-control" id="bp_number" name="bp_number"
                         placeholder="BP Number" value="{{$leadData?$leadData->bp_number:''}}" >
                    </div>
                </div>
				<!--
                <div class=" col-md-6">
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Partner Contact</label>
                        <div style="clear:both;"></div>
                        <input type="text" class="form-control controlicons" name="partner_contacts" id="partner_contacts"
                            placeholder="Partner Contact" value="{{$leadData?$leadData->partner_contacts:''}}" >

                        <div class="socialicons">
                            <a herf="#" data-bs-toggle="" data-bs-target=""><i
                                    class="bi bi-hand-index-thumb"></i></a>
                            <i class="bi bi-trash3"></i>
                            <div style="clear:both;"></div>
                        </div>

                    </div>
                </div>
				-->
            </div>
        </div>
        <div class="bgwhite2">
            <div class="heading_two ">
                <h2><i class="bi bi-ticket iconsbg2"></i>More Information </h2>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="mb-3 {{ $create_lead=='Create Lead'?:addUIComponent('SOCIALTICKET_EDIT_LEAD_EXCEPT_CHANGE_USER') }}">
                        <label for="lead_source" class="form-label">Lead Source</label>
                        <select class="form-select {{$leadData? 'readonly':''}}" aria-label="Default select example" name="lead_source"
                            id="lead_source">
							<option value="Other" {{$leadData && $leadData->lead_source=='Other'?'selected':''}}>Other</option>
                              @foreach(getSource() as $key => $getSources)
                                <option value="{{$getSources->value}}" {{$leadData && $leadData->lead_source==$getSources->value?'selected':''}}>{{$getSources->value}}</option>
                              @endforeach
                            <option value="Portal"{{$leadData && $leadData->lead_source=='Portal'?'selected':''}}>Portal</option>
                            <option value="Call"{{$leadData && $leadData->lead_source=='Call'?'selected':''}}>Call</option>
                            <option value="Inbounced Email"{{$leadData && $leadData->lead_source=='Inbounced Email'?'selected':''}}>Inbounced Email</option>
                        </select>

                    </div>
                </div>
                <div class=" col-md-6">
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assigned to</label>
                        <div style="clear:both;"></div>
                        <select class="form-select" aria-label="Default select example" name="assigned_to"
                            id="assigned_to">
                            @if(!empty($getUser) && $getUser->count())
                                @foreach($getUser as $key => $getUsers)
                                   <option value="{{$getUsers->id}}" {{$leadData && $leadData->assigned_to==$getUsers->id?'selected':''}}>{{$getUsers->name}}</option>
                                @endforeach
                            @endif      
                        </select>

                    </div>
                </div>
                <div class="col-md-6">
                <div class="">
                <label for="socialUser_name" class="form-label">Upload Document <span  class="starred">*</span></label>
                  <input class="form-control" type="file" id="media" name="media" accept=".pdf, .jpg, .jpeg, .xlsx, .mp4, .docx, .png, .doc">
                </div>
                
                @if(count($attacheddata)>0)
                <div class="documentType coulams_form">

                  <ul>
                  @foreach($attacheddata as $attacheddatas)
                   <?php $filename = pathinfo($attacheddatas->fileName)['basename']; ?>
                      <li>{{$filename}} <a href="/deleteattachmentfromLead/{{$leadData?$leadData->id:''}}"><i class="bi bi-x-lg"></i></a></li>
                  @endforeach    
                  </ul>
                </div>
                @endif 
                </div>

                <div class=" col-md-12">
                    <div class="buttons_prime">
                    @if($leadData && $leadData != "")    
                       <a type="button" href="/tweetLogList/{{$leadData->id}}" class="btn btn-danger {{ addUIComponent('LEAD_VIEW_CHANGE_LOG') }}">View Change Log</a>
                    @else
                       <button type="button" class="btn btn-danger">View Change Log</button>
                    @endif     
                        <button type="submit" class="btn btn-danger {{ addUIComponent('LEAD_SAVE') }}">Save</button>
                        <a type="button" href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </form>
    </div>
</div>
</div>



<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog modalwidth">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Search social User
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="modalforms">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Name</label>
                                <input type="text" class="form-control" id="searchInput" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Assigned
                                    to</label>
                                <select class="form-control" placeholder="Source" id="searchSelect">
                                               <option value="" disabled selected>Select your option</option>
                                            @if(!empty($getUser) && $getUser->count())
                                              @foreach($getUser as $key => $getUsers)
                                                 <option value="{{$getUsers->name}}">{{$getUsers->name}}</option>
                                              @endforeach
                                            @endif    
                                            </select> 
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <button type="button" onclick="search()"
                                    class="btn btn-danger marginalign">Search</button>
                                <button type="button" class="btn btn-secondary marginalign">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="socialuser mt-4">
                    <div class="headingmain">
                        <h5>Social User</h5>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>

                                <th scope="col">Name</th>
                                <th scope="col">Assigned to
                                </th>
                                <th scope="col">Date Created
                                </th>
                            </tr>
                        </thead>
                        <!-- <tbody id="socialUserTable">
  @if(!empty($getSocialUser) && $getSocialUser->count())
   @foreach($getSocialUser as $key => $SocialUser)
    <tr>
      <td>{{$SocialUser->socialUser_name}}({{$SocialUser->socialUser_userName}})</td>
      <td>{{$SocialUser->assigned_to}}</td>
      <td>{{$SocialUser->postDate}}</td>
    </tr> 
    @endforeach
   @endif  
  </tbody> -->
                    </table>
                </div>
            </div>


            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
    <script>
    $("#lead").addClass("active");

    function openNav() {
        document.getElementById("mySidenav").style.width =
            "250px";
        document.getElementById("main").style.marginLeft =
            "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width =
            "64px";
        document.getElementById("main").style.marginLeft =
            "64px";
    }

    function search() {
        var input, select, filterInput, filterSelect, table,
            tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        select = document.getElementById("searchSelect");
        filterInput = input.value.toUpperCase();
        filterSelect = select.value.toUpperCase();
        table = document.getElementById("socialUserTable");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[
                0]; // Index 0 for the first column
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().includes(
                        filterInput) && txtValue
                    .toUpperCase().includes(filterSelect)) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const copyAddressCheckbox = document.getElementById("copy_address");
        const primaryAddressFields = {
            address: document.getElementById("primary_address"),
            city: document.getElementById("primary_city"),
            state: document.getElementById("primary_state"),
            postalCode: document.getElementById("primary_postal_code"),
            country: document.getElementById("primary_country"),
        };
        const otherAddressFields = {
            address: document.getElementById("other_address"),
            city: document.getElementById("other_city"),
            state: document.getElementById("other_state"),
            postalCode: document.getElementById("other_postal_code"),
            country: document.getElementById("other_country"),
        };

        function copyAddress() {
            if (copyAddressCheckbox.checked) {
                // Copy values from primary address to other address
                otherAddressFields.address.value = primaryAddressFields.address.value;
                otherAddressFields.city.value = primaryAddressFields.city.value;
                otherAddressFields.state.value = primaryAddressFields.state.value;
                otherAddressFields.postalCode.value = primaryAddressFields.postalCode.value;
                otherAddressFields.country.value = primaryAddressFields.country.value;
            } else {
                // Clear other address fields
                for (const field in otherAddressFields) {
                    otherAddressFields[field].value = "";
                }
            }
        }

        // Add event listener to the checkbox
        copyAddressCheckbox.addEventListener("change", copyAddress);

        // Initially copy the address if the checkbox is checked on page load
        copyAddress();
    });


    </script>

    @endsection