<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
// use App\Models\UserAssignRule;
use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\GetTweet;
use App\Models\AdminChanges;
use App\Models\TweetLog;
use App\Models\User;
use App\Models\LeadTicket;
use App\Models\SocialTicket;
use App\Models\TicketSapGroups;
use App\Models\Activity;
use Exception;
use DB;
use View;

class PreDefinedReportController extends Controller{

    public function getSocialPostReport(Request $request)
    {
		try{
            $today=Carbon::today();
            $defaultReportDays = getValueByKey('DEFAULT_REPORT_DAYS');
            $start=$today->subDays($defaultReportDays);
            $end=Carbon::today();  
		   $post = GetTweet::orderBy('istPostDate','DESC');
           if(isset($request->start) && isset($request->end)){
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);
            $diffInDays = $start->diffInDays($end);
            $reportDyasDiffLimit = getValueByKey('REPORT_DAYS_DIFFERENCE_LIMIT');
            if($diffInDays > $reportDyasDiffLimit){
                throw new Exception("Please select days difference less than $reportDyasDiffLimit  days");
            }
            $posts = $post->whereDate('istPostDate', '>=', $start)
            ->whereDate('istPostDate', '<=', $end);
           }else{
            $posts = $post->whereDate('istPostDate', '>=', $start)
            ->whereDate('istPostDate', '<=', $end);
           }
           
		  
		  if(isset($request->download))
		  {	$col = [];
            $col= ['id',"Post Message","Social User","Source","Post Url","Post Date","Category","Status","Converted","Ticket Id","Lead Number","Aging","BP Number","Reason"];
			$data = [];
			$posts = $post->get();
            if(!empty($posts)){
                foreach($posts as $info){
                    $info->leads = implode(',', LeadTicket::where('getTweet_id', $info->getTweet_id)->pluck('leadId')->toArray());
					$info->socialTickets = implode(',', SocialTicket::where('getTweet_id', $info->getTweet_id)->pluck('ticket_id')->toArray());
                    $info->activies = Activity::leftjoin('users','users.id','=','tb_activity.created_by')
                    ->where("post_id",$info->id)
                    ->select('tb_activity.text','tb_activity.created_at','users.name')
                    ->get();
                }
            }
			
			return  downloadCsv($posts,$col);
		  } 

          $posts = $post->paginate(getValueByKey('PAGENATION_COUNT'));
          $start=$start->format('Y-m-d');
          $end=$end->format('Y-m-d');  
		
		} catch(Exception $e) {
			//echo $e->getMessage();die;
			return redirect()->back()->with('message',$e->getMessage());
        }
        
