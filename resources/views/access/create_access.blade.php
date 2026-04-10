@extends('auth.layouts')

@section('content')
<style>
#accessbox .HIDDEN{
  display:block !important;
}
#accessbox .READ_ONLY{
  pointer-events: visible !important;
}
  </style>

    <div class="container-fluid" id="accessbox">
        <div class="div_container">
            <div class="bgwhite2">
            <form action="/createUpdateRoleAccess" method="POST">
              @csrf
                <div class="borderalignheading">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="heading_two">
                                <h2><i class="bi bi-ticket iconsbg2"></i>Access</h2>
                            </div>
                        </div>
                        <div class="col-md-4 hide">
                        <select name="role_id" id="role_id" class="form-select" aria-label="Default select example">
                          @if(!empty($role) && $role->count())
                            @foreach($role as $key => $roles)
                              <option value="{{$roles->role_id}}" {{$roles && $roles->role_id==$id?'selected':''}}>{{$roles->role_name}}</option>
                            @endforeach
                           @endif
                        </select>
                        </div>
                    </div>
                </div>
                <div class="busines_details">
                    <div class="moreinfo table-responsive">
                    <table class="table">
                        <thead>
                            <tr>   
                            <th scope="col">Component Name</th>
                            <th scope="col">Access @if(!empty($access) && count($access))
                          @foreach($access as $key => $accesss)   
                          <div class="form-check form-check-inline">
                             <input class="form-check-input" type="radio" name="access"  id="message-{{$key}}"
                                value="{{$key}}" >
                             <label class="form-check-label" for="message-{{$key}}">
                             {{$accesss}} </label>
                           </div>
                          @endforeach
                        @endif  </th>
                            <!-- <th scope="col">Assign To</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        @if(!empty($component) && $component->count())
                          @foreach($component as $key => $components)
                            <tr>    
                            <td>
                           {{ ucfirst(strtolower($components->component_type))}} -> {{$components->component_label}}     
                            </td>
                        <td>
                        <div class="col-md-8">
                        <div class="radiocheckin mt-1">
                        <div class="form-check p-0">        
                        @if(!empty($access) && count($access))
                          @foreach($access as $key => $accesss)   
                          <div class="form-check form-check-inline">
                             <input class="form-check-input allRadio {{$key}}" type="radio" name="{{$components->component_key}}" id="message-{{$components->component_key}}-{{$key}}"
                                value="{{$key}}" {{$components->access==$key?'checked':''}}>
                             <label class="form-check-label" for="message-{{$components->component_key}}-{{$key}}">
                             {{$accesss}} </label>
                           </div>
                          @endforeach
                        @endif  
                        </div>
                        </div>
                        </div>
                           </td>
                            </tr>  
                          @endforeach
                        @endif   
                        </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="buttons_prime">
                       <button type="submit" class="btn btn-danger {{ addUIComponent('ADMINMANAGEMENT_ACCESS_SAVE') }}">Save</button>
                       <a type="button" href="/accessRole" class="btn btn-secondary">Cancel</a>
                   </div>
                </div>
                </form>  
            </div>
        </div>
    </div>




<script>
$("#admin").addClass("active");

$("input[type=radio][name=access]").click(function()
{
	var access = $(this).val();
	$(".allRadio").prop('checked', false);
	$("."+access). prop('checked', true);
})


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