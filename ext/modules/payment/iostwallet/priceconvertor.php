<?php 
chdir('../../../../');
require ('includes/application_top.php');
require ('iostpay_functions.php');


function conPrice( $invoice_amount ){
        $url = 'https://pro-api.coinmarketcap.com/v1/tools/price-conversion';

         $parameters = [
              'id' => '825',
              'amount' => $invoice_amount,
              'convert' => 'IOST'
           ];
       $CMC_KEY     = MODULE_PAYMENT_CMC_API_KEY ;
        $headers = [
                  'Accepts: application/json',
                  'X-CMC_PRO_API_KEY: '.$CMC_KEY
                  ];

        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL
        
         $curl = curl_init(); // Get cURL resource
        // Set cURL options
        curl_setopt_array($curl, array(
          CURLOPT_URL => $request,            // set the request URL
          CURLOPT_HTTPHEADER => $headers,     // set the headers 
          CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));
        
        $response = curl_exec($curl); // Send the request, save the response
        $response	=	json_decode($response, true) ;
        
        
        if( isset( $response ) ){
            
            $iostPrice       =        $response['data']['quote']['IOST']['price'] ; 
			
            return $iostPrice ;
          }

}		
		
			