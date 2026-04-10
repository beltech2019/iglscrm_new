<?php
  
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use  App\Models\UserAssignRule;
use  App\Models\PostAssignRule;
use App\Models\TwitterConfigDetail;
use App\Models\ProjectAttachment;
use App\Models\Template;
use Carbon\CarbonPeriod;
use  App\Models\SocialPlatform;
use App\Models\AdminChanges;
use App\Models\GetTweet;
use  App\Models\Defaults;
use  App\Models\TweetLog;
use  App\Models\RoleAccessMapping;
use  App\Models\TicketSapGroups;
use  App\Models\User;
use  App\Models\LeadTicket;
use  App\Models\SocialTicket;
use  App\Models\Role;
use  App\Models\Column;
use  App\Models\Option;
use  App\Models\Component;
use  App\Models\RulePriority;
use  App\Models\SapTicketCodeGroups;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Support\Facades\Log;

/**
 * Write code on Method

 * @return response()
 */
if (! function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}
  
/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('convertMdyToYmd')) {
    function convertMdyToYmd($date)
    {
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }
}

if (! function_exists('convertMdyToYmdhis')) {
    function convertMdyToYmdhis($date)
    {
		if($date == ":00")
		{
			return todayDate24();
		}
		else{
			if(isDate($date,'d-m-Y H:i:s'))
			{
				return Carbon::createFromFormat('d-m-Y H:i:s', $date)->format('Y-m-d H:i:s');
			}
			else{
				return todayDate24();
			}
			
		}
    }
}

if (! function_exists('currentTimestamp')) {
    function currentTimestamp()
    {
        return Carbon::createFromFormat('m-d-Y',new Date());
    }
}


function success(array $data=[],$statusCode=200)
{
    $data['success'] = true;
    return response()->json($data, $statusCode);
}

function getColumn($type)
{
	 $data = Column::where('type',$type)->OrderBy('sort_order','asc')->get();
	return $data;
}
function loggedUserId()
{
    $id = Auth::id();
    return $id;
}

function loggedUserRole()
{
    $id = Auth::id();
	$user=User::find($id);
	$roleInfo = Role::find($user->role);
    return $roleInfo->role_key;
}

function loggedUserInfo()
{
    $id = Auth::id();
	$user=User::find($id);
	$roleInfo = Role::find($user->role);
    return $roleInfo;
}


function showHide($request)
{
    if($request->column)
	{
		foreach($request->column as $postId)
		{
			$post = Column::find($postId);
			$post->update(['is_show'=>1]);
		}
	}
	if($request->columnHide)
	{
		foreach($request->columnHide as $postId)
		{
			$post = Column::find($postId);
			$post->update(['is_show'=>0]);
		}
	}
}

