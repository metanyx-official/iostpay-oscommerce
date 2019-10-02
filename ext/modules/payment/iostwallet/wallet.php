<?php  
 require_once('priceconvertor.php') ;

    $cancel_url	=	 isset( $_POST['cancel_url'] )      ? $_POST['cancel_url'] : '' ;
    $order_id	=	 isset( $_POST['order_id'] )      ? $_POST['order_id'] : '' ;
	$invoice_amount	=	 isset( $_POST['amount'] )      ? $_POST['amount'] : '' ;
	$account_id 	=	 isset( $_POST['account_id'] )  ? $_POST['account_id'] : '' ;
	$store_name	    =	 isset( $_POST['store_name'] )  ? $_POST['store_name'] : '' ;
	$store_logo	    =	 isset( $_POST['store_logo'] )  ? $_POST['store_logo'] :
	    '' ;
	    
	 $iost_amount =  conPrice( $invoice_amount ) ;

     
       $iost_amount    =   number_format((float)$iost_amount, 2, '.', '');
       
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

 <form method="post" name="iostwalletform" id="iostwalletform" >

	<div>
	 <!-- <label>  Order Id </label> -->
		<input type="hidden" name="order_id" value="<?php  echo  isset( $_REQUEST['order_id'] )  ?  $_REQUEST['order_id'] : '' ; ?>">
	</div>

	<div>
	 <!-- <label>  Amount </label>	 -->
		<input type="hidden" name="invoice_amount" value="<?php  echo  isset( $_REQUEST['amount'] )  ?  $_REQUEST['amount'] : '' ; ?>">	
	</div>	

	<div>
	 <!-- <label>  Currency </label>	 -->
		<input type="hidden" name="invoice_currency" value="<?php  echo  isset( $_REQUEST['currency'] )  ?  $_REQUEST['currency'] : '' ; ?>">	
	</div>	

	<div>
	 <!-- <label>  Notify URL </label>	 -->
		<input type="hidden" name="notify_url" id="notify_url" value="<?php  echo  isset( $_REQUEST['notify_url'] )  ?  $_REQUEST['notify_url'] : '' ; ?>">	
	</div>

	<div>
	 <!-- <label>  Return URL </label>	 -->
		<input type="hidden" name="return_url" id="return_url" value="<?php  echo  isset( $_REQUEST['return_url'] )  ?  $_REQUEST['return_url'] : '' ; ?>">	
		
		
		<input type="hidden" name="cancel_url" id="cancel_url" value="<?php  echo  isset( $cancel_url )  ?  $cancel_url : '' ; ?>">	
	</div>

	<div>
	 <!-- <label>  Invoice Created At </label>	 -->
		<input type="hidden" name="invoice_created_at" id="invoice_created_at" value="<?php  echo  date('m/d/Y') ; ?>">	
	</div>

	<div>
	 <!-- <label>  Secret Key </label>	 -->
		<input type="hidden" name="account_id" value="<?php  echo  isset( $_REQUEST['account_id'] )  ?  $_REQUEST['account_id'] : '' ; ?>">	
	</div>

	<div>
	 <!-- <label>  Checksum </label>	 -->
		<input type="hidden" name="checksum" value="<?php  echo  isset( $_REQUEST['checksum'] )  ?  $_REQUEST['checksum'] : '' ; ?>">	
	</div>	

	<div>
	 <!-- <label>  Status </label>			 -->
		<input type="hidden" name="invoice_status" id="invoice_status" value="" >
	</div>	

	<div>
	 <!-- <label>  Transaction Id </label>		 -->
		<input type="hidden" name="txid" id="txid" value="" >
	</div>	
	
    <div>
	 <!-- <label>  Transaction Id </label>		 -->
		<input type="hidden" name="custom" id="custom" value="<?php  echo  isset( $_REQUEST['custom'] )  ?  $_REQUEST['custom'] : '' ; ?>" >
	</div>	
 	<!--<button  name="" id="processOrder" > Process </button> -->
	
