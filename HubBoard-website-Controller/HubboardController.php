<?php
///////////////////////////////////////////////////////////////////////////////
// File:             HubboardController
// Project:			 MyHubber
// Module:			 HubBoard
// Author:           Hareesh
// 
//
// Credits:          @myhubber.com
///////////////////////////////////////////////////////////////////////////////
namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use Session;
use App\Http\Requests;
use App\Pals;
use App\Hubboards;
use Redirect;
use App\Posts;
use App\User;
use File;
use Image;
use Mail;
use DB;
use Sly\NotificationPusher\PushManager,
    Sly\NotificationPusher\Adapter\Apns as ApnsAdapter,
    Sly\NotificationPusher\Adapter\Gcm as GcmAdapter,
    Sly\NotificationPusher\Collection\DeviceCollection,
    Sly\NotificationPusher\Model\Device,
    Sly\NotificationPusher\Model\Message,
    Sly\NotificationPusher\Model\Push; 

class HubboardController extends Controller
{
	// This function checks the user authentication and fetches the Hubboard Ads from general classifieds category home page.
	// parentID 5 is general classifieds. Classified ads comes under general classifieds will be shown in home page.
	public function index()
	{
		$userId 	= '';
		$parentID	= 5;		
		
		$userId		= Auth::user()->ID;
		$catID		= Hubboards::listAdCat($parentID);
		
		
		$data['boards']['getRecommendedAd']= Hubboards::getRecommendedAd($catID);
		
		return view('hubboards.en.index')->with('data', $data);
	}

	// This function checks the user authentication and sends enquiry mail for the respective classified ad.
	// @ var name, email, mobile, msg, mail
	public function sendcontactform(Request $request){
		
		$userId					= '';
		$userId					=  Auth::user()->ID;
		$name 					=  $request->get('name');
		$email 					=  $request->get('email');
		$mobile 				=  $request->get('mobile');
		$msg 					=  $request->get('msg');
		$mail 					=  $request->get('mail');
		//
		$data = "You received an enquiry for your ad."."\r\n"."\r\n"."Name: ".$name."\r\n"."\r\n"."Phone: ".$mobile."\r\n"."\r\n"."Email: ".$email."\r\n"."\r\n"."Message:\r\n".$msg;
		//
		Mail::send('hubboards.en.mail', ['user' => $data], function ($m) use ($mail) {
			$m->from('info@myhubber.com', 'MyHUBBER');

			$m->to($mail, $mail)->subject('Enquiry for Ad');
		});
		return Redirect::back();
	}	
	
	//This function checks the user authentication and lists HUBboard ads page for a particular category eg: vehicles. 
	// @ var parentID
	public function listfull($parentID){
		
		$userId	= '';
		$userId	= Auth::user()->ID;
		$catbin	= Hubboards::listAdCat($parentID);
		
		if($catbin){
		$catID	= $catbin;
		}
		else{
		$catID[]= 0;
		$catID[]= $parentID;
		}
		
		$data['userData']=Auth::user();
		$data['parentID']=$parentID;
		$data['boards']['getRecommendedAd']= Hubboards::getAdbycat($catID);
		
		return view('hubboards.en.listall')->with('data', $data);
	}	
	
	
	
	

}
?>