<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TweetPost;
use App\Models\GetTweet;
use App\Models\TweetLog;
use App\Models\TweetReply;
use App\Models\SocialUser;
use App\Models\Defaults;
use App\Models\Favourite;
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
use Illuminate\Support\Facades\Storage;

class TweetController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth')->except([
            'getTweetIdsByJob','getPostBywhatsapp','getPostByfacebook','getPostByLinkedin'
        ]);
		
		
    }
    
    public  function getPostBywhatsapp(Request $request)
    {	DB::beginTransaction();
		try{
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
			$reply = TweetReply::where('replyToTweetId',$postData->getTweet_id)
			->orderBy('post_id', 'desc')->get();
			return \View::make('post.postTweet', compact(['postData','getUser','getSocialUser','reply']));
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
			//log::info('reply'.$id.$request);
			$postData = GetTweet::withTrashed()->where('getTweet_id',$id2)->first();		
			//log::info($id);
			
			if(strtoupper($request->source) == 'LINKEDIN')
			{
				
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
				}
				$responseBody = $this->postLinkedinComment($request,$id);
				
				
				$tweet_save = TweetReply::create([
				  'replyToTweetId'=>$id,
				  'tweeter_id'=>$postData->id,
				  'tweeter_text'=>$request->postMessage,
				  'socialUser_id'=>$request->socialUser_id?$request->socialUser_id:$postData->socialUser_id,
				  'media_type'=>$media_type,
				  'url'=>''
				]);
			}
			else if(strtoupper($request->source) == 'FACEBOOK')
			{
				
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
				  'url'=>''
				]);
				
				$url = sprintf(getValueByKey('FACEBOOK_URL'),$id);  
				$responseBody = $this->postFacebookComment($request,$id);
				
				
				
			}
			else if(strtoupper($request->source) == 'WHATSAPP')
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
				$res = json_decode($responseBody);
				$id = $res->messages[0]->id;
				$data = [
                  'tweeter_id'=>$id,
                  'tweeter_text'=>$request->postMessage,
                  'replyToTweetId'=>$id,
				  'socialUser_id'=>$request->mobile_no,
                ];
				$tweet_save = TweetReply::create($data);
			}
			elseif(strtoupper($request->source) == 'TWITTER'){
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
			            'in_reply_to_tweet_id' => $id],
						 "media" => ["media_ids" => [$logMediaId]]]);
				}else {
				    $return = $client->tweet()->create()->performRequest(['text' => $request->postMessage,'reply' => [
					   'in_reply_to_tweet_id' => $id
				    ]]);
			    }
				$url = "";
				$pattern = '/https?:\/\/\S+/';
				if (preg_match($pattern, $return->data->text, $matches)) {
					$url = $matches[0];
				}
				$tweet_save = TweetReply::create([
				  'replyToTweetId'=>$id,
				  'tweeter_id'=>$return->data->id,
				  'tweeter_text'=>$return->data->text,
				  'socialUser_id'=>$request->socialUser_id?$request->socialUser_id:$postData->socialUser_id,
				  'media_type'=>$media_type,
				  'url'=>$url
				]);
			}else {
				throw new \Exception('You cant reply on '.$request->source);
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
			}
           return redirect()->back()->with('success',$message);
        }
    }
	
    
	public function dashboard(Request $request)
    {
		try{
		   $post = GetTweet::orderBy('id','DESC');
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
				$post = $post->whereBetween($request->filterType,[$startDate,$endDate]);  
		  }
		  
		  $postColumn = getColumn('post');
		  
		  if(isset($request->download))
		  {	$col = [];
			$col= ['id',"Post Message","Social User","Source","Post Url","Post Date","Category","Status"];
									
			$data = [];
			$post = $post->get();
			
			
			return  downloadCsv($post,$col);
		  }
		 
		   $post = $post->paginate(getValueByKey('PAGENATION_COUNT'));
		   
		
		} catch(Exception $e) {
			//echo $e->getMessage();die;
			return redirect()->back()->with('message',$e->getMessage());
        }
        
		return \View::make('post.dashboard', compact(['post','postColumn']));
    }

    
	public function recentdashboard(Request $request)
    {
		try{
			$endDate = Carbon::now();
            $startDate = $endDate->copy()->subDays(getValueByKey('LAST_POST_DAYS'));
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
              ];  
			  
			if($id)
			{
				$oldVal = GetTweet::find($id);
				$postInfo = GetTweet::find($id);
				$postInfo->update($request->all());
				$changes = $postInfo->getChanges();
				createLog($oldVal,$changes,'post');			
			}
			else{
				$postInfo = GetTweet::create($requestInfo);
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
        return redirect()->route('dashboard');
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
			$getFavourite = Favourite::where('user_id',$logUser)
			->where('type_id',$getSocial->id)
			->where('type','tb_gettweet')
			->first();
			$reply = TweetReply::where('replyToTweetId',$getSocial->getTweet_id)
			->orderBy('post_id', 'desc')
			->get();
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
        return \View::make('post.socialpost_innerpage', compact(['getSocial','reply','getFavourite']));
    }

    public function deletePost($id)
    {  try{
           $item = GetTweet::find($id);     
           if (!$item) {
               return response()->json(['message' => 'Item not found'], 404);
            }
           $item->delete();
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
			if($id)
			{
				$create_post="Edit Post";
				$postData = GetTweet::find($id);
                $formattedDate=$postData->istPostDate;
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
			return \View::make('post.createpost', compact(['modal','formattedDate','getSource','create_post','postData','getUser','getSocialUser',]));
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
			$save_default=Defaults::where('key','ENDTIME_USERACCOUNT1')->first();
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
			$accountId = getValueByKey('USERACCOUNNTNO1');
		    $settings = getClientInfo();
		    $settings['account_id'] =$accountId;
			$getUserName= getValueByKey('TWITTER_USERNAMES');
		    $client = new Client($settings);
			$userName= explode(',', $getUserName);
			foreach($userName as $userNames){
				$checkDefault=Defaults::where('key',$userNames."_ACCOUNT_KEY")->first();
				
				if(!$checkDefault){
                    $auther = $client->userLookup()->findByIdOrUsername($userNames)->performRequest();
					if($auther){
					    $saveDefault=Defaults::create([
						   'key'=>$userNames."_ACCOUNT_KEY",
						   'value'=>$auther->data->id,
						//   'type'=>'user',
						   'label'=>$userNames." User Id"
					    ]);
						
						$nowTime = Carbon::now('UTC');
						$end_time = $nowTime->format('Y-m-d\TH:i:s\Z');
		
					    $resDate = Defaults::create([
						   'key'=>$userNames."_ENDTIME_USERACCOUNT",
						   'value'=>$end_time,
						   'type'=>'user',
						   'label'=>$userNames." End Time"
					    ]);
						
						$accountId = $saveDefault->value;
				    }
				}
				else{
					$end_time = Defaults::where('key',$userNames."_ENDTIME_USERACCOUNT")->first();
					$end_time = $end_time->value;
					$accountId = $checkDefault->value;
				}
				$this->getTweetIdsByJob1($accountId,$end_time);
			}
			
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


	public  function getPostByfacebook(Request $request)
    {	DB::beginTransaction();
		try{
			$challenge ="";
		    if(isset($request->hub_challenge))
		    {
			    $challenge = $request->hub_challenge;
		    }
			$payload = $request->all();
			Log::debug($payload);
		    if($payload && getSourceStatus("FACEBOOK"))
		    {
				$field = $payload['entry'][0]['changes'][0]['field'];
				if($field =='mention')
				{
					$body =$payload['entry'][0]['changes'][0]['value']['message'];
					$post_id =$payload['entry'][0]['changes'][0]['value']['post_id'];
					$item =$payload['entry'][0]['changes'][0]['value']['item'];
					$toNum  = "32102023";
					$name = "Facebook";
					$user_name = "facebook2023";
				
					$body =$payload['entry'][0]['changes'][0]['value']['message'];
					$id =$payload['entry'][0]['changes'][0]['value']['post_id'];
					$item =$payload['entry'][0]['changes'][0]['value']['item'];
		 
					$todayDate = Carbon::now()->format('Y-m-d h:i:s A');
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


	public function postLinkedinComment($request,$postId)
	{
		$accessToken = getValueByKey('LINKEDIN_TOKEN');
		$file = $request->file('media');
		$payload = json_decode($request->other_info);
		$subscriber = $payload->notifications[0]->subscriber;
		
		$entity = $payload->notifications[0]->decoratedSourcePost->entity;
		$sourcePost = $payload->notifications[0]->sourcePost;
		
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
	
	public  function getPostByLinkedin(Request $request)
    {	DB::beginTransaction();
		try{
			$response = [];
			Log::debug($request->all());
			$receivedChallengeCode = $request->challengeCode;
			if(!isset($request->type))
			{
				$clientSecret = getValueByKey('LINKEDIN_CLIENT_SECRET');
				// Compute the challengeResponse using HMAC-SHA256
				$challengeResponse = hash_hmac('sha256', $receivedChallengeCode, $clientSecret);

				// Construct the JSON response
				$response = array(
					"challengeCode" => $receivedChallengeCode,
					"challengeResponse" => $challengeResponse
				);
				
			}
			else{
				$payload = $request->all();
				$info = $payload['notifications'][0]['decoratedSourcePost']['text'];
				if($info)
				{
					$body = $payload['notifications'][0]['decoratedSourcePost']['text'];
					$entity = $payload['notifications'][0]['decoratedSourcePost']['entity'];
					
					$owner = $payload['notifications'][0]['decoratedSourcePost']['owner'];
					$owners = explode(":",$owner);
					$lastIndex = count($owners) - 1;
					
					$res = $this->getLinkedinUser($owners[$lastIndex]);
					 
					$post_id = $payload['notifications'][0]['sourcePost'];
					//$post_id = getDigitCodeForLinkedin();
					$id = $post_id;
					$toNum  = $res->vanityName;
					$name = $res->localizedFirstName." ".$res->localizedLastName;
					$user_name = $res->vanityName;
					
		 
					$todayDate = Carbon::now()->format('Y-m-d h:i:s A');
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
					
					$assigntoid=getUserAssignRule('gettweet','Linkedin',$body);
					$category=setPostAssignRule('post',$body);
					$assignto=User::find($assigntoid);
					
					$assigntoname="";
					if($assignto){
						$assigntoname=$assignto->name;
					}
					$checkReply=getValueByKey('AUTO_REPLY_LINKEDIN');
                    
					$postInfo = GetTweet::create([
						'getTweet_id'=>$id,
						'postMessage'=>$body,
						'socialUser_id'=>$toNum,
						'mobile_no'=>$toNum,
						'source'=>"Linkedin",
						'postUrl'=>"",
						'postDate'=>$todayDate,
						'istPostDate'=>$todayDate,
						'socialUser_name'=>$name,
						'socialUser_userName'=>$userName,
						'assignedto'=>$assigntoname,
						'other_info'=>json_encode($payload),
						'post_category'=>$category
					]); 
					
					if($checkReply=="true"){
					    $replyMessage=getValueByKey('AUTO_REPLY_ON_POST');
					    $requestData = [
						   'source' => "Linkedin",
						   'postMessage' => $replyMessage,
						   'socialUser_id' => $toNum,
						   'newPostMessage' => "hello",
						   'other_info'=>json_encode($payload),
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
				
			}
			DB::commit();
			return $response;
		}
		catch(Exception $e) {
			DB::rollback();
			Log::debug( $e->getMessage());
			// return redirect()->back()->with('message',$e->getMessage());
        }
	}
	
	public function getLinkedinUser($userId)
	{
		$accessToken = getValueByKey('LINKEDIN_TOKEN');
		$client = new Whatsapp();
		
		$url = sprintf(getValueByKey('LINKEDIN_USER_URL'),urlencode($userId))."?oauth2_access_token=".$accessToken;
		$headers = [
			'Content-Type' => 'application/json',
			'X-Restli-Protocol-Version' => '2.0.0',
		];

		$response = $client->get($url, [
			'headers' => $headers,
		]);
		$statusCode = $response->getStatusCode();
		$responseBody = $response->getBody()->getContents();
		$res = json_decode($responseBody);
		return  $res;
	}
}
