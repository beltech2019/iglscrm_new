@extends('auth.layouts')

@section('content')
    <div class="">
    <div class="bgwhite2 widthtall">
        <div class="row ">
            <div class="col-md-6">
                <div class="containsmain">
                <h5 class="">{{ __('Change Password') }}</h5>
                <div class="password_correcter">
                    <ul>
                      <li><i class="bi bi-check"></i> At Least 6 Characters</li>    
                      <li><i class="bi bi-check"></i> At Least One Uppercase letter (A...Z)</li>    
                      <li><i class="bi bi-check"></i> At Least One Lowercase letter (a...z)</li>    
                      <li><i class="bi bi-check"></i> At Least One Number (1...9)</li>    
   
                    <ul>
                </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="">
                

                    <form action="{{ route('update-password') }}" method="POST">
                        @csrf
                        <div class="">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @elseif (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="oldPasswordInput" class="form-label">Old Password</label>
                                <input name="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" id="oldPasswordInput"
                                    placeholder="Old Password" required>
                                @error('old_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="newPasswordInput" class="form-label">New Password</label>
                                <input name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" id="newPasswordInput"
                                    placeholder="New Password"  required pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,}$">
                                @error('new_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="confirmNewPasswordInput" class="form-label">Confirm New Password</label>
                                <input name="new_password_confirmation" type="password" class="form-control" id="confirmNewPasswordInput"
                                    placeholder="Confirm New Password" required>
                            </div>

                        </div>

                        <div class="">
                            <button class="btn btn-danger">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
 
    <script>
  document.getElementById('newPasswordInput').addEventListener('input', function () {
    var newPassword = document.getElementById('newPasswordInput').value;
    var confirmNewPassword = document.getElementById('confirmNewPasswordInput').value;
    var passwordMatch = newPassword === confirmNewPassword;
    
    document.getElementById('confirmNewPasswordInput').setCustomValidity(passwordMatch ? '' : 'Passwords do not match.');
  });

  document.getElementById('confirmNewPasswordInput').addEventListener('input', function () {
    var newPassword = document.getElementById('newPasswordInput').value;
    var confirmNewPassword = document.getElementById('confirmNewPasswordInput').value;
    var passwordMatch = newPassword === confirmNewPassword;
    
    document.getElementById('confirmNewPasswordInput').setCustomValidity(passwordMatch ? '' : 'Passwords do not match.');
  });
</script>
    
@endsection