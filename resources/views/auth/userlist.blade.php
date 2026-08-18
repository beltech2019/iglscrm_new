@extends('auth.layouts')

@section('content')




<div class="container-fluid">
    <div class="div_container">
        <div class="ig-page-header">
            <div>
                <span class="ig-eyebrow-light">Administration</span>
                <h1><i class="bi bi-people"></i> User List</h1>
                <p>Everyone with access to this workspace.</p>
            </div>
            <a href="\register" class="btn btn-danger ig-btn-add {{ addUIComponent('ADMINMANAGEMENT_CREATE_REGISTER') }}"><i class="bi bi-plus-lg"></i> New User</a>
        </div>
        <div class="bgwhite2 ig-panel">
            <div class="table-responsive">
            <table class="table ig-table {{ addUIComponent('ADMINMANAGEMENT_USER_LIST_TABLE') }}" data-ig-tabletools>
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
                        <td><span class="ig-badge ig-badge-{{ \Illuminate\Support\Str::slug($user->status ?? '') }}">{{$user->status}}</span></td>
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
					@else
					<tr class="ig-empty-row">
						<td colspan="7">
							<div class="ig-empty-state">
								<i class="bi bi-people"></i>
								<h6>No users found</h6>
							</div>
						</td>
					</tr>
					@endif
                </tbody>
            </table>
            </div>
				<div class="my-2 d-flex ig-table-toolbar">
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