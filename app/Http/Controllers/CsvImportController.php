<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GetTweet;
use App\Models\SocialUser;
use App\Models\SocialTicket;
use Log;
use Illuminate\Support\Facades\DB;

class CsvImportController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth')->except([]);
    }
    public function showImportForm()
    {
        return view('post.import');
    }

    public function import(Request $request)
    {
          if ($request->hasFile('csv_file')) {
            $table = $request->table;
            $file = $request->file('csv_file');
            
            $csvData = array_map('str_getcsv', file($file));
            
            switch ($table) {
                case 'tb_gettweet':
				$this->createPost($csvData);
               
                    break;
                   
                    case 'tb_social_user':
                        $socialusers =    DB::table('tb_social_user')->insert([                   
                            'user_id ' => $row[0],
                            'name' => $row[1],
                            'date_modified' => $row[2],
                            'user_name'=> $row[3], 
                            
                        ]);
                        // return redirect($csvData);
                        break;

					case 'tb_leads':
						$leads =     DB::table('tb_leads')->insert([                   
                             'getTweet_id' => $row[0],
                             'greeting_first_name' => $row[1],
                             'socialUser_id' => $row[2],
                             'first_name'=> $row[3],
                             'last_name'=> $row[4],
                             'type'=> $row[5],
                             'title'=> $row[6],
                             'department'=> $row[7],
                             'customer_name'=> $row[8],
                             'status'=> $row[9],
                             'office_phone'=> $row[10],
                             'mobile'=> $row[11],
                             'website'=> $row[12],
                             'approval_status'=> $row[13],
                             'primary_address'=> $row[14],
                             'primary_city'=> $row[15],
                             'primary_state'=> $row[16],
                             'primary_postal_code'=> $row[17],
                             'primary_country'=> $row[18],
                             'other_address'=> $row[19],
                             'other_city'=> $row[20],
                             'other_state'=> $row[21],
                             'other_postal_code'=> $row[23],
                             'other_country'=> $row[24],
                             'email_address'=> $row[25],
                             'converted' =>  $row[26],
                             'description' =>  $row[27],
                             'fax' =>  $row[28],
                             'partner_contacts' =>  $row[29],
                             'lead_source' =>  $row[30],
                             'assigned_to' =>  $row[31],
                             'created_date' =>  $row[32],
                             'leadBy' =>  $row[33],
                             'leadById' =>  $row[34],
                             'leadId' =>  $row[35],
                             
                         ]);
                         break;

                             ///// Import data into tb_socialticket table
						case 'tb_socialticket':
							$this->createTicket($csvData);
                         break;
                          default:
                             // Handle the case where no or invalid table is selected
                            break;
            }
            

            return redirect()->route('import')->with('success', 'CSV data imported successfully.');
        }
          
         return redirect()->route('import')->with('error', 'CSV file not provided.');
   }
   
   
   public function createPost($csvData)
   {
	   $count = 0;
	   foreach ($csvData as $row) {
		if($count > 0 && $row[0])
		{
		$socialUser_id = getParenthesesString(isset($row[2])?$row[2]:'');
		$auther_id=GetTweet::where('socialUser_id',$socialUser_id)->first();
		$socialUser =SocialUser::where('user_name',$socialUser_id)->first();
		// if('10-05-2022 09:55' == $socialUser_id)
		// {
			// Log::debug($row);
			// Log::debug(json_encode($csvData));
		// }
		
		// Log::debug([
					// 'user_id'=>$socialUser_id,
					// 'name'=> userNameByText(isset($row[2])?$row[2]:''),
					// 'user_name'=>$socialUser_id
				// ]);
		
			if(!$auther_id && !$socialUser){
				$userName= $socialUser_id;
				$saveSocialUser=SocialUser::create([
					'user_id'=>$socialUser_id,
					'name'=> userNameByText(isset($row[2])?$row[2]:''),
					'user_name'=>$userName
				]);
				
				$name = userNameByText(isset($row[2])?$row[2]:'');
				
				
			}else{
				$name=$auther_id?$auther_id->socialUser_name:userNameByText(isset($row[1])?$row[1]:'');
				$userName=$auther_id?$auther_id->socialUser_userName:$socialUser_id;
			}
			$id = (int) trim(isset($row[0])? $row[0]:0);
			
			if(!isset($row[0]) || $id == 0 || (isset($row[4])?$row[4]:'')  == '' || is_string($id) ||  !$name)
			{
				
			}
			else{
			//$postDate = istDate(isset($row[5])?convertMdyToYmdhis($row[5].":00"):todayDate());
			//echo $id."********".$postDate."**".(isset($row[5])?$row[5].":00":"no Date")."<br>";
			$category=setPostAssignRule('post',isset($row[4])?$row[4]:' ');
			$requestInfo = [                   
				'getTweet_id' => $id,
				'postMessage' => isset($row[4])?$row[4]:' ',
				'socialUser_name' => $name,
				'socialUser_userName'=>$userName,
				'socialUser_id'=> $socialUser_id,
				'source'=> 'Twitter',
				'postUrl'=> isset($row[3])?$row[3]:'',
				'postDate'=> isoDate(isset($row[5])?convertMdyToYmdhis($row[5].":00"):todayDate()),
				'istPostDate'=> istDate(isset($row[5])?convertMdyToYmdhis($row[5].":00"):todayDate()),
				'mobile_no'=> isset($row[6])?$row[6]:'',
				'status'=> "New",
				'post_category'=>$category
				];
				
				$oldPost = GetTweet::where('getTweet_id',$id)->first();
				if($oldPost)
				{
					$oldPost->update($requestInfo);
				}
				else{
					$gettweets = DB::table('tb_gettweet')->insert($requestInfo);
				}
					
			}
		}
		$count++;
	   }
	   //die;
   }
   
   public function createTicket($csvData)
   {
	   $count = 0;
	   $arr =[];
	   foreach ($csvData as $row) {
		if($count > 0 && isset($row[0]))
		{
			
			$id = isset($row[0])? $row[0]:0;
			$id = (int)trim($id);
			
			
			if(!isset($row[0]) || $id == 0 || (isset($row[1])?$row[1]:'')  == '' || $id == '')
			{
				//Log::debug($id);
			}
			else{
				 
				$post = GetTweet::where('getTweet_id', $id)->first();
				//Log::debug($post);
				if(!$post)
				{
					//Log::debug($id);
					$arr [] = $id;
				}
				else{
					    Log::debug($post);
						$status = isset($row[6])?$row[6]:'New';
						if($status == "Closed")
						{
							$status = "Close";
						}
						
						$requestInfo = [                   
						 'getTweet_id' =>$post->getTweet_id,
						 'ticket_id' => getDigitCodeForTicket(),
						 'postMessage' => isset($row[2])? $row[2]:'',
						 'subject' => isset($row[2])? $row[2]:'',
						 'socialUser' => $post->socialUser_name,
						 'source'=> 'Twitter',
						 'priority'=> isset($row[5])?$row[5]:'Low',
						 'status'=> $status,
						 'socialUser_id'=> $post->socialUser_id ,
						// 'type'=> $category,
						 'subject'=> isset($row[1])?$row[1]:'',
						 'description'=> isset($row[9])?$row[9]:'',
						 'date_Created'=> istDate(isset($row[8])?convertMdyToYmdhis($row[8].":00"):todayDate()),
						 'postDate'=>isoDate(isset($row[8])?convertMdyToYmdhis($row[8].":00"):todayDate())
						
					 ];
							
					$oldPost = SocialTicket::where('getTweet_id',$post->getTweet_id)->first();
					if($oldPost)
					{
						//$oldPost->update($requestInfo);
					}
					else{
						$socialtickets = DB::table('tb_socialticket')->insert($requestInfo);
					}
			}
						
			}
		}
		$count++;
	   }
	   Log::debug($arr);
   }
   
    
 }