</form>

  <script type="text/javascript" src="iost/dist/iost.min.js"></script>
  
  <script type="text/javascript">

    var transfer;
    document.addEventListener("DOMContentLoaded", async function(event) {
      await new Promise(done => setTimeout(() => done(), 500));
	
		var cancel_url	=	$('#cancel_url').val() ;

        if( IWalletJS.account.name == null ){
            
            alert('Please Unlock your wallet and return back!') ;
          
             setInterval(function () {
                	window.location.href = cancel_url ;	
              }, 1000);
           

            return false;
        } 

      IWalletJS.enable().then(function(account) {
        if(!account) return;

        document.getElementById('login').innerHTML = "Wallet Name: " + account;

        const iost = IWalletJS.newIOST(IOST);

        // transfer = function() {
            

          const tx = iost.callABI("token.iost", "transfer", ["iost", account, '<?php echo $account_id ?>', '<?php echo $iost_amount ;  ?>', "<?php echo $order_id ;  ?>"]);
                      tx.addApprove("iost", "<?php echo $iost_amount ;  ?>");
    
        //   const tx = iost.callABI("token.iost", "transfer", ["iost", account, '<?php echo $account_id ?>', '1', "<?php echo $store_name ;  ?>"]);
          
          
          
          
          iost.signAndSend(tx)
            .on('pending', function(txid) {
                
				document.getElementById("txid").value =    txid   ;
				
				console.log( 'First :'+txid ) ;
				
				})
				
				
            .on('success', function(result) {

				document.getElementById("invoice_status").value =  'success'  ;
				document.getElementById("page").style.display = 'block'  ;	
			    setInterval(function () {
					action_notifyUrl() ;
				}, 15000);

            })
            .on('failed', function(failed) {
            
         //    console.log( 'second :'+failed ) ;


	    // document.getElementById("loading").style.display =  'none'  ;
				
	    document.getElementById("invoice_status").value =  'failed'  ;
				
		document.getElementById("cancelledPayment").innerHTML = 'Payment is Failed'  ;
		    
	
		        
                setInterval(function () {
					action_notifyUrl() ;
				}, 15000);



            })
    
		
      })
    })

 $( document ).on('click', '#processOrder', function( event ){
     
        event.preventDefault() ;
     action_notifyUrl() ;
 }) 
	
 function action_notifyUrl(){
	 
			var invoice_status	=	$('#invoice_status').val() ;
			var return_url  	=	$('#return_url').val() ;
			var cancel_url  	=	$('#cancel_url').val() ;


			$.ajax({
				  url: 'iost_action.php',
				  type:'post',
				  data: $('#iostwalletform').serialize() + "&invoice_status="+invoice_status,
				  success: function( json ){
					  
					  console.log( json ) ;
					  
        			const obj = JSON.parse(json);
        			var message    =  obj.message
        			
        		    document.getElementById("message").innerHTML = message  ;
				
				
		    		  if(obj.status_code != 'SUCCESS'){
					            return_url = cancel_url ;
					    }
					        
					   setInterval(function () {
							window.location.href = return_url;
						},2000);
					  

				  }
			})
			
 }
</script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
<div class="row">  
     <div class="store_logo col-md-12 text-center"> 
        <img src ="<?php echo $store_logo ;   ?>"  >
    </div>
 <div class="col-md-12"> 
    <h1> 
        <div id="login"></div> 
    </h1>
 </div>
<div class="col-md-12 text-center"> 
     <div id="page">
        <p> Please wait we are processing your order .....  </p>
      </div>
</div>
<div class="col-md-12 text-center"> 
     <div id="cancelledPayment">
      
      </div>
</div>

<div class="col-md-12 text-center"> 
     <div id="message">
      
      </div>
</div>

</div>

<div id="loading"></div>
<style>
div#login {
    text-align: center;
}
body {
    /*background: #FFF url("http://i.imgur.com/KheAuef.png") top left repeat-x;*/
    font-family:"Brush Script MT", cursive;
}
h1 {
    font-size: 2em;
    margin-bottom: 0.2em;
    padding-bottom: 0;
}
p {
    font-size: 1.5em;
    margin-top: 0;
    padding-top: 0;
}
#page {
    display: none;
}
#loading {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 100;
    width: 100vw;
    height: 100vh;
    background-color: rgba(192, 192, 192, 0.5);
    background-image: url("http://i.stack.imgur.com/MnyxU.gif");
    background-repeat: no-repeat;
    background-position: center;
}
</style>

<script>
function onReady(callback) {
    var intervalID = window.setInterval(checkReady, 5000);
     window.clearInterval(intervalID);
    function checkReady() {
        if (document.getElementsByTagName('body')[0] !== undefined) {
            window.clearInterval(intervalID);
            callback.call(this);
        }
    }
}

function show(id, value) {
    document.getElementById(id).style.display = value ? 'block' : 'none';
}

onReady(function () {
    show('page', true);
    show('loading', false);
});
</script>