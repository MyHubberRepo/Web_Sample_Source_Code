<?php
///////////////////////////////////////////////////////////////////////////////
// File:             HubvoucherController
// Project:			 MyHubber
// Module:			 Voucher Wallet
// Author:           Riyaz
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
use App\Posts;
use App\User;
use App\Voucher;
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

class HubvoucherController extends Controller
{
	//This function shows the home page of Voucher Wallet
	public function index()
	{
		
		
		$data['userData']	= Auth::user();	
		
		return view('voucher.en.landing')->with('data', $data);
	}
	
	// This function shows select merchant page of Voucher wallet
	// @var id - category id for merchant
	public function opencat($id)
	{				
		$data['userData']	= Auth::user();
		$data['cat']		= Voucher::getmerchantbycat($id);		
		
		return view('voucher.en.opencat')->with('data', $data);
	}
	// This function shows select merchant branch page of Voucher wallet
	// @var id, categoryid 
	public function openmerchant($id, $categoryid)
	{
		
		$data['userData']	= Auth::user();
		$data['branch']		= Voucher::getbranchbymer($id, $categoryid);
		
		return view('voucher.en.openbranch')->with('data', $data);
	}
	
	
}
?>