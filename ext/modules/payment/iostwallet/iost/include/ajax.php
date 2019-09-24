<?php
require_once('DbQueryClass.php') ;	


	$data = $_POST	;
 
  // print_r($data) ; 
	
	// die() ;
	
	// $objDbQuery->saveIOST_wallet( $data ) ;
	
	$data['invoice_reference'] = '' ;
	
	$notify_url  = 	$data['notify_url'] ;
	
	$postfields = http_build_query( $data ) ;
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $notify_url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	// Receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);

	curl_close ($ch);
	
	
	// print_r( $server_output ) ;
	
	
	
