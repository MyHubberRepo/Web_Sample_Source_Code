<?php
///////////////////////////////////////////////////////////////////////////////
// File:             voucher-services
// Project:			 MyHubber
// Module:			 Voucher Wallet (web-service)
// Author:           Riyaz
// 
//
// Credits:          @myhubber.com
///////////////////////////////////////////////////////////////////////////////

// This function returns all the deatils for the particular voucher
// Required values - getVoucherDetails, voucherid, userid
if($_POST['getVoucherDetails'])
{
	$voucherid 		= $_POST['voucherid'];
	$userid 		= $_POST['userid'];
	$currentDate 	=  date("Y-m-d H:i:s");
	
	if($userid > 0 and $voucherid > 0)
	{
	
			 $sqlredeem = $db->prepare("SELECT * from hubber_voucher_redeem where redeem_voucherid = :voucherid and redeem_userid = :userid");
		  
			  $sqlredeem->bindParam(':voucherid',$voucherid,PDO::PARAM_STR);
			  $sqlredeem->bindParam(':userid',$userid,PDO::PARAM_STR);
			  $sqlredeem->execute();
			  $row_count1 = $sqlredeem->rowCount();
			 
			  if($row_count1>0)
				{
					$advtBanner['is_success'] = "false";
					$advtBanner['message']    = "You have already redeemed this voucher";	 
					print json_encode($advtBanner);  
					exit;
				}
				else
				{
				
					$advtBanner = array();
					 $sql = $db->prepare("SELECT voucher_id, voucher_name, voucher_artwork, voucher_androidartwork, voucher_description, voucher_merchant, merchant_logo, merchant_tradename, branch_name, branch_pin, branch_location FROM hubber_voucher inner join hubber_merchant on voucher_merchant = merchant_id inner join hubber_shopbranch on voucher_merchant = branch_merchant where voucher_id =:voucherid and voucher_status = '1' and voucher_enddate >= CURDATE() and voucher_startdate <= CURDATE() and merchant_status = 'active' and branch_status = '1'");
				  
					  $sql->bindParam(':voucherid',$voucherid,PDO::PARAM_STR);
					  $sql->execute();
					  $row_count = $sql->rowCount();
					  if($row_count>0)
						{
							$advtBanner['is_success'] 	= "true";
							$advtBanner['message'] 		= "success";
							while($rowSub = $sql->fetch(PDO::FETCH_ASSOC))
							{
								$advtBanner['voucherdetails'][] = $rowSub;
							}
							
							//insert record to check who viewed the voucher							
							$inssql = $db->prepare("insert into  hubber_voucher_view(view_userid, view_voucherid, view_datetime) values (:userid, :voucherid, :currentDate)");
							$inssql->bindParam(':userid',$userid, PDO::PARAM_STR);
							$inssql->bindParam(':voucherid',$voucherid, PDO::PARAM_STR);
							$inssql->bindParam(':currentDate',$currentDate, PDO::PARAM_STR);
							$inssql->execute();							
							
						}
						else{
							$advtBanner['is_success'] = "false";
							$advtBanner['message']    = "Error";	        
						}
					print json_encode($advtBanner);  
				}
		}
		else
		{
			$advtBanner['is_success'] = "false";
			$advtBanner['message']    = "Invalid voucherid or User id";	
			print json_encode($advtBanner);  
		}
}






?>
