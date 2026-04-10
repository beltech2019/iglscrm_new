<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocialTicket;
use App\Models\GetTweet;
use App\Models\Activity;
use App\Models\Favourite;
use App\Models\Conversation;
use App\Models\ProjectAttachment;
use App\Models\TweetLog;
use App\Models\LeadTicket;
use App\Models\SocialUser;
use App\Models\TweetReply;
use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Exception;
use DB;
use View;


class LeadController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth')->except([
            
        ]);
    }
	

	public function generateTicketFromLead(Request $request,$id)
    {
        DB::beginTransaction();
        try{
            $currentDateTime = Carbon::now();
            $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
            $response=LeadTicket::where('id',$id)->first();
			$oldVal=LeadTicket::where('id',$id)->first();
            $assigntoid=getUserAssignRule('ticket',$response->lead_source,$response->description);
			$assignto=User::find($assigntoid);
			$assigntoname="";
            $status="New";
			// if($assignto){
			// 	$assigntoname=$assignto->id;
            //     $status="Assigned";
			// }
			if(isset($response->assigned_to)){
				$assigntoname=$response->assigned_to;
                $status="Assigned";
			}else{
				$assigntoname=loggedUserId();
				$status="Assigned";
			}
            $requestInfo = [
                'getTweet_id'=>$response->getTweet_id==null?"Lead".$response->leadId:$response->getTweet_id,
                'postMessage'=>$response->description,
                'socialUser'=>$response->first_name." ".$response->last_name,
                'socialUser_id'=>$response->socialUser_id,
                'source'=>$response->lead_source,
                'postDate'=>$response->created_date,
                'mobile_no'=>$response->mobile,
                'email_id'=>$response->email_address,
                'status'=>$status,
                'priority'=>"Low",
                'converted'=>"True",
                'date_Created'=>$formattedDateTime,
                'subject'=>$response->description,
                'assigned_to'=>$assigntoname,
				'final_state'=>"Open",
				'resolution'=>$response->resolution,
				'bipNumber'=>$response->bp_number,
              ]; 
            $requestInfo['ticket_id'] = getDigitCodeForTicket();	  
			$response->update(['convertedtoticket'=>'1']);
			$postInfo = SocialTicket::create($requestInfo);
			$role = loggedUserRole();
			adminChange("no",$postInfo,'tb_socialticket','create');
			$activityInfo = Activity::where('post_id',$id)->update(['post_id' => $postInfo->id,'type'=>"TICKET"]);
			$response->delete();
			adminChange("no",$oldVal->id,'tb_leads','delete');
            DB::commit();
            return redirect()->route('getSocialTicket')->with('success','Ticket generated');
       } catch(Exception $e) {
           DB::rollback();
           return redirect()->back()->with('message',$e->getMessage());
       }
        
    }


    public function generateLead(Request $request,$id)
    {
        DB::beginTransaction();
        try{
            $leadById=loggedUserId();
			$leadBy=User::find($leadById);
			$currentDateTime = Carbon::now();
            $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
			$response=GetTweet::where('getTweet_id',$id)->first();
			$assigntoid=getUserAssignRule('lead',$response->source,$response->postMessage);
			$assignto=User::find($assigntoid);
			$assigntoname="";
            $status="New";
			if($assignto){
				$assigntoname=$assignto->id;
                $status="Assigned";
			}
			$nameParts = explode(' ', $response->socialUser_name);
			
			$lastname = "";
			$count=0;
			if(count($nameParts) > 1){
				foreach($nameParts as $namePart){
				 if($count>0){	
				   $lastname.= $namePart;
				   $lastname.= " ";
				 }
				 $count++;
			    }

			}
			$requestInfo = [
                'first_name'=>$nameParts[0],
                'last_name'=>$lastname,
				'getTweet_id'=>$response->getTweet_id,
				'socialUser_id'=>$response->socialUser_id,
                'type'=>"Hot",
                'office_phone'=>$request->input('office_phone'),
                'description'=>$response->postMessage." Posted On: ".$response->istPostDate." Link To Post: ".$response->postUrl,
                'title'=>$response->postMessage,
                'mobile'=>$response->mobile_no,
                'department'=>$request->input('department'),
                'customer_name'=>$request->input('customer_name'),
				'website'=>$request->input('website'),
                'status'=>$status,
                'converted'=>"1",
				'lead_source'=>$response->source,
				'assigned_to'=>$leadById,
				'approval_status'=>$response->approval_status,
				'leadById'=>$leadById,
				'leadBy'=>$leadBy->name,
				'created_date'=>$formattedDateTime,
				'assigned_to'=>$assigntoname,
				'bp_number'=>$response->bp_number,
              ]; 
			
			$requestInfo['leadId'] = getDigitCodeForLead();			  
			$postInfo = LeadTicket::create($requestInfo);
			$role = loggedUserRole();
			adminChange("no",$postInfo,'tb_leads','create');
			$response->update(['convertLead'=>1]);
            DB::commit();
            if($role == 'VENDOR'){
                return redirect()->back()->with('success','Lead generated');
            }else{
                return redirect()->route('getLeads')->with('success','Lead generated');
			}	
       } catch(Exception $e) {
           DB::rollback();
           return redirect()->back()->with('message',$e->getMessage());
       }
        
    } 
    
    public function getLeads(Request $request,$id =null)
    {
        try {
		    $getInfo = LeadTicket::orderBy('id','desc');
			$getInfo = $getInfo->leftjoin('users','users.id','=','tb_leads.assigned_to');
			$filterInfo = [];
            if($id)
			{
                $decodedText = urldecode($id);
                $normalText = Str::title($decodedText);
				$getInfo = $getInfo->where('assigned_to',$normalText);
                $filterInfo['id']= $normalText;
			}
			elseif($request->id)
			{
				$getInfo = $getInfo->where('id',$request->id);
				$filterInfo['id']= $request->id;
			}
			
		    if($request->name)
			{
				$getInfo = $getInfo->where('first_name','like',"%".$request->name."%");
				$filterInfo['name']= $request->name;
			}			
		    if($request->first_name)
			{
				$getInfo = $getInfo->where('first_name','like',"%".$request->first_name."%");
				$filterInfo['first_name']= $request->first_name;
			}			
		    if($request->last_name)
			{
				$getInfo = $getInfo->where('last_name','like',"%".$request->last_name."%");
				$filterInfo['last_name']= $request->last_name;
			}
			
			
			if($request->user)
			{
				$getInfo = $getInfo->where('socialUser','like','%'.$request->user.'%');
				$filterInfo['user']= $request->user;
			}
			
			if($request->priority)
			{
				$getInfo = $getInfo->where('priority','like','%'.$request->priority.'%');
				$filterInfo['priority']= $request->priority;
			}
			if($request->lead_source)
			{
				$getInfo = $getInfo->where('lead_source','like','%'.$request->lead_source.'%');
				$filterInfo['lead_source']= $request->lead_source;
			}
			if($request->subject)
			{
				$getInfo = $getInfo->where('subject','like', '%'.$request->subject.'%');
				$filterInfo['subject']= $request->subject;
			}
			if($request->description)
			{
				$getInfo = $getInfo->where('description','like', '%'.$request->description.'%');
				$filterInfo['description']= $request->description;
			}
			if($request->status)
			{
				$getInfo = $getInfo->where('status','like','%'.$request->status.'%');
				$filterInfo['status']= $request->status;
			}
			if($request->created_date)
			{
				$getInfo = $getInfo->whereDate('created_date','like','%'.$request->created_date.'%');
				$filterInfo['created_date']= $request->created_date;
			}
			$role = loggedUserRole();
            if($role==="OTHERUSER"){
                $userId = loggedUserId();
                $asiignedhistory=assigntoLog($userId,"Lead");
                $logInfo = 'tb_leads.id';
                $getInfo = $getInfo->whereIn($logInfo, $asiignedhistory);
            }
			$getInfo =$getInfo->select('tb_leads.*','users.name');
			if(isset($request->download))
			{	$col = [];
				$col= ["Post Id","Lead Number","Post Message","Social User","Lead Source","Status",'Date Created',
                "Assign TO","Description","Resolution","BP Number","Mobile No","Lead Created By","Department"];
				$data = [];
				$getInfo = $getInfo->get();
				return  downloadLeadCsv($getInfo,$col);
			}
			
			
			$lead = $getInfo->paginate(getValueByKey('PAGENATION_COUNT'));
			$lead->appends($filterInfo);
            return \View::make('lead.leadsList', compact('lead'));	
       } catch(Exception $e) {
		return redirect()->back()->with('message',$e->getMessage());
       }
        
    }

	public function getRecentLeads(Request $request,$id =null)
    {
        try {
			$endDate = Carbon::now();
            $startDate = $endDate->copy()->subDays(getValueByKey('LAST_POST_DAYS')-1);
		    $getInfo = LeadTicket::whereDate('created_date', '>=', $startDate)
			->whereDate('created_date', '<=', $endDate)
			->orderBy('id','desc');
			$filterInfo = [];
            if($id)
			{
                $decodedText = urldecode($id);
                $normalText = Str::title($decodedText);
				$getInfo = $getInfo->where('assigned_to',$normalText);
                $filterInfo['id']= $normalText;
			}
			elseif($request->id)
			{
				$getInfo = $getInfo->where('id',$request->id);
				$filterInfo['id']= $request->id;
			}
			
		    if($request->name)
			{
				$getInfo = $getInfo->where('first_name','like',"%".$request->name."%");
				$filterInfo['name']= $request->name;
			}
			
			if($request->user)
			{
				$getInfo = $getInfo->where('socialUser','like','%'.$request->user.'%');
				$filterInfo['user']= $request->user;
			}
			
			if($request->priority)
			{
				$getInfo = $getInfo->where('priority','like','%'.$request->priority.'%');
				$filterInfo['priority']= $request->priority;
			}
			if($request->source)
			{
				$getInfo = $getInfo->where('source','like','%'.$request->source.'%');
				$filterInfo['source']= $request->source;
			}
			if($request->subject)
			{
				$getInfo = $getInfo->where('subject','like', '%'.$request->subject.'%');
				$filterInfo['subject']= $request->subject;
			}
			if($request->description)
			{
				$getInfo = $getInfo->where('description','like', '%'.$request->description.'%');
				$filterInfo['description']= $request->description;
			}
			if($request->status)
			{
				$getInfo = $getInfo->where('status','like','%'.$request->status.'%');
				$filterInfo['status']= $request->status;
			}
			if($request->created)
			{
				$getInfo = $getInfo->where('date_Created','like','%'.$request->created.'%');
				$filterInfo['created']= $request->created;
			}
			$role = loggedUserRole();
            if($role==="OTHERUSER"){
                $userId = loggedUserId();
                $asiignedhistory=assigntoLog($userId,"Lead");
                $getInfo = $getInfo->whereIn('tb_leads.id', $asiignedhistory);
            }
			if(isset($request->download))
			{	$col = [];
				$col= ["Post Id","Lead Number","Post Message","Social User","Lead Source","Status",'Date Created',
                "Assign TO","Description","Resolution","BP Number","Mobile No","Lead Created By","Department"];
				$data = [];
				$getInfo = LeadTicket::leftjoin('users','users.id','=','tb_leads.assigned_to');
				$getInfo = $getInfo->get();
				return  downloadLeadCsv($getInfo,$col);
			}
			$lead = $getInfo->paginate(getValueByKey('PAGENATION_COUNT'));
            return \View::make('lead.leadsList', compact('lead'));	
       } catch(Exception $e) {
		return redirect()->back()->with('message',$e->getMessage());
       }
        
    }

	public function getLeadById(Request $request,$id)
	{
		try{
			$leadData = LeadTicket::find($id);
			$reply = TweetReply::join('users','users.id','=','tb_tweet_reply.user_id')
			->where('tb_tweet_reply.replyToTweetId',$leadData->getTweet_id)
			->select('tb_tweet_reply.*','users.name')
			->orderBy('post_id', 'desc')
			->get();
            $logUser=loggedUserId();
            $getFavourite = Favourite::where('user_id',$logUser)
            ->where('type_id',$leadData->id)
            ->where('type','tb_leads')
            ->first();
			$getActivity = Activity::join('users','users.id','=','tb_activity.created_by')->where('tb_activity.post_id',$id)->where('tb_activity.type','Lead')->select('tb_activity.*','users.name')->orderBy('tb_activity.id','DESC')->get();
			$getSocialLog = TweetLog::where('post_id',$id)->where('post_type','Lead')->where('field','status')->get();
			$attacheddata=ProjectAttachment::where('attachment_id',$leadData->getTweet_id)->get();
            $dmData=Conversation::where('socialpost_id',$leadData->getTweet_id)
            ->orderBy('message_time', 'desc')
            ->get();
			return \View::make('lead.lead_inner', compact(['leadData','getActivity','getSocialLog','getFavourite','reply','attacheddata','dmData']));
		} catch(Exception $e) {
			Log::debug($e->getMessage());
			return redirect()->back()->with('message',$e->getMessage());
        }	
	}	

	public function leadReply($id)
    {
		try{
			$postData= "";
			if($id)
			{
				$postData = LeadTicket::find($id);
			}
            $reply = TweetReply::join('users','users.id','=','tb_tweet_reply.user_id')
			->where('tb_tweet_reply.replyToTweetId',$postData->getTweet_id)
			->select('tb_tweet_reply.*','users.name')
			->orderBy('post_id', 'desc')
			->get();
            $dmData=Conversation::where('socialpost_id',$postData->getTweet_id)
            ->orderBy('message_time', 'desc')
            ->get();
			return \View::make('post.postTweetOnLead', compact(['postData','reply','dmData']));
		} catch(Exception $e) {
			Log::debug($e->getMessage());
			return redirect()->back()->with('message',$e->getMessage());
        }
        
    }
	
	public function createLead(Request $request,$id =null)
	{
		try{
			$create_lead="Create Lead";
		    $leadData= "";
			$attacheddata=[];
			if($id)
			{
				$leadData = LeadTicket::find($id);
				$create_lead="Edit Lead";
				$attacheddata=ProjectAttachment::where('attachment_id',$leadData->getTweet_id)->get();
			}
			$getUser=User::where('role', '!=', '2')->get();
			return \View::make('lead.create_leads', compact(['leadData','getUser','create_lead','attacheddata']));
		} catch(Exception $e) {
			Log::debug($e->getMessage());
			return redirect()->back()->with('message',$e->getMessage());
        }	
	}
	
	public function deleteAllLead(Request $request)
    {
		try{
			if($request->postid)
			{
				foreach($request->postid as $postId)
				{
					$post = LeadTicket::find($postId);
					$post->delete();
					adminChange("no",$postId,'tb_leads','delete');
				}
			}
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
       return redirect()->back()->with('message','Deleted');
    }
	 
	public function deleteLead($id)
    {
		try{
        $item = LeadTicket::find($id);     
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        $item->delete();
		adminChange("no",$id,'tb_leads','delete');
        } catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
       return redirect()->back()->with('message','Deleted');
    }
	
	public function createUpdateLead(Request $request,$id =null)
    {
		DB::beginTransaction();
		try{
			$leadById=loggedUserId();
			$leadBy=User::find($leadById);
			$currentDateTime = Carbon::now();
            $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
			$leadId=LeadTicket::orderby('id','desc')->get();
			
			$requestInfo = [
                'greeting_first_name'=>$request->input('greeting_first_name'),
				'socialUser_id'=>$request->input('socialUser_id'),
                'first_name'=>$request->input('first_name'),
                'last_name'=>$request->input('last_name'),
                'type'=>$request->input('type'),
                'office_phone'=>$request->input('office_phone'),
                'title'=>$request->input('title'),
                'mobile'=>$request->input('mobile'),
                'department'=>$request->input('department'),
                'customer_name'=>$request->input('customer_name'),
				'website'=>$request->input('website'),
                'status'=>$request->input('status'),
                'approvel_status'=>$request->input('approvel_status'),
                'primary_address'=>$request->input('primary_address'),
                'primary_city'=>$request->input('primary_city'),
                'primary_state'=>$request->input('primary_state'),
                'primary_postal_code'=>$request->input('primary_postal_code'),
                'primary_country'=>$request->input('primary_country'),
                'other_address'=>$request->input('other_address'),
				'other_city'=>$request->input('other_city'),
                'other_state'=>$request->input('other_state'),
                'other_postal_code'=>$request->input('other_postal_code'),
                'other_country'=>$request->input('other_country'),
                'email_address'=>$request->input('email_address'),
                'converted'=>$request->input('converted'),
                'description'=>$request->input('description'),
                'fax'=>$request->input('fax'),
				'partner_contacts'=>$request->input('partner_contacts'),
				'lead_source'=>$request->input('lead_source'),
				'bp_number'=>$request->input('bp_number'),
				'resolution'=>$request->input('resolution'),
				'assigned_to'=>$leadById,
				'leadById'=>$leadById,
				'leadBy'=>$leadBy->name,
				'created_date'=>$formattedDateTime,
              ];  
			  
			if($id)
			{
				$oldVal = LeadTicket::find($id);
				$postInfo = LeadTicket::find($id);
				if($request->hasFile('media')){
					$files = $request->file('media');
					$res = uploadFileDocuments($files,'projectAttachments');
					$uploadDateTime = Carbon::now();
					$uploadtime = $uploadDateTime->format('Y-m-d H:i:s');
					if($res){
						$attachment=ProjectAttachment::create([
							'attachment_id'=>$postInfo->getTweet_id,
							'fileName'=>$res['fileName'],
							'filePath'=>$res['filePath'],
							'fileUrl'=>$res['fileUrl'],
							'upload_time'=>$uploadtime
						]);
						adminChange("no",$attachment,'tb_projectattachment','create');
					}
				}
				$postInfo->update($request->all());
				$changes = $postInfo->getChanges();
				createLog($oldVal,$changes,'Lead');
				adminChange($oldVal,$changes,'tb_leads','update');
			}
			else{
				$requestInfo['leadId'] = getDigitCodeForLead();
				$requestInfo['getTweet_id'] = $requestInfo['leadId'];
				if($request->hasFile('media')){
					$files = $request->file('media');
					$res = uploadFileDocuments($files,'projectAttachments');
					$uploadDateTime = Carbon::now();
					$uploadtime = $uploadDateTime->format('Y-m-d H:i:s');
					if($res){
						$attachment=ProjectAttachment::create([
							'attachment_id'=>$requestInfo['leadId'],
							'fileName'=>$res['fileName'],
							'filePath'=>$res['filePath'],
							'fileUrl'=>$res['fileUrl'],
							'upload_time'=>$uploadtime
						]);
						adminChange("no",$attachment,'tb_projectattachment','create');
					}
				}
				$postInfo = LeadTicket::create($requestInfo);
				adminChange("no",$postInfo,'tb_leads','create');
				$socialUser=SocialUser::where('user_id',$request->input('socialUser_id'))->first();
				if(!$socialUser){
				    $saveSocialUser=SocialUser::create([
					  'user_id'=>$request->input('socialUser_id'),
                      'user_name'=>$request->input('first_name').$request->input('last_name').$request->input('socialUser_id'),
				      'name'=>$request->input('first_name').' '.$request->input('last_name'),
			        ]);
		    	}
			}
			DB::commit();
            return redirect()->route('getLeads')->with('success','Lead Saved');
       } catch(Exception $e) {
           DB::rollback();
			return redirect()->back()->with('message',$e->getMessage());
        }
    }

	public  function deleteattachmentfromLead($id)
    {	
		DB::beginTransaction();
		try{	
            $getLeadTicket = LeadTicket::where('id', $id)->first();
			$attachment = ProjectAttachment::where('attachment_id',$getLeadTicket->getTweet_id)->first();
			$attachment->delete();
			adminChange("no",$attachment->id,'tb_projectattachment','delete');
		    DB::commit();
			return redirect()->route('createLead', ['id' => $id]);
		}
		catch(Exception $e) {
			DB::rollback();
			Log::debug( $e->getMessage());
			// return response()->json(['error' => $e->getMessage()], 400);
			// return redirect()->back()->with('message',$e->getMessage());
        }
	}
    

}
