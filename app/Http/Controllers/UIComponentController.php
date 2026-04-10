<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\UserAssignRule;
use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Component;
use App\Models\RoleAccessMapping;
use Exception;
use DB;
use View;


class UIComponentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            
        ]);
    }
	public function accessRole(Request $request)
	{
		try{
    		$currentUserRole = loggedUserRole();
            $role = [];
            if ($currentUserRole === 'SUPER ADMIN') {
                $role = Role::get();
            } else {
                $role = Role::where('role_name', '!=', 'Super Admin')->get();
            }
		} catch(Exception $e) {
           return redirect()->back()->with('message', $e->getMessage());
		}
		return \View::make('access.access_list', compact(['role']));
	}
	
	public function getRoleWiseComponents(Request $request,$id)
	{
		try{
			
			$role = Role::get();
			$loggedUserRole = loggedUserRole();
			$component = [];
			if ($loggedUserRole == 'SUPER ADMIN') {
				$component = Component::leftJoin('tb_role_access_mapping', function($leftJoin)use ($id)
				{
					$leftJoin->on('tb_role_access_mapping.component_id', '=', 'tb_component.id');
					$leftJoin->where('tb_role_access_mapping.user_role_id',$id);
				})
				->select('tb_component.*','tb_role_access_mapping.*','tb_component.id as id','tb_role_access_mapping.id as mapId')
				->get();	
			}else {
				$component = Component::leftJoin('tb_role_access_mapping', function($leftJoin)use ($id)
				{
					$leftJoin->on('tb_role_access_mapping.component_id', '=', 'tb_component.id');
					$leftJoin->where('tb_role_access_mapping.user_role_id',$id);
				})->where('tb_component.component_key', 'not like', '%LEAD%')
				->where('tb_component.component_type', 'not like', '%LEAD%')
				->select('tb_component.*','tb_role_access_mapping.*','tb_component.id as id','tb_role_access_mapping.id as mapId')
				->get();
			}
			$access = getAccessType();
		} catch(Exception $e) {
           return redirect()->back()->with('message', $e->getMessage());
		}
		return \View::make('access.create_access', compact(['component','access','role','id']));
	}
	
	public function createUpdateRoleAccess(Request $request)
	{
		try{
			$id = $request->role_id;
			$loggedUserRole = loggedUserRole();
			$component = [];
			if ($loggedUserRole == 'SUPER ADMIN') {
				$component = Component::leftJoin('tb_role_access_mapping', function($leftJoin)use ($id)
				{
					$leftJoin->on('tb_role_access_mapping.component_id', '=', 'tb_component.id');
					$leftJoin->where('tb_role_access_mapping.user_role_id',$id);
				})
				->select('tb_component.*','tb_role_access_mapping.*','tb_component.id as id','tb_role_access_mapping.id as mapId')
				->get();	
			}else {
				$component = Component::leftJoin('tb_role_access_mapping', function($leftJoin)use ($id)
				{
					$leftJoin->on('tb_role_access_mapping.component_id', '=', 'tb_component.id');
					$leftJoin->where('tb_role_access_mapping.user_role_id',$id);
				})->where('tb_component.component_key', 'not like', '%LEAD%')
				->where('tb_component.component_type', 'not like', '%LEAD%')
				->select('tb_component.*','tb_role_access_mapping.*','tb_component.id as id','tb_role_access_mapping.id as mapId')
				->get();
			}
			//$accessArray = [];
			if($component)
			{
				$index = 0;
				foreach($component as $component)
				{
					$access_key = $component->component_key;
					$accessArray = [
						'access'=>$request->$access_key,
						'component_id'=>$component->id,
						'user_role_id'=>$id
					];
					$index++;
					if($component->mapId)
					{
						$update = RoleAccessMapping::find($component->mapId);
						$oldVal = RoleAccessMapping::find($component->mapId);
						$update->update($accessArray);
						$changes = $update->getChanges();
						if($changes){
							adminChange($oldVal,$changes,'tb_role_access_mapping','update');
						}
					}
					else{
						RoleAccessMapping::create($accessArray);
					}
				}
				
				
			}
			$access = getAccessType();
		} catch(Exception $e) {
           return redirect()->back()->with('message', $e->getMessage());
		}
		return redirect()->back()->with('message','updated');
	}
    

}
