<?php
///////////////////////////////////////////////////////////////////////////////
// File:             social-services
// Project:			 MyHubber
// Module:			 Hubpost (Webservice)
// Author:           Hareesh
// 
//
// Credits:          @myhubber.com
///////////////////////////////////////////////////////////////////////////////

$db = new PDO("mysql:host=;dbname=", "", "", array(PDO::ATTR_PERSISTENT => true));
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// This function updates the view count for hubpost
// Required values - updateViewCount, usersID, postsID
if($_POST['updateViewCount'])
{
	$usersID	= '';
	$postsID	= '';
	$usersID	= $_POST['usersID'];
	$postsID	= $_POST['postsID'];
	$currentdate= date("Y-m-d:H:i:s");  

	if($usersID=='' && $postsID=='')
	{
		$updateViewCount['is_success']  = "false";
		$updateViewCount['message'] 	= "Error : Unable to update";
		print json_encode($updateViewCount);
		exit;
	}
 
 	try
	{

 	$stmt =$db->prepare("INSERT INTO hubber_post_view (view_post_id, view_user_id, view_datetime, view_status) VALUES (:postID, :usersID,'$currentdate','1') ON DUPLICATE KEY UPDATE view_datetime='$currentdate'");		
		$stmt->bindParam(':postID', $postsID);
		$stmt->bindParam(':usersID', $usersID);
		$stmt->execute();

		$updateViewCount['is_success'] 	= "true";
		$updateViewCount['message'] 	= "Success";

	}
	catch (PDOException $exception)
	{
		$errorMessage = $exception->getMessage(); 

		$updateViewCount['is_success'] 	= "false";
		$updateViewCount['message']	 	= $errorMessage;
	}

	print json_encode($updateViewCount);
}


// This function lists all HashTags in hubpost
// Required values - listAllHashTags
if(isset($_POST['listAllHashTags']))
{
	$stmt = $db->prepare("SELECT DISTINCT(hashTag) FROM `hubber_posts_hash_tags`");
			$stmt->execute();
			$hashTags=array();
			while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) 
			{
				$hashTags[]['hashTag']=utf8_encode(html_entity_decode($result['hashTag'], ENT_COMPAT,"UTF-8")); 
			}

	$listAllHashTags['is_success'] 	= "true";
	$listAllHashTags['message'] 	= "Success";
	$listAllHashTags['HashTags'] 	= $hashTags;
	print json_encode($listAllHashTags);
}




