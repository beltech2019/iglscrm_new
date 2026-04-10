<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TweetPost;
use App\Models\GetTweet;
use App\Models\TweetLog;
use App\Models\TweetReply;
use App\Models\Conversation;
use App\Models\SocialTicket;
use App\Models\LeadTicket;
use App\Models\ProjectAttachment;
use App\Models\TwitterConfigDetail;
use App\Models\SocialUser;
use App\Models\Activity;
use App\Models\Defaults;
use App\Models\Favourite;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Log;
use Illuminate\Support\Facades\Http;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DateTime;
use Twitter;
use Exception;
use DB;
use View;
use Noweh\TwitterApi\Client;
use GuzzleHttp\Client as Whatsapp;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Support\Facades\Storage;

class TweetController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth')->except([
            'getPostInstagram','getTweetIdsByJob','getPostBywhatsapp','getPostByfacebook'
        ]);
		
		
    }
    
    public  function getPostBywhatsapp(Request $request)
    {	DB::beginTransaction();
		try{
		$status = getPlatformStatus('Whatsapp');
		if($status==1){
		$payload = $request->all();
		$challenge = $request->hub_challenge;
		   if($payload  && getSourceStatus("WHATSAPP"))
		   {
			 $fromNum = $payload['entry'][0]['changes'][0]['value']['metadata']['display_phone_number'];
			 $name = $payload['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];
			 $toNum = $payload['entry'][0]['changes'][0]['value']['messages'][0]['from'];
			// $id = $payload['entry'][0]['changes'][0]['value']['messages'][0]['id'];
			 $id = getDigitCodeForWhatsapp();
			 $dateTime = $payload['entry'][0]['changes'][0]['value']['messages'][0]['timestamp'];
			 $body = $payload['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
			 $type = $payload['entry'][0]['changes'][0]['value']['messages'][0]['type'];
			// $id = $payload['entry'][0]['id'];
			 
			$todayDate = Carbon::now()->format('Y-m-d h:i:s A');
			       $auther_id=GetTweet::where('socialUser_id',$toNum)->first();
                    if(!$auther_id){
						$userName= $toNum;
						$saveSocialUser=SocialUser::create([
							'user_id'=>$toNum,
                            'name'=>$name,
                            'user_name'=>$toNum
						]);
                    }else{
                        $name=$auther_id->socialUser_name;
                        $userName=$auther_id->socialUser_userName;
                    }
					$assigntoid=getUserAssignRule('gettweet','Whatsapp',$body);
					$category=setPostAssignRule('post',$body);
					$assignto=User::find($assigntoid);
					$assigntoname="";
					if($assignto){
						$assigntoname=$assignto->name;
					}
					$checkReply=getValueByKey('AUTO_REPLY_WHATSAPP');
                    if($checkReply=="true"){
					    $replyMessage=getValueByKey('AUTO_REPLY_ON_POST');
					    $requestData = [
						   'source' => "Whatsapp",
						   'postMessage' => $replyMessage,
						   'socialUser_id' => $toNum,
						   'newPostMessage' => "hello"
					    ];
					    $request = new Request($requestData);
					    $id=$toNum;
					    $result = $this->replyTweetId($request,$id);
				    }
                    $postInfo = GetTweet::create([
                        'getTweet_id'=>$id,
                        'postMessage'=>$body,
                        'socialUser_id'=>$toNum,
						'mobile_no'=>$toNum,
                        'source'=>"Whatsapp",
                        'postUrl'=>"",
                        'postDate'=>$todayDate,
						'istPostDate'=>$todayDate,
                        'socialUser_name'=>$name,
                        'socialUser_userName'=>$userName,
						'assignedto'=>$assigntoname,
						'post_category'=>$category
                    ]); 
					$oldVal = $postInfo;
					if($assigntoname)
					{
						$postInfo->update(['status'=>'Open']);
						createLog($oldVal,$changes,'post');
					}
		   }
         DB::commit();
		return $challenge;
		}
	
		}  catch(Exception $e) {
			DB::rollback();
			echo $e->getMessage();
        }
    }
	
    public function postTweet(Request $request)
    {
        DB::beginTransaction();
        try{
			if(strtoupper($request->source) == 'WHATSAPP')
			{
				$token =getValueByKey('WHATSAPP_TOKEN');
				$url = getValueByKey('WHATSAPP_URL').'?access_token='.$token;  
				$sender = getValueByKey('WHATSAPP_SENDER'); 
				
				$client = new Whatsapp();
				$response = $client->post($url, [
				'headers' => [
					'Authorization' => 'Bearer ' . $token,
					'Content-Type' => 'application/json',
				],																						
				
				'json' => [
				//	'sender' => $sender,
					'recipient' => 'individual',
					'messaging_product' => "whatsapp",
					'type' => "text",
					 "to" => $request->mobile_no,
					 "text"=> [
							"preview_url"=> false,
							"body"=>$request->postMessage
						]
				],
				]);

				$statusCode = $response->getStatusCode();
				$responseBody = $response->getBody()->getContents();
				$postData = GetTweet::where('getTweet_id',$id)->first();
				$tweet_save = TweetPost::create([
                  'tweeter_id'=>getDigitCodeForWhatsapp(),
                  'tweeter_text'=>$request->postMessage,
				  'socialUser_id'=>$postData->socialUser_id,
                ]);
			}
			else{
             $consumer_Key=getValueByKey('CONSUMER_KEY');
             $consumer_Secret=getValueByKey('CONSUMER_SECRET');
             $access_Token=getValueByKey('ACCESS_TOKEN');
             $token_Secret=getValueByKey('TOKEN_SECRET');
             $connection = new TwitterOAuth($consumer_Key,$consumer_Secret,$access_Token,$token_Secret);
             $connection->setApiVersion("2");
             $tweet_parameters['text'] = $request->postMessage;
             $result = $connection->post('tweets', $tweet_parameters,true);
             if($connection->getLastHttpCode()==403){
                throw new \Exception("You are not allowed to create a Tweet with duplicate content.");       
             }elseif($connection->getLastHttpCode()==201){
                $tweet_save = TweetPost::create([
                  'tweeter_id'=>$result->data->id,
                  'tweeter_text'=>$result->data->text,
                ]);
             }
			}
             DB::commit();
             return redirect()->back()->with('message',"Send");
            //  return success(['response' => $result],$connection->getLastHttpCode());
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->back()->with('message',$e->getMessage());

        }
    }


	public function postreply($id)
    {
		try{
			$postData= "";
			if($id)
			{
				$postData = GetTweet::find($id);
			}
			$getUser = User::get();
            $getSocialUser = GetTweet::get();
			$reply = TweetReply::join('users','users.id','=','tb_tweet_reply.user_id')
			->where('tb_tweet_reply.replyToTweetId',$postData->getTweet_id)
			->select('tb_tweet_reply.*','users.name')
			->orderBy('post_id', 'desc')
			->get();
			$dmData=Conversation::where('socialpost_id',$postData->getTweet_id)
			->orderBy('message_time', 'desc')
			->get();
			return \View::make('post.postTweet', compact(['postData','getUser','getSocialUser','reply','dmData']));
		} catch(Exception $e) {
			Log::debug($e->getMessage());
			return redirect()->back()->with('message',$e->getMessage());
        }
        
    }


	public function replyTweetId(Request $request,$id=null)
    {
		try{
			$trimmedPostMessage = trim($request->postMessage);
			if ($request->method() == 'GET') {
			//	throw new \Exception('Invalid Source');
			}
			
			if (empty($trimmedPostMessage)) {
				throw new \Exception('Please enter a valid message');
			}
			$id2=$id;

			$postData = GetTweet::withTrashed()->where('getTweet_id',$id2)->first();
			if(strtoupper($request->source) == 'LINKEDIN')
			{
			    if(!$postData){
				   throw new Exception("This Post Id is not available on ".$request->source);
			    }
				$media_type="Text";
				if($request->hasFile('media')){
					$media_ext=$request->media->extension();
					if($media_ext=="jpg" || $media_ext=="jpeg" || $media_ext=="png"){
						$media_type="Image";
					}elseif($media_ext="mp4"){
						$media_type="Video";
					}
					else{
						throw new Exception("invalid media type.");
					}
				}
				if(!isset($request->other_info))
				{
				  $request->other_info =  $postData->other_info;
				  $url=$postData->postUrl;
                  $pattern = '/urn:li:activity:\d+/';
                  if (preg_match($pattern, $url, $matches)) {
                    $activityId = $matches[0];
					$request->postUrl=$matches[0];
                  }
				}
				$responseBody = $this->postLinkedinComment($request,$id);
				
				
				$tweet_save = TweetReply::create([
				  'replyToTweetId'=>$id,
				  'tweeter_id'=>$postData->id,
				  'tweeter_text'=>$request->postMessage,
				  'socialUser_id'=>$request->socialUser_id?$request->socialUser_id:$postData->socialUser_id,
				  'media_type'=>$media_type,
				  'user_id'=>loggedUserId(),
				  'url'=>''
				]);
			}
			else if(strtoupper($request->source) == 'FACEBOOK')
			{
			    if(!$postData){
				   throw new Exception("This Post Id is not available on ".$request->source);
			    }
				$media_type="Text";
				if($request->hasFile('media')){
					$media_ext=$request->media->extension();
					if($media_ext=="jpg" || $media_ext=="jpeg" || $media_ext=="png"){
						$media_type="Image";
					}elseif($media_ext="mp4"){
						$media_type="Video";
					}
					else{
						throw new Exception("invalid media type.");
					}
				}
				$tweet_save = TweetReply::create([
				  'replyToTweetId'=>$id,
				  'tweeter_id'=>$postData->id,
				  'tweeter_text'=>$request->postMessage,
				  'socialUser_id'=>$request->socialUser_id?$request->socialUser_id:$postData->socialUser_id,
				  'media_type'=>$media_type,
				  'user_id'=>loggedUserId(),
				  'url'=>''
				]);
				
				$url = sprintf(getValueByKey('FACEBOOK_URL'),$id);  
				$responseBody = $this->postFacebookComment($request,$id);
				
				
				
			}
			else if(strtoupper($request->source) == 'WHATSAPP')
			{
			    if(!$postData){
				   throw new Exception("This Post Id is not available on ".$request->source);
			    }
				$token =getValueByKey('WHATSAPP_TOKEN');
				$url = getValueByKey('WHATSAPP_URL').'?access_token='.$token;  
				$sender = getValueByKey('WHATSAPP_SENDER');

				$client = new Whatsapp();
				$response = $client->post($url, [
					'headers' => [
						'Authorization' => 'Bearer ' . $token,
						'Content-Type' => 'application/json',
					],		
					'json' => [
					//	'sender' => $sender,
						'recipient' => 'individual',
						'messaging_product' => "whatsapp",
						'type' => "text",
						 "to" => $request->mobile_no,
						 "text"=> [
								"preview_url"=> false,
								"body"=>$request->postMessage
							]
					],
				]);
				
				$statusCode = $response->getStatusCode();
				
				$responseBody = $response->getBody()->getContents();
				$res = json_decode($responseBody);
				$id = $res->messages[0]->id;
				$data = [
                  'tweeter_id'=>$id,
                  'tweeter_text'=>$request->postMessage,
                  'replyToTweetId'=>$id,
				  'socialUser_id'=>$request->mobile_no,
				  'user_id'=>loggedUserId(),
                ];
				$tweet_save = TweetReply::create($data);
			}
			elseif(strtoupper($request->source) == 'TWITTER'){
			    if(!$postData){
				   throw new Exception("This Post Id is not available on ".$request->source);
			    }
			  if(isset($request->replyondm)){
				  $responsedm=$this->replyDMByTwitter($request->postMessage,$id2);

			  }else{	
				$accountId = getValueByKey('REPLY_USERACCOUNNTNO');;
				$settings = getReplyClientInfo();
				$settings['account_id'] =$accountId;  
				$client = new Client($settings);
				$return ="";
				$media_type="Text";
				if($request->hasFile('media')){
					$media_ext=$request->media->extension();
					if($media_ext=="jpg" || $media_ext=="jpeg" || $media_ext=="png"){
						$media_type="Image";
					}elseif($media_ext="mp4"){
						$media_type="Video";
					}
					$fileName = $request->media->path(); 
					$consumer_Key=getValueByKey('REPLY_CONSUMER_KEY');
                    $consumer_Secret=getValueByKey('REPLY_CONSUMER_SECRET');
                    $access_Token=getValueByKey('REPLY_ACCESS_TOKEN');
                    $token_Secret=getValueByKey('REPLY_TOKEN_SECRET');  
                    $connection = new TwitterOAuth($consumer_Key,$consumer_Secret,$access_Token,$token_Secret);
					$media1 ="";
					if($media_type=="Video"){
                        $media1 = $connection->upload('media/upload', ['media' => $fileName , 'media_type' => 'video/mp4'],true);
				    }elseif($media_type=="Image"){
                        $media1 = $connection->upload('media/upload', ['media' => $fileName]);
				    }
		            $logMediaId = $media1->media_id_string;
		            $return = $client->tweet()->create()->performRequest(['text' => $request->postMessage,'reply' => [
			            'in_reply_to_tweet_id' => $request->getTweet_id],
						 "media" => ["media_ids" => [$logMediaId]]]);
				}else {
				    $return = $client->tweet()->create()->performRequest(['text' => $request->postMessage,'reply' => [
					   'in_reply_to_tweet_id' => $request->getTweet_id
				    ]]);
			    }
				$url = "";
				$pattern = '/https?:\/\/\S+/';
				if (preg_match($pattern, $return->data->text, $matches)) {
					$url = $matches[0];
				}
				$tweet_save = TweetReply::create([
				  'replyToTweetId'=>$request->getTweet_id,
				  'tweeter_id'=>$return->data->id,
				  'tweeter_text'=>$return->data->text,
				  'socialUser_id'=>$request->socialUser_id?$request->socialUser_id:$postData->socialUser_id,
				  'media_type'=>$media_type,
				  'user_id'=>loggedUserId(),
				  'url'=>$url
				]);  
				$postData->update([
					'dm_status'=>'CLOSE',
				]);
			  }
			}else {
				throw new \Exception('This service is not available on this platform');
			}
		if(!$request->newPostMessage){	
		    $responeded = GetTweet::withTrashed()->where('getTweet_id',$id2)->first();
		    $todayDate = date('Y-m-d');
            $responeded->update([
			   'responed'=>"1",'responseDate'=>$todayDate,
		    ]); 
	    }
		DB::commit();
		return redirect()->back()->with('success',"Send");
		} catch(Exception $e) {
            
			Log::debug($e->getMessage());
			$message=$e->getMessage();
			if(Str::contains($e->getMessage(),'You attempted to reply to a Tweet that is deleted or not visible to you')){
				$message="You attempted to reply to a Tweet that is deleted or not visible to you.";
				DB::rollback();
			}elseif(Str::contains($e->getMessage(),"You are not allowed to create a Tweet with duplicate content.")){
				$message="You are not allowed to reply a Tweet with duplicate content.";
				DB::rollback();
			}
			elseif(Str::contains($e->getMessage(),"Unsupported request - method type")){
				$message="Send";
			}elseif(Str::contains($e->getMessage(),'One or more parameters to your request was invalid.')){
				$message="This user is not found on twitter";
				DB::rollback();
			}elseif(Str::contains($e->getMessage(),'You do not have permission to DM one or more participants')){
				$message="You can't reply that user who is not following you";
				DB::rollback();
			}
           return redirect()->back()->with('success',$message);
        }
    }
	
	public function savebpnumber()
    {
        $posts = GetTweet::get();
        foreach ($posts as $post) {
			$lowercaseMessage = strtolower($post->postMessage);
			$contains = Str::contains($lowercaseMessage,'bp');
			if($contains){
				$position = strpos($lowercaseMessage, 'bp');

				if ($position !== false) {
					$lowercaseMessage = substr($lowercaseMessage, $position + strlen('bp'));
					$lowercaseMessage = substr($lowercaseMessage,0,20);
					preg_match('/\b\d{9,12}\b/', $lowercaseMessage, $matches);
					if (!empty($matches)) {
						$bpNumber = $matches[0];
						$post->update(['bp_number'=>$bpNumber]);
					}
				}	
		    }
        }
		return true;
    }

    
	public function dashboard(Request $request)
    {
		try{
		//   $abc= $this->savebpnumber();
		   $post = GetTweet::orderBy('istPostDate','DESC');
		  if($request->postid)
		  {
			 $post = $post->where('getTweet_id','like','%'.$request->postid.'%');  
		  }
		  if($request->user)
		  {
			$user = $request->user;
			if(strpos($user, "(") !== false){
				$user = strstr($user, '(', true);
				$pattern = '/\((.*?)\)/'; 
				if (preg_match($pattern, $request->user , $matches)) {
					$result = $matches[1];
					$post = $post->where('socialUser_userName',$result);
					$post = $post->where('socialUser_name',$user);
				}
			}
			$post = $post->where('socialUser_name','like','%'.$user.'%');    
			 
			  
		  }
		  if($request->url)
		  {
			 $post = $post->where('postUrl','like','%'.$request->url.'%');  
		  }
		  if($request->message)
		  {
			 $searchValue = substr($request->message, 0, 140);
			 $post = $post->where('postMessage','like','%'.$searchValue.'%');  
		  }
		  if($request->source)
		  {
			 $post = $post->where('source','like','%'.$request->source.'%');  
		  }
		  
		  if($request->converted)
		  {
			 $post = $post->where('converted','like','%'.$request->converted.'%');  
		  }
		  
		  if($request->status)
		  {
			 $post = $post->where('status','like','%'.$request->status.'%');  
		  }
		  
		  if($request->category)
		  {
			 $post = $post->where('post_category','like','%'.$request->category.'%');  
		  }

		  
		  if($request->startDate && $request->endDate && $request->filterType)
		  {
			  if(isset($request->filterType))
			  {
				  $startDate = $request->startDate;
				  $endDate = $request->endDate;
			  }
				$post = $post->whereDate($request->filterType,'>=',$startDate)
				             ->whereDate($request->filterType,'<=',$endDate);  
		  }
		  
		  $postColumn = getColumn('post');
		  
		  if(isset($request->download))
		  {	$col = [];
			$col= ['id',"Post Message","Social User","Source","Post Url","Post Date","Category","Status","Converted","Ticket Id","Lead Number","Aging","BP Number","Reason"];
									
			$data = [];
			$post = $post->get();
			if(!empty($post)){
                foreach($post as $info){
					$info->leads = implode(',', LeadTicket::where('getTweet_id', $info->getTweet_id)->pluck('leadId')->toArray());
					$info->socialTickets = implode(',', SocialTicket::where('getTweet_id', $info->getTweet_id)->pluck('ticket_id')->toArray());
                    $info->activies = Activity::leftjoin('users','users.id','=','tb_activity.created_by')
                    ->where("post_id",$info->id)
                    ->select('tb_activity.text','tb_activity.created_at','users.name')
                    ->get();
                }
            }
			
			return  downloadCsv($post,$col);
		  }
		 
		   $post = $post->paginate(getValueByKey('PAGENATION_COUNT'));
		   
		
		} catch(Exception $e) {
			//echo $e->getMessage();die;
			return redirect()->back()->with('message',$e->getMessage());
        }
        $departments = Department::all();
		return \View::make('post.dashboard', compact(['post','postColumn','departments']));
    }

    
	public function recentdashboard(Request $request)
    {
		try{
			$endDate = Carbon::now();
            $startDate = $endDate->copy()->subDays(getValueByKey('LAST_POST_DAYS')-1);
		   $post = GetTweet::whereDate('istPostDate', '>=', $startDate)
                  ->whereDate('istPostDate', '<=', $endDate)
                  ->orderby('id','desc');
		  if($request->postid)
		  {
			 $post = $post->where('getTweet_id','like','%'.$request->postid.'%');  
		  }
		  if($request->user)
		  {
			 $post = $post->where('socialUser_name','like','%'.$request->user.'%');  
		  }
		  if($request->url)
		  {
			 $post = $post->where('postUrl','like','%'.$request->url.'%');  
		  }
		  if($request->message)
		  {
			 $post = $post->where('postMessage','like','%'.$request->message.'%');  
		  }
		  if($request->source)
		  {
			 $post = $post->where('source','like','%'.$request->source.'%');  
		  }
		  
		  if($request->converted)
		  {
			 $post = $post->where('converted','like','%'.$request->converted.'%');  
		  }
		  
		  if($request->postDate)
		  {
			 $post = $post->where('postDate','like','%'.$request->postDate.'%');  
		  }
		  
		  if(isset($request->download))
		  {	$col = [];
			$col= ['id',"Post Message","Social User","Source","Post Url","Post Date","Category","Status","Converted","Ticket Id","Lead Number","Aging","BP Number","Reason"];
									
			$data = [];
			$post = $post->get();
			if(!empty($post)){
                foreach($post as $info){
					$info->socialTickets = implode(',', SocialTicket::where('getTweet_id', $info->getTweet_id)->pluck('ticket_id')->toArray());
					$info->leads = implode(',', LeadTicket::where('getTweet_id', $info->getTweet_id)->pluck('leadId')->toArray());
                    $info->activies = Activity::leftjoin('users','users.id','=','tb_activity.created_by')
                    ->where("post_id",$info->id)
                    ->select('tb_activity.text','tb_activity.created_at','users.name')
                    ->get();
                }
            }
			
			return  downloadCsv($post,$col);
		  }
		   $post = $post->paginate(getValueByKey('PAGENATION_COUNT'));
		   $postColumn = getColumn('post');
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
        
		return \View::make('post.dashboard', compact(['post','postColumn']));
    }

	public function manualAddSocailPost(Request $request,$id =null)
    {
        $validate = Validator::make($request->all(), [
            'getTweet_id' => 'required',
         ]);
         DB::beginTransaction();
		try{
			$check=GetTweet::where('getTweet_id',$request->input('getTweet_id'))->first();
			if($check && !$id){
				throw new \Exception("This post id already exist");   	
			}
			if($request->hasFile('media')){
				$files = $request->file('media');
				$res = uploadFileDocuments($files,'projectAttachments');
				$uploadDateTime = Carbon::now();
				$uploadtime = $uploadDateTime->format('Y-m-d H:i:s');
                if($res){
					$attachment=ProjectAttachment::create([
						'attachment_id'=>$request->input('getTweet_id'),
                        'fileName'=>$res['fileName'],
                        'filePath'=>$res['filePath'],
                        'fileUrl'=>$res['fileUrl'],
						'upload_time'=>$uploadtime
					]);
                    adminChange("no",$attachment,'tb_projectattachment','create');
				}
			}
			$requestInfo = [
                'getTweet_id'=>$request->input('getTweet_id'),
                'postMessage'=>$request->input('postMessage'),
                'socialUser_name'=>$request->input('socialUser_name'),
				'socialUser_id'=>$request->input('socialUser_id'),
                'source'=>$request->input('source'),
                'postUrl'=>$request->input('postUrl'),
                'postDate'=>$request->input('istPostDate'),
				'istPostDate'=>$request->input('istPostDate'),
				'post_category'=>$request->input('post_category'),
                'mobile_no'=>$request->input('mobile_no'),
                'email_id'=>$request->input('email_id'),
                'assignedto'=>$request->input('assigned_to'),
				'category'=>$request->input('category'),
				'status'=>$request->input('status'),
				'bp_number'=>$request->input('bp_number'),
              ];  
			  
			$logInUserId=loggedUserId();
			if($id)
			{
				$oldVal = GetTweet::find($id);
				$postInfo = GetTweet::find($id);
				$postInfo->update($request->all());
				if(isset($request->reason_text)){
					$activityInfo = [
						'created_by'=> $logInUserId,
						'type'=>'POST',
						'post_id'=> $id,
						'text'=>$request->reason_text,
					];
					$activity = Activity::create($activityInfo);
				}
				$changes = $postInfo->getChanges();
				createLog($oldVal,$changes,'post');
				if($changes){
					adminChange($oldVal,$changes,'tb_gettweet','update');
				}		
			}
			else{
				$postInfo = GetTweet::create($requestInfo);
				if(!empty($postInfo && isset($request->reason_text))){
					$activityInfo = [
						'created_by'=> $logInUserId,
						'type'=>'POST',
						'post_id'=> $postInfo->id,
						'text'=>$request->reason_text,
					];
					$activity = Activity::create($activityInfo);
				}

				adminChange("no",$postInfo,'tb_gettweet','create');
				$socialUser=SocialUser::where('user_id',$request->input('socialUser_id'))->first();
				if(!$socialUser){
			      $saveSocialUser=SocialUser::create([
					'user_id'=>$request->input('socialUser_id'),
                    'user_name'=>$request->input('socialUser_name').$request->input('socialUser_id'),
				    'name'=>$request->input('socialUser_name'),
			      ]);
			    }
			}
			
		} catch(Exception $e) {
			Log::debug($e->getMessage());
            DB::rollback();
			return redirect()->back()->with('message',$e->getMessage());
        }
        DB::commit();
		// return redirect()->back()->with('message',"Saved");
        return redirect()->route('dashboard')->with('success','Post Saved');
    }


	public function getTweetIds(Request $request)
    {
		try{
		$accountId = getValueByKey('USERACCOUNNTNO');
		$settings = getClientInfo();
		$nowTime = Carbon::now('UTC');
		$start_time=getValueByKey('ENDTIME_USERACCOUNT');
        $timeString = $start_time;
		$carbon = Carbon::parse($timeString);
		$newCarbon = $carbon->addSeconds(1);
		$start_time = $newCarbon->format('Y-m-d\TH:i:s\Z');
		$end_time = $nowTime->format('Y-m-d\TH:i:s\Z');
		$settings['account_id'] =$accountId;  
		$client = new Client($settings);
		$tweets	 = $client->timeline()->getRecentMentions($accountId,$start_time,$end_time)->performRequest();
		if($tweets->meta->result_count!=0)
		{
        $tweets = array_reverse($tweets->data);
        if(!empty($tweets	))
		{
			foreach($tweets	 as $data)
			{
                $getTweet_id=GetTweet::where('getTweet_id',$data->id)->first();
                if(!$getTweet_id){
                    $carbonDate = Carbon::parse($data->created_at, 'UTC');
                    $istDate = $carbonDate->tz('Asia/Kolkata');
                    $formattedDate= $istDate->format('Y-m-d H:i:s');
                    $auther_id=GetTweet::where('socialUser_id',$data->author_id)->first();
                    if(!$auther_id){
                        $auther = $client->userLookup()->findByIdOrUsername(intval($data->author_id))->performRequest();
                        $auther = $auther->data;
                        $name=$auther->name;
                        $userName=$auther->username;
						$saveSocialUser=SocialUser::create([
							'user_id'=>$data->author_id,
                            'name'=>$name,
                            'date_modified'=>$formattedDate,
                            'user_name'=>$userName
						]);
                    }else{
                        $name=$auther_id->socialUser_name;
                        $userName=$auther_id->socialUser_userName;
                    }
					$cleanedText = preg_replace('/\s+/', ' ', $data->text);
					$assigntoid=getUserAssignRule('gettweet','twitter',$cleanedText);
					$category=setPostAssignRule('post',$cleanedText);
					$assignto=User::find($assigntoid);
					$assigntoname="";
					if($assignto){
						$assigntoname=$assignto->id;
					}
					$checkReply=getValueByKey('AUTO_REPLY_TWITTER');
                    if($checkReply=="true"){
					    $replyMessage=getValueByKey('AUTO_REPLY_ON_POST');
					    $requestData = [
						   'source' => "Twitter",
						   'postMessage' => $replyMessage,
						   'socialUser_id' => $data->author_id,
						   'newPostMessage' => "hello"
					    ];
					    $request = new Request($requestData);
					    $id=$data->id;
					    $result = $this->replyTweetId($request,$id);
				    }
                    $postInfo = GetTweet::create([
                        'getTweet_id'=>$data->id,
                        'postMessage'=>$cleanedText,
                        'socialUser_id'=>$data->author_id,
                        'source'=>"Twitter",
                        'postUrl'=>"https://twitter.com/user/status/".$data->id,
                        'postDate'=>$data->created_at,
						'istPostDate'=>$formattedDate,
                        'socialUser_name'=>$name,
                        'socialUser_userName'=>$userName,
						'post_category'=>$category,
						'assignedto'=>$assigntoname
                    ]);  
					$end_time = $data->created_at;
                }
			}
			$save_default=Defaults::where('key','ENDTIME_USERACCOUNT')->first();
			$save_default->update([
				'value'=>$end_time,
			]);
		}
	    }
		return success(['response' => $tweets]);
		} catch(Exception $e) {
            DB::rollback();
            return error(['error' => $e->getMessage()], 400);
        }
    }

         
    public function getSocialPostById($id)
    {
		try{
			$logUser=loggedUserId();
			$getSocial = GetTweet::where('getTweet_id',$id)->first();
			$attacheddata=ProjectAttachment::where('attachment_id',$id)->get();
			$getFavourite = Favourite::where('user_id',$logUser)
			->where('type_id',$getSocial->id)
			->where('type','tb_gettweet')
			->first();
			$getActivity = Activity::join('users','users.id','=','tb_activity.created_by')->where('tb_activity.post_id',$getSocial->id)->where('tb_activity.type','POST')->select('tb_activity.*','users.name')->orderBy('tb_activity.id','DESC')->get();
			$reply = TweetReply::join('users','users.id','=','tb_tweet_reply.user_id')
			->where('tb_tweet_reply.replyToTweetId',$id)
			->select('tb_tweet_reply.*','users.name')
			->orderBy('post_id', 'desc')
			->get();
			$dmData=Conversation::where('socialpost_id',$id)
			->orderBy('message_time', 'desc')
			->get();
			$leads =  LeadTicket::where('getTweet_id', $getSocial->getTweet_id)->get();
			$socialTickets = SocialTicket::where('getTweet_id', $getSocial->getTweet_id)->get();
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
        return \View::make('post.socialpost_innerpage', compact(['getSocial','reply','getFavourite','dmData','attacheddata','getActivity','leads','socialTickets']));
    }

    public function deletePost($id)
    {  
		try{
            $item = GetTweet::find($id);     
            if (!$item) {
               return response()->json(['message' => 'Item not found'], 404);
            }
            $item->delete();
			adminChange("no",$id,'tb_gettweet','delete');
            $post = GetTweet::get();		
            return redirect()->route('dashboard');
        } catch(Exception $e) {
           return redirect()->back()->with('message',$e->getMessage());
        }
    }
	public function tweetLogList(Request $request, $id,$type='post')
    {
		try{
			$getSocialLog = TweetLog::where('post_id',$id)->where('post_type',$type)->orderBy('log_id','DESC')->get();
           
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
        return \View::make('post.change_log', compact(['getSocialLog']));
    }

    public function createpost(Request $request, $id=null)
    {
		$now=Carbon::now();
		$formattedDate= $now->format('Y-m-d H:i:s');
		try{
			$create_post="Create Post";
			$postData= "";
			$getSource = getSource();
			$attacheddata=[];
			$getActivity=[];
			$departments = Department::all(); 
			if($id)
			{
				$create_post="Edit Post";
				$postData = GetTweet::find($id);
                $formattedDate=$postData->istPostDate;
				$attacheddata=ProjectAttachment::where('attachment_id',$postData->getTweet_id)->get();
				$getActivity = Activity::join('users','users.id','=','tb_activity.created_by')->where('tb_activity.post_id',$id)->where('tb_activity.type','POST')->select('tb_activity.*','users.name')->orderBy('tb_activity.id','DESC')->get();
				if ($request->isMethod('post') && $request->has('department') && $postData) {
					$request->validate([
						'department' => 'nullable|exists:tb_department,department_id'
					]);

					$postData->department = $request->department ?: null;
					$postData->save();
				}
			}
			$getUser = User::get();
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
			return \View::make('post.createpost', compact(['modal','formattedDate','getSource','create_post','postData','getUser','getSocialUser','attacheddata','getActivity', 'departments']));
		} catch(Exception $e) {
			Log::debug($e->getMessage());
			return redirect()->back()->with('message',$e->getMessage());
        }
    }
	
	
	
	public function deleteAll(Request $request)
    {
		try{
			if($request->postid)
			{
				foreach($request->postid as $postId)
				{
					$post = GetTweet::find($postId);
					$post->delete();
					adminChange("no",$postId,'tb_gettweet','delete');
				}
			}
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
       return redirect()->back()->with('message','Successfully Deleted');
    }
	
	public function getTweetIdsByJob()
    {
		try{
        $accountId = getValueByKey('USERACCOUNNTNO');
		$settings = getClientInfo();
		$nowTime = Carbon::now('UTC');
		$start_time=getValueByKey('ENDTIME_USERACCOUNT');
        $timeString = $start_time;
		$carbon = Carbon::parse($timeString);
		$newCarbon = $carbon->addSeconds(1);
		$start_time = $newCarbon->format('Y-m-d\TH:i:s\Z');
		$end_time = $nowTime->format('Y-m-d\TH:i:s\Z');
		$settings['account_id'] =$accountId;  
		$client = new Client($settings);
		$tweets	 = $client->timeline()->getRecentMentions($accountId,$start_time,$end_time)->performRequest();
		if($tweets->meta->result_count!=0)
		{
        $tweets = array_reverse($tweets->data);
        if(!empty($tweets	))
		{
			foreach($tweets	 as $data)
			{
                $getTweet_id=GetTweet::where('getTweet_id',$data->id)->first();
                if(!$getTweet_id){
                    $carbonDate = Carbon::parse($data->created_at, 'UTC');
                    $istDate = $carbonDate->tz('Asia/Kolkata');
                    $formattedDate= $istDate->format('Y-m-d H:i:s');
                    $auther_id=GetTweet::where('socialUser_id',$data->author_id)->first();
                    if(!$auther_id){
                        $auther = $client->userLookup()->findByIdOrUsername(intval($data->author_id))->performRequest();
                        $auther = $auther->data;
                        $name=$auther->name;
                        $userName=$auther->username;
						$saveSocialUser=SocialUser::create([
							'user_id'=>$data->author_id,
                            'name'=>$name,
                            'date_modified'=>$formattedDate,
                            'user_name'=>$userName
						]);
                    }else{
                        $name=$auther_id->socialUser_name;
                        $userName=$auther_id->socialUser_userName;
                    }
					$cleanedText = preg_replace('/\s+/', ' ', $data->text);
					$assigntoid=getUserAssignRule('gettweet','twitter',$cleanedText);
					$category=setPostAssignRule('post',$cleanedText);
					$assignto=User::find($assigntoid);
					$assigntoname="";
					if($assignto){
						$assigntoname=$assignto->name;
					}
                    $postInfo = GetTweet::create([
                        'getTweet_id'=>$data->id,
                        'postMessage'=>$cleanedText,
                        'socialUser_id'=>$data->author_id,
                        'source'=>"Twitter",
                        'postUrl'=>"https://twitter.com/user/status/".$data->id,
                        'postDate'=>$data->created_at,
						'istPostDate'=>$formattedDate,
                        'socialUser_name'=>$name,
                        'socialUser_userName'=>$userName,
						'assignedto'=>$assigntoname,
						'post_category'=>$post_category,
                    ]);  
					$end_time = $data->created_at;
                }
			}
			$save_default=Defaults::where('key','ENDTIME_USERACCOUNT')->first();
			$save_default->update([
				'value'=>$end_time,
			]);
		}
	    }
		return success(['response' => $tweets]);
		} catch(Exception $e) {
            DB::rollback();
            return error(['error' => $e->getMessage()], 400);
        }
    }
	

	public function getTweetIdsByJob1($accountId,$start_time)
    {
		try{
        //$accountId = getValueByKey('USERACCOUNNTNO1');
		$settings = getClientInfo();
		$nowTime = Carbon::now('UTC');
		//$start_time=getValueByKey('ENDTIME_USERACCOUNT1');
        $timeString = $start_time;
		$carbon = Carbon::parse($timeString);
		$newCarbon = $carbon->addSeconds(1);
		$start_time = $newCarbon->format('Y-m-d\TH:i:s\Z');
		$end_time = $nowTime->format('Y-m-d\TH:i:s\Z');
		$settings['account_id'] =$accountId;  
		log::info($start_time.'   '.$end_time);
		$client = new Client($settings);
		$tweets	 = $client->timeline()->getRecentMentions($accountId,$start_time,$end_time)->performRequest();
		if($tweets->meta->result_count!=0)
		{
        $tweets = array_reverse($tweets->data);
        if(!empty($tweets	))
		{
			foreach($tweets	 as $data)
			{
                $getTweet_id=GetTweet::where('getTweet_id',$data->id)->first();
                if(!$getTweet_id){
                    $carbonDate = Carbon::parse($data->created_at, 'UTC');
                    $istDate = $carbonDate->tz('Asia/Kolkata');
                    $formattedDate= $istDate->format('Y-m-d H:i:s');
                    $auther_id=GetTweet::where('socialUser_id',$data->author_id)->first();
                    if(!$auther_id){
                        $auther = $client->userLookup()->findByIdOrUsername(intval($data->author_id))->performRequest();
                        $auther = $auther->data;
                        $name=convertSpecialCharToNormalChar($auther->name);
						$name=remove_emoji($name);
                        $userName=$auther->username;
						$saveSocialUser=SocialUser::create([
							'user_id'=>$data->author_id,
                            'name'=>$name,
                            'date_modified'=>$formattedDate,
                            'user_name'=>$userName
						]);
                    }else{
                        $name=$auther_id->socialUser_name;
                        $userName=$auther_id->socialUser_userName;
                    }
					$cleanedText = preg_replace('/\s+/', ' ', $data->text);
					$assigntoid=getUserAssignRule('gettweet','twitter',$cleanedText);
					$category=setPostAssignRule('post',$cleanedText);
					$assignto=User::find($assigntoid);
					$assigntoname="";
					if($assignto){
						$assigntoname=$assignto->name;
					}
					$checkReply=getValueByKey('AUTO_REPLY_TWITTER');
                    if($checkReply=="true"){
					    $replyMessage=getValueByKey('AUTO_REPLY_ON_POST');
					    $requestData = [
						   'source' => "Twitter",
						   'postMessage' => $replyMessage,
						   'socialUser_id' => $data->author_id,
						   'newPostMessage' => "hello"
					    ];
					    $request = new Request($requestData);
					    $id=$data->id;
					    $result = $this->replyTweetId($request,$id);
				    }
                    $postInfo = GetTweet::create([
                        'getTweet_id'=>$data->id,
                        'postMessage'=>$cleanedText,
                        'socialUser_id'=>$data->author_id,
                        'source'=>"Twitter",
                        'postUrl'=>"https://twitter.com/user/status/".$data->id,
                        'postDate'=>$data->created_at,
						'istPostDate'=>$formattedDate,
                        'socialUser_name'=>$name,
                        'socialUser_userName'=>$userName,
                        'post_category'=>$category,
						'assignedto'=>$assigntoname,
						'bp_number'=>bpnumberfetch($cleanedText)
                    ]);  
					$end_time = $data->created_at;
                }
			}
			$save_default=Defaults::where('key','ENDTIME_USERACCOUNT1')->first();
			$save_default->update([
				'value'=>$end_time,
			]);
		}
	    }
		return success(['response' => $tweets]);
		} catch(Exception $e) {
            DB::rollback();
            log::debug('error'.$e->getMessage());
            return error(['error' => $e->getMessage()], 400);
        }
    }
    
    
    

	public function getTweetIdsByJobFree()
    {
		try{
			// $configdetails = TwitterConfigDetail::get();
			$selectedDetails = TwitterConfigDetail::find(2);
			// foreach($configdetails as $configdetail){
			// 	if(lasttimecheck($configdetail->last_time) && $configdetail->count < 100){
            //         $selectedDetails = $configdetail;
			// 		break;
			// 	}
			// }
			$accountId = '1844635590546837513';
			$end_time = getValueByKey('1844635590546837513_ENDTIME_USERACCOUNT');
			$this->getTweetIdsByJobFree3($selectedDetails,$end_time,$accountId);
			return $end_time;
		} catch(Exception $e) {
            return error(['error' => $e->getMessage()], 400);
        }
    }

	public function getTweetIdsByJobFree2()
    {
		try{
			// $configdetails = TwitterConfigDetail::get();
			$selectedDetails = TwitterConfigDetail::find(2);
			// foreach($configdetails as $configdetail){
			// 	if(lasttimecheck($configdetail->last_time) && $configdetail->count < 100){
            //         $selectedDetails = $configdetail;
			// 		break;
			// 	}
			// }
			$accountId = '1552177045383180288';
			$end_time = getValueByKey('1552177045383180288_ENDTIME_USERACCOUNT');
			$this->getTweetIdsByJobFree3($selectedDetails,$end_time,$accountId);
			return $end_time;
		} catch(Exception $e) {
            return error(['error' => $e->getMessage()], 400);
        }
    }

	public function getTweetIdsByJobFree3($selectedDetails,$start_time,$accountId)
    {
        $savelastTime=Carbon::now()->format('Y-m-d H:i:s');
		try{
		$settings = getClientInfoFree($selectedDetails);
		log::info($settings);
		$nowTime = Carbon::now('UTC');
        $timeString = $start_time;
		$carbon = Carbon::parse($timeString);
		$newCarbon = $carbon->addSeconds(1);
		$start_time = $newCarbon->format('Y-m-d\TH:i:s\Z');
		$end_time = $nowTime->format('Y-m-d\TH:i:s\Z');
		$settings['account_id'] =$accountId;  
		$client = new Client($settings);
		$i=0;
		$tweets	 = $client->timeline()->getRecentMentions($accountId)->performRequest([
        'start_time' => $start_time,
        'end_time' => $end_time,

        'tweet.fields' => 'author_id,created_at,attachments,text',
        'expansions' => 'attachments.media_keys',
        'media.fields' => 'preview_image_url,url,type',
        'poll.fields' => 'duration_minutes,end_datetime,id,options'
    ]);
		log::info("API Hit");
		if($tweets->meta->result_count!=0)
		{
        $tweets = array_reverse($tweets->data);
        if(!empty($tweets	))
		{
			foreach($tweets	 as $data)
			{
			    $i++;
                $getTweet_id=GetTweet::where('getTweet_id',$data->id)->first();
                if(!$getTweet_id){
                    log::info("Tweet get");
                    $carbonDate = Carbon::parse($data->created_at, 'UTC');
                    $istDate = $carbonDate->tz('Asia/Kolkata');
                    $formattedDate= $istDate->format('Y-m-d H:i:s');
                    $auther_id=GetTweet::where('socialUser_id',$data->author_id)->first();
                    if(!$auther_id){
						$selectedDetails2=checkuserlasttime();
				// 		if(checkuserlasttime()){
				// 			$settings = getClientInfoFree($selectedDetails2);
				// 			$settings['account_id'] =$accountId;  
				// 			$client = new Client($settings);
				// 			$auther = $client->userLookup()->findByIdOrUsername(intval($data->author_id))->performRequest();
				// 			$auther = $auther->data;
				// 			$name=convertSpecialCharToNormalChar($auther->name);
				// 			$name=remove_emoji($name);
				// 			$userName=$auther->username;
				// 			$saveSocialUser=SocialUser::create([
				// 				'user_id'=>$data->author_id,
				// 				'name'=>$name,
				// 				'date_modified'=>$formattedDate,
				// 				'user_name'=>$userName
				// 			]);
				// 			$saveConfigDetail=TwitterConfigDetail::find($selectedDetails2->id);
				// 			$saveConfigDetail->update(['user_last_time'=>$savelastTime]);
				// 		}else{
							$name=$data->author_id;
							$userName=$data->author_id;
							$saveSocialUser=SocialUser::create([
								'user_id'=>$data->author_id,
								'name'=>$name,
								'date_modified'=>$formattedDate,
								'user_name'=>$userName
							]);
				// 		}
                    }else{
                        $name=$auther_id->socialUser_name;
                        $userName=$auther_id->socialUser_userName;
                    }
					$cleanedText = preg_replace('/\s+/', ' ', $data->text);
					$assigntoid=getUserAssignRule('gettweet','twitter',$cleanedText);
					$category=setPostAssignRule('post',$cleanedText);
					$assignto=User::find($assigntoid);
					$assigntoname="";
					if($assignto){
						$assigntoname=$assignto->name;
					}
					$checkReply=getValueByKey('AUTO_REPLY_TWITTER');
                    if($checkReply=="true"){
					    $replyMessage=getValueByKey('AUTO_REPLY_ON_POST');
					    $requestData = [
						   'source' => "Twitter",
						   'postMessage' => $replyMessage,
						   'socialUser_id' => $data->author_id,
						   'newPostMessage' => "hello"
					    ];
					    $request = new Request($requestData);
					    $id=$data->id;
					    $result = $this->replyTweetId($request,$id);
				    }
                    $postInfo = GetTweet::create([
                        'getTweet_id'=>$data->id,
                        'postMessage'=>$cleanedText,
                        'socialUser_id'=>$data->author_id,
                        'source'=>"Twitter",
                        'postUrl'=>"https://twitter.com/user/status/".$data->id,
                        'postDate'=>$data->created_at,
						'istPostDate'=>$formattedDate,
                        'socialUser_name'=>$name,
                        'socialUser_userName'=>$userName,
                        'post_category'=>$category,
						'assignedto'=>$assigntoname,
						'bp_number'=>bpnumberfetch($cleanedText)
                    ]);  
					$end_time = $data->created_at;
                }
			}
			$save_default=Defaults::where('key',$accountId.'_ENDTIME_USERACCOUNT')->first();
			$save_default->update([
				'value'=>$end_time,
			]);
		}
	    }
		$saveConfigDetail=TwitterConfigDetail::find($selectedDetails->id);
		$saveConfigDetail->update([
			'last_time'=>$savelastTime,
			'count'=>$i + $saveConfigDetail->count 
		]);
		return $i;
		} catch(Exception $e) {
            log::error($e);
            $saveConfigDetail=TwitterConfigDetail::find($selectedDetails->id);
            $saveConfigDetail->update([
			'last_time'=>$savelastTime 
		    ]);
            return error(['error' => $e->getMessage()], 400);
        }
    }

	
	public function showHideColumn(Request $request)
    {
		try{
			showHide($request);
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
       return redirect()->back()->with('success','Column filter success');
    }

	public function Profile(Request $request)
    {
		try{
			showHide($request);
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
       return redirect()->back()->with('success','Column filter success');
    }


	public function getUserbyusername()
    {
		try{
		    log::info("job");
			$accountId = getValueByKey('USERACCOUNNTNO1');
		    $settings = getClientInfo();
		    $settings['account_id'] =$accountId;
			$getUserName= getValueByKey('TWITTER_USERNAMES');
		    $client = new Client($settings);
			$userName= explode(',', $getUserName);
		    $status = getPlatformStatus('Twitter');
		    if($status==1){
		        $this->getTweetIdsByJobFree();
		        $this->getTweetIdsByJobFree2();
// 			foreach($userName as $userNames){
// 				$checkDefault=Defaults::where('key',$userNames."_ACCOUNT_KEY")->first();
				
// 				if(!$checkDefault){
//                     $auther = $client->userLookup()->findByIdOrUsername($userNames)->performRequest();
// 					if($auther){
// 					    $saveDefault=Defaults::create([
// 						   'key'=>$userNames."_ACCOUNT_KEY",
// 						   'value'=>$auther->data->id,
// 						//   'type'=>'user',
// 						   'label'=>$userNames." User Id"
// 					    ]);
						
// 						$nowTime = Carbon::now('UTC');
// 						$end_time = $nowTime->format('Y-m-d\TH:i:s\Z');
		
// 					    $resDate = Defaults::create([
// 						   'key'=>$userNames."_ENDTIME_USERACCOUNT",
// 						   'value'=>$end_time,
// 						   'type'=>'user',
// 						   'label'=>$userNames." End Time"
// 					    ]);
						
// 						$accountId = $saveDefault->value;
// 				    }
// 				}
// 				else{
// 					$end_time = Defaults::where('key',$userNames."_ENDTIME_USERACCOUNT")->first();
// 					$end_time = $end_time->value;
// 					$accountId = $checkDefault->value;
// 				}
// 				$this->getTweetIdsByJob1($accountId,$end_time);
// 			}
		    }
			$statuslinkedin = getPlatformStatus('Linkedin');
		    if($statuslinkedin==1){
			  $this->getLinkedinPost();
			}  
			$facebookstatus = getPlatformStatus('Facebook');
			if($facebookstatus==1){
			  $this->getPostByfacebook();
			} 
			// $instastatus = getPlatformStatus('Instagram');
			// if($instastatus==1){
			//   $this->getPostInsta();
			// }  
		} catch(Exception $e) {
			Log::debug( $e->getMessage());
			// return redirect()->back()->with('message',$e->getMessage());
        }
    }

    
	public function uploadDoc(){
		$consumer_Key=getValueByKey('CONSUMER_KEY');
        $consumer_Secret=getValueByKey('CONSUMER_SECRET');
        $access_Token=getValueByKey('ACCESS_TOKEN');
        $token_Secret=getValueByKey('TOKEN_SECRET');
        $connection = new TwitterOAuth($consumer_Key,$consumer_Secret,$access_Token,$token_Secret);
        $media1 = $connection->upload('media/upload', ['media' => 'C:\Users\asxni\Downloads\Artboard2.jpg']);
		$logMediaId = $media1->media_id_string; 
		$accountId = getValueByKey('userAccounntNo');
		$settings = getClientInfo();
		$settings['account_id'] =$accountId;  
		$id="1683468218487263232";
		$client = new Client($settings);
		$return = $client->tweet()->create()->performRequest(['text' => "My tweet testing",'reply' => [
			'in_reply_to_tweet_id' => $id
		], "media" => ["media_ids" => [$logMediaId]]]);
		return success(['response' => $return]);
	}


	// public  function getPostByfacebook(Request $request)
    // {	DB::beginTransaction();
	// 	try{
	// 		$status = getPlatformStatus('Facebook');
	// 		if($status==1){
	// 		$challenge ="";
	// 	    if(isset($request->hub_challenge))
	// 	    {
	// 		    $challenge = $request->hub_challenge;
	// 	    }
	// 		$payload = $request->all();
	// 		Log::debug($payload);
	// 	    if($payload && getSourceStatus("FACEBOOK"))
	// 	    {
	// 			$field = $payload['entry'][0]['changes'][0]['field'];
	// 			if($field =='mention')
	// 			{
	// 				$body =$payload['entry'][0]['changes'][0]['value']['message'];
	// 				$post_id =$payload['entry'][0]['changes'][0]['value']['post_id'];
	// 				$item =$payload['entry'][0]['changes'][0]['value']['item'];
	// 				$toNum  = "32102023";
	// 				$name = "Facebook";
	// 				$user_name = "facebook2023";
				
	// 				$body =$payload['entry'][0]['changes'][0]['value']['message'];
	// 				$id =$payload['entry'][0]['changes'][0]['value']['post_id'];
	// 				$item =$payload['entry'][0]['changes'][0]['value']['item'];
		 
	// 				$todayDate = Carbon::now()->format('Y-m-d h:i:s A');
	// 				$auther_id=GetTweet::where('socialUser_id',$toNum)->first();
					
	// 				if(!$auther_id){
	// 					$userName= $toNum;
	// 					$saveSocialUser=SocialUser::create([
	// 						'user_id'=>$toNum,
	// 						'name'=>$name,
	// 						'user_name'=>$user_name
	// 					]);
	// 				}else{
	// 					$name=$auther_id->socialUser_name;
	// 					$userName=$auther_id->socialUser_userName;
	// 				}
					
	// 				$assigntoid=getUserAssignRule('gettweet','Facebook',$body);
	// 				$category=setPostAssignRule('post',$body);
	// 				$assignto=User::find($assigntoid);
					
	// 				$assigntoname="";
	// 				if($assignto){
	// 					$assigntoname=$assignto->name;
	// 				}
	// 				$checkReply=getValueByKey('AUTO_REPLY_FACEBOOK');
    //                 if($checkReply=="true"){
	// 				    $replyMessage=getValueByKey('AUTO_REPLY_ON_POST');
	// 				    $requestData = [
	// 					   'source' => "Facebook",
	// 					   'postMessage' => $replyMessage,
	// 					   'socialUser_id' => $toNum,
	// 					   'newPostMessage' => "hello"
	// 				    ];
	// 				    $request = new Request($requestData);
	// 				    $id=$id;
	// 				    $result = $this->replyTweetId($request,$id);
	// 			    }
	// 				$postInfo = GetTweet::create([
	// 					'getTweet_id'=>$id,
	// 					'postMessage'=>$body,
	// 					'socialUser_id'=>$toNum,
	// 					'mobile_no'=>$toNum,
	// 					'source'=>"Facebook",
	// 					'postUrl'=>"",
	// 					'postDate'=>$todayDate,
	// 					'istPostDate'=>$todayDate,
	// 					'socialUser_name'=>$name,
	// 					'socialUser_userName'=>$userName,
	// 					'assignedto'=>$assigntoname,
	// 					'post_category'=>$category
	// 				]); 
	// 				$oldVal = $postInfo;
	// 				if($assigntoname)
	// 				{
	// 					$postInfo->update(['status'=>'Open']);
	// 					createLog($oldVal,$changes,'post');
	// 				}
	// 			}
	// 		}
	// 	    DB::commit();
	// 		return $challenge;
	// 	  }	
	// 	}
	// 	catch(Exception $e) {
	// 		DB::rollback();
	// 		Log::debug( $e->getMessage());
	// 		// return redirect()->back()->with('message',$e->getMessage());
    //     }
	// }


	public function postLinkedinComment($request,$postId)
	{
		$accessToken = getValueByKey('LINKEDIN_TOKEN');
		$file = $request->file('media');
		$payload = json_decode($request->other_info);
		$subscriber = 'urn:li:organization:458244';
		$sourcePost=$request->postUrl;
		$entity = $request->getTweet_id;
		
		if ($request->hasFile('media')) {
            $image = $request->file('media');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads'), $imageName);
			$imagePath = public_path('uploads')."/".$imageName;
        }
		
		$commentMessage = $request->postMessage;
		

		$client = new Whatsapp();
		
		$url = sprintf(getValueByKey('LINKEDIN_URL'),urlencode($entity));
		$requestBody = [
		   "actor"=>$subscriber,
		   "object"=>$sourcePost,
		   "message"=>[
			  "text"=>$commentMessage
		   ]
		];
		$headers = [
			'Authorization' => 'Bearer ' . $accessToken,
			'Content-Type' => 'application/json',
		];

		$response = $client->post($url, [
			'headers' => $headers,
			'json' => $requestBody,
		]);
		return  $response;
	}
	public function postFacebookComment($request,$postId)
	{
		$accessToken = getValueByKey('FACEBOOK_TOKEN');;
		$file = $request->file('media');
		$imagePath = "";
		if ($request->hasFile('media')) {
            $image = $request->file('media');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads'), $imageName);
			$imagePath = public_path('uploads')."/".$imageName;
        }
		
		$commentMessage = $request->postMessage;
		

		$client = new Whatsapp();

		$url =  sprintf(getValueByKey('FACEBOOK_URL'),$postId);;
		$requestBody = [
			'multipart' => [
				[
					'name' => 'message',
					'contents' => $commentMessage,
				],
				
			],
			'query' => [
				'access_token' => $accessToken,
			],
		];
		
		if($imagePath)
		{
			$requestBody['multipart'][] = [
				'name' => 'attachment',
				'contents' => fopen($imagePath, 'r'),
				'filename' => 'image.jpg',
			];
		}
		$response = $client->post($url,$requestBody);
		$statusCode = $response->getStatusCode();
		if ($statusCode === 200) {
			if($imagePath)
			{
				unlink($imagePath);
			}
		} else {
			// Handle error
			$responseBody = $response->getBody()->getContents();
			if($imagePath)
			{
				unlink($imagePath);
			}
			// Handle the response body for error details
		}
		return  $response;
	}
	
	// public  function getPostByLinkedin(Request $request)
    // {	DB::beginTransaction();
	// 	try{
	// 		$response = [];
	// 		Log::debug($request->all());
	// 		$receivedChallengeCode = $request->challengeCode;
	// 		if(!isset($request->type))
	// 		{
	// 			$clientSecret = getValueByKey('LINKEDIN_CLIENT_SECRET');
	// 			// Compute the challengeResponse using HMAC-SHA256
	// 			$challengeResponse = hash_hmac('sha256', $receivedChallengeCode, $clientSecret);

	// 			// Construct the JSON response
	// 			$response = array(
	// 				"challengeCode" => $receivedChallengeCode,
	// 				"challengeResponse" => $challengeResponse
	// 			);
				
	// 		}
	// 		else{
	// 			$payload = $request->all();
	// 			$info = $payload['notifications'][0]['decoratedSourcePost']['text'];
	// 			if($info)
	// 			{
	// 				$body = $payload['notifications'][0]['decoratedSourcePost']['text'];
	// 				$entity = $payload['notifications'][0]['decoratedSourcePost']['entity'];
					
	// 				$owner = $payload['notifications'][0]['decoratedSourcePost']['owner'];
	// 				$owners = explode(":",$owner);
	// 				$lastIndex = count($owners) - 1;
					
	// 				$res = $this->getLinkedinUser($owners[$lastIndex]);
				
	// 				$post_id = $payload['notifications'][0]['sourcePost'];
	// 				//$post_id = getDigitCodeForLinkedin();
	// 				$id = $post_id;
	// 				$posts = explode(":",$post_id);
	// 				$postLastIndex = count($posts) - 1;
	// 				$id = $posts[$postLastIndex];
	// 				$toNum  = $res->id;
	// 				$name = $res->localizedFirstName." ".$res->localizedLastName;
	// 				$user_name = $res->vanityName;
					
		 
	// 				$todayDate = Carbon::now()->format('Y-m-d h:i:s A');
	// 				$auther_id=GetTweet::where('socialUser_id',$toNum)->first();
					
	// 				if(!$auther_id){
	// 					$userName= $res->vanityName;
	// 					$saveSocialUser=SocialUser::create([
	// 						'user_id'=>$toNum,
	// 						'name'=>$name,
	// 						'user_name'=>$res->vanityName
	// 					]);
	// 				}else{
	// 					$name=$auther_id->socialUser_name;
	// 					$userName=$auther_id->socialUser_userName;
	// 				}
					
	// 				$assigntoid=getUserAssignRule('gettweet','Linkedin',$body);
	// 				$category=setPostAssignRule('post',$body);
	// 				$assignto=User::find($assigntoid);
					
	// 				$assigntoname="";
	// 				if($assignto){
	// 					$assigntoname=$assignto->name;
	// 				}
	// 				$checkReply=getValueByKey('AUTO_REPLY_LINKEDIN');
                    
	// 				$postInfo = GetTweet::create([
	// 					'getTweet_id'=>$id,
	// 					'postMessage'=>$body,
	// 					'socialUser_id'=>$toNum,
	// 				//	'mobile_no'=>$toNum,
	// 					'source'=>"Linkedin",
	// 					'postUrl'=>"",
	// 					'postDate'=>$todayDate,
	// 					'istPostDate'=>$todayDate,
	// 					'socialUser_name'=>$name,
	// 					'socialUser_userName'=>$userName,
	// 					'assignedto'=>$assigntoname,
	// 					'other_info'=>json_encode($payload),
	// 					'post_category'=>$category
	// 				]); 
					
	// 				if($checkReply=="true"){
	// 				    $replyMessage=getValueByKey('AUTO_REPLY_ON_POST');
	// 				    $requestData = [
	// 					   'source' => "Linkedin",
	// 					   'postMessage' => $replyMessage,
	// 					   'socialUser_id' => $toNum,
	// 					   'newPostMessage' => "hello",
	// 					   'other_info'=>json_encode($payload),
	// 				    ];
	// 				    $request = new Request($requestData);
					   
	// 				    $result = $this->replyTweetId($request,$id);
	// 			    }
					
	// 				$oldVal = $postInfo;
	// 				if($assigntoname)
	// 				{
	// 					$postInfo->update(['status'=>'Open']);
	// 					createLog($oldVal,$changes,'post');
	// 				}
	// 			}
				
	// 		}
	// 		DB::commit();
	// 		return $response;
	// 	}
	// 	catch(Exception $e) {
	// 		DB::rollback();
	// 		Log::debug( $e->getMessage());
	// 		// return redirect()->back()->with('message',$e->getMessage());
    //     }
	// }
	
	// public function getLinkedinUser($userId)
	// {
	// 	$accessToken = getValueByKey('LINKEDIN_TOKEN');
	// 	$client = new Whatsapp();
		
	// 	$url = sprintf(getValueByKey('LINKEDIN_USER_URL'),urlencode($userId))."?oauth2_access_token=".$accessToken;
	// 	$headers = [
	// 		'Content-Type' => 'application/json',
	// 		'X-Restli-Protocol-Version' => '2.0.0',
	// 	];

		
	// 	$response = $client->get($url, [
	// 		'headers' => $headers,
	// 	]);
	// 	$statusCode = $response->getStatusCode();
	// 	$responseBody = $response->getBody()->getContents();
	// 	$res = json_decode($responseBody);
	// 	return  $res;
	// }


	public function getLinkedinPost()
	{
		DB::beginTransaction();
		try{	
		$allresponse=[];
		$postInfo=[];
		$accessToken = getValueByKey('LINKEDIN_TOKEN');
		$response = Http::withHeaders([
			'X-Restli-Protocol-Version'=>'2.0.0',
			'LinkedIn-Version'=>'202306',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$accessToken,
        ])
        ->get('https://api.linkedin.com/rest/organizationalEntityNotifications?q=criteria&actions=List(SHARE_MENTION)&organizationalEntity=urn%3Ali%3Aorganization%3A458244');

		$statusCode = $response->getStatusCode();
		$responseBody = $response->getBody()->getContents();
		$res = json_decode($responseBody);
        $elements=$res->elements;
		foreach($elements as $element){
			$shareid=urlencode($element->generatedActivity);
			$post_id=GetTweet::where('getTweet_id',$element->generatedActivity)->first();
			if(!$post_id){
            if ($shareid) {
				 $postresponse = Http::withHeaders([
					'X-Restli-Protocol-Version'=>'2.0.0',
					'LinkedIn-Version'=>'202306',
					'Content-Type' => 'application/json',
					'Authorization' => 'Bearer '.$accessToken,
				])
				->get('https://api.linkedin.com/rest/posts/'.$shareid);
				$statusCode = $postresponse->getStatusCode();
				$postresponseBody = $postresponse->getBody()->getContents();
				$postres = json_decode($postresponseBody);
				if ($postres && isset($postres->author)) {					
					$id=$postres->id;
					$companywithurn=explode(":",$element->organizationalEntity);
					$company=count($companywithurn) - 1;
					$body=$postres->commentary;
				    $owners = explode(":",$postres->author);
				    $lastIndexs = count($owners) - 1;
					$timestamp = $postres->lastModifiedAt / 1000;
					$dateTime = date("Y-m-d h:i:s", $timestamp);
					$name = "";
					$userName = "";
					$toNum=$owners[$lastIndexs];
					$user=[];
					$auther_id=GetTweet::where('socialUser_id',$toNum)->first();
					if(!$auther_id){
					if (preg_match('/^urn:li:(person|organization|organizationBrand):/', $postres->author, $matches)) {
						$type = $matches[1];
						if ($type === "person") {
							$user = $this->getLinkedinUser($owners[$lastIndexs],$accessToken);
							if ($user && !isset($user->status)) {
								$toNum  = $user->id;
								$name = $user->localizedFirstName." ".$user->localizedLastName;
								$name=convertSpecialCharToNormalChar($name);
								$name=remove_emoji($name);
								$userName = remove_emoji($user->vanityName);
								$userName = convertSpecialCharToNormalChar($userName);
							}
						} elseif ($type === "organization") {
							$user = $this->getLinkedinOrgnisation($owners[$lastIndexs],$accessToken);
							if ($user && !isset($user->status)) {
								$orgnisationid=$owners[$lastIndexs];
								$toNum  = $user->results->$orgnisationid->id;
								$name = $user->results->$orgnisationid->localizedName;
								$name=remove_emoji($name);
								$name=convertSpecialCharToNormalChar($name);
								$userName = $user->results->$orgnisationid->vanityName;
							}
						}else{
							$user = $this->getLinkedinOrgnisation($owners[$lastIndexs],$accessToken);
							if ($user && !isset($user->status)) {
								$orgnisationid=$owners[$lastIndexs];
								$toNum  = $user->results->$orgnisationid->id;
								$name = $user->results->$orgnisationid->localizedName;
								$name=convertSpecialCharToNormalChar($name);
								$name=remove_emoji($name);
								$userName = $user->results->$orgnisationid->vanityName;
							}
						}
					}
						$saveSocialUser=SocialUser::create([
							'user_id'=>$toNum,
							'name'=>$name,
							'user_name'=>$userName
						]);
					}else{
						$name=$auther_id->socialUser_name;
						$userName=$auther_id->socialUser_userName;
					}
					$assigntoid=getUserAssignRule('gettweet','Linkedin',$body);
					$category=setPostAssignRule('post',$body);
					$assignto=User::find($assigntoid);
					$posturl="https://www.linkedin.com/feed/update/".$element->sourcePost."/?actorCompanyId=".$company;
					$assigntoname="";
					if($assignto){
						$assigntoname=$assignto->name;
					}
					$checkReply=getValueByKey('AUTO_REPLY_LINKEDIN');
			        $body = convertSpecialCharToNormalChar($body);
			        $body = remove_emoji($body);
					$postInfo = GetTweet::create([
						'getTweet_id'=>$element->generatedActivity,
						'postMessage'=>$body,
						'socialUser_id'=>$toNum,
					//	'mobile_no'=>$toNum,
						'source'=>"Linkedin",
						'postUrl'=>$posturl,
						'postDate'=>$dateTime,
						'istPostDate'=>$dateTime,
						'socialUser_name'=>$name,
						'socialUser_userName'=>$userName,
						'assignedto'=>$assigntoname,
						'other_info'=>json_encode($postres),
						'post_category'=>$category,
						'bp_number'=>bpnumberfetch($body)
					]);
					if($checkReply=="true"){
					    $replyMessage=getValueByKey('AUTO_REPLY_ON_POST');
					    $requestData = [
						   'source' => "Linkedin",
						   'postMessage' => $replyMessage,
						   'socialUser_id' => $toNum,
						   'newPostMessage' => "hello",
						   'other_info'=>json_encode($postres),
					    ];
					    $request = new Request($requestData);
					   
					    $result = $this->replyTweetId($request,$id);
				    }
					
					$oldVal = $postInfo;
					if($assigntoname)
					{
						$postInfo->update(['status'=>'Open']);
						createLog($oldVal,$changes,'post');
					}
				}
				$allresponse[]=$postInfo;
            }
		    }
		}
		DB::commit();
			return $allresponse;
		}
		catch(Exception $e) {
			DB::rollback();
			Log::debug( $e->getMessage());
			// return redirect()->back()->with('message',$e->getMessage());
        }  
	}

	public function getLinkedinUser($user,$accessToken)
	{
		$res=[];
		$response = Http::withHeaders([
			'X-Restli-Protocol-Version'=>'2.0.0',
			'LinkedIn-Version'=>'202306',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$accessToken,
        ])
        ->get('https://api.linkedin.com/v2/people/(id:'.$user.')');

		$statusCode = $response->getStatusCode();
		$responseBody = $response->getBody()->getContents();
		$res = json_decode($responseBody);  
		return  $res;
	}

	public function getLinkedinOrgnisation($user,$accessToken)
	{
		$res=[];
		$response = Http::withHeaders([
			'X-Restli-Protocol-Version'=>'2.0.0',
			'LinkedIn-Version'=>'202306',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$accessToken,
        ])
        ->get('https://api.linkedin.com/rest/organizationsLookup?ids=List('.$user.')');

		$statusCode = $response->getStatusCode();
		$responseBody = $response->getBody()->getContents();
		// log::info(json_encode($responseBody));
		$res = json_decode($responseBody);  
		return  $res;
	}


	public  function getPostByfacebook()
    {	DB::beginTransaction();
		try{
			$challenge ="";
		    $accessToken = getValueByKey('FACEBOOK_TOKEN');
		    $response = Http::withHeaders([
				'Content-Type' => 'application/json',
			])
			->get('https://graph.facebook.com/v17.0/809771529057153/tagged?access_token='.$accessToken.'&fields=from,id,message,tagged_time&limit=100');
	
			$statusCode = $response->getStatusCode();
			$responseBody = $response->getBody()->getContents();
			$data = json_decode($responseBody, true);
		    if($responseBody)
		    {
				foreach($data['data'] as $messageData){
					$body =$messageData['message'];					
			        $body = convertSpecialCharToNormalChar($body);
			        $body = remove_emoji($body);
					$id =$messageData['id'];
					$post_id=GetTweet::where('getTweet_id',$id)->first();
					if(!$post_id){
		            list($urluser, $urlpost) = explode('_', $id);
					$posturl='https://www.facebook.com/'.$urluser.'/posts/'.$urlpost;
					$toNum  = $urluser;
					$name = "Facebook";
					$user_name = "facebook".$urluser;
					if(isset($messageData['from'])){
                        $toNum  = $urluser;
					    $name = $messageData['from']['name'];
						$name=convertSpecialCharToNormalChar($name);
						$name=remove_emoji($name);
					    $user_name = $name.$urluser;
					}
					$carbonTime = Carbon::parse($messageData['tagged_time']);
                    $carbonTime->addHours(5)->addMinutes(30);
                    $indianRailwayTime = $carbonTime->format('Y-m-d H:i:s');
					$auther_id=GetTweet::where('socialUser_id',$toNum)->first();
					
					if(!$auther_id){
						$userName= $toNum;
						$saveSocialUser=SocialUser::create([
							'user_id'=>$toNum,
							'name'=>$name,
							'user_name'=>$user_name
						]);
					}else{
						$name=$auther_id->socialUser_name;
						$userName=$auther_id->socialUser_userName;
					}
					
					$assigntoid=getUserAssignRule('gettweet','Facebook',$body);
					$category=setPostAssignRule('post',$body);
					$assignto=User::find($assigntoid);
					
					$assigntoname="";
					if($assignto){
						$assigntoname=$assignto->name;
					}
					$checkReply=getValueByKey('AUTO_REPLY_FACEBOOK');
                    if($checkReply=="true"){
					    $replyMessage=getValueByKey('AUTO_REPLY_ON_POST');
					    $requestData = [
						   'source' => "Facebook",
						   'postMessage' => $replyMessage,
						   'socialUser_id' => $toNum,
						   'newPostMessage' => "hello"
					    ];
					    $request = new Request($requestData);
					    $id=$id;
					    $result = $this->replyTweetId($request,$id);
				    }
					$postInfo = GetTweet::create([
						'getTweet_id'=>$id,
						'postMessage'=>$body,
						'socialUser_id'=>$toNum,
						'mobile_no'=>$toNum,
						'source'=>"Facebook",
						'postUrl'=>$posturl,
						'postDate'=>$indianRailwayTime,
						'istPostDate'=>$indianRailwayTime,
						'socialUser_name'=>$name,
						'socialUser_userName'=>$userName,
						'assignedto'=>$assigntoname,
						'post_category'=>$category,
						'bp_number'=>bpnumberfetch($body)
					]); 
					$oldVal = $postInfo;
					if($assigntoname)
					{
						$postInfo->update(['status'=>'Open']);
						createLog($oldVal,$changes,'post');
					}
				    }
			    }	
			}
		    DB::commit();
			return $challenge;
		}
		catch(Exception $e) {
			DB::rollback();
			Log::debug( $e->getMessage());
			// return redirect()->back()->with('message',$e->getMessage());
        }
	}


	public  function getDMByfacebook()
    {	DB::beginTransaction();
		try{
			$challenge =[];
			$challenge2 =[];
		    $accessToken = getValueByKey('FACEBOOK_TOKEN');
			$client = new Whatsapp();
		    $response = $client->get('https://graph.facebook.com/v17.0/809771529057153/conversations?access_token='.$accessToken.'&limit=2&fields=messages{id,created_time,from,to,message},updated_time,link');
			$statusCode = $response->getStatusCode();
			$responseBody = $response->getBody()->getContents();
			$data = json_decode($responseBody, true);
		    if($responseBody && getSourceStatus("FACEBOOK"))
		    {
				foreach($data['data'] as $conversationData){
					$challenge['fb_id'] = $conversationData['id'];
					$challenge['link'] = $conversationData['link'];
                    $challenge['name'] = $conversationData['messages']['data'][0]['from']['name'];
                    $challenge['message'] = $conversationData['messages']['data'][0]['message'];
                    $challenge['createdTime'] = \Carbon\Carbon::parse($conversationData['messages']['data'][0]['created_time'])
                    ->setTimezone('Asia/Kolkata')
                    ->format('Y-m-d H:i:s');
					$challenge['updated_time'] = \Carbon\Carbon::parse($conversationData['updated_time'])
                    ->setTimezone('Asia/Kolkata')
                    ->format('Y-m-d H:i:s');

					$challenge2[]=$challenge;
			    }	
			}
		    DB::commit();
			return $challenge2;
		}
		catch(Exception $e) {
			DB::rollback();
			Log::debug( $e->getMessage());
			// return redirect()->back()->with('message',$e->getMessage());
        }
	}


	public  function getDMByTwitter()
    {
		try{
			$challenge =[];
			$challenge2 =[];
			$client=twitteroauth();
			$query='max_results=10&event_types=MessageCreate&dm_event.fields=attachments,created_at,dm_conversation_id,event_type,id,participant_ids,referenced_tweets,sender_id,text&expansions=attachments.media_keys,participant_ids,referenced_tweets.id,sender_id&media.fields=alt_text,duration_ms,height,media_key,non_public_metrics,organic_metrics,preview_image_url,promoted_metrics,public_metrics,type,url,variants,width&user.fields=created_at,description,entities,id,location,most_recent_tweet_id,name,pinned_tweet_id,profile_image_url,protected,public_metrics,url,username,verified,verified_type,withheld&tweet.fields=attachments,author_id,context_annotations,conversation_id,created_at,edit_controls,edit_history_tweet_ids,entities,geo,id,in_reply_to_user_id,lang,non_public_metrics,note_tweet,organic_metrics,possibly_sensitive,promoted_metrics,public_metrics,referenced_tweets,reply_settings,source,text,withheld';
	        $response=$client->get('dm_events?'.$query);
			$statusCode = $response->getStatusCode();
			$responseBody = $response->getBody()->getContents();
			$responsedata = json_decode($responseBody, true);
			Log::debug($responseBody);
		    if($responseBody)
		    {
				$data = $responsedata['data'];
                $includes = $responsedata['includes']['users'];

                foreach ($data as $tweetData) {
                    $challenge['id'] = $tweetData['id'];
                    $challenge['created_at'] = \Carbon\Carbon::parse($tweetData['created_at'])
                    ->setTimezone('Asia/Kolkata')
                    ->format('Y-m-d H:i:s');
                    $challenge['text'] = $tweetData['text'];
                    $challenge['sender_id'] = $tweetData['sender_id'];
                    $challenge['dm_conversation_id'] = $tweetData['dm_conversation_id'];
                    // Find the corresponding user information
                    $senderInfo = collect($includes)->firstWhere('id', $tweetData['sender_id']);
                    if ($senderInfo) {
                        $challenge['user_id'] = $senderInfo['id'];
                        $challenge['user_name'] = $senderInfo['name'];
                        $challenge['user_username'] = $senderInfo['username'];
                    } else {
                        // Handle the case where user information is not found
                        $challenge['user_id'] = null;
                        $challenge['user_name'] = 'Unknown';
                        $challenge['user_username'] = 'Unknown';
                    }
					$this->saveDMByTwitter($challenge);
                    $challenge2[]=$challenge;
                }	
			}
			return $challenge2;
		}
		catch(Exception $e) {
			Log::debug( $e->getMessage());
			// return redirect()->back()->with('message',$e->getMessage());
        }
	}


	public  function saveDMByTwitter($response)
    {	
		DB::beginTransaction();
		try{	
			$data=[];
			$postdatas=GetTweet::where('conversation_id',$response['dm_conversation_id'])
			->where('dm_status','START')
			->where('dm_startdate', '<=', $response['created_at'])
			->get();
			if($postdatas){
			    foreach($postdatas as $postdata){
					$checkExistmessage=Conversation::where('message_id',$response['id'])
					->where('socialpost_id',$postdata->getTweet_id)
					->first();                
					if(!$checkExistmessage){
					    $conversationcreate=Conversation::create([
						    'conversation_id' => $response['dm_conversation_id'],
						    'socialpost_id' => $postdata->getTweet_id,
						    'message_id' => $response['id'],
						    'sender_id' => $response['user_id'],
						    'sender_name' => $response['user_name'],
						    'sender_username' => $response['user_username'],
						    'source' => 'Twitter',
						    'message' => $response['text'],
						    'message_time' => $response['created_at']
					    ]);                             
				    }       
			    }
			}	
			DB::commit(); 
			return $data;
		}
		catch(Exception $e) {
			DB::rollback();
			Log::debug( $e->getMessage());
			// return redirect()->back()->with('message',$e->getMessage());
        }
	}	


	public  function replyDMByTwitter($postMessage,$id)
    {	
		DB::beginTransaction();
		try{
			$postData = GetTweet::withTrashed()->where('getTweet_id',$id)->first();	
			// $dmsenderid=getValueByKey('TWITTER_SENDER_ID');
			// $dmsendername=getValueByKey('TWITTER_SENDER_NAME');
			// $dmsenderusername=getValueByKey('TWITTER_SENDER_USERNAME');
			$dmsenderid="230013521";
			$dmsendername="chetan singh chauhan";
			$dmsenderusername="chauhancsingh";
			$currentDateTime = Carbon::now();
            $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
			$postMessageData = [
			    'text' => $postMessage
			];
			$jsonData = json_encode($postMessageData);
			$client=twitteroauth();
	        $response=$client->post('dm_conversations/with/'.$postData->socialUser_id.'/messages', [
				'body' => $jsonData,
				'headers' => [
					'accept'=> 'application/json',
					'Content-Type' => 'application/json',
				],
			]);
			$statusCode = $response->getStatusCode();
			$responseBody = $response->getBody()->getContents();
			$data = json_decode($responseBody, true);
			if($data){
				$conversationcreate=Conversation::create([
					'conversation_id' => $data['data']['dm_conversation_id'],
					'socialpost_id' => $postData->getTweet_id,
					'message_id' => $data['data']['dm_event_id'],
					'sender_id' => $dmsenderid,
					'sender_name' => $dmsendername,
					'sender_username' => $dmsenderusername,
					'source' => 'Twitter',
					'message' => $postMessage,
					'message_time' => $formattedDateTime
				]); 
				if ($postData->dm_status === "CLOSE" || $postData->dm_status == null){
				    $postData->update([
					   'dm_status'=>'START',
					   'dm_startdate'=>$formattedDateTime,
					   'conversation_id'=>$data['data']['dm_conversation_id']
				    ]);
			    }
			}
			Log::debug($responseBody);
		    DB::commit();
		}
		catch(Exception $e) {
			DB::rollback();
			Log::debug( $e->getMessage());
			throw new Exception($e->getMessage());
        }
	}	


	public  function getPostInstagram(Request $request)
    {	
		// DB::beginTransaction();
		try{	
			$challenge ="";
		    if(isset($request->hub_challenge))
		    {
			    $challenge = $request->hub_challenge;
		    }
			$payload = $request->all();
			Log::debug($payload);
			return $challenge;
		    // DB::commit();
		}
		catch(Exception $e) {
			// DB::rollback();
			Log::debug( $e->getMessage());
			// return response()->json(['error' => $e->getMessage()], 400);
			// return redirect()->back()->with('message',$e->getMessage());
        }
	}

	public  function deleteattachment($id)
    {	
		DB::beginTransaction();
		try{	
			$attachment = ProjectAttachment::where('attachment_id',$id)->first();
			$attachment->delete();
		    DB::commit();
			adminChange("no",$attachment->id,'tb_projectattachment','delete');
			$gettweet=GetTweet::where('getTweet_id',$id)->first();
			return redirect()->route('editSocialPost', ['id' => $gettweet->id]);
		}
		catch(Exception $e) {
			DB::rollback();
			Log::debug( $e->getMessage());
			// return response()->json(['error' => $e->getMessage()], 400);
			// return redirect()->back()->with('message',$e->getMessage());
        }
	}

	public  function getPostInsta()
    {	
		DB::beginTransaction();
		try{	
			$allresponse=[];
			$allresponse2=[];
		    $accessToken = getValueByKey('INSTAGRAM_TOKEN');
			$client = new Whatsapp();
		    $response = $client->get('https://graph.facebook.com/v18.0/17841403933414926/tags?access_token='.$accessToken.'&fields=id,caption,media_type,media_url,username,timestamp,permalink');
	
			$statusCode = $response->getStatusCode();
			$responseBody = $response->getBody()->getContents();
			$allresponse[]=$responseBody;
			$data = json_decode($responseBody, true);
			if($data['paging']['cursors']['after']){
				$after=$data['paging']['cursors']['after'];
				$response = $client->get('https://graph.facebook.com/v18.0/17841403933414926/tags?access_token='.$accessToken.'&fields=id,caption,media_type,media_url,username,timestamp,permalink&after='.$after);
				$statusCode = $response->getStatusCode();
				$responseBody = $response->getBody()->getContents();
				$allresponse[]=$responseBody;
				$data = json_decode($responseBody, true);
				if($data['paging']['cursors']['after']){
					$after=$data['paging']['cursors']['after'];
					$response = $client->get('https://graph.facebook.com/v18.0/17841403933414926/tags?access_token='.$accessToken.'&fields=id,caption,media_type,media_url,username,timestamp,permalink&after='.$after);
					$statusCode = $response->getStatusCode();
					$responseBody = $response->getBody()->getContents();
					$allresponse[]=$responseBody;
					$data = json_decode($responseBody, true);
				}
			}			
			if($allresponse){
				foreach($allresponse as $allresponses){
					$res = json_decode($allresponses, true);
					foreach($res['data'] as $responsedata){
						$body ="No caption";
						if(isset($responsedata['caption'])){
							$body =$responsedata['caption'];					
							$body = convertSpecialCharToNormalChar($body);
							$body = remove_emoji($body);
						}
						if(isset($responsedata['media_url'])){
                            $body =$body." ".strtolower($responsedata['media_type'])." url: ".$responsedata['media_url'];
						}
						$id =$responsedata['id'];
						$post_id=GetTweet::where('getTweet_id',$id)->first();
						if(!$post_id){
							$posturl=$responsedata['permalink'];
							$postTime=\Carbon\Carbon::parse($responsedata['timestamp'])
							->setTimezone('Asia/Kolkata')
							->format('Y-m-d H:i:s');
							$userid=instauserid();
							$userName=$responsedata['username'];
							$name=$responsedata['username'];
							$auther_id=GetTweet::where('socialUser_userName',$userName)->first();
					
							if(!$auther_id){
								$saveSocialUser=SocialUser::create([
									'user_id'=>$userid,
									'name'=>$name,
									'user_name'=>$userName
								]);
							}else{
								$name=$auther_id->socialUser_name;
								$userName=$auther_id->socialUser_userName;
							}
							
							$assigntoid=getUserAssignRule('gettweet','Instagram',$body);
							$category=setPostAssignRule('post',$body);
							$assignto=User::find($assigntoid);
							
							$assigntoname="";
							if($assignto){
								$assigntoname=$assignto->name;
							}
							$checkReply=getValueByKey('AUTO_REPLY_INSTAGRAM');
							if($checkReply=="true"){
								$replyMessage=getValueByKey('AUTO_REPLY_ON_POST');
								$requestData = [
								   'source' => "Instagram",
								   'postMessage' => $replyMessage,
								   'socialUser_id' => $userName,
								   'newPostMessage' => "hello"
								];
								$request = new Request($requestData);
								$id=$id;
								$result = $this->replyTweetId($request,$id);
							}
							$postInfo = GetTweet::create([
								'getTweet_id'=>$id,
								'postMessage'=>$body,
								'socialUser_id'=>$userid,
								'source'=>"Instagram",
								'postUrl'=>$posturl,
								'postDate'=>$postTime,
								'istPostDate'=>$postTime,
								'socialUser_name'=>$name,
								'socialUser_userName'=>$userName,
								'assignedto'=>$assigntoname,
								'post_category'=>$category
							]); 
							$allresponse2[]=$postInfo;
							$oldVal = $postInfo;
							if($assigntoname)
							{
								$postInfo->update(['status'=>'Open']);
								createLog($oldVal,$changes,'post');
							}
							
						}
					}
				}
			}
		    DB::commit();
			return $allresponse2;
		}
		catch(Exception $e) {
			DB::rollback();
			Log::debug( $e->getMessage());
        }
	}
	

public function resetPostCountJob()
{
    DB::beginTransaction();
    try {
        $configdetails = TwitterConfigDetail::get();
        
        foreach ($configdetails as $config) {
            $accessToken = $config->bearer_token;
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get('https://api.twitter.com/2/usage/tweets');
            
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();
            $res = json_decode($responseBody);
            
            if (isset($res->data->project_usage)) {
                $config->count = $res->data->project_usage;
                $config->save();
            }
            
            Log::info('Twitter API Response:', [
                'status' => $statusCode,
                'body' => $res->data->project_usage ?? 'N/A'
            ]);
        }
        
        DB::commit();
    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Error in resetPostCountJob:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
    }
}


}
