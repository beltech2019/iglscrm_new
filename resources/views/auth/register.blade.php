@extends('auth.layouts')

@section('content')

<div class="row">
    <div class="col-md-8">

        <div class="bgwhite2">
        
            <div class="heading_two mb-4">
                        <h2><i class="bi bi-ticket iconsbg2"></i>Register</h2>
                    </div>
            <div class="">
                <form action="{{ route('store',$user?$user->id:'') }}" method="post">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="name" class=" form-label">Name <span class="starred">*</span></label>
                                <div class="">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ $user?$user->name:old('name') }}" required>
                                    @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="email" class="form-label">Email
                                    Address <span class="starred">*</span></label>
                                <div class="">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ $user?$user->email:old('email') }}" required>
                                    @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="password"
                                    class=" form-label">Password <span class="starred">*</span></label>
                                <div class="">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" value="{{$user?'*********':''}}" required>
                                    @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="password_confirmation"
                                    class=" form-label">Confirm Password <span class="starred">*</span></label>
                                <div class="">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required value="{{$user?'*********':''}}">
                                </div>
                            </div>
                            @guest
                            @php

                            $roleInfo = "";
                            @endphp

                            @else
                            @php

                            $loggedUser = \Auth::user();
                            $roleInfo = \App\Models\Role::get();

                            @endphp

                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="role" class=" form-label">Role</label>
                                <div class="">
                                    <select class="form-control" id="role" name="role" required>
                                        @foreach($roleInfo as $role)
                                        @if(loggedUserRole() == 'SUPER ADMIN' || $role->role_name != 'Super Admin')
                                        <option value="{{$role->role_id}}"
                                            {{$user && $role->role_id == $user->role? 'selected':''}}>
                                            {{$role->role_name}}</option>
                                        @endif    
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="department" class=" form-label">Department</label>
                                <div class="">
                                    <select class="form-control" id="department" name="department" required>
                                        @foreach($department as $depart)
                                        <option value="{{$depart->department_id}}"
                                            {{$user && $depart->department_id == $user->department? 'selected':''}}>
                                            {{$depart->department_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="region"
                                    class=" form-label">Region <span class="starred">*</span></label>
                                <div class="">
                                    <input type="text" class="form-control @error('region') is-invalid @enderror"
                                        id="region" name="region" value="{{$user?$user->region:''}}" required>
                                    @if ($errors->has('region'))
                                    <span class="text-danger">{{ $errors->first('region') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
						
                        <div class="col-md-6">
                        <div class="mb-3 row">
                        <label for="status" class=" form-label">Status <span class="starred">*</span></label>
                        <div class="">
                            <select class="form-control" id="status" name="status" required>
                                <option value=""></option>
                                <option value="Active" {{$user && $user->status == 'Active'? 'selected':''}}>Active
                                </option>
                                <option value="Inactive" {{$user && $user->status == 'Inactive'? 'selected':''}}>
                                    Inactive</option>
                            </select>
                        </div>
                    </div>
                        </div>
                        <div class="col-md-12">
                        <div class="alignbuttonmain">
                        <input type="submit" class="btn btn-danger {{ addUIComponent('ADMINMANAGEMENT_USERS_SAVE') }}" value="Save" style="margin:0;">
                        <a type="button" href="/user-list" class="btn btn-secondary">Cancel</a>
                    </div>
                        </div>
                      
                    </div>





                 
                 

                </form>
            </div>
        </div>
    </div>
</div>
<script>

  
$("#admin").addClass("active");
var password = document.getElementById("password"),
    confirm_password = document.getElementById("password_confirmation");

function validatePassword() {
    if (password.value != confirm_password.value) {
        confirm_password.setCustomValidity("Passwords Don't Match");
    } else {
        confirm_password.setCustomValidity('');
    }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
</script>
<script>
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