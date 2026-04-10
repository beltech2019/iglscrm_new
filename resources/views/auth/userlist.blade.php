@extends('auth.layouts')

@section('content')




<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="heading_two ">
                        <h2><i class="bi bi-mailbox iconsbg"></i>User List </h2>
                    </div>
                </div>
                <div class="col-md-6">
				<div class="iconsmenu">
                        <ul class="ms-auto">
                            
                            <li><a href="\register" class="{{ addUIComponent('ADMINMANAGEMENT_CREATE_REGISTER') }}"><i class="bi bi-plus-lg"></i></a></li>
                            <div style="clear:both;"></div>
                        </ul>
                    </div>
                     <a href="/user-list"></a>
                </div>
            </div>
            <table class="table {{ addUIComponent('ADMINMANAGEMENT_USER_LIST_TABLE') }}">
                <thead>
                    <tr>
                        <th scope="col">S.No.</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
					@if(!empty($users) && $users->count())
                    <?php $i = 1;?>
					@foreach($users as $key => $user)
                    <tr>
      
                        
                        <td><?php echo $i ?></td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->role_name}}</td>
                        <td>{{$user->status}}</td>
                        <td>{{$user->created_at}}</td>
                        <td>
                           <div class="editer_file">
                                <div class="dropdown">
                                    <button class="settingicons" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear"></i>
                                    </button>
									
                                    <ul class="dropdown-menu dropdownmenu_innner leftdropdown"
                                        aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item  {{ addUIComponent('ADMINMANAGEMENT_EDIT_REGISTER') }}" href="/register/{{$user->id}}"><i
                                                    class="bi bi-pencil"></i> Edit</a></li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </td>
                   
                    </tr>
                  <?php $i++;?>
                    @endforeach
					@endif
                </tbody>
            </table>
				<div class="my-2 d-flex ">
				
				 {!! $users->links() !!}
				</div>
        </div>
    </div>
</div>
</div>

<script>
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
<script>
	$("#admin").addClass("active");
    
    </script>
@endsection