		return \View::make('predefineReport.report', compact(['posts','start','end']));
    }

    public function getSocialTicketReport(Request $request)
    {
        try {
            $today=Carbon::today();
            $defaultReportDays = getValueByKey('DEFAULT_REPORT_DAYS');
            $start=$today->subDays($defaultReportDays);
            $end=Carbon::today();
            $getInfo = SocialTicket::leftjoin('users','users.id','=','tb_socialticket.assigned_to')
            ->orderby('date_Created','desc');
            if(isset($request->start) && isset($request->end)){
                $start = Carbon::parse($request->start);
                $end = Carbon::parse($request->end);
                $diffInDays = $start->diffInDays($end);
                $reportDyasDiffLimit = getValueByKey('REPORT_DAYS_DIFFERENCE_LIMIT');
                if($diffInDays > $reportDyasDiffLimit){
                    throw new Exception("Please select days difference less than $reportDyasDiffLimit  days");
                }
                $getInfo = $getInfo->whereDate('date_Created', '>=', $start)
                  ->whereDate('date_Created', '<=', $end);
            }else{
                $getInfo = $getInfo->whereDate('date_Created', '>=', $start)
                  ->whereDate('date_Created', '<=', $end);
            }
		    
            if(isset($request->download))
            {	
              $col = [];
              $col= ['Post ID','Ticket ID',"Post Message","Social User","Source","Final Status","Status",
              "AssignTo","Creation Date","BP Number","Mobile Number","Description","Resolution","Additional Text","SAP Ticket","PostUrl",
              "Activity"];
              $getInfo = $getInfo->select('tb_socialticket.*','users.name')->get();
              if(!empty($getInfo)){
                  foreach($getInfo as $info){
                      $info->activies = Activity::leftjoin('users','users.id','=','tb_activity.created_by')
                      ->where("post_id",$info->id)
                      ->select('tb_activity.text','tb_activity.created_at','users.name')
                      ->get();
                       $info->sap_ticket = TicketSapGroups::where('ticket_id', $info->id)
                      ->distinct()  
                      ->pluck('sap_object_id') 
                      ->unique()  
                      ->implode('/');  
                  
                  $info->sap_ticket = $info->sap_ticket ?: '';
                  }
              }
              return  downloadTicketReportCsv($getInfo,$col);
            }
			$getInfo = $getInfo->select('tb_socialticket.*','users.name')->paginate(getValueByKey('PAGENATION_COUNT'));
            

            if(!empty($getInfo)){
                foreach($getInfo as $info){
                    $info->activies = Activity::leftjoin('users','users.id','=','tb_activity.created_by')
                    ->where("post_id",$info->id)
                    ->select('tb_activity.*','users.name')
                    ->get();
                }
            }
            
          $start=$start->format('Y-m-d');
          $end=$end->format('Y-m-d');
		return \View::make('predefineReport.ticketreport', compact(['getInfo','start','end']));

            // return $getInfo;	
       } catch(Exception $e) {
        return redirect()->back()->with('message',$e->getMessage());
       }
        
    }


    public function getViewLogReport(Request $request)
    {
        try {
            $today = Carbon::today();
            $defaultReportDays = getValueByKey('DEFAULT_REPORT_DAYS') ?? 30;
            $start = $today->copy()->subDays($defaultReportDays);
            $end = $today;

            $userMap = User::pluck('name', 'name')->toArray(); 

            $query = \DB::table('tb_change_log as cl')
                ->leftJoin('tb_socialticket as st', 'cl.post_id', '=', 'st.id')
                ->leftJoin('users as u', 'cl.change_by', '=', 'u.name')  
                ->select('cl.*', 'st.ticket_id', 'u.name as changer_name')
                ->orderBy('cl.change_date', 'DESC');

            if ($request->filled(['start', 'end'])) {
                $start = Carbon::parse($request->start)->startOfDay();
                $end = Carbon::parse($request->end)->endOfDay();

                $diff = $start->diffInDays($end);
                $limit = getValueByKey('REPORT_DAYS_DIFFERENCE_LIMIT') ?? 60;
                if ($diff > $limit) {
                    throw new Exception("Select a date range less than $limit days.");
                }

                $query->whereBetween('cl.change_date', [$start, $end]);
            } else {
                $query->whereBetween('cl.change_date', [$start->startOfDay(), $end->endOfDay()]);
            }

            if ($request->filled('change_by')) {
                $query->whereRaw("TRIM(cl.change_by) = ?", [(string)$request->change_by]);
            }

            $paginationCount = getValueByKey('PAGENATION_COUNT') ?? 20;
            $logs = $query->paginate($paginationCount)->appends($request->except('page'));


            // CSV export
            if ($request->has('download')) {
                $columns = ['Post ID', 'Ticket ID', 'Field', 'New Value', 'Old Value', 'Changed By', 'Change Date'];
                $data = [];
                foreach ($query->get() as $log) {
                    $data[] = [
                        $log->post_id,
                        $log->ticket_id,
                        changeByKey($log->field),
                        $log->new_value,
                        $log->old_value,
                        $log->changer_name ?? $log->change_by,
                        $log->change_date,
                    ];
                }
                return downloadlogCsv($data, $columns);
            }

            return view('predefineReport.viewlogreport', [
                'posts' => $logs,
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
                'userMap' => $userMap,
                'selectedUser' => $request->change_by,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage());
        }
    }


    public function getLeadsReport(Request $request,$id =null)
    {
        try {
            $today=Carbon::today();
            $defaultReportDays = getValueByKey('DEFAULT_REPORT_DAYS');
            $start=$today->subDays($defaultReportDays);
            $end=Carbon::today();
		    $getInfo = LeadTicket::orderBy('id','desc');
            $getInfo = $getInfo->leftjoin('users','users.id','=','tb_leads.assigned_to');
			if(isset($request->start) && isset($request->end)){
                $start = Carbon::parse($request->start);
                $end = Carbon::parse($request->end);
                $diffInDays = $start->diffInDays($end);
                $reportDyasDiffLimit = getValueByKey('REPORT_DAYS_DIFFERENCE_LIMIT');
                if($diffInDays > $reportDyasDiffLimit){
                    throw new Exception("Please select days difference less than $reportDyasDiffLimit  days");
                }
                $getInfo = $getInfo->whereDate('created_date', '>=', $start)
                ->whereDate('created_date', '<=', $end);
            }else{
                $getInfo = $getInfo->whereDate('created_date', '>=', $start)
                ->whereDate('created_date', '<=', $end);
            }
			$role = loggedUserRole();
            if($role==="OTHERUSER"){
                $userId = loggedUserId();
                $getInfo = $getInfo->where('assigned_to','like','%'.$userId.'%');
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
			
			$getInfo = $getInfo->paginate(getValueByKey('PAGENATION_COUNT'));
            $start=$start->format('Y-m-d');
            $end=$end->format('Y-m-d');
          return \View::make('predefineReport.leadreport', compact(['getInfo','start','end']));
       } catch(Exception $e) {
		return redirect()->back()->with('message',$e->getMessage());
       }
        
    }


    public function getadminupdatebyReport(Request $request)
    {
        try {
            $userlist=User::get();
            $user=[];
            $today=Carbon::today();
            $defaultReportDays = getValueByKey('DEFAULT_REPORT_DAYS');
            $start=$today->subDays($defaultReportDays);
            $end=Carbon::today();
		    $getInfo = AdminChanges::leftjoin('users','users.id','=','tb_admin_change.change_by')
            ->orderBy('tb_admin_change.id','desc');
			if(isset($request->start) && isset($request->end)){
                $start = Carbon::parse($request->start);
                $end = Carbon::parse($request->end);
                if(isset($request->adminuser)){
                    $getInfo=$getInfo->where('tb_admin_change.change_by',$request->adminuser);
                    $user=$request->adminuser;
                }
            }

            if(isset($request->download))
			{	
                $diffInDays = $start->diffInDays($end);
                $reportDyasDiffLimit = getValueByKey('REPORT_DAYS_DIFFERENCE_LIMIT');
                if($diffInDays > $reportDyasDiffLimit){
                    throw new Exception("Please select days difference less than $reportDyasDiffLimit  days");
                }
                $col = [];
				$col= ['Sr No',"New Value","Old Value","Field","Action","Update By","Update Time","Description"];
				$data = [];
                $count=1;
				$getInfo = $getInfo->get();
                foreach($getInfo as $info){
                    $innerdata['sr_no']=$count++;
                    $innerdata['new_value']=$info->table_name == "tb_rule_priority" ?
                    (reportfortbpriority($info->field_id) . ' = ' . $info->new_value) :
                    ($info->table_name == "tb_socialplatform" ?
                    (reportfortbsocialplatform($info->field_id, $info->new_value)) :
                    ($info->table_name == "tb_defaults" ?
                    (reportfortbdefaults($info->field_id) . ' = ' . $info->new_value) :
                    ($info->field == "role" &&  $info->table_name == "users" ?
                    (reportfortbusers($info->field_id,$info->new_value)) :
                    (($info->new_value == "1" &&  $info->old_value == "0") ||
                    ($info->new_value == "0" &&  $info->old_value == "1")?
                    (reportfortruefalse($info->new_value)) :
                    $info->new_value))));
                    $innerdata['old_value']=$info->table_name == "tb_rule_priority" ?
                    (reportfortbpriority($info->field_id) . ' = ' . $info->old_value) :
                    ($info->table_name == "tb_socialplatform" ?
                    (reportfortbsocialplatform($info->field_id, $info->old_value)) :
                    ($info->table_name == "tb_defaults" ?
                    (reportfortbdefaults($info->field_id) . ' = ' . $info->old_value) :
                    ($info->field == "role" &&  $info->table_name == "users" ?
                    (reportfortbusers($info->field_id,$info->old_value)) :
                    (($info->new_value == "1" &&  $info->old_value == "0") ||
                    ($info->new_value == "0" &&  $info->old_value == "1")?
                    (reportfortruefalse($info->old_value)) :
                    $info->old_value))));
                    $innerdata['field']=$info->field;
                    $innerdata['operation']=$info->operation;
                    $innerdata['name']=$info->name;
                    $innerdata['change_date']=$info->change_date;
                    $innerdata['description']=reportfordescription($info->field_id,$info->table_name,$info->operation);
                    $data[]=$innerdata;
                }
				return  downloadtrackcsv($data,$col);
			}
            $getInfo=$getInfo->whereDate('tb_admin_change.change_date', '>=', $start)
            ->whereDate('tb_admin_change.change_date', '<=', $end)
            ->select('tb_admin_change.*','users.name')
			->paginate(getValueByKey('PAGENATION_COUNT'));
            $start=$start->format('Y-m-d');
            $end=$end->format('Y-m-d');
          return \View::make('adminchangesReport.adminchangeupdatereport', compact(['getInfo','start','end','user','userlist']));
       } catch(Exception $e) {
		return redirect()->back()->with('message',$e->getMessage());
       }
        
    }


    public function getadmincreatebyReport(Request $request)
    {
        try {
            $userlist=User::get();
            $user=[];
            $today=Carbon::today();
            $defaultReportDays = getValueByKey('DEFAULT_REPORT_DAYS');
            $start=$today->subDays($defaultReportDays);
            $end=Carbon::today();
		    $getInfo = AdminChanges::leftjoin('users','users.id','=','tb_admin_change.change_by')
            ->where('tb_admin_change.operation','create')
            ->orderBy('tb_admin_change.id','desc');
			if(isset($request->start) && isset($request->end)){
                $start = Carbon::parse($request->start);
                $end = Carbon::parse($request->end);
                $diffInDays = $start->diffInDays($end);
                $reportDyasDiffLimit = getValueByKey('REPORT_DAYS_DIFFERENCE_LIMIT');
                if($diffInDays > $reportDyasDiffLimit){
                    throw new Exception("Please select days difference less than $reportDyasDiffLimit  days");
                }
                $getInfo=$getInfo->where('tb_admin_change.change_by',$request->adminuser);
                $user=$request->adminuser;
            }
            $getInfo=$getInfo->whereDate('tb_admin_change.change_date', '>=', $start)
            ->whereDate('tb_admin_change.change_date', '<=', $end)
            ->select('tb_admin_change.*','users.name')
			->paginate(getValueByKey('PAGENATION_COUNT'));
            $start=$start->format('Y-m-d');
            $end=$end->format('Y-m-d');
          return \View::make('adminchangesReport.adminchangecreatereport', compact(['getInfo','start','end','user','userlist']));
       } catch(Exception $e) {
		return redirect()->back()->with('message',$e->getMessage());
       }
        
    }



    public function getadmindeletebyReport(Request $request)
    {
        try {
            $userlist=User::get();
            $user=[];
            $today=Carbon::today();
            $defaultReportDays = getValueByKey('DEFAULT_REPORT_DAYS');
            $start=$today->subDays($defaultReportDays);
            $end=Carbon::today();
		    $getInfo = AdminChanges::leftjoin('users','users.id','=','tb_admin_change.change_by')
            ->where('tb_admin_change.operation','delete')
            ->orderBy('tb_admin_change.id','desc');
			if(isset($request->start) && isset($request->end)){
                $start = Carbon::parse($request->start);
                $end = Carbon::parse($request->end);
                $diffInDays = $start->diffInDays($end);
                $reportDyasDiffLimit = getValueByKey('REPORT_DAYS_DIFFERENCE_LIMIT');
                if($diffInDays > $reportDyasDiffLimit){
                    throw new Exception("Please select days difference less than $reportDyasDiffLimit  days");
                }
                $getInfo=$getInfo->where('tb_admin_change.change_by',$request->adminuser);
                $user=$request->adminuser;
            }
            $getInfo=$getInfo->whereDate('tb_admin_change.change_date', '>=', $start)
            ->whereDate('tb_admin_change.change_date', '<=', $end)
            ->select('tb_admin_change.*','users.name')
			->paginate(getValueByKey('PAGENATION_COUNT'));
            $start=$start->format('Y-m-d');
            $end=$end->format('Y-m-d');
          return \View::make('adminchangesReport.adminchangedeletereport', compact(['getInfo','start','end','user','userlist']));
       } catch(Exception $e) {
		return redirect()->back()->with('message',$e->getMessage());
       }
        
    }



}