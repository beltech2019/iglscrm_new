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
use App\Models\PostAssignRule;
use Exception;
use DB;
use View;


class UserAssignRuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            
        ]);
    }
    public function addUserAssignRule(Request $request,$id=null)
    {
        DB::beginTransaction();
		try{
            $social_type ="";
            if($request->social_type)
			{
				$social_type = implode(",", $request->social_type);
			}
            $keywordArray = explode(',', $request->Keyword);
            $maxKeyLen=getValueByKey('KEYWORD_LENGTH');
            foreach ($keywordArray as &$keyword) {
                $keyword = trim($keyword);
                if (strlen($keyword) < $maxKeyLen) {
                    return redirect()->back()->with('success','Minimum Keyword length is '.$maxKeyLen);        
                }
            }
            $user=User::find($request->user_id);
            $add="";
			$response = [
                'user_id'=>$request->user_id,
                'name'=>$user->name,
                'Keyword'=>$request->Keyword,
                'social_type'=>$social_type,
                'assign_type'=>$request->assign_type,
                'from_date'=>$request->from_date,
                'to_date'=>$request->to_date,
                'enable'=>$request->enable
            ];
            if($id){
                $add = UserAssignRule::find($id);
                $oldVal = UserAssignRule::find($id);
				$add->update($response);
                $changes = $add->getChanges();
                if($changes){
                    adminChange($oldVal,$changes,'tb_user_assign_rule','update');
                } 
            }else{
                $add = UserAssignRule::create($response); 
                adminChange("no",$add,'tb_user_assign_rule','create');
            }
		} catch(Exception $e) {
            DB::rollback();
			return redirect()->back()->with('message',$e->getMessage());
        }
        DB::commit();
        return redirect()->route('userAssignRuleList');
    }

  
    public function getUserAssignRule(Request $request,$id=null)
    {
		try{
			$user = User::get();
            $rules = "";
            $socialtype="";
            if($id){
                $rules = UserAssignRule::find($id);    
                $socialtype=explode(',',$rules->social_type);
            }
            return \View::make('post.busines_rule', compact(['user','rules','socialtype']));
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
    }

    public function userAssignRuleList(Request $request)
    {
		try{
			$rulesList = UserAssignRule::get();
            return \View::make('post.busines_info', compact(['rulesList']));
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
    }

    public function deleteRule($id)
    {   
        try{
            $item = UserAssignRule::find($id);     
            if (!$item) {
                return response()->json(['message' => 'Item not found'], 404);
            }
            $item->delete();	
            adminChange("no",$id,'tb_user_assign_rule','delete');
            return redirect()->route('userAssignRuleList');
        } catch(Exception $e) {
            return redirect()->back()->with('message',$e->getMessage());
        }
    }

}
