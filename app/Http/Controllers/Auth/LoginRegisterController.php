<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Defaults;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\SocialPlatform;
use App\Models\AdminChanges;
use App\Models\Department;
use App\Models\Role;
use Exception;
use DB;
use Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Encryption\Encrypter; 

class LoginRegisterController extends Controller
{
    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except([
            'login', 'authenticate','password','changepassword'
        ]);
    }

    /**
     * Display a registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request,$id=null)
    {	
		try{
			$user = false;
			$department = Department::get();
			if($id)
			{
				$user = user::find($id);
			}
		} catch(Exception $e) {
			Log::debug($e->getMessage());
			return redirect()->back()->with('message',$e->getMessage());
       }
        return view('auth.register',compact(['user','department']));
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id=null)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users,email,'.$id,
            'password' => 'required|min:8|confirmed',
            'department' => 'required',
            'region' => 'required',
        ]);
		try{
		if($id)
		{
            $operation="update";
			$requestData = [
				'name' => $request->name,
				'email' => $request->email,
				'role' => $request->role,
				'status' => $request->status,
				'department' => $request->department,
				'region' => $request->region,
			];
			if($request->password !="*********")
			{
				$requestData['password'] = Hash::make($request->password);
			}
			$user = User::find($id);
            $oldVal=User::find($id);
			$user->update($requestData);
			$msg = "successfully updated!";
            $changes = $user->getChanges();
            if($changes){
                adminChange($oldVal,$changes,'users',$operation);
            }
		}
		else{
            $operation="create";
			$user=User::create([
				'name' => $request->name,
				'email' => $request->email,
				'role' => $request->role,
				'department' => $request->department,
				'region' => $request->region,
				'password' => Hash::make($request->password)
			]);
			$msg = "successfully registered!";
            adminChange("no",$user,'users',$operation);
		}

        $credentials = $request->only('email', 'password');
       // Auth::attempt($credentials);
       // $request->session()->regenerate();
        return redirect()->route('userList')->withSuccess($msg);
		} catch(Exception $e) {
           DB::rollback();
           Log::debug($e->getMessage());
		   return redirect()->back()->with('message',$e->getMessage());
       }
    }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
		try{
		$user = User::where('email',$request->email)->first();
		if($user && $user->status != 'Active')
		{
			throw new \Exception("User not active");
		}
        if(Auth::attempt($credentials))
        {
			Cookie::queue(Cookie::make('user_id', $user->id, '2400'));

			\Session::put('userId', $user->id);
            $request->session()->regenerate();
            return redirect()->route('countDashboard')
                ->withSuccess('You have successfully logged in!');
        }
		
		
		
		} catch(Exception $e) {
           DB::rollback();
           Log::debug($e->getMessage());
		   return back()->withErrors([
            'email' => $e->getMessage(),
        ])->onlyInput('email');
       }
	    return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records.',
        ])->onlyInput('email');

    } 
    
    /**
     * Display a dashboard to authenticated users.
     *
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
		try{
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
		} catch(Exception $e) {
           DB::rollback();
           Log::debug($e->getMessage());
		   return redirect()->back()->with('message',$e->getMessage());
       }
    }

	public function password(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
		try{
        $status = Password::sendResetLink($request->only('email'));
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with(['status' => __($status)]);
        }
		
		} catch(Exception $e) {
           DB::rollback();
           Log::debug($e->getMessage());
		   return redirect()->back()->with('message',$e->getMessage());
       }
        return back()->withErrors(['email' => __($status)]);
    }
    public function changepassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
		try{
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }
		} catch(Exception $e) {
           DB::rollback();
           Log::debug($e->getMessage());
		   return redirect()->back()->with('message',$e->getMessage());
       }
        return back()->withErrors(['email' => [__($status)]]);
    }
	
	public function updatePassword(Request $request)
	{
			# Validation
			$request->validate([
				'old_password' => 'required',
				'new_password' => 'required|confirmed',
			]);

			try{
			#Match The Old Password
			if(!Hash::check($request->old_password, auth()->user()->password)){
				return back()->with("error", "Old Password Doesn't match!");
			}


			#Update the new Password
			User::whereId(auth()->user()->id)->update([
				'password' => Hash::make($request->new_password)
			]);
			} catch(Exception $e) {
			   DB::rollback();
			   Log::debug($e->getMessage());
			   return redirect()->back()->with('message',$e->getMessage());
		   }

			return back()->with("status", "Password changed successfully!");
	}
	
	public function adminManagement(Request $request)
	{
		return view('auth.admin_management');
	}
	
	public function userList(Request $request)
	{
		try{
			$users = [];
            $currentUserRole = loggedUserRole();
            if ($currentUserRole === 'SUPER ADMIN') {
                $users = User::join('tb_roles','tb_roles.role_id','=','users.role')->select('*','users.created_at as created_at')->paginate(getValueByKey('PAGENATION_COUNT'));
            } else {
                $users = User::join('tb_roles', 'tb_roles.role_id', '=', 'users.role')
                ->select('*', 'users.created_at as created_at')
                ->where('tb_roles.role_name', '!=', 'Super Admin')
                ->paginate(getValueByKey('PAGENATION_COUNT'));
            }
			
		return view('auth.userlist', compact('users'));
		} catch(Exception $e) {
           DB::rollback();
           Log::debug($e->getMessage());
			return redirect()->back()->with('message',$e->getMessage());
       }
	}
	
	public function updateConfig(Request $request)
	{
		try{
			$loggedUserRole = loggedUserRole();
			if('POST' == $request->getMethod())
			{
				$index = 0;
				foreach($request->config as $config)
				{
					$info = Defaults::find($request->id[$index]);
                    $oldVal=Defaults::find($request->id[$index]);
					$info->update(["value"=>$config]);
					$index++;
                    $changes = $info->getChanges();
                    if($changes){
                        adminChange($oldVal,$changes,'tb_defaults','update');
                    }
				}
			}
			
    		if($loggedUserRole == 'SUPER ADMIN'){
                    $default = Defaults::where('type','user')->get();
    
                }else{
                    $default = Defaults::where('type','user')->whereNot('key','TABLES_NAME')->whereNot('key','SEARCH_COLUMN')->get();
                }
			
		return view('auth.config', compact('default'))->with('message','Updated configuration Succcessfullly');
		} catch(Exception $e) {
           DB::rollback();
           Log::debug($e->getMessage());
		   return redirect()->back()->with('message',$e->getMessage());
       }
	}

    
    public function socialPlatform(Request $request)
	{
		try{
			if('POST' == $request->getMethod())
			{
                $indexStatus = 0;
				foreach($request->id as $id)
                {
                    $info = SocialPlatform::find($id);
                    $oldVal=SocialPlatform::find($id);
                    if(isset($request->status) && $info->value==$request->status[$indexStatus]){
                        $info->update(["status" => 1]);
                        if($indexStatus < count($request->status)-1){
                            $indexStatus++;
                        }
                    }else{
                        $info->update(["status" => 0]);
                    }
                    $changes = $info->getChanges();
                    if($changes){
                        adminChange($oldVal,$changes,'tb_socialplatform','update');
                    }
                }
			}
			
			$source = SocialPlatform::where('key','SOURCE')->get();
			
		return view('auth.socialPlatform', compact('source'))->with('message','Updated configuration Succcessfullly');
		} catch(Exception $e) {
           DB::rollback();
           Log::debug($e->getMessage());
		   return redirect()->back()->with('message',$e->getMessage());
       }
	}

}