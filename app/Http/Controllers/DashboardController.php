<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\TweetPost;
use App\Models\GetTweet;
use App\Models\LeadTicket;
use App\Models\SocialTicket;
use App\Models\TweetLog;
use App\Models\User;
use Carbon\Carbon;
use Log;
use DB;
class DashboardController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth')->except([
            
        ]);
	}
    public function countDashboard(Request $request)
    {
		try{
            $role = loggedUserRole();
			$endDate = Carbon::now();
            $startDate = $endDate->copy()->subDays(getValueByKey('LAST_POST_DAYS')-1);
            $endGraphDate = Carbon::now();
            $startGraphDate = $endDate->copy()->subDays(getValueByKey('LAST_POST_DAYS')-1);
            $endPostGraphDate = Carbon::now();
            $startPostGraphDate = $endDate->copy()->subDays(getValueByKey('LAST_POST_DAYS')-1);
            $endGraphLeadDate = Carbon::now();
            $startGraphLeadDate = $endDate->copy()->subDays(getValueByKey('LAST_POST_DAYS')-1);
            $endGraphSentimateDate = Carbon::now();
            $startGraphSentimateDate = $endDate->copy()->subDays(getValueByKey('LAST_POST_DAYS')-1);
            $ticketshowActive="show active";
            $ticketActive="active";
            $postshowActive="";
            $postActive="";
            $leadshowActive="";
            $leadActive="";
            $sentimateShowActive="";
            $sentimateActive="";
            if($request->tab_type==="post"){
                $postshowActive="show active";
                $postActive="active";
                $ticketshowActive="";
                $ticketActive="";
            }elseif($request->tab_type==="lead"){
                $leadshowActive="show active";
                $leadActive="active";
                $ticketshowActive="";
                $ticketActive="";
            }elseif($request->tab_type==="sentimate"){
                $sentimateShowActive="show active";
                $sentimateActive="active";
                $ticketshowActive="";
                $ticketActive="";
            } 

            if($request->startPostGraphDate){
                $startPostGraphDate = parseDateWithCurrentTime($request->startPostGraphDate);
                $endPostGraphDate= parseDateWithCurrentTime($request->endPostGraphDate);
            }
            $getPostGraphNewData = $this->getPostGraphsData($startPostGraphDate, $endPostGraphDate, 'New');
            $getPostGraphDuplicateData = $this->getPostGraphsData($startPostGraphDate, $endPostGraphDate, 'Duplicate');
            $getPostNewBoxData2 = $this->getPostNewBoxData($startPostGraphDate, $endPostGraphDate );
            $getTicketNewBoxData2 = $this->getTicketNewBoxData($startPostGraphDate, $endPostGraphDate );
            $getLeadNewBoxData2 = $this->getLeadNewBoxData($startPostGraphDate, $endPostGraphDate );
            $getPostGraphConvertedData = $this->getPostGraphTicketData($startGraphDate, $endGraphDate, '1');

            if($request->startGraphDate){
                $startGraphDate=parseDateWithCurrentTime($request->startGraphDate);
                $endGraphDate=parseDateWithCurrentTime($request->endGraphDate);
            }
            $getSocailGraphData = GetTweet::whereDate('istPostDate', '>=', $startGraphDate)->whereDate('istPostDate', '<=', $endGraphDate)->get();
            $getTicketGraphData = $this->getSocialTicketData($startGraphDate, $endGraphDate);
            $getTicketGraphNewData = $this->getSocialTicketData($startGraphDate, $endGraphDate, 'New');
            $getTicketGraphPendingData = $this->getSocialTicketData($startGraphDate, $endGraphDate, 'Pending With Team');
            $getTicketGraphMoveData = $this->getSocialTicketData($startGraphDate, $endGraphDate, 'Move To Internal Team');
            $getTicketGraphResolvedData = $this->getSocialTicketData($startGraphDate, $endGraphDate, 'Resolved');
            $getTicketGraphRejectedData = $this->getSocialTicketData($startGraphDate, $endGraphDate, 'Rejected');
            $getTicketGraphDuplicateData = $this->getSocialTicketData($startGraphDate, $endGraphDate, 'Duplicate');   
            $getTicketGraphAssignedData = $this->getSocialTicketData($startGraphDate, $endGraphDate, 'Assigned');   
            $getPostGraphconvertLeadData=$this->getPostGraphData($startGraphDate, $endGraphDate, '1');
            $getPostNewBoxData1 = $this->getPostNewBoxData($startGraphDate, $endGraphDate );
            $getTicketNewBoxData1 = $this->getTicketNewBoxData($startGraphDate, $endGraphDate );
            Log::info($getTicketNewBoxData1);
            $getLeadNewBoxData1 = $this->getLeadNewBoxData($startGraphDate, $endGraphDate );
            if($request->startGraphLeadDate){
                $startGraphLeadDate=parseDateWithCurrentTime($request->startGraphLeadDate);
                $endGraphLeadDate=parseDateWithCurrentTime($request->endGraphLeadDate);
            }
            $getLeadGraphData = $this->getLeadTicketData($startGraphLeadDate, $endGraphLeadDate);
            $getLeadNewBoxData = $this->getLeadTicketData($startDate, $endDate, 'New');
            $getLeadGraphNewData = $this->getLeadTicketData($startGraphLeadDate, $endGraphLeadDate, 'New');
            $getLeadGraphAssignedData = $this->getLeadTicketData($startGraphLeadDate, $endGraphLeadDate, 'Assigned');
            $getLeadGraphInProcessData = $this->getLeadTicketData($startGraphLeadDate, $endGraphLeadDate, 'In Process');
            $getLeadGraphCovertedData = $this->getLeadTicketData($startGraphLeadDate, $endGraphLeadDate, 'Converted');
            $getLeadGraphRecycledData = $this->getLeadTicketData($startGraphLeadDate, $endGraphLeadDate, 'Recycled');
            $getLeadGraphDuplicateData = $this->getLeadTicketData($startGraphLeadDate, $endGraphLeadDate, 'Duplicate');
            $getLeadGraphDeadData = $this->getLeadTicketData($startGraphLeadDate, $endGraphLeadDate, 'Dead');
            $getPostNewBoxData3 = $this->getPostNewBoxData($startGraphLeadDate , $endGraphLeadDate );
            $getTicketNewBoxData3 = $this->getTicketNewBoxData($startGraphLeadDate , $endGraphLeadDate );
            $getLeadNewBoxData3 = $this->getLeadNewBoxData($startGraphLeadDate , $endGraphLeadDate );
            if($request->startGraphSentimateDate){
                $startGraphSentimateDate=parseDateWithCurrentTime($request->startGraphSentimateDate);
                $endGraphSentimateDate=parseDateWithCurrentTime($request->endGraphSentimateDate);
            }
            $getSentimateGraphNagativeData = $this->getPostSentimateData($startGraphSentimateDate, $endGraphSentimateDate, 'Feedback Nagative');
            $getSentimateGraphPositiveData = $this->getPostSentimateData($startGraphSentimateDate, $endGraphSentimateDate, 'Feedback Positive');
            $getSentimateGraphComplaintData = $this->getPostSentimateData($startGraphSentimateDate, $endGraphSentimateDate, 'Complaint');
            $getSentimateGraphQueryData = $this->getPostSentimateData($startGraphSentimateDate, $endGraphSentimateDate, 'Query');
            $getSentimateGraphInformationData = $this->getPostSentimateData($startGraphSentimateDate, $endGraphSentimateDate, 'Information');
            $getSentimateGraphSpamData = $this->getPostSentimateData($startGraphSentimateDate, $endGraphSentimateDate, 'Spam');
            $getPostNewBoxData4 = $this->getPostNewBoxData($startGraphSentimateDate , $endGraphSentimateDate );
            $getTicketNewBoxData4 = $this->getTicketNewBoxData($startGraphSentimateDate , $endGraphSentimateDate );
            $getLeadNewBoxData4 = $this->getLeadNewBoxData($startGraphSentimateDate , $endGraphSentimateDate );
            $getSocailData = GetTweet::orderBy('istPostDate','DESC')->get();
            $getSocailRecentData = GetTweet::whereDate('istPostDate', '>=', $startDate)
            ->whereDate('istPostDate', '<=', $endDate)->get();
            $getTicketRecentData = SocialTicket::whereDate('date_Created', '>=', $startDate)
            ->whereDate('date_Created', '<=', $endDate)->get();
            $getLeadRecentData=LeadTicket::whereDate('created_date', '>=', $startDate)
            ->whereDate('created_date', '<=', $endDate)->get();
            if($role==="OTHERUSER"){
                $userId = loggedUserId();
                $asiignedhistory=assigntoLog($userId,"ticket");
                $getTicketRecentData = SocialTicket::whereIn('id', $asiignedhistory)
                ->whereDate('date_Created', '>=', $startDate)
                ->whereDate('date_Created', '<=', $endDate)->get();
            }
            $getTicketData = SocialTicket::orderBy('id','DESC')->get();
            if($role==="OTHERUSER"){
                $userId = loggedUserId();
                $asiignedhistory=assigntoLog($userId,"ticket");
                $getTicketData = SocialTicket::whereIn('id', $asiignedhistory)
                ->orderBy('id','DESC')->get();
            }
            $endDate = formatDate($endDate);
            $startDate = formatDate($startDate);
            $endGraphDate = formatDate($endGraphDate);
            $startGraphDate = formatDate($startGraphDate);
            $endPostGraphDate = formatDate($endPostGraphDate);
            $startPostGraphDate = formatDate($startPostGraphDate);
            $endGraphLeadDate = formatDate($endGraphLeadDate);
            $startGraphLeadDate = formatDate($startGraphLeadDate);
            $startGraphSentimateDate = formatDate($startGraphSentimateDate);
            $endGraphSentimateDate = formatDate($endGraphSentimateDate);            
            $totalPostCount=GetTweet::get();
            $totalTicketCount=SocialTicket::get();
            if($role==="OTHERUSER"){
                $userId = loggedUserId();
                $asiignedhistory=assigntoLog($userId,"ticket");
                $totalTicketCount = SocialTicket::whereIn('id', $asiignedhistory)->get();
            }
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
        return \View::make('post.dashboard_new', compact(['getPostNewBoxData1','getPostNewBoxData2','getPostNewBoxData3','getPostNewBoxData4','getTicketNewBoxData1','getTicketNewBoxData2','getTicketNewBoxData3','getTicketNewBoxData4','getLeadNewBoxData1','getLeadNewBoxData2','getLeadNewBoxData3','getLeadNewBoxData4','getLeadRecentData','sentimateShowActive','sentimateActive','startGraphSentimateDate','endGraphSentimateDate','getSentimateGraphSpamData','getSentimateGraphInformationData','getSentimateGraphQueryData','getSentimateGraphComplaintData','getSentimateGraphPositiveData','getSentimateGraphNagativeData','postActive','postshowActive','leadActive','leadshowActive','ticketshowActive','ticketActive','getSocailRecentData','getTicketRecentData','getSocailData','getTicketData','getPostGraphconvertLeadData','getTicketGraphNewData','getTicketGraphDuplicateData','getTicketGraphRejectedData','getTicketGraphPendingData','getTicketGraphMoveData','getTicketGraphResolvedData','getTicketGraphAssignedData','getSocailGraphData','getTicketGraphData','totalPostCount','totalTicketCount','getPostGraphNewData','getPostGraphDuplicateData','getPostGraphConvertedData','getLeadGraphData','getLeadGraphNewData','getLeadGraphAssignedData','getLeadGraphInProcessData','getLeadGraphCovertedData','getLeadGraphRecycledData','getLeadGraphDuplicateData','getLeadGraphDeadData','endDate','startDate','endGraphDate','startGraphDate','endPostGraphDate','startPostGraphDate','endGraphLeadDate','startGraphLeadDate']));
    }
	
    public function getPostNewBoxData($startDate , $endDate){
       $getPostNewBoxData =  GetTweet::whereDate('istPostDate', '>=', $startDate)
            ->whereDate('istPostDate', '<=', $endDate)->get();
            return $getPostNewBoxData;
    }

    public function getTicketNewBoxData($startDate , $endDate){
        $getTicketNewBoxData =  SocialTicket::whereDate('date_Created', '>=', $startDate)
        ->whereDate('date_Created', '<=', $endDate)->get();
             return $getTicketNewBoxData;
    }

    public function getLeadNewBoxData($startDate , $endDate){
        $getLeadNewBoxData =  LeadTicket::whereDate('created_date', '>=', $startDate)
        ->whereDate('created_date', '<=', $endDate)->get();
             return $getLeadNewBoxData;
    }
	
	public function globalSearch(Request $request)
	{
		try{
			$search = "";
			if($request->type == "ALL")
			{
				$data = getValueByKey('SEARCH_COLUMN');
				$keyArray = explode(",",$data);
				$searchValue = $request->search;
                $searchValue = remove_emoji($searchValue);
                $searchValue = substr($searchValue, 0, 140);
				$data = DB::table('tb_gettweet')
					->leftjoin('users','users.id','=','tb_gettweet.assignedto')
					->select('tb_gettweet.postMessage', 'tb_gettweet.socialUser_name as socialUser', 'tb_gettweet.id','users.name as name','tb_gettweet.getTweet_id',DB::raw("'post' as type"))
					->where('tb_gettweet.postMessage', 'LIKE', '%'.$searchValue.'%')
					->orWhere('tb_gettweet.socialUser_name', 'LIKE', '%'.$searchValue.'%')
					->orWhere('users.name', 'LIKE', '%'.$searchValue.'%');
				$data->union(
					DB::table('tb_leads')
						->leftjoin('users','users.id','=','tb_leads.assigned_to')
						->select('tb_leads.description as postMessage', 'tb_leads.customer_name as socialUser', 'tb_leads.id','users.name as name','tb_leads.getTweet_id',DB::raw("'lead' as type"))
						->where('tb_leads.description', 'LIKE', '%'.$searchValue.'%')
						->orWhere('tb_leads.customer_name', 'LIKE', '%'.$searchValue.'%')
						->orWhere('users.name', 'LIKE', '%'.$searchValue.'%')
				);

				$data->union(
					DB::table('tb_socialticket')
						->leftjoin('users','users.id','=','tb_socialticket.assigned_to')
						->select('tb_socialticket.postMessage', 'tb_socialticket.socialUser', 'tb_socialticket.id','users.name as name','tb_socialticket.getTweet_id',DB::raw("'ticket' as type"))
						->where('tb_socialticket.postMessage', 'LIKE', '%'.$searchValue.'%')
						->orWhere('tb_socialticket.socialUser', 'LIKE', '%'.$searchValue.'%')
						->orWhere('users.name', 'LIKE', '%'.$searchValue.'%')
				); 
				$search = $data->paginate(getValueByKey('PAGENATION_COUNT'));
	
			}
			elseif($request->type == "Post")
			{
				$data = getValueByKey('SEARCH_COLUMN');
				$keyArray = explode(",",$data);
				$searchValue = $request->search;
				$data = DB::table('tb_gettweet')
					->leftjoin('users','users.id','=','tb_gettweet.assignedto')
					->select('tb_gettweet.postMessage', 'tb_gettweet.socialUser_name as socialUser', 'tb_gettweet.id','users.name as name','tb_gettweet.getTweet_id',DB::raw("'post' as type"))
					->where('tb_gettweet.postMessage', 'LIKE', '%'.$searchValue.'%')
					->orWhere('tb_gettweet.socialUser_name', 'LIKE', '%'.$searchValue.'%')
					->orWhere('users.name', 'LIKE', '%'.$searchValue.'%');
				$search = $data->paginate(getValueByKey('PAGENATION_COUNT'));
				
	
			}
			elseif($request->type == "Ticket")
			{
				$data = getValueByKey('SEARCH_COLUMN');
				$keyArray = explode(",",$data);
				$searchValue = $request->search;
				$data = DB::table('tb_socialticket')
						->leftjoin('users','users.id','=','tb_socialticket.assigned_to')
						->select('tb_socialticket.postMessage', 'tb_socialticket.socialUser', 'tb_socialticket.id','users.name as name','tb_socialticket.getTweet_id',DB::raw("'ticket' as type"))
						->where('tb_socialticket.postMessage', 'LIKE', '%'.$searchValue.'%')
						->orWhere('tb_socialticket.socialUser', 'LIKE', '%'.$searchValue.'%')
						->orWhere('users.name', 'LIKE', '%'.$searchValue.'%');
					
				$search = $data->paginate(getValueByKey('PAGENATION_COUNT'));
				
	
			}
			elseif($request->type == "Lead")
			{
				$data = getValueByKey('SEARCH_COLUMN');
				$keyArray = explode(",",$data);
				$searchValue = $request->search;
				$data = DB::table('tb_leads')
						->leftjoin('users','users.id','=','tb_leads.assigned_to')
						->select('tb_leads.description as postMessage', 'tb_leads.customer_name as socialUser', 'tb_leads.id','users.name as name','tb_leads.getTweet_id',DB::raw("'lead' as type"))
						->where('tb_leads.description', 'LIKE', '%'.$searchValue.'%')
						->orWhere('tb_leads.customer_name', 'LIKE', '%'.$searchValue.'%')
						->orWhere('users.name', 'LIKE', '%'.$searchValue.'%');		       
				$search = $data->paginate(getValueByKey('PAGENATION_COUNT'));
	
			}
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
		return \View::make('post.globalSearch', compact(['search']));
	}


    function getSocialTicketData($start, $end, $status = null) {
        $role = loggedUserRole();
        $query = SocialTicket::whereDate('date_Created', '>=', $start)
            ->whereDate('date_Created', '<=', $end);
    
        if ($status !== null) {
            $query->where('status', $status);
        }

        if($role==="OTHERUSER"){
            $userId = loggedUserId();
            $asiignedhistory=assigntoLog($userId,"ticket");
            $query->whereIn('id', $asiignedhistory);
        }
    
        return $query->get();
    }
    
    function getPostGraphData($start, $end, $convertLead = null) {
        $query = GetTweet::whereDate('istPostDate', '>=', $start)
            ->whereDate('istPostDate', '<=', $end);
    
        if ($convertLead !== null) {
            $query->where('convertLead', $convertLead);
        }
    
        return $query->get();
    }

    function getPostGraphTicketData($start, $end, $convertLead = null) {
        $query = GetTweet::whereDate('istPostDate', '>=', $start)
            ->whereDate('istPostDate', '<=', $end);
    
        if ($convertLead !== null) {
            $query->where('converted', $convertLead);
        }
    
        return $query->get();
    }
    
    function getPostGraphsData($start, $end, $status = null) {
        $query = GetTweet::whereDate('istPostDate', '>=', $start)
            ->whereDate('istPostDate', '<=', $end);
    
        if ($status !== null) {
            $query->where('status', $status);
        }
    
        return $query->get();
    }

    function getPostSentimateData($start, $end, $post_category = null) {
        $query = GetTweet::whereDate('istPostDate', '>=', $start)
            ->whereDate('istPostDate', '<=', $end);
    
        if ($post_category !== null) {
            $query->where('post_category', $post_category);
        }
    
        return $query->get();
    }

    function getLeadTicketData($start, $end, $status = null) {
        $role = loggedUserRole();
        $query = LeadTicket::whereDate('created_date', '>=', $start)
            ->whereDate('created_date', '<=', $end);
    
        if ($status !== null) {
            $query->where('status', $status);
        }
        if($role==="OTHERUSER"){
            $userId = loggedUserId();
            $asiignedhistory=assigntoLog($userId,"Lead");
            $query->whereIn('id', $asiignedhistory);
        }
        return $query->get();
    }

    // public function downloadData()
    // {
    //     $perPage = 20000;
    //     return new StreamedResponse(function () use ($perPage) {
    //         $handle = fopen('php://output', 'w');
    //         fputcsv($handle, ['group_name', 'last_message_time', 'status', 'created_at', 'updated_at', 'deleted_at']); 
    //         $query = DB::table('tb_group')->orderBy('id');
    //         $query->chunk($perPage, function ($downloads) use ($handle) {
    //             foreach ($downloads as $download) {
    //                 fputcsv($handle, (array)$download);
    //                 ob_flush();
    //                 flush();
    //             }
    //         });

    //         fclose($handle);
    //     }, 200, [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => 'attachment; filename=downloads.csv',
    //     ]);
    // }


}
