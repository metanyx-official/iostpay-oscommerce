<?php

	if( isset($_POST['txid']) ){
	
		$data = $_POST	;
		

        	$transactionId = 		$data['txid'] ; 
		
		  $ch2 = curl_init();
            $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
              );
              
            curl_setopt($ch2, CURLOPT_URL, 'http://api.iost.io/getTxByHash/'.$transactionId);
            curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch2, CURLOPT_HEADER, 0);
            $body = '{}';
        
            curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "GET"); 
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        
            $response = curl_exec($ch2);
            
            $resp    =    json_decode( $response, true ) ;



                if( isset( $resp['transaction']['tx_receipt']['status_code'] ) ){ 
                    
                    $status_code  =  $resp['transaction']['tx_receipt']['status_code'] ;
                    
                    $message      =  $resp['transaction']['tx_receipt']['message'] ;
                    
                }
                
                if( $status_code == 'RUNTIME_ERROR'){
                    $message = 'Balance not enough.' ;
                    $status_code = 'sum_too_low' ;
                }
                
            $array_resp = array(
                        'message' => isset( $message ) ? $message : '' ,
                        'status_code' => isset( $status_code ) ? $status_code : '' ,
                       )        ;
                       
  
    
        echo json_encode( $array_resp ) ;
           
        if( $status_code == 'SUCCESS' ){
            $status   = 'approved' ;
        }
        elseif( $status_code == 'sum_too_low' ){
            $status   = 'sum_too_low' ;
        }else{
             $status   = 'pending' ;
        }
	   // $status   = ( $status_code == 'SUCCESS' ) ? 'approved' : ( $status_code == 'sum_too_low' ) ? "sum_too_low" : "pending";
	   // $status   = ( $status_code == 'sum_too_low' ) ? 'sum_too_low' : ' ' ; 
     
        // 	  sum_too_low
	   //   print_r($array_resp) ;

		$data['invoice_reference'] = '' ;
		
		$data['invoice_status'] = $status;
		
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
		
		    // echo 1 ;
		print_r( $server_output ) ;
		
    		// print_r( $_POST ) ;
    	
        	// die() ;
	}	
	
	
	