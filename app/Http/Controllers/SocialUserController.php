<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SocialUser;
use App\Models\Favourite;
use Exception;
use Log;
use DB;
use View;

class SocialUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            
        ]);
    }

    public function userProfile(Request $request,$id)
    {
       try {
           $logUser=loggedUserId();
           $getUser = SocialUser::where('user_id',$id)->first();
           $getFavourite = Favourite::where('user_id',$logUser)
           ->where('type_id',$getUser->id)
           ->where('type','tb_social_user')
           ->first();
           return \View::make('post.user_profile', compact(['getUser','getFavourite']));
       } catch(Exception $e) {
           return redirect()->back()->with('message', $e->getMessage());
      }
    }

    public function addFavourite(Request $request)
    {
       try {
           $message="Favourite Added";
           $id=loggedUserId();
           $nowTime = Carbon::now();
           $getUser = Favourite::where('user_id',$id)
           ->where('type_id',$request->type_id)
           ->where('type',$request->type)
           ->first();
           if(!$getUser){
             $create=Favourite::create([
                'user_id'=> $id,
                'type_id'=> $request->type_id,
                'type'=> $request->type,
                'date_created'=> $nowTime,
                'status'=> '1',
             ]);
           }else{
             if($getUser->status=='1'){
                $getUser->update([
                   'status'=> '0',
                ]);
                $message="Favourite Removed";
             }else{
                $getUser->update([
                    'status'=> '1',
                ]);
             }
           }
           return redirect()->back()->with('message', $message);
       } catch(Exception $e) {
           return redirect()->back()->with('message', $e->getMessage());
      }
    }

    public function getFavourite(Request $request)
    {
       try {
           $id=loggedUserId();
           $getFavourite = Favourite::leftjoin('tb_leads','tb_leads.id','=','tb_favourite.type_id')
           ->leftjoin('tb_gettweet','tb_gettweet.id','=','tb_favourite.type_id')
           ->leftjoin('tb_socialticket','tb_socialticket.id','=','tb_favourite.type_id')
           ->select(
               'tb_favourite.*',
               'tb_gettweet.postMessage',
               'tb_leads.description',
               'tb_socialticket.postMessage AS ticketPostMessage',
               'tb_gettweet.getTweet_id',
               'tb_socialticket.ticket_id',
               'tb_leads.getTweet_id AS leadGetTweet_id',
               'tb_socialticket.getTweet_id AS ticketGetTweet_id',
               'tb_leads.first_name',
               'tb_leads.leadId',
               'tb_gettweet.socialUser_name',
               'tb_leads.last_name',
               'tb_socialticket.socialUser',
               'tb_gettweet.istPostDate',
               'tb_leads.created_date',
               'tb_socialticket.date_Created')
           ->where('tb_favourite.user_id',$id)
           ->where('tb_favourite.status','1')
           ->orderBy('tb_favourite.updated_at', 'DESC')
           ->paginate(getValueByKey('PAGENATION_COUNT'));
           return \View::make('post.favourite', compact(['getFavourite']));
       } catch(Exception $e) {
           return redirect()->back()->with('message', $e->getMessage());
      }
    }
	
}