function assignedMe()
    {
		try{
            $assignedToMe=[];
            $assignedUser="";
            $userId=loggedUserId();
            foreach($getTicketData as $getassigned_to)
            {
                if($getassigned_to->assigned_to){
                    $assignedUser=User::find($getassigned_to->assigned_to);
                    if($assignedUser){
                       $assignedToMe=SocialTicket::where('assigned_to', $assignedUser->id)->get();
                    }
                }
            }
            return $assignedToMe;
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
    }

function error(array $data=[],$statusCode=400)
{
    $data['success'] = false;
    $data['validator'] = false;
    return response()->json($data, $statusCode);
}

function errorValidator($data,$statusCode=400)
{
    $error = [];
    $error['success'] = false;
    $error['validator'] = true;
    $error['error'] = $data;
    return response()->json($error, $statusCode);
}

function getValueByKey($key,$isUpdate=false)
{
    $value = Defaults::where('key',$key)->value('value');
    if(!$value)
    {
        throw new Exception("INVALID_DEFAULT_KEY");
    }
	if($isUpdate)
	{
		$data = Defaults::where('key',$key)->first();
		$data->update(['value'=>$data->value+1]);
	}
    return $value;
}

function getClientInfo()
{
	$consumer_Key=getValueByKey('CONSUMER_KEY');
	$consumer_Secret=getValueByKey('CONSUMER_SECRET');
	$access_Token=getValueByKey('ACCESS_TOKEN');
	$token_Secret=getValueByKey('TOKEN_SECRET');
	$bearer_token=getValueByKey('BEARER_TOKEN');
   
	$settings['access_token'] = $access_Token;
	$settings['access_token_secret'] = $token_Secret;
	$settings['consumer_key'] =$consumer_Key;
	$settings['consumer_secret'] =$consumer_Secret;
	$settings['bearer_token'] = $bearer_token;
	return $settings;
}



function getClientInfoFree($selectedDetails)
{
	$settings['access_token'] = $selectedDetails->access_token;
	$settings['access_token_secret'] = $selectedDetails->token_secret;
	$settings['consumer_key'] =$selectedDetails->consumer_key;
	$settings['consumer_secret'] =$selectedDetails->consumer_secret;
	$settings['bearer_token'] = $selectedDetails->bearer_token;
	return $settings;
}


function getReplyClientInfo()
{
	$consumer_Key=getValueByKey('REPLY_CONSUMER_KEY');
	$consumer_Secret=getValueByKey('REPLY_CONSUMER_SECRET');
	$access_Token=getValueByKey('REPLY_ACCESS_TOKEN');
	$token_Secret=getValueByKey('REPLY_TOKEN_SECRET');
	$bearer_token=getValueByKey('REPLY_BEARER_TOKEN');
   
	$settings['access_token'] = $access_Token;
	$settings['access_token_secret'] = $token_Secret;
	$settings['consumer_key'] =$consumer_Key;
	$settings['consumer_secret'] =$consumer_Secret;
	$settings['bearer_token'] = $bearer_token;
	return $settings;
}

function changeByKey($key)
{
    $name = str_replace('_', ' ', $key);
    return $name;
}

function changeByCodeKey($key)
{
    $name = str_replace(' ', '_', $key);
	if($name)
	{
		$name = strtoupper($name);
	}
    return $name;
}

function getUserAssignRule($assign_type,$social_type,$message)
{
	try{
		$userId="";
		$message= Str::lower($message);
		$currentDate = now()->toDateString();
		$get = UserAssignRule::where('assign_type',$assign_type)
		       ->where('enable','1')
		       ->whereRaw("FIND_IN_SET('$social_type', social_type)")
			   ->whereRaw('(now() between from_date and to_date)')
			   ->get(); 	   	   
		foreach ($get as $data) {
			$array = explode(",", $data->Keyword);
			foreach ($array as $value) {
			   $value= Str::lower($value);
			   if (Str::contains($message, $value)) {
				   $userId= $data->user_id;
				   continue;
			   }
			}                    
		}    
		return $userId;          
	} catch(Exception $e) {
		return redirect()->back()->with('message',$e->getMessage());
	}
}


function getDateRange($range)
{
	$now = Carbon::now();
	$range = strtolower($range);

	switch ($range) {
		case 'today':
			$startDate = $now->startOfDay();
			break;

		case 'yesterday':
			$startDate = $now->subDay()->startOfDay();
			break;

		case 'this week':
			$startDate = $now->startOfWeek();
			break;

		case 'last week':
			$startDate = $now->subWeek()->startOfWeek();
			break;

		case 'this month':
			$startDate = $now->startOfMonth();
			break;

		case 'last month':
			$startDate = $now->subMonth()->startOfMonth();
			break;

		case 'this quater':
			$startDate = $now->startOfQuarter();
			break;

		case 'last quater':
			$startDate = $now->subQuarter()->startOfQuarter();
			break;

		case 'this year':
			$startDate = $now->startOfYear();
			break;

		case 'last year':
			$startDate = $now->subYear()->startOfYear();
			break;

		default:
			$startDate = null;
			break;
	}
    
	return $startDate;
}

function getOperator($operator)
{
	switch ($operator) {
		case 'EQUAL_TO':
			$operatorSign = '=';
			break;

		case 'NOT_EQUAL_TO':
			$operatorSign = '<>';
			break;

		case 'GREATER_THAN':
			$operatorSign = '>';
			break;

		case 'LESS_THAN':
			$operatorSign = '<';
			break;

		case 'GREATER_THAN_OR_EQUAL_TO':
			$operatorSign = '>=';
			break;

		case 'LESS_THAN_OR_EQUAL_TO':
			$operatorSign = '<=';
			break;

		case 'CONTAINS':
			$operatorSign = 'like';
			break;	

		case 'STARTS_WITH':
			$operatorSign = 'like';
			break;
				
		case 'ENDS_WITH':
			$operatorSign = 'like';
			break;

		default:
			$operatorSign = null;
			break;
	}
    
	return $operatorSign;
}


function setPostAssignRule($assign_type,$message)
{
	try{
		$category="Spam";
		$message= Str::lower($message);
		$assign_typeupper= Str::upper($assign_type);
		$priority=RulePriority::where('type',$assign_typeupper)->orderBy('value','asc')->get(); 	   
		foreach ($priority as $data) {
			$get = PostAssignRule::where('type',$assign_type)
		       ->where('status','Active')
			   ->where('category',$data->label)
			   ->first();   
		    if($get){
				$array = explode(",", $get->keyword);
				foreach ($array as $value) {
					$value= Str::lower($value);
					if (Str::contains($message, $value)) {
						$category= $get->category;
						return $category;  
					}
				}
		    }	                    
		}   
		return $category;          
	} catch(Exception $e) {
		return redirect()->back()->with('message',$e->getMessage());
	}
}

function assigntoLog($id,$type){
	$res = [];
	if($type=='ticket'){
		$res=SocialTicket::where('assigned_to',$id)
		->whereNull('deleted_at')
		->pluck('id')
		->toArray();
	}elseif($type=='Lead'){
		$res=LeadTicket::where('assigned_to',$id)
		->whereNull('deleted_at')
		->pluck('id')
		->toArray();
	}
	$res = array_merge(
        $res,
	$res = TweetLog::where('assignto_by_id', $id)
    ->where('field', 'assignedto')
    ->where('post_type', $type)
    ->distinct()
    ->pluck('post_id')
    ->toArray()
	);
	$res = array_unique($res);
	return $res;
}


function createLog($postInfo,$data,$type)
{
//var_dump($postInfo->status);die;
	if(!empty($data))
	{
		$requestInfo = [];
		foreach($data as $key=>$val)
		{
			if($key !='updated_at')
			{
				$name = "";
				$assignname = "";
				$assignid="";
				if($name = auth()->user()->name)
				{
					if($type == 'ticket')
					{
						// if($key == 'assigned_to' && $postInfo->assigned_to !== $val)
						// {
							// $requestInfo[] = ['field'=>'final_state','new_value'=>'Open',"old_value"=>$postInfo->$key,'change_by'=>$name,'change_date'=>now(),'post_type'=>$type,'post_id'=>$postInfo->id];
							// $requestInfo[] = ['field'=>'post_status','new_value'=>'Assigned',"old_value"=>$postInfo->$key,'change_by'=>$name,'change_date'=>now(),'post_type'=>$type,'post_id'=>$postInfo->id];
						// }
						// else if($postInfo->status != $val && $val && $key == 'status' && in_array($val,['Pending with team','Move to internal Team']))
						// {
							// $requestInfo[] = ['field'=>'final_state','new_value'=>'In Process',"old_value"=>$postInfo->final_state,'change_by'=>$name,'change_date'=>now(),'post_type'=>$type,'post_id'=>$postInfo->id];
							
							// $requestInfo[] = ['field'=>'post_status','new_value'=>$val,"old_value"=>$postInfo->post_status,'change_by'=>$name,'change_date'=>now(),'post_type'=>$type,'post_id'=>$postInfo->id];
						// }
						
					}
					if($val !=$postInfo->$key)
					{
						if(in_array($key,["assigned_to","assignedto","assigned_to"]))
						{
							$field="assignedto";
							if($val)
							{
							    $user = User::find($val);
							    if( $user)
							    {
							     $assignname= $user->name;
								 $assignid=$user->id;
							    }
							}
							$oldName= "";
							if($postInfo->$key)
							{
							    $oldNameInfo = User::find($postInfo->$key);
							    if( $oldNameInfo)
							    {
							    $oldName= $oldNameInfo->name;
							    }
							}
							$requestInfo[] = ['field'=>$field,'new_value'=>$assignname,"old_value"=>$oldName,'change_by'=>$name,'change_date'=>now(),'post_type'=>$type,'post_id'=>$postInfo->id,"assignto_by_id"=>$assignid];
						}
						else{
							$requestInfo[] = ['field'=>$key,'new_value'=>$val,"old_value"=>$postInfo->$key,'change_by'=>$name,'change_date'=>now(),'post_type'=>$type,'post_id'=>$postInfo->id,"assignto_by_id"=>$assignid];
						}
					}
					
					
					// if($postInfo->status != $val && $val && $key == 'status' && in_array($val,['Resolved','Rejected','Duplicate']))
					// {
						// $requestInfo[] = ['field'=>'final_state','new_value'=>'Close',"old_value"=>$postInfo->final_state,'change_by'=>$name,'change_date'=>now(),'post_type'=>$type,'post_id'=>$postInfo->id];
						
						// $requestInfo[] = ['field'=>'post_status','new_value'=>$val,"old_value"=>$postInfo->status,'change_by'=>$name,'change_date'=>now(),'post_type'=>$type,'post_id'=>$postInfo->id];
						
						// $requestInfo[] = ['field'=>'post_status','new_value'=>'Close',"old_value"=>$val,'change_by'=>$name,'change_date'=>now(),'post_type'=>$type,'post_id'=>$postInfo->id];

					// }
				}
			}
		}
		if(!empty($requestInfo))
		{
			$res = TweetLog::insert($requestInfo);
		}
	}
	
	
}


function adminChange($postInfo,$data,$type,$operation)
{
	if(!empty($data))
	{
		$requestInfo = [];
		if($operation=="update"){
			foreach($data as $key=>$val)
			{
				if($key !='updated_at')
				{
					$name = "";
					$assignname = "";
					$assignid="";
					if($name = auth()->user()->id)
					{
						if($val !=$postInfo->$key)
						{
							$primary_key=$postInfo->getKeyName();
							if(in_array($key,["assigned_to","assignedto","assigned_to"]))
							{
								$field="assignedto";
								if($val)
								{
									$user = User::find($val);
									if( $user)
									{
									$assignname= $user->name;
									$assignid=$user->id;
									}
								}
								$oldName= "";
								if($postInfo->$key)
								{
									$oldNameInfo = User::find($postInfo->$key);
									if( $oldNameInfo)
									{
									$oldName= $oldNameInfo->name;
									}
								}
								$requestInfo[] = ['field'=>$field,'new_value'=>$assignname,"old_value"=>$oldName,'change_by'=>$name,'change_date'=>now(),'table_name'=>$type,'field_id'=>$postInfo->$primary_key,"assignto_by_id"=>$assignid,"operation"=>$operation];
							}
							else{
								$requestInfo[] = ['field'=>$key,'new_value'=>$val,"old_value"=>$postInfo->$key,'change_by'=>$name,'change_date'=>now(),'table_name'=>$type,'field_id'=>$postInfo->$primary_key,"assignto_by_id"=>$assignid,"operation"=>$operation];
							}
						}
						
					}
				}
			}
		}elseif($operation=="create"){
			$name = auth()->user()->id;
			$primary_key=$data->getKeyName();
			$requestInfo[] = ['change_by'=>$name,'change_date'=>now(),'table_name'=>$type,'field_id'=>$data->$primary_key,"operation"=>$operation];
		}elseif($operation=="delete"){
			$name = auth()->user()->id;
			$requestInfo[] = ['change_by'=>$name,'change_date'=>now(),'table_name'=>$type,'field_id'=>$data,"operation"=>$operation];
		}	
		if(!empty($requestInfo))
		{
			$res = AdminChanges::insert($requestInfo);
		}
	}
	
	
}


	function getDigitCodeForLead()
	{
		$value = getValueByKey('LEAD_NO',true);
		$lengthVal = strlen($value);
		$genratedVal = $value;
		if($lengthVal == 1)
		{
			$genratedVal = '000'.$value;
		}
		else if($lengthVal == 2)
		{
			$genratedVal = '00'.$value;
		}
		else if($lengthVal == 3)
		{
			$genratedVal = '0'.$value;
		}
		
		
		$date = date("mY")."LE".$genratedVal;
		return $date;
	
	}

	function getDigitCodeForTicket()
	{
		$value = getValueByKey('TICKET_NO',true);
		$lengthVal = strlen($value);
		$genratedVal = $value;
		if($lengthVal == 1)
		{
			$genratedVal = '000'.$value;
		}
		else if($lengthVal == 2)
		{
			$genratedVal = '00'.$value;
		}
		else if($lengthVal == 3)
		{
			$genratedVal = '0'.$value;
		}
		
		
		$date = date("mY")."TI".$genratedVal;
		return $date;
		
	}

	function getDigitCodeForWhatsApp()
	{
		$value = getValueByKey('WHATSAPP_NO',true);
		$lengthVal = strlen($value);
		$genratedVal = $value;
		if($lengthVal == 1)
		{
			$genratedVal = '000'.$value;
		}
		else if($lengthVal == 2)
		{
			$genratedVal = '00'.$value;
		}
		else if($lengthVal == 3)
		{
			$genratedVal = '0'.$value;
		}
		
		
		$date = date("mY")."WP".$genratedVal;
		return $date;
		
		
	}
	
	function getDigitCodeForLinkedin()
	{
		$value = getValueByKey('LINKEDIN_NO',true);
		$lengthVal = strlen($value);
		$genratedVal = $value;
		if($lengthVal == 1)
		{
			$genratedVal = '000'.$value;
		}
		else if($lengthVal == 2)
		{
			$genratedVal = '00'.$value;
		}
		else if($lengthVal == 3)
		{
			$genratedVal = '0'.$value;
		}
		
		
		$date = date("mY")."WP".$genratedVal;
		return $date;
		
		
	}
	function todayDate()
	{
		return (new DateTime)->format('Y-m-d h:i:s');
	}
	
	function todayDate24()
	{
		return (new DateTime)->format('Y-m-d H:i:s');
	}

	function getDaysByDate($to)
	{
		//$to = Carbon::createFromFormat('Y-m-d H:i:s', $to);
		$from = todayDate();
		

		$diff = strtotime($to) - strtotime($from);
		$data = abs(round($diff / 86400));
		
		return $data;
	}
	
	 function getHeaderForExcel()
	 {
		  $headers = array(
        'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Content-Disposition' => 'attachment; filename=abc.csv',
        'Expires' => '0',
        'Pragma' => 'public',
		);
		return $headers;
	 }
	function  downloadCsv($data,$col)
	{
		$headers = getHeaderForExcel();
		$filename = "doenload.csv";
		$handle = fopen($filename, 'w');
		fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); 
		fputcsv($handle, $col);

		
			foreach ($data as $posts) {
				// Add a new row with data
				$formattedStatus = formatStatus($posts); 
				$converted = "NO";
				if($posts->converted || $posts->convertLead){
					$converted = "YES";
				}
				$aging=getDaysByDate($posts->istPostDate);
				fputcsv($handle, [
				"'" . strval($posts->getTweet_id),
				$posts->postMessage,
				$posts->socialUser_name." (".$posts->socialUser_userName?$posts->socialUser_userName:''.")",
				$posts->source,
				$posts->postUrl,
				$posts->istPostDate,
				$posts->post_category,
				$posts->status,
				$converted,
				$posts->socialTickets,
				$posts->leads,
				$aging,
				$posts->bp_number?"'" . strval($posts->bp_number):'',
				$formattedStatus
				]);
			}
		fclose($handle);

		return Response::download($filename, "download.csv", $headers);
	}
	
	function  downloadtrackcsv($data,$col)
	{
		$headers = getHeaderForExcel();
		$filename = "doenload.csv";
		$handle = fopen($filename, 'w');
		fputcsv($handle, $col);

		
			foreach ($data as $posts) {
				// Add a new row with data
				fputcsv($handle, [
				$posts['sr_no'],
				$posts['new_value'],
				$posts['old_value'],
				$posts['field'],
				$posts['operation'],
				$posts['name'],
				$posts['change_date'],
				$posts['description'],
				]);
			}
		fclose($handle);

		return Response::download($filename, "download.csv", $headers);
	}

	function  downloadTicketCsv($data,$col)
	{
		$headers = getHeaderForExcel();
		$filename = "doenload.csv";
		$handle = fopen($filename, 'w');
		fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($handle, $col);

		
			foreach ($data as $posts) {
				$formattedStatus = formatStatus($posts); 
				// Add a new row with data
				fputcsv($handle, [
				"'" . strval($posts->getTweet_id),
				$posts->ticket_id,
				$posts->postMessage,
				$posts->socialUser,
				$posts->source,
				$posts->final_state,
				$posts->status,
				$posts->name,
				$posts->date_Created,
				$posts->bipNumber?"'" . strval($posts->bipNumber):'',
				$posts->mobile_no,
				$posts->description,
				$posts->resolution,
				$posts->additional_Text,
				$posts->postUrl,
				$formattedStatus
				]);
			}
		fclose($handle);

		return Response::download($filename, "download.csv", $headers);
	}

	function  downloadTicketReportCsv($data,$col)
	{
		$headers = getHeaderForExcel();
		$filename = "doenload.csv";
		$handle = fopen($filename, 'w');
		fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($handle, $col);

		
			foreach ($data as $posts) {
				$formattedStatus = formatStatus($posts); 
				// Add a new row with data
				fputcsv($handle, [
				"'" . strval($posts->getTweet_id),
				$posts->ticket_id,
				$posts->postMessage,
				$posts->socialUser,
				$posts->source,
				$posts->final_state,
				$posts->status,
				$posts->name,
				$posts->date_Created,
				$posts->bipNumber?"'" . strval($posts->bipNumber):'',
				$posts->mobile_no,
				$posts->description,
				$posts->resolution,
				$posts->additional_Text,
				$posts->sap_ticket,
				$posts->postUrl,
				$formattedStatus
				]);
			}
		fclose($handle);

		return Response::download($filename, "download.csv", $headers);
	}
	
	function downloadlogCsv($data, $columns)
    {
        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=log_report.csv",
            "Cache-Control" => "no-cache, must-revalidate",
            "Pragma" => "public",
            "Expires" => "0",
        ];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

	function formatStatus($post)
		{
			// Assuming $post->status is a JSON string, decode it to an array
			$statuses = json_decode($post->activies, true);

			$formattedStatus = [];
			foreach ($statuses as $status) {
				$formattedStatus[] = $status['name'] . ' (' . date('Y-m-d H:i:s', strtotime($status['created_at'])) . ') : ' . $status['text'];
			}

			return implode("\n", $formattedStatus);
		}
	
	function  downloadLeadCsv($data,$col)
	{
		$headers = getHeaderForExcel();
		$filename = "doenload.csv";
		$handle = fopen($filename, 'w');
		fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($handle, $col);

		
			foreach ($data as $posts) {
				// Add a new row with data
				$formattedDate =  date('Y-m-d H:i:s', strtotime($posts->created_date));
				fputcsv($handle, [
				"'" . strval($posts->getTweet_id),
				$posts->leadId,
				$posts->title,
				$posts->first_name." ".$posts->last_name,
				$posts->lead_source,
				$posts->status,
				$formattedDate,
				$posts->name,
				$posts->description,
				$posts->resolution,
				$posts->bp_number?"'" . strval($posts->bp_number):'',
				$posts->mobile,
				$posts->leadBy,
				$posts->department,
				]);
			}
		fclose($handle);

		return Response::download($filename, "download.csv", $headers);
	}
	
	
	function getOptionByKey($key)
	{
		$option =  Option::where('status',1)->whereIn('key',explode(',',$key))->get();
		return $option;
	}
	
	function getKeyByValue($data,$key,$datatype)
	{
		$optionData = [];
		
		if($data && count($data) > 0)
		{
			foreach($data as $option)
			{
				
				if(strtoupper($option->key) == strtoupper($key))
				{
					if($option->specialLogic)
					{
						$dataTypes = explode(",",$option->specialLogic);
						if(in_array($datatype,$dataTypes))
						{
							$optionData[] = $option;
						}
					}
					else{
						$optionData[] = $option;
					}
				}
			}
		}
		
		return $optionData;
	}
	
	function replaceString($val)
	{
		$remove[] = "'";
		$remove[] = '`';

		return  str_replace( $remove, "", $val );
					
	}
	
	function getAccessType()
	{
		$access= ["READ_WRITE"=>"Read Write","READ_ONLY"=>"Read Only","HIDDEN"=>"Hidden"];
		return $access;
	}
	
	function addUIComponent($key)
	{
		$id = loggedUserId();
		$UIcomponent = [];
		$access = [];
		if($id)
		{
			$user = User::find($id);
			
			if($user)
			{
				$UIcomponent = Component::leftJoin('tb_role_access_mapping', function($leftJoin)use ($user)
				{
					$leftJoin->on('tb_role_access_mapping.component_id', '=', 'tb_component.id');
					$leftJoin->where('tb_role_access_mapping.user_role_id',$user->role);
				})
				->select('tb_component.*','tb_role_access_mapping.*','tb_component.id as id','tb_role_access_mapping.id as mapId')
				->get();
				
				foreach($UIcomponent as $compo)
				{
					if(!$compo->mapId)
					{
						$compo->access = "HIDDEN";
					}
					$access [$compo->component_key] = $compo->access;
				}
				
				
			}
		}
		if(isset($access[$key]))
		{
			return $access[$key];
		}
		return  "HIDDEN";
	}
	
	function  downloadReportCsv($data,$col,$field)
	{
		$headers = getHeaderForExcel();
		$filename = "doenload.csv";
		$handle = fopen($filename, 'w');
		fputcsv($handle, $col);

		if(!empty($data) && $data->count())
		{
			foreach($data as $key => $datas)
			{	$dataArray = [];	   
				if(!empty($field) && $field->count())
				{
					foreach($field as $key => $fields) 
					{			   
						$dataArray[] = $datas->{$fields->field_key};
					}
				}
				fputcsv($handle, $dataArray);
			}
		}
		fclose($handle);

		return Response::download($filename, "download.csv", $headers);
	}

	function formatDate($date) {
		return $date->format('Y-m-d');
	}
	
	function parseDateWithCurrentTime($dateString) {
		$parsedDate = Carbon::parse($dateString);
		return $parsedDate->setTime(Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second);
	}

	function getSource() {
		$source = SocialPlatform::where('status',1)->where('key','SOURCE')->get();
		return $source;
	}

	function getSourceStatus($value) {
		$source = SocialPlatform::whereRaw('UPPER(value) = ?', [strtoupper($value)])->first();
		$status="";
		if($source->status==1){
			$status= true;
		}else{
            $status= false;  
		}
		return $status;
	}

	function getSocialIcon($value,$isImg=null) {
		$icon="";
		if (strtoupper($value)=="FACEBOOK"){
			$icon="bi bi-facebook";
		}elseif (strtoupper($value)=="TWITTER"){
            $icon="bi bi-twitter";  
		}elseif (strtoupper($value)=="WHATSAPP") {
			$icon="bi bi-whatsapp";
		}elseif (strtoupper($value)=="LINKEDIN") {
			$icon="bi bi-linkedin";
		}elseif (strtoupper($value)=="INSTAGRAM") {
			$icon="bi bi-instagram";
		}
		
		$icon=$isImg?strtolower($value).".png":$icon;
		
		return $icon;
	}
	
	function getUrlinString($text)
	{
	$pattern = "/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/i";
    $textWithLinks = preg_replace($pattern, '<a href="$0" target="_blank">$0</a>', $text);
	return $textWithLinks;
	}

	function getParenthesesString($string)
	{
		preg_match_all('/\(([^)]+)\)/', $string, $matches);
		$extractedValues = $string;
		$extracted = $matches[1];
		if(count($matches[1]) > 0)
		{
			$extractedValues = $extracted[0];
		}
		return $extractedValues;
	}
	function userNameByText($string)
	{
		$position = strpos($string, "(");
		$substringBeforeParentheses = $string;
		// Check if an opening parenthesis was found
		if ($position !== false) {
			// Get the substring before the opening parenthesis
			$substringBeforeParentheses = substr($string, 0, $position);
		}
		return $substringBeforeParentheses;
	}

	function isoDate($originalDateTime)
	{
		// Original date and time string
		//$originalDateTime = "2023-07-14 09:38:03";
		$iso8601DateTime = "";
		if(isDate($originalDateTime))
		{
			// Create a DateTime object from the original string
			$dateTime = new DateTime($originalDateTime);

			// Format the DateTime object in ISO 8601 format
			$iso8601DateTime = $dateTime->format("Y-m-d\TH:i:s.u\Z");
		}

		return $iso8601DateTime;
	}
	
	function istDate($originalDateTime)
	{
		$iso8601DateTime = "";
		if(isDate($originalDateTime))
		{
			$dateTime = new DateTime($originalDateTime);

			// Format the DateTime object in ISO 8601 format
			 $iso8601DateTime = $dateTime->format("Y-m-d H:i:s");
		}
		

		return $iso8601DateTime;
	}	
	function substrTxt($text)
	{
		$text = substr($text, 0, 8);
		return $text;
	}
	
	function isDate($dateString,$expectedFormat= "Y-m-d H:i:s")
	{
		$dateTime = DateTime::createFromFormat($expectedFormat, $dateString);
		if ($dateTime && $dateTime->format($expectedFormat) == $dateString) {
			$res = true;
		} else {
			$res = false;
		}

		return $res;
	}


	function getPlatformStatus($source){
		$platform = SocialPlatform::where('key','SOURCE')
		->where('value',$source)
		->first();
		return $platform->status;
	}

	function twitteroauth(){
		$stack = HandlerStack::create();
			$consumer_Key=getValueByKey('CONSUMER_KEY');
			$consumer_Secret=getValueByKey('CONSUMER_SECRET');
			$access_Token=getValueByKey('ACCESS_TOKEN');
			$token_Secret=getValueByKey('TOKEN_SECRET');
			$auth = new Oauth1([
				'consumer_key' => $consumer_Key,
				'consumer_secret' => $consumer_Secret,
				'token' => $access_Token,
				'token_secret' => $token_Secret
			]);
	
			$stack->push($auth);
	
			$client = new Client([
				'base_uri' => 'https://api.twitter.com/2/',
				'handler' => $stack,
				'auth' => 'oauth'
			]);
			return $client;
	}

	function uploadFileDocuments($fileDocument,$folder)
    {
		$uploadFileInfo = [];
		$filename = "";
		$filepath = "";
		// AWS S3 storage
	   $fileUploadName = $fileDocument->getClientOriginalName();
	   $name="attachment".getValueByKey('AWS_FILENAME');
	   $prefix = Carbon::now()->format('YmdHis');
	   $url = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
	   $filename = $name.'_'.$prefix . '.' . $fileDocument->getClientOriginalExtension();
	   $host = request()->getHttpHost();
	   if($folder)
	   {
		  $filename = $folder."/".$filename;
	   }
	   $filepath = $host .'/'. $filename;
	   
	   $storagePath = $url . $filepath ;
	   
	   //Store on AWS S3 Bucket
	   \Storage::disk('s3')->put($filepath, file_get_contents($fileDocument));
	   $uploadFileInfo['fileName'] = $filename;
	   $uploadFileInfo['filePath'] = $storagePath;
	   $url = downloadBox($filename);
	   $uploadFileInfo['fileUrl'] = $url;
	   getValueByKey('AWS_FILENAME',true);
       return $uploadFileInfo;   
	}

	function downloadBox($filename)
    {
	

		$host = request()->getHttpHost();
    
		// Use the Storage facade to access the 's3' disk
		$disk = \Storage::disk('s3');

		$fileUrl = $disk->temporaryUrl(
			$host.'/'.$filename,
		    now()->addDays(6)
		);
	
		return $fileUrl;
		
	}



	function convertSpecialCharToNormalChar($text) 
	{	
		$text = preg_replace('/\*([^*]+)\*/', '$1', $text);
		$text = preg_replace('/\[(.*?)\]\((.*?)\)/', '$1', $text);
		$text = preg_replace('/\s+/', ' ', $text);
		$text = str_replace('\\', '', $text);
		$target = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', '?', '.', ',', '"', "'"];
		$specialList = [
			'serifBold' => ['𝐚', '𝐛', '𝐜', '𝐝', '𝐞', '𝐟', '𝐠', '𝐡', '𝐢', '𝐣', '𝐤', '𝐥', '𝐦', '𝐧', '𝐨', '𝐩', '𝐪', '𝐫', '𝐬', '𝐭', '𝐮', '𝐯', '𝐰', '𝐱', '𝐲', '𝐳', '𝐀', '𝐁', '𝐂', '𝐃', '𝐄', '𝐅', '𝐆', '𝐇', '𝐈', '𝐉', '𝐊', '𝐋', '𝐌', '𝐍', '𝐎', '𝐏', '𝐐', '𝐑', '𝐒', '𝐓', '𝐔', '𝐕', '𝐖', '𝐗', '𝐘', '𝐙', '𝟎', '𝟏', '𝟐', '𝟑', '𝟒', '𝟓', '𝟔', '𝟕', '𝟖', '𝟗', '❗', '❓', '.', ',', '"', "'"],
			'serifItalic' => ['𝑎', '𝑏', '𝑐', '𝑑', '𝑒', '𝑓', '𝑔', 'ℎ', '𝑖', '𝑗', '𝑘', '𝑙', '𝑚', '𝑛', '𝑜', '𝑝', '𝑞', '𝑟', '𝑠', '𝑡', '𝑢', '𝑣', '𝑤', '𝑥', '𝑦', '𝑧', '𝐴', '𝐵', '𝐶', '𝐷', '𝐸', '𝐹', '𝐺', '𝐻', '𝐼', '𝐽', '𝐾', '𝐿', '𝑀', '𝑁', '𝑂', '𝑃', '𝑄', '𝑅', '𝑆', '𝑇', '𝑈', '𝑉', '𝑊', '𝑋', '𝑌', '𝑍', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', '?', '.', ',', '"', "'"],
			'serifBoldItalic' => ['𝒂', '𝒃', '𝒄', '𝒅', '𝒆', '𝒇', '𝒈', '𝒉', '𝒊', '𝒋', '𝒌', '𝒍', '𝒎', '𝒏', '𝒐', '𝒑', '𝒒', '𝒓', '𝒔', '𝒕', '𝒖', '𝒗', '𝒘', '𝒙', '𝒚', '𝒛', '𝑨', '𝑩', '𝑪', '𝑫', '𝑬', '𝑭', '𝑮', '𝑯', '𝑰', '𝑱', '𝑲', '𝑳', '𝑴', '𝑵', '𝑶', '𝑷', '𝑸', '𝑹', '𝑺', '𝑻', '𝑼', '𝑽', '𝑾', '𝑿', '𝒀', '𝒁', '𝟎', '𝟏', '𝟐', '𝟑', '𝟒', '𝟓', '𝟔', '𝟕', '𝟖', '𝟗', '❗', '❓', '.', ',', '"', "'"],
			'sans' => ['𝖺', '𝖻', '𝖼', '𝖽', '𝖾', '𝖿', '𝗀', '𝗁', '𝗂', '𝗃', '𝗄', '𝗅', '𝗆', '𝗇', '𝗈', '𝗉', '𝗊', '𝗋', '𝗌', '𝗍', '𝗎', '𝗏', '𝗐', '𝗑', '𝗒', '𝗓', '𝖠', '𝖡', '𝖢', '𝖣', '𝖤', '𝖥', '𝖦', '𝖧', '𝖨', '𝖩', '𝖪', '𝖫', '𝖬', '𝖭', '𝖮', '𝖯', '𝖰', '𝖱', '𝖲', '𝖳', '𝖴', '𝖵', '𝖶', '𝖷', '𝖸', '𝖹', '𝟢', '𝟣', '𝟤', '𝟥', '𝟦', '𝟧', '𝟨', '𝟩', '𝟪', '𝟫', '!', '?', '.', ',', '"', "'"],
			'sansBold' => ['𝗮', '𝗯', '𝗰', '𝗱', '𝗲', '𝗳', '𝗴', '𝗵', '𝗶', '𝗷', '𝗸', '𝗹', '𝗺', '𝗻', '𝗼', '𝗽', '𝗾', '𝗿', '𝘀', '𝘁', '𝘂', '𝘃', '𝘄', '𝘅', '𝘆', '𝘇', '𝗔', '𝗕', '𝗖', '𝗗', '𝗘', '𝗙', '𝗚', '𝗛', '𝗜', '𝗝', '𝗞', '𝗟', '𝗠', '𝗡', '𝗢', '𝗣', '𝗤', '𝗥', '𝗦', '𝗧', '𝗨', '𝗩', '𝗪', '𝗫', '𝗬', '𝗭', '𝟬', '𝟭', '𝟮', '𝟯', '𝟰', '𝟱', '𝟲', '𝟳', '𝟴', '𝟵', '❗', '❓', '.', ',', '"', "'"],
			'sansItalic' => ['𝘢', '𝘣', '𝘤', '𝘥', '𝘦', '𝘧', '𝘨', '𝘩', '𝘪', '𝘫', '𝘬', '𝘭', '𝘮', '𝘯', '𝘰', '𝘱', '𝘲', '𝘳', '𝘴', '𝘵', '𝘶', '𝘷', '𝘸', '𝘹', '𝘺', '𝘻', '𝘈', '𝘉', '𝘊', '𝘋', '𝘌', '𝘍', '𝘎', '𝘏', '𝘐', '𝘑', '𝘒', '𝘓', '𝘔', '𝘕', '𝘖', '𝘗', '𝘘', '𝘙', '𝘚', '𝘛', '𝘜', '𝘝', '𝘞', '𝘟', '𝘠', '𝘡', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', '?', '.', ',', '"', "'"],
			'sansBoldItalic' => ['𝙖', '𝙗', '𝙘', '𝙙', '𝙚', '𝙛', '𝙜', '𝙝', '𝙞', '𝙟', '𝙠', '𝙡', '𝙢', '𝙣', '𝙤', '𝙥', '𝙦', '𝙧', '𝙨', '𝙩', '𝙪', '𝙫', '𝙬', '𝙭', '𝙮', '𝙯', '𝘼', '𝘽', '𝘾', '𝘿', '𝙀', '𝙁', '𝙂', '𝙃', '𝙄', '𝙅', '𝙆', '𝙇', '𝙈', '𝙉', '𝙊', '𝙋', '𝙌', '𝙍', '𝙎', '𝙏', '𝙐', '𝙑', '𝙒', '𝙓', '𝙔', '𝙕', '𝟎', '𝟏', '𝟐', '𝟑', '𝟒', '𝟓', '𝟔', '𝟕', '𝟖', '𝟗', '❗', '❓', '.', ',', '"', "'"],
			'script' => ['𝒶', '𝒷', '𝒸', '𝒹', 'ℯ', '𝒻', 'ℊ', '𝒽', '𝒾', '𝒿', '𝓀', '𝓁', '𝓂', '𝓃', 'ℴ', '𝓅', '𝓆', '𝓇', '𝓈', '𝓉', '𝓊', '𝓋', '𝓌', '𝓍', '𝓎', '𝓏', '𝒜', 'ℬ', '𝒞', '𝒟', 'ℰ', 'ℱ', '𝒢', 'ℋ', 'ℐ', '𝒥', '𝒦', 'ℒ', 'ℳ', '𝒩', '𝒪', '𝒫', '𝒬', 'ℛ', '𝒮', '𝒯', '𝒰', '𝒱', '𝒲', '𝒳', '𝒴', '𝒵', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', '?', '.', ',', '"', "'"],
			'scriptBold' => ['𝓪', '𝓫', '𝓬', '𝓭', '𝓮', '𝓯', '𝓰', '𝓱', '𝓲', '𝓳', '𝓴', '𝓵', '𝓶', '𝓷', '𝓸', '𝓹', '𝓺', '𝓻', '𝓼', '𝓽', '𝓾', '𝓿', '𝔀', '𝔁', '𝔂', '𝔃', '𝓐', '𝓑', '𝓒', '𝓓', '𝓔', '𝓕', '𝓖', '𝓗', '𝓘', '𝓙', '𝓚', '𝓛', '𝓜', '𝓝', '𝓞', '𝓟', '𝓠', '𝓡', '𝓢', '𝓣', '𝓤', '𝓥', '𝓦', '𝓧', '𝓨', '𝓩', '𝟎', '𝟏', '𝟐', '𝟑', '𝟒', '𝟓', '𝟔', '𝟕', '𝟖', '𝟗', '❗', '❓', '.', ',', '"', "'"],
			'fraktur' => ['𝔞', '𝔟', '𝔠', '𝔡', '𝔢', '𝔣', '𝔤', '𝔥', '𝔦', '𝔧', '𝔨', '𝔩', '𝔪', '𝔫', '𝔬', '𝔭', '𝔮', '𝔯', '𝔰', '𝔱', '𝔲', '𝔳', '𝔴', '𝔵', '𝔶', '𝔷', '𝔄', '𝔅', 'ℭ', '𝔇', '𝔈', '𝔉', '𝔊', 'ℌ', 'ℑ', '𝔍', '𝔎', '𝔏', '𝔐', '𝔑', '𝔒', '𝔓', '𝔔', 'ℜ', '𝔖', '𝔗', '𝔘', '𝔙', '𝔚', '𝔛', '𝔜', 'ℨ', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', '?', '.', ',', '"', "'"],
			'frakturBold' => ['𝖆', '𝖇', '𝖈', '𝖉', '𝖊', '𝖋', '𝖌', '𝖍', '𝖎', '𝖏', '𝖐', '𝖑', '𝖒', '𝖓', '𝖔', '𝖕', '𝖖', '𝖗', '𝖘', '𝖙', '𝖚', '𝖛', '𝖜', '𝖝', '𝖞', '𝖟', '𝕬', '𝕭', '𝕮', '𝕯', '𝕰', '𝕱', '𝕲', '𝕳', '𝕴', '𝕵', '𝕶', '𝕷', '𝕸', '𝕹', '𝕺', '𝕻', '𝕼', '𝕽', '𝕾', '𝕿', '𝖀', '𝖁', '𝖂', '𝖃', '𝖄', '𝖅', '𝟎', '𝟏', '𝟐', '𝟑', '𝟒', '𝟓', '𝟔', '𝟕', '𝟖', '𝟗', '❗', '❓', '.', ',', '"', "'"],
			'monospace' => ['𝚊', '𝚋', '𝚌', '𝚍', '𝚎', '𝚏', '𝚐', '𝚑', '𝚒', '𝚓', '𝚔', '𝚕', '𝚖', '𝚗', '𝚘', '𝚙', '𝚚', '𝚛', '𝚜', '𝚝', '𝚞', '𝚟', '𝚠', '𝚡', '𝚢', '𝚣', '𝙰', '𝙱', '𝙲', '𝙳', '𝙴', '𝙵', '𝙶', '𝙷', '𝙸', '𝙹', '𝙺', '𝙻', '𝙼', '𝙽', '𝙾', '𝙿', '𝚀', '𝚁', '𝚂', '𝚃', '𝚄', '𝚅', '𝚆', '𝚇', '𝚈', '𝚉', '𝟶', '𝟷', '𝟸', '𝟹', '𝟺', '𝟻', '𝟼', '𝟽', '𝟾', '𝟿', '！', '？', '．', '，', '"', '＇'],
			'fullwidth' => ['ａ', 'ｂ', 'ｃ', 'ｄ', 'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ', 'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ', 'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ', 'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ', 'ｙ', 'ｚ', 'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ', 'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ', 'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ', 'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ', 'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ', 'Ｚ', '０', '１', '２', '３', '４', '５', '６', '７', '８', '９', '！', '？', '．', '，', '"', '＇'],
			'doublestruck' => ['𝕒', '𝕓', '𝕔', '𝕕', '𝕖', '𝕗', '𝕘', '𝕙', '𝕚', '𝕛', '𝕜', '𝕝', '𝕞', '𝕟', '𝕠', '𝕡', '𝕢', '𝕣', '𝕤', '𝕥', '𝕦', '𝕧', '𝕨', '𝕩', '𝕪', '𝕫', '𝔸', '𝔹', 'ℂ', '𝔻', '𝔼', '𝔽', '𝔾', 'ℍ', '𝕀', '𝕁', '𝕂', '𝕃', '𝕄', 'ℕ', '𝕆', 'ℙ', 'ℚ', 'ℝ', '𝕊', '𝕋', '𝕌', '𝕍', '𝕎', '𝕏', '𝕐', 'ℤ', '𝟘', '𝟙', '𝟚', '𝟛', '𝟜', '𝟝', '𝟞', '𝟟', '𝟠', '𝟡', '❕', '❔', '.', ',', '"', "'"],
			'capitalized' => ['ᴀ', 'ʙ', 'ᴄ', 'ᴅ', 'ᴇ', 'ꜰ', 'ɢ', 'ʜ', 'ɪ', 'ᴊ', 'ᴋ', 'ʟ', 'ᴍ', 'ɴ', 'ᴏ', 'ᴘ', 'q', 'ʀ', 'ꜱ', 'ᴛ', 'ᴜ', 'ᴠ', 'ᴡ', 'x', 'ʏ', 'ᴢ', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '﹗', '﹖', '﹒', '﹐', '"', "'"],
			'circled' => ['ⓐ', 'ⓑ', 'ⓒ', 'ⓓ', 'ⓔ', 'ⓕ', 'ⓖ', 'ⓗ', 'ⓘ', 'ⓙ', 'ⓚ', 'ⓛ', 'ⓜ', 'ⓝ', 'ⓞ', 'ⓟ', 'ⓠ', 'ⓡ', 'ⓢ', 'ⓣ', 'ⓤ', 'ⓥ', 'ⓦ', 'ⓧ', 'ⓨ', 'ⓩ', 'Ⓐ', 'Ⓑ', 'Ⓒ', 'Ⓓ', 'Ⓔ', 'Ⓕ', 'Ⓖ', 'Ⓗ', 'Ⓘ', 'Ⓙ', 'Ⓚ', 'Ⓛ', 'Ⓜ', 'Ⓝ', 'Ⓞ', 'Ⓟ', 'Ⓠ', 'Ⓡ', 'Ⓢ', 'Ⓣ', 'Ⓤ', 'Ⓥ', 'Ⓦ', 'Ⓧ', 'Ⓨ', 'Ⓩ', '⓪', '①', '②', '③', '④', '⑤', '⑥', '⑦', '⑧', '⑨', '!', '?', '.', ',', '"', "'"],
			'parenthesized' => ['⒜', '⒝', '⒞', '⒟', '⒠', '⒡', '⒢', '⒣', '⒤', '⒥', '⒦', '⒧', '⒨', '⒩', '⒪', '⒫', '⒬', '⒭', '⒮', '⒯', '⒰', '⒱', '⒲', '⒳', '⒴', '⒵', '🄐', '🄑', '🄒', '🄓', '🄔', '🄕', '🄖', '🄗', '🄘', '🄙', '🄚', '🄛', '🄜', '🄝', '🄞', '🄟', '🄠', '🄡', '🄢', '🄣', '🄤', '🄥', '🄦', '🄧', '🄨', '🄩', '⓿', '⑴', '⑵', '⑶', '⑷', '⑸', '⑹', '⑺', '⑻', '⑼', '!', '?', '.', ',', '"', "'"],
			'underlinedSingle' => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', '?', '.', ',', '"', "'"],
			'underlinedDouble' => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', '?', '.', ',', '"', "'"],
			'strikethroughSingle' => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', '?', '.', ',', '"', "'"],
			'crosshatch' => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', '?', '.', ',', '"', "'"],
		];
	
		foreach ($specialList as $list) {
			$text = str_replace($list, $target, $text);
		}
	
		return $text;
	}

	function remove_emoji($string)
    {
    // Match Enclosed Alphanumeric Supplement
    $regex_alphanumeric = '/[\x{1F100}-\x{1F1FF}]/u';
    $clear_string = preg_replace($regex_alphanumeric, '', $string);

    // Match Miscellaneous Symbols and Pictographs
    $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clear_string = preg_replace($regex_symbols, '', $clear_string);

    // Match Emoticons
    $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clear_string = preg_replace($regex_emoticons, '', $clear_string);

    // Match Transport And Map Symbols
    $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clear_string = preg_replace($regex_transport, '', $clear_string);
    
    // Match Supplemental Symbols and Pictographs
    $regex_supplemental = '/[\x{1F900}-\x{1F9FF}]/u';
    $clear_string = preg_replace($regex_supplemental, '', $clear_string);

    // Match Miscellaneous Symbols
    $regex_misc = '/[\x{2600}-\x{26FF}]/u';
    $clear_string = preg_replace($regex_misc, '', $clear_string);

    // Match Dingbats
    $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
    $clear_string = preg_replace($regex_dingbats, '', $clear_string);

    return $clear_string;
    }


	function instauserid()
    {
		$id=Carbon::now()->format('Ymdhis').getValueByKey('INSTA_USERID');
		getValueByKey('INSTA_USERID',true);
		return $id;
	}

	function bpnumberfetch($postMessage){
		$lowercaseMessage = strtolower($postMessage);
		$contains = Str::contains($lowercaseMessage,'bp');
		if($contains){
			$position = strpos($lowercaseMessage, 'bp');

			if ($position !== false) {
				$lowercaseMessage = substr($lowercaseMessage, $position + strlen('bp'));
				$lowercaseMessage = substr($lowercaseMessage,0,20);
				preg_match('/\b\d{9,12}\b/', $lowercaseMessage, $matches);
				if (!empty($matches)) {
					$bpNumber = $matches[0];
					return $bpNumber;
				}
			}	
		}
		return null;
	}

	function reportfortbpriority($id)
    {
		$priority=RulePriority::find($id);
		$value=$priority->label;
		return $value;
	}


	function reportfortbsocialplatform($id,$status)
    {
		$platform=SocialPlatform::find($id);
		$value="";
		if($status=='1'){
		    $value=$platform->value.' Active';
		}elseif($status=='0'){
			$value=$platform->value.' InActive';
		}
		return $value;
	}

	function reportfortbdefaults($id)
    {
		$priority=Defaults::find($id);
		$value=$priority->label;
		return $value;
	}

	function reportfortbusers($id,$rolevalue)
    {
		$users=User::find($id);
		$role=Role::find($rolevalue);
		$value=$users->name.' role = '.$role->	role_name;
		return $value;
	}

	function reportfortruefalse($id)
    {
		$value="";
		if($id=="1"){
			$value="True";
		}elseif($id=="0"){
			$value="False";
		}
		return $value;
	}

	function reportfordescription($id,$table,$action)
    {
		$value="";
		if($action=="create"){		
			if($table=="users"){
				$query = DB::table($table)->find($id);
				$role=Role::find($query->role);
				$value="Name = ".$query->name." Role = ".$role->role_name;
			}elseif($table=="tb_projectattachment"){
				$query = DB::table($table)->find($id);
				$value="File Name = ".$query->fileName." Post Id = ".$query->attachment_id;
			}elseif($table=="tb_socialticket"){
				$query = DB::table($table)->find($id);
				$value="Ticket No = ".$query->ticket_id." Subject = ".$query->subject;
			}elseif($table=="tb_leads"){
				$query = DB::table($table)->find($id);
				$value="Lead No = ".$query->leadId." Description = ".$query->description;
			}elseif($table=="tb_post_type_rules"){
				$query = DB::table($table)->where('ruleid',$id)->first();
				$value="Type = ".$query->type." Category = ".$query->category." Status = ".$query->status." Keywords = ".$query->keyword;
			}elseif($table=="tb_template"){
				$query = DB::table($table)->find($id);
				$value="Name = ".$query->template_name." Content = ".$query->template_content;
			}elseif($table=="tb_gettweet"){
				$query = DB::table($table)->find($id);
				$value="Post Id = ".$query->getTweet_id." Post Message = ".$query->postMessage;
			}elseif($table=="tb_user_assign_rule"){
				$query = DB::table($table)->find($id);
				$value="User Name = ".$query->name." Keywords = ".$query->Keyword." Social Type = ".$query->social_type;
			}
	    }
        

		if($action=="delete"){
			if($table=="tb_projectattachment"){
				$query=ProjectAttachment::withTrashed()->find($id);
				$value="File Name = ".$query->fileName." Post Id = ".$query->attachment_id;
			}elseif($table=="tb_socialticket"){
				$query=SocialTicket::withTrashed()->find($id);
				$value="Ticket No = ".$query->ticket_id." Subject = ".$query->subject;
			}elseif($table=="tb_leads"){
				$query=LeadTicket::withTrashed()->find($id);
				$value="Lead No = ".$query->leadId." Description = ".$query->description;
			}elseif($table=="tb_post_type_rules"){
				$query=PostAssignRule::withTrashed()->where('ruleid',$id)->first();
				$value="Type = ".$query->type." Category = ".$query->category." Status = ".$query->status." Keywords = ".$query->keyword;
			}elseif($table=="tb_template"){
				$query=Template::withTrashed()->find($id);
				$value="Name = ".$query->template_name." Content = ".$query->template_content;
			}elseif($table=="tb_gettweet"){
				$query=GetTweet::withTrashed()->find($id);
				$value="Post Id = ".$query->getTweet_id." Post Message = ".$query->postMessage;
			}elseif($table=="tb_user_assign_rule"){
				$query=UserAssignRule::withTrashed()->find($id);
				$value="User Name = ".$query->name." Keywords = ".$query->Keyword." Social Type = ".$query->social_type;
			}
	    }

		if($action=="update"){		
			if($table=="users"){
				$query = DB::table($table)->find($id);
				$role=Role::find($query->role);
				$value="Name = ".$query->name." Role = ".$role->role_name;
			}elseif($table=="tb_projectattachment"){
				$query = DB::table($table)->find($id);
				$value="File Name = ".$query->fileName." Post Id = ".$query->attachment_id;
			}elseif($table=="tb_socialticket"){
				$query = DB::table($table)->find($id);
				$value="Ticket No = ".$query->ticket_id." Subject = ".$query->subject;
			}elseif($table=="tb_leads"){
				$query = DB::table($table)->find($id);
				$value="Lead No = ".$query->leadId." Description = ".$query->description;
			}elseif($table=="tb_post_type_rules"){
				$query = DB::table($table)->where('ruleid',$id)->first();
				$value="Type = ".$query->type." Category = ".$query->category." Status = ".$query->status." Keywords = ".$query->keyword;
			}elseif($table=="tb_template"){
				$query = DB::table($table)->find($id);
				$value="Name = ".$query->template_name." Content = ".$query->template_content;
			}elseif($table=="tb_gettweet"){
				$query = DB::table($table)->find($id);
				$value="Post Id = ".$query->getTweet_id." Post Message = ".$query->postMessage;
			}elseif($table=="tb_user_assign_rule"){
				$query = DB::table($table)->find($id);
				$value="User Name = ".$query->name." Keywords = ".$query->Keyword." Social Type = ".$query->social_type;
			}elseif($table=="tb_defaults"){
				$query = DB::table($table)->where('default_id',$id)->first();
				$value="Label = ".$query->label." Value = ".$query->value; 
			}elseif($table=="tb_socialplatform"){
				$query = DB::table($table)->find($id);
				$value=" Social Type = ".$query->value." Status = ".$query->status==1?"Active":"InActive";
			}elseif($table=="tb_rule_priority"){
				$query = DB::table($table)->find($id);
				$value="Label = ".$query->label." Position = ".$query->value." Type = ".$query->type;
			}elseif($table=="tb_role_access_mapping"){
				$query=RoleAccessMapping::leftjoin('tb_roles','tb_roles.role_id','=','tb_role_access_mapping.user_role_id')
				->leftjoin('tb_component','tb_component.id','=','tb_role_access_mapping.component_id')
				->where('tb_role_access_mapping.id',$id)
				->select('tb_role_access_mapping.*','tb_component.component_label','tb_roles.role_name')->first();
				$value="Component = ".$query->component_label." Status = ".$query->access=="READ_WRITE"?"Read Write":($query->access=="READ_ONLY"?"Read Only":"Hide")." Role = ".$query->role_name;
			}
	    }

		return $value;
	}
	
	function createTicket($bpno,$ct,$cg,$description,$notes)
	{
			$client = new Client();
			$url = getValueByKey('SAPTICKETCREATEURL');

			$payload = [
				'PartnerNo' => $bpno,
				'CatalogType' => $ct,
				'Codegroup' => $cg,
				'Description' => "SOCIAL CRM-Social media",
				'Notes' => $notes
			];
			$response = $client->post($url, [
				'headers' => [
					'X-CSRF-Token' => getValueByKey('SAPTICKETCREATETOKEN'),
					'X-Requested-With' => 'X',
					'Content-Type' => 'application/json'
				],
				'json' => $payload
			]);
	
			$body = $response->getBody()->getContents();
			$xml = new SimpleXMLElement($body);
			$objectId = $xml->xpath('//d:ObjectId')[0] ?? null;
			$validFromExt = $xml->xpath('//d:ValidFromExt')[0] ?? null;

			if (!$objectId && $validFromExt) {
				$objectId = $validFromExt;
			}
			if ($objectId || $validFromExt) {
			$updated = (string) $xml->updated;
			$status = (string) $xml->xpath('//d:Status')[0];
			$partnerNo = (string) $xml->xpath('//d:PartnerNo')[0];
			$processType = (string) $xml->xpath('//d:ProcessType')[0];
	
			$data = [
				'updated' => $updated,
				'status' => $status,
				'object_id' => $objectId,
				'partner_no' => $partnerNo,
				'process_type' => $processType
			];
	
			return $data;
		}
	}


    function fetchTicketStatus($bpnumber,$objectid,$assignedto,$ticketid)
	{
			$client = new Client();
			
			$url = getValueByKey('SAPTICKETFETCHSTATUSURL').'?$filter=Partner%20eq%20%27'.$bpnumber.'%27';

			$response = $client->get($url, [
				'headers' => [
					'X-Requested-With' => 'X',
					'Content-Type' => 'application/xml'
				]
			]);
			$body = $response->getBody()->getContents();
			$xml = new SimpleXMLElement($body);
			
			$entries = [];
			foreach ($xml->entry as $entry) {
				$objectId = $entry->xpath('.//d:ObjectId');
				$validFromExt = $entry->xpath('.//d:ValidFromExt');

				$objectIdValue = isset($objectId[0]) ? (string) $objectId[0] : '';
				$validFromExtValue = isset($validFromExt[0]) ? (string) $validFromExt[0] : '';

				if (!empty($objectIdValue) || !empty($validFromExtValue)) {
					$processType = isset($entry->xpath('.//d:ProcessType')[0]) ? (string) $entry->xpath('.//d:ProcessType')[0] : '';
					$description = isset($entry->xpath('.//d:Description')[0]) ? (string) $entry->xpath('.//d:Description')[0] : '';
					$kurztext = isset($entry->xpath('.//d:Kurztext')[0]) ? (string) $entry->xpath('.//d:Kurztext')[0] : '';
					$claimingAccount = isset($entry->xpath('.//d:ClaimingAccount')[0]) ? (string) $entry->xpath('.//d:ClaimingAccount')[0] : '';
					$concatStat = isset($entry->xpath('.//d:Concatstat')[0]) ? (string) $entry->xpath('.//d:Concatstat')[0] : '';
					$txt30 = isset($entry->xpath('.//d:Txt30')[0]) ? (string) $entry->xpath('.//d:Txt30')[0] : '';
					$partner = isset($entry->xpath('.//d:Partner')[0]) ? (string) $entry->xpath('.//d:Partner')[0] : '';

					$entries[] = [
						'object_id' => $objectIdValue,
						'process_type' => $processType,
						'description' => $description,
						'claiming_account' => $claimingAccount,
						'concat_stat' => $concatStat,
						'txt30' => $txt30,
						'partner' => $partner,
						'kurztext' => $kurztext
					];
				}
    }
    log::info($entries);
    $html = '';
    foreach ($entries as $index => $entry) {
		$updatestatus = TicketSapGroups::where('sap_object_id', $entry['object_id'])
		->where('ticket_id', $ticketid)
		->first();
		if($updatestatus){
			$updatestatus->update(["sap_status"=>$entry['txt30']]);
		}
		if($objectid == $entry['object_id']){
			$html .= "<table style='border-collapse: collapse; width: 100%;'>";
			$html .= "<tr><td style='border: 1px solid #000; padding: 8px;'><strong>Ticket ID</strong></td><td style='border: 1px solid #000; padding: 8px;'>" . $entry['object_id'] . "</td></tr>";
			$html .= "<tr><td style='border: 1px solid #000; padding: 8px;'><strong>BP Number</strong></td><td style='border: 1px solid #000; padding: 8px;'>" . $entry['claiming_account'] . "</td></tr>";
			$html .= "<tr><td style='border: 1px solid #000; padding: 8px;'><strong>Assigned To</strong></td><td style='border: 1px solid #000; padding: 8px;'>" . $assignedto . "</td></tr>";
			$html .= "<tr><td style='border: 1px solid #000; padding: 8px;'><strong>Status</strong></td><td style='border: 1px solid #000; padding: 8px;'>" . $entry['txt30'] . "</td></tr>";
			$html .= "<tr><td style='border: 1px solid #000; padding: 8px;'><strong>Description</strong></td><td style='border: 1px solid #000; padding: 8px;'>" . $entry['description'] . "</td></tr>";
			$html .= "<tr><td style='border: 1px solid #000; padding: 8px;'><strong>Comment</strong></td><td style='border: 1px solid #000; padding: 8px;'>" . $entry['kurztext'] . "</td></tr>";
			$html .= "</table>";

		}
	}
    return $html;
}


   function getCodeOptions()
	{
		$distinctCatalogTypes = SapTicketCodeGroups::select('catalog_type', 'catlog_type_desc')
		->distinct()
		->get();
		return $distinctCatalogTypes;
	}

	function getCodeSubOptions($type)
	{
		$data = SapTicketCodeGroups::where('catalog_type',$type)->get();
		return $data;
	}
	
	function lasttimecheck($time){
	$time = Carbon::parse($time);
    $now = Carbon::now();
    $diffInMinutes = $now->diffInMinutes($time);
    if ($diffInMinutes > 16) {
        return true;
    }
    return false;
}

function checkuserlasttime(){
	$configdetails = TwitterConfigDetail::get();
	$selectedDetails = false;
	foreach($configdetails as $configdetail){
		if(lastusertimecheck($configdetail->user_last_time)){
			$selectedDetails = $configdetail;
			return $selectedDetails;
		}
	}
	return $selectedDetails;
}

function lastusertimecheck($lastTime) {
    $lastTime = new DateTime($lastTime);
    $currentTime = new DateTime();
    $interval = $currentTime->diff($lastTime);
    
    if ($interval->h >= 24 || $interval->d > 0) {
        return true;
    }
    
    return false;
}