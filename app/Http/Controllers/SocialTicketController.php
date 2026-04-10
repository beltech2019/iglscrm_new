<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocialTicket;
use App\Models\GetTweet;
use App\Models\Activity;
use App\Models\TweetLog;
use App\Models\ProjectAttachment;
use App\Models\Conversation;
use App\Models\Favourite;
use App\Models\Defaults;
use App\Models\TweetReply;
use App\Models\TicketSapGroups;
use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Exception;
use DB;
use View;

class SocialTicketController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth')->except([ 'getSocialTicketStatusReportbyDates'
            
        ]);
    }
    public function generateTicket(Request $request,$id)
    {
        DB::beginTransaction();
        try{
            $currentDateTime = Carbon::now();
            $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
            $response=GetTweet::where('id',$id)->first();
            $assigntoid=getUserAssignRule('ticket',$response->source,$response->postMessage);
			$assignto=User::find($assigntoid);
			$assigntoname="";
            $status="New";
			if($assignto){
				$assigntoname=$assignto->id;
                $status="Assigned";
			}
            $requestInfo = [
                'getTweet_id'=>$response->getTweet_id,
                'postMessage'=>$response->postMessage,
                'socialUser'=>$response->socialUser_name."(".$response->socialUser_userName.")",
                'socialUser_id'=>$response->socialUser_id,
                'source'=>$response->source,
                'postUrl'=>$response->postUrl,
                'postDate'=>$response->postDate,
                'mobile_no'=>$response->mobile_no,
                'email_id'=>$response->email_id,
                'status'=>$status,
                'priority'=>"Low",
                'converted'=>"True",
                'date_Created'=>$formattedDateTime,
                'suggestion'=>$request->input('suggestion'),
                'subject'=>$response->postMessage,
                'description'=>$request->input('description'),
                'additional_Text'=>$request->input('additional_Text'),
                'resolution'=>$request->input('resolution'),
                'final_state'=>$request->input('final_state'),
                'assigned_to'=>$assigntoname,
				'bipNumber'=>$response->bp_number,
				'final_state'=>"Open",
              ]; 
            $requestInfo['ticket_id'] = getDigitCodeForTicket();	  
			$response->update(['converted'=>true]);
			$postInfo = SocialTicket::create($requestInfo);
            DB::commit();
			$role=loggedUserRole();
            adminChange("no",$postInfo,'tb_socialticket','create');
			if($role == 'VENDOR'){
                return redirect()->back()->with('success','Ticket Generated');
			}else{
                return redirect()->route('getSocialTicket')->with('success','Ticket Generated');
            }
       } catch(Exception $e) {
           DB::rollback();
           return redirect()->back()->with('message',$e->getMessage());
       }
        
    } 
    
    public function getSocialTicket(Request $request,$id =null)
    {
        try {
		    $getInfo = SocialTicket::leftjoin('users','users.id','=','tb_socialticket.assigned_to')
            ->leftJoin('tb_department', 'users.department', '=', 'tb_department.department_id')->leftJoin('sap_ticket_set', 'sap_ticket_set.ticket_id', '=', 'tb_socialticket.id')->orderBy('tb_socialticket.date_Created','desc');
			$filterInfo = [];
            if($id)
			{
                $decodedText = urldecode($id);
                $normalText = Str::title($decodedText);
				$getInfo = $getInfo->where('tb_socialticket.assigned_to',$normalText);
                $filterInfo['id']= $normalText;
			}
			elseif($request->id)
			{
				$getInfo = $getInfo->where('tb_socialticket.ticket_id',$request->id);
				$filterInfo['id']= $request->id;
			}
			
			if ($request->sapticket) {
                $sapTicket = TicketSapGroups::where('sap_object_id', $request->sapticket)->pluck('ticket_id')->toArray(); 
                $getInfo = $getInfo->whereIn('tb_socialticket.id', $sapTicket);
            }
            
            if ($request->assignedto) {
                $getInfo = $getInfo->where('tb_socialticket.assigned_to',$request->assignedto);
            }

            if ($request->bipnumber) {
                $getInfo = $getInfo->where('tb_socialticket.bipnumber',$request->bipnumber);
            }
            
            if($request->user_id){
				$getInfo = $getInfo->where('tb_socialticket.assigned_to',$request->user_id);
            }
			if($request->user)
			{
				$getInfo = $getInfo->where('tb_socialticket.socialUser','like','%'.$request->user.'%');
				$filterInfo['user']= $request->user;
			}
			
			if($request->priority)
			{
				$getInfo = $getInfo->where('tb_socialticket.priority','like','%'.$request->priority.'%');
				$filterInfo['priority']= $request->priority;
			}
			if($request->source)
			{
				$getInfo = $getInfo->where('tb_socialticket.source','like','%'.$request->source.'%');
				$filterInfo['source']= $request->source;
			}
			if($request->subject)
			{
				$getInfo = $getInfo->where('subject','like', '%'.$request->subject.'%');
				$filterInfo['subject']= $request->subject;
			}
			if($request->status)
			{
				$getInfo = $getInfo->where('tb_socialticket.status','like','%'.$request->status.'%');
				$filterInfo['status']= $request->status;
			}
			if($request->created)
			{
				$getInfo = $getInfo->where('tb_socialticket.date_Created','like','%'.$request->created.'%');
				$filterInfo['created']= $request->created;
			}
			
			if ($request->final_state) {
                $getInfo = $getInfo->where('tb_socialticket.final_state', 'like', '%' . $request->final_state . '%');
                $filterInfo['final_state'] = $request->final_state;
            }
			
            $role = loggedUserRole();
            if($role==="OTHERUSER"){
                $userId = loggedUserId();
                $asiignedhistory=assigntoLog($userId,"ticket");
                $getInfo = $getInfo->whereIn('tb_socialticket.id', $asiignedhistory);
            }
            $getInfo = $getInfo->distinct()->select('tb_socialticket.*','users.name',
            'tb_department.department_name as assigned_department', DB::raw('IF(sap_ticket_set.ticket_id IS NOT NULL, "yes", "no") as sapstatus'));
			if(isset($request->download))
			{	$col = [];
				$col= ['Post ID','Ticket ID',"Post Message","Social User","Source","Final Status","Status",
                "AssignTo","Creation Date","BP Number","Mobile Number","Description","Resolution","Additional Text","PostUrl",
                "Activity"];
				$data = [];
				$getInfo = $getInfo->get();
                if(!empty($getInfo)){
                    foreach($getInfo as $info){
                        $info->activies = Activity::leftjoin('users','users.id','=','tb_activity.created_by')
                        ->where("post_id",$info->id)
                        ->select('tb_activity.text','tb_activity.created_at','users.name')
                        ->get();
                    }
                }
				return  downloadTicketCsv($getInfo,$col);
			}
            
            $getUser = User::where('role', '!=', '2')->whereNot('name','Arvind Sharma')->get();
			$getInfo = $getInfo->paginate(getValueByKey('PAGENATION_COUNT'));
			$getInfo->appends($filterInfo);
			
            return \View::make('post.socialticket', compact('getInfo','id','getUser'));
       } catch(Exception $e) {
            return redirect()->back()->with('message',$e->getMessage());
       }
        
    }

    public function getRecentSocialTicket(Request $request)
    {
        try {
            $endDate = Carbon::now();
            $startDate = $endDate->copy()->subDays(getValueByKey('LAST_POST_DAYS')-1);
		    $getInfo = SocialTicket::leftjoin('users','users.id','=','tb_socialticket.assigned_to')
	              ->leftJoin('sap_ticket_set', 'sap_ticket_set.ticket_id', '=', 'tb_socialticket.id')
                  ->whereDate('date_Created', '>=', $startDate)
                  ->whereDate('date_Created', '<=', $endDate)
                  ->orderby('date_Created','desc');
			$filterInfo = [];
			if($request->id)
			{
				$getInfo = $getInfo->where('tb_socialticket.id',$request->id);
				$filterInfo['id']= $request->id;
			}
			
			if ($request->sapticket) {
                $sapTicket = TicketSapGroups::where('sap_object_id', $request->sapticket)->pluck('ticket_id')->toArray(); 
                $getInfo = $getInfo->whereIn('tb_socialticket.id', $sapTicket);
            }
            
            if ($request->assignedto) {
                $getInfo = $getInfo->where('tb_socialticket.assigned_to',$request->assignedto);
            }

            if ($request->bipnumber) {
                $getInfo = $getInfo->where('tb_socialticket.bipnumber',$request->bipnumber);
            }
            
			if($request->user)
			{
				$getInfo = $getInfo->where('tb_socialticket.socialUser','like','%'.$request->user.'%');
				$filterInfo['user']= $request->user;
			}
			
			if($request->priority)
			{
				$getInfo = $getInfo->where('tb_socialticket.priority','like','%'.$request->priority.'%');
				$filterInfo['priority']= $request->priority;
			}
			if($request->source)
			{
				$getInfo = $getInfo->where('tb_socialticket.source','like','%'.$request->source.'%');
				$filterInfo['source']= $request->source;
			}
			if($request->subject)
			{
				$getInfo = $getInfo->where('tb_socialticket.subject','like', '%'.$request->subject.'%');
				$filterInfo['subject']= $request->subject;
			}
			if($request->status)
			{
				$getInfo = $getInfo->where('tb_socialticket.status','like','%'.$request->status.'%');
				$filterInfo['status']= $request->status;
			}
			if($request->created)
			{
				$getInfo = $getInfo->where('tb_socialticket.date_Created','like','%'.$request->created.'%');
				$filterInfo['created']= $request->created;
			}
            $getInfo = $getInfo->distinct()->select('tb_socialticket.*','users.name', DB::raw('IF(sap_ticket_set.ticket_id IS NOT NULL, "yes", "no") as sapstatus'));
            $role = loggedUserRole();
            if($role==="OTHERUSER"){
                $userId = loggedUserId();
                $asiignedhistory=assigntoLog($userId,"ticket");
                $getInfo = $getInfo->whereIn('tb_socialticket.id', $asiignedhistory);
            }
            if(isset($request->download))
			{	$col = [];
				$col= ['Post ID','Ticket ID',"Post Message","Social User","Source","Final Status","Status",
                "AssignTo","Creation Date","BP Number","Mobile Number","Description","Resolution","Additional Text","PostUrl",
                "Activity"];
				$data = [];
				$getInfo = $getInfo->get();
                if(!empty($getInfo)){
                    foreach($getInfo as $info){
                        $info->activies = Activity::leftjoin('users','users.id','=','tb_activity.created_by')
                        ->where("post_id",$info->id)
                        ->select('tb_activity.text','tb_activity.created_at','users.name')
                        ->get();
                    }
                }
				return  downloadTicketCsv($getInfo,$col);
			}
			 $getUser = User::where('role', '!=', '2')->whereNot('name','Arvind Sharma')->get();
			$getInfo = $getInfo->paginate(getValueByKey('PAGENATION_COUNT'));
            return \View::make('post.socialticket', compact('getInfo','getUser'));	
       } catch(Exception $e) {
        return redirect()->back()->with('message',$e->getMessage());
       }
        
    }

    public function editTicket(Request $request,$id)
    {
    try {
        $getSocialUser = GetTweet::orderby('id','desc');
			$modal=false;
			if($request->user){
			    $getSocialUser = $getSocialUser->where('socialUser_name','like','%'.$request->user.'%');  
				$modal=true;
			}
			if($request->assignedto){
			    $getSocialUser = $getSocialUser->where('assignedto','like','%'.$request->assignedto.'%');  
				$modal=true;
			}
			if($request->page){
				$modal=true;
			}
			$getSocialUser = $getSocialUser->paginate(getValueByKey('PAGENATION_COUNT'));
            $getUser = User::where('role', '!=', '2')->get();
        $getSocialTicket = SocialTicket::where('id', $id)->first();
        $attacheddata=ProjectAttachment::where('attachment_id',$getSocialTicket->getTweet_id)->get();
        return \View::make('post.edit_social_ticket', compact(['getSocialTicket','getUser','getSocialUser','id','modal','attacheddata']));
       } catch(Exception $e) {
        Log::debug($e->getMessage());
        return redirect()->back()->with('message', $e->getMessage());
      }
    }   

    public function updateTicket(Request $request,$id)
    {
        DB::beginTransaction();
        try{
            $old=SocialTicket::find($id);
            $response=SocialTicket::find($id);
            if($request->hasFile('media')){
                log:info("hello media");
				$files = $request->file('media');
				$res = uploadFileDocuments($files,'projectAttachments');
				$uploadDateTime = Carbon::now();
				$uploadtime = $uploadDateTime->format('Y-m-d H:i:s');
                if($res){
					$attachment=ProjectAttachment::create([
						'attachment_id'=>$response->getTweet_id,
                        'fileName'=>$res['fileName'],
                        'filePath'=>$res['filePath'],
                        'fileUrl'=>$res['fileUrl'],
						'upload_time'=>$uploadtime
					]);
                    adminChange("no",$attachment,'tb_projectattachment','create');
				}
			}
            $requestInfo = [
                'getTweet_id'=>$response->getTweet_id,
                'socialUser'=>$request->input('socialUser'),
                'source'=>$request->input('source'),
                'subSource'=>$request->input('subSource'),
                'status'=>$request->input('status')?$request->input('status'):$old->status,
                'type'=>$request->input('type'),
                'bipNumber'=>$request->input('bipNumber'),
                'priority'=>$request->input('priority'),
                'suggestion'=>$request->input('suggestion'),
                'assigned_to'=>$request->input('assignedto'),
                'subject'=>$request->input('subject'),
                'postMessage'=>$response->postMessage,
                'postUrl'=>$response->postUrl,
                'converted'=>"True",
                'date_Created'=>$response->date_Created,
                'description'=>$request->input('description'),
                'additional_Text'=>$request->input('additional_Text'),
                'resolution'=>$request->input('resolution')
              ];

              if($request->input('status')=="Duplicate"){
                $requestInfo['final_state']="Close"; 
                $checkReply="";
                if(strtoupper($old->source)=="FACEBOOK"){
                   $checkReply=getValueByKey('AUTO_REPLY_FACEBOOK');
                }elseif(strtoupper($old->source)=="TWITTER") {
                   $checkReply=getValueByKey('AUTO_REPLY_TWITTER');  
                }elseif(strtoupper($old->source)=="LINKEDIN") {
                   $checkReply=getValueByKey('AUTO_REPLY_LINKEDIN');  
                }elseif(strtoupper($old->source)=="WHATSAPP") {
                   $checkReply=getValueByKey('AUTO_REPLY_WHATSAPP');  
                }
                if($checkReply=="true"){
                   $replyMessage = getValueByKey('AUTO_REPLY_ON_CLOSE');
                   $requestData = [
                     'source' => $old->source,
                     'postMessage' => $replyMessage,
                   ];
                   $request = new Request($requestData);
                   $id=$old->getTweet_id;
                   $result = app('App\Http\Controllers\TweetController')->replyTweetId($request,$id);
                }
              }else{
                
                $requestInfo['final_state']=$request->input('final_state');
              }
			
			$response->update($requestInfo);
			$changes = $response->getChanges();
			createLog($old,$changes,'ticket');
            adminChange($old,$changes,'tb_socialticket','update');
            DB::commit();
             return redirect()->route('getSocialTicket')->with('success','Ticket Updated');
       } catch(Exception $e) {
           DB::rollback();
           return redirect()->back()->with('message',$e->getMessage());
       }
        
    } 

	public function getSocialTicketById(Request $request,$id)
    {
		try{
			$getsocial = SocialTicket::where('id',$id)->first();
			$attacheddata=ProjectAttachment::where('attachment_id',$getsocial->getTweet_id)->get();
            $logUser=loggedUserId();
            $getFavourite = Favourite::where('user_id',$logUser)
            ->where('type_id',$getsocial->id)
            ->where('type','tb_socialticket')
            ->first();
			$getsocial = SocialTicket::where('id',$id)->first();
            $reply = TweetReply::join('users','users.id','=','tb_tweet_reply.user_id')
			->where('tb_tweet_reply.replyToTweetId',$getsocial->getTweet_id)
			->select('tb_tweet_reply.*','users.name')
			->orderBy('post_id', 'desc')
			->get();
			$getActivity = Activity::join('users','users.id','=','tb_activity.created_by')->where('tb_activity.post_id',$id)->where('tb_activity.type','TICKET')->select('tb_activity.*','users.name')->orderBy('tb_activity.id','DESC')->get();
			$getSocialLog = TweetLog::where('post_id',$id)->where('post_type','ticket')->whereIn('field',['status'])->get();
            $dmData=Conversation::where('socialpost_id',$getsocial->getTweet_id)
            ->orderBy('message_time', 'desc')
            ->get();
            $saptickets = TicketSapGroups::where('ticket_id',$id)->get();
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
        return \View::make('post.socialpost_inner', compact(['reply','getsocial','getActivity','getSocialLog','getFavourite','attacheddata','dmData','saptickets']));
    }

    public function deleteTicket($id)
    {
        $item = SocialTicket::find($id);     
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        $item->delete();
        adminChange("no",$id,'tb_socialticket','delete');
        return redirect()->route('getSocialTicket');
    }
	
	public function createUpdateActivity(Request $request,$id=null)
    {
        try{    
			if ($id) {
			   $item = Activity::find($id);
			   $item->update($request->all());
			}
			else{
				$item = Activity::create($request->all());
			}		
         return redirect()->back()->with('message','History Added');
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
    }
	

    public function ticketReply($id)
    {
		try{
			$postData= "";
			if($id)
			{
				$postData = SocialTicket::find($id);
			}
            $reply = TweetReply::join('users','users.id','=','tb_tweet_reply.user_id')
			->where('tb_tweet_reply.replyToTweetId',$postData->getTweet_id)
			->select('tb_tweet_reply.*','users.name')
			->orderBy('post_id', 'desc')
			->get();
            $dmData=Conversation::where('socialpost_id',$postData->getTweet_id)
            ->orderBy('message_time', 'desc')
            ->get();
			return \View::make('post.postTweetOnTicket', compact(['postData','reply','dmData']));
		} catch(Exception $e) {
			Log::debug($e->getMessage());
			return redirect()->back()->with('message',$e->getMessage());
        }
        
    }
	
	public function deleteAllTicket(Request $request)
    {
		try{
			if($request->postid)
			{
				foreach($request->postid as $postId)
				{
					$post = SocialTicket::find($postId);
					$post->delete();
                    adminChange("no",$postId,'tb_socialticket','delete');
				}
			}
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
       return redirect()->back()->with('message','Deleted');
    }
	public function updateTicketBtText(Request $request,$postId)
    {
		try{
			$post = SocialTicket::find($postId);
            $old=SocialTicket::find($postId);
			$post->update(array_merge($request->all(), ['internalUpdate' => $request->internalUpdate?$request->internalUpdate:0]));		
            $changes = $post->getChanges();
            adminChange($old,$changes,'tb_socialticket','update');
			
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
       return redirect()->back()->with('success','Ticket updated');
    }
	
	public function markDuplicate(Request $request,$id)
    {
        DB::beginTransaction();
        try{
            $old=SocialTicket::find($id);
            $response=SocialTicket::find($id);
            $requestInfo = [
                
                'status'=>$request->input('status')?$request->input('status'):$old->status,
                'final_state'=>'Close',
              ];  
			
			$response->update($requestInfo);
			$changes = $response->getChanges();
			createLog($old,$changes,'ticket');
            adminChange($old,$changes,'tb_socialticket','update');
            $checkReply="";
            if(strtoupper($old->source)=="FACEBOOK"){
              $checkReply=getValueByKey('AUTO_REPLY_FACEBOOK');
            }elseif(strtoupper($old->source)=="TWITTER") {
              $checkReply=getValueByKey('AUTO_REPLY_TWITTER');  
            }elseif(strtoupper($old->source)=="LINKEDIN") {
                $checkReply=getValueByKey('AUTO_REPLY_LINKEDIN');  
            }elseif(strtoupper($old->source)=="WHATSAPP") {
                $checkReply=getValueByKey('AUTO_REPLY_WHATSAPP');  
            }
            if($checkReply=="true"){
              $replyMessage = getValueByKey('AUTO_REPLY_ON_CLOSE');
              $requestData = [
                'source' => $old->source,
                'postMessage' => $replyMessage,
              ];
              $request = new Request($requestData);
              $id=$old->getTweet_id;
              $result = app('App\Http\Controllers\TweetController')->replyTweetId($request,$id);
            }
            DB::commit();
             return redirect()->route('getSocialTicket');
       } catch(Exception $e) {
           DB::rollback();
           return redirect()->back()->with('message',$e->getMessage());
       }
        
    } 
	
	public function getSocialTicketStatusReportbyDates(Request $request){
        
        $files = $request->file('document');
					$res = uploadFileDocuments($files,'projectAttachments');
                return $res;

//         $getSocialLog = TweetLog::where('post_type', 'ticket')
//         ->where('field', 'status');
//     $getSocialLog = $getSocialLog->leftJoin('tb_socialticket', 'tb_socialticket.id', '=', 'tb_change_log.post_id')
//         ->get();
// return $getSocialLog;
    }

    public  function deleteattachmentfromticket($id)
    {	
		DB::beginTransaction();
		try{	
            $getSocialTicket = SocialTicket::where('id', $id)->first();
			$attachment = ProjectAttachment::where('attachment_id',$getSocialTicket->getTweet_id)->first();
			$attachment->delete();
			adminChange("no",$attachment->id,'tb_projectattachment','delete');
		    DB::commit();
			return redirect()->route('editTicket', ['id' => $id]);
		}
		catch(Exception $e) {
			DB::rollback();
			Log::debug( $e->getMessage());
			// return response()->json(['error' => $e->getMessage()], 400);
			// return redirect()->back()->with('message',$e->getMessage());
        }
	}
	
	public function getSubOptions($option)
    {
        $suboptions = getCodeSubOptions($option);
        return response()->json(['suboptions' => $suboptions]);
    }
}
