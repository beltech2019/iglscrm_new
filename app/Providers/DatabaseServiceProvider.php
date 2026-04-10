<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\SocialTicket;
use App\Models\Favourite;
use App\Models\Component;
use Auth;
use Log;
use Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Encryption\Encrypter;
class DatabaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
		try{
			 $assignedToMe=[];
             $favouriteToMe=[];
			 $UIcomponent = [];
			$id = Cookie::get('user_id');		
			if($id)
			{
				$assignedToMe=SocialTicket::where('assigned_to',$id)->limit(10)->get();
				$favouriteToMe = Favourite::leftjoin('tb_leads','tb_leads.id','=','tb_favourite.type_id')
				->leftjoin('tb_gettweet','tb_gettweet.id','=','tb_favourite.type_id')
				->leftjoin('tb_socialticket','tb_socialticket.id','=','tb_favourite.type_id')
				->select(
					'tb_favourite.*',
					'tb_gettweet.postMessage',
					'tb_gettweet.source',
					'tb_leads.description',
					'tb_leads.lead_source AS leadSource',
					'tb_socialticket.postMessage AS ticketPostMessage',
					'tb_gettweet.getTweet_id',
					'tb_leads.getTweet_id AS leadGetTweet_id',
					'tb_socialticket.getTweet_id AS ticketGetTweet_id',
					'tb_leads.first_name',
					'tb_gettweet.socialUser_name',
					'tb_leads.last_name',
					'tb_socialticket.socialUser',
					'tb_socialticket.source AS ticketSource'
				)
				->where('tb_favourite.user_id',$id)
				->where('tb_favourite.status','1')
				->orderBy('tb_favourite.updated_at', 'DESC')
				->limit(10)
				->get();
				
				if($favouriteToMe && count($favouriteToMe))
				{
					foreach($favouriteToMe as $favourite)
					{
						if($favourite->type == 'tb_lead')
						{
							$favourite->source = $favourite->leadSource;
						}
						else if($favourite->type == 'tb_socialticket')
						{
							$favourite->source = $favourite->ticketSource;
						}
						
					}
				}
				$user = User::find($id);
				if($user)
				{
					$UIcomponent = Component::leftJoin('tb_role_access_mapping', function($leftJoin)use ($user)
					{
						$leftJoin->on('tb_role_access_mapping.user_role_id', '=', 'tb_component.id');
						$leftJoin->where('tb_role_access_mapping.user_role_id',$user->role);
					})
					->select('tb_component.*','tb_role_access_mapping.*','tb_component.id as id','tb_role_access_mapping.id as mapId')
					->get();
				}
			}
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
        view()->share(['assignedToMe'=> $assignedToMe,'favouriteToMe'=> $favouriteToMe,'UIcomponent'=>$UIcomponent]);
    }
}
