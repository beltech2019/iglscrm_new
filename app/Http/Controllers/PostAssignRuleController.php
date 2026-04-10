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
use App\Models\RulePriority;
use App\Models\PostAssignRule;
use Exception;
use DB;
use View;


class PostAssignRuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            
        ]);
    }
    public function addPostAssignRule(Request $request,$id=null)
    {
        DB::beginTransaction();
		try{
            $type ="";
            if($request->type)
			{
				$type = implode(",", $request->type);
			}
            $keywordArray = explode(',', $request->keyword);
            $maxKeyLen= (int)getValueByKey('KEYWORD_LENGTH');
            foreach ($keywordArray as &$keyword) {
                $keyword = trim($keyword);
                if (strlen($keyword) < $maxKeyLen) {
					throw new Exception('Minimum Keyword length is '.$maxKeyLen);        
                }
            }
            
			$response = [
                'keyword'=>$request->keyword,
                'type'=>$type,
                'status'=>$request->status,
                'category'=>$request->category
            ];
			$rule = PostAssignRule::where('type',$type)->where('category',$request->category)->first();
            if($id){
				if($rule && $rule->ruleid != $id)
				{
					throw new Exception("allready set for this caregory and type");
				}
                $add = PostAssignRule::find($id);
                $oldVal=PostAssignRule::find($id);
				$add->update($response); 
                $changes = $add->getChanges();
                if($changes){
                    adminChange($oldVal,$changes,'tb_post_type_rules','update');
                }
            }else{
				if($rule)
				{
					throw new Exception("allready set for this caregory and type");
				}
                $add = PostAssignRule::create($response); 
                if($changes){
                    adminChange("no",$add,'tb_post_type_rules','create');
                }
            }
		} catch(Exception $e) {
            DB::rollback();
			return redirect()->back()->with('message',$e->getMessage())->withInput($request->input());
        }
        DB::commit();
       return redirect()->route('postAssignRuleList')->with('success','Post rules succss');
    }

  
    public function getPostAssignRule(Request $request,$id=null)
    {
		try{
			$rules ="";
			$type ="";
            if($id){
                $rules = PostAssignRule::find($id);    
                $type=explode(',',$rules->type);
            }
            return \View::make('rule.post_rule', compact(['rules','type']));
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
    }

    public function postAssignRuleList(Request $request)
    {
		try{
			$rulesList = PostAssignRule::get();
            return \View::make('rule.post_rule_list', compact(['rulesList']));
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
    }

    public function deletePostRule($id)
    {  try{
           $item = PostAssignRule::find($id);     
           if (!$item) {
               Throw new Excetion('Item not found');
            }
           $item->delete();		
           adminChange("no",$id,'tb_post_type_rules','delete');
           return redirect()->route('postAssignRuleList');
        } catch(Exception $e) {
           return redirect()->back()->with('message',$e->getMessage());
        }
    }



    
    public function addPostAssignRulePriority(Request $request)
    {
        DB::beginTransaction();
		try{
            $requestData = $request->all();
            $keys=array_keys($requestData);
            $count=0;
            foreach ($keys as $key ) {
                $priority = RulePriority::where('key',$key)->first();
                $oldVal=RulePriority::where('key',$key)->first();
                if($priority){
                    $priority->update(['value'=>$requestData[$key]]);
                    $changes = $priority->getChanges();
                    if($changes){
                        adminChange($oldVal,$changes,'tb_rule_priority','update');
                    }
                }
            }
		} catch(Exception $e) {
            DB::rollback();
			return redirect()->back()->with('message',$e->getMessage())->withInput($request->input());
        }
        DB::commit();
       return redirect()->route('postAssignRuleList')->with('success','Priority Updated');
    }
	
	
	 public function postRulePriority(Request $request)
    {
		try{
			$post = RulePriority::where('type', 'Post')->orderBy('value', 'asc')->get()->toArray();
            $ticket = RulePriority::where('type', 'Ticket')->orderBy('value', 'asc')->get()->toArray();
            $lead = RulePriority::where('type', 'Lead')->orderBy('value', 'asc')->get()->toArray();
            return \View::make('rule.post_rule_setpriority', compact(['post','ticket','lead']));
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
    }
		

}
