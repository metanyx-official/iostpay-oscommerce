<?php
require_once('config/database.php') ;

Class DbQueryClass{
	
	private $conn ; 
	
	function __construct( $conn ){
			
		$this->conn = $conn ;	
	}
	
	function saveIOST_wallet( $data ){
			
			extract( $data ) ;
			
			// $invoice_created_at = date('d/m/Y') ;
			
		$sql = "INSERT into iost_wallet( order_id,  invoice_amount, invoice_currency, invoice_created_at,
				 invoice_status, checksum) VALUES ( '$order_id', '$invoice_amount', '$invoice_currency', '$invoice_created_at', '$invoice_status', '$checksum' 
					)" ;
					
		$result		=	mysqli_query( $this->conn, $sql) ;
					
		return $result ;			
	}

	
	
 }

 $objDbQuery = new DbQueryClass( $conn ) ;