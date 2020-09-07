<?php

function insert_tickets(){

	$access_token = "f868cf8d78e60133c31e14f80e243836";
	$org_id = "60005778740";

	$post_data = [
				  "productId" => "",
				  "contactId" => "1892000000042032",
				  "subject" => "Real Time analysis",
				  "departmentId" => "123123",
				  "description" => "Hai This is Description",
				  "priority" => "High",
				  "phone" => "8867658898",
				  "category" => "general",
				  "email" => "jagan.ml1993@gmail.com"
				];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://desk.zoho.com/api/v1/tickets");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:Zoho-oauthtoken'.$access_token , 'orgId:'.$org_id) );

	$response = curl_exec($ch);
	$response = json_decode($response);

	var_dump($response);
}

insert_tickets();

?>