 
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

 <div id="login"></div>
    <button onclick="transfer()">transfer</button>
  <div id="log"></div>
  <div id="message"> </div>

<?php  
 // echo "<pre>" ;
	// print_r( $_POST) ;
 // echo "</pre>" ;
?>
 <form method="post" name="iostwalletform" id="iostwalletform" >

<div>
 <label>  Order Id </label>
	<input type="text" name="order_id" value="<?php  echo  isset( $_REQUEST['order_id'] )  ?  $_REQUEST['order_id'] : '' ; ?>">
</div>

<div>
 <label>  Amount </label>	
	<input type="text" name="invoice_amount" value="<?php  echo  isset( $_REQUEST['amount'] )  ?  $_REQUEST['amount'] : '' ; ?>">	
</div>	

<div>
 <label>  Currency </label>	
	<input type="text" name="invoice_currency" value="<?php  echo  isset( $_REQUEST['currency'] )  ?  $_REQUEST['currency'] : '' ; ?>">	
</div>	

<div>
 <label>  Notify URL </label>	
	<input type="text" name="notify_url" id="notify_url" value="<?php  echo  isset( $_REQUEST['notify_url'] )  ?  $_REQUEST['notify_url'] : '' ; ?>">	
</div>

<div>
 <label>  Return URL </label>	
	<input type="text" name="return_url" id="return_url" value="<?php  echo  isset( $_REQUEST['return_url'] )  ?  $_REQUEST['return_url'] : '' ; ?>">	
</div>

<div>
 <label>  Invoice Created At </label>	
	<input type="text" name="invoice_created_at" id="invoice_created_at" value="<?php  echo  date('m/d/Y') ; ?>">	
</div>

<div>
 <label>  Secret Key </label>	
	<input type="text" name="secret_key" value="<?php  echo  isset( $_REQUEST['secret_key'] )  ?  $_REQUEST['secret_key'] : '' ; ?>">	
</div>

<div>
 <label>  Checksum </label>	
	<input type="text" name="checksum" value="<?php  echo  isset( $_REQUEST['checksum'] )  ?  $_REQUEST['checksum'] : '' ; ?>">	
</div>	

<div>
 <label>  Status </label>			
	<input type="text" name="invoice_status" id="invoice_status" value="" >
</div>	

<div>
 <label>  Transaction Id </label>		
	<input type="text" name="txid" id="txid" value="" >
</div>	
	
	<button  name="" id="processOrder" > Process </button>
	</form>

  <script type="text/javascript" src="dist/iost.min.js"></script>
  
  <script type="text/javascript">
    function log(msg) {
      var p = document.createElement('p');
      p.innerHTML = msg;
      document.getElementById('log').appendChild(p);
    }
	
	$(document).on('click', '#processOrder', function( event ){
			
			event.preventDefault() ;
			
			var invoice_status	=	$('#invoice_status').val() ;
			var return_url	=	$('#return_url').val() ;
				
			$.ajax({
				  url: 'include/ajax.php',
				  type:'post',
				  data: $('#iostwalletform').serialize() + "&invoice_status="+invoice_status,
				  success: function( resp ){
					  
					  console.log( resp ) ;
					  // window.location.href = return_url;

				  }
			})
			
			// $.ajax({
				// url : $('#notify_url').val(),
				// type: 'post',
				// data:{
					// order_id: $('#order_id').val(),
					// invoice_reference: '',
					// invoice_amount: $('#invoice_amount').val(),
					// invoice_currency: $('#invoice_currency').val(),
					// invoice_created_at: $('#invoice_created_at').val(),
					// invoice_status: $('#invoice_status').val(),
					// checksum: $('#checksum').val(),
					// },
				// success:function( resp ){
					// console.log(resp) ;
				// }	
			// })
		
	})
	

    var transfer;
    document.addEventListener("DOMContentLoaded", async function(event) {
      await new Promise(done => setTimeout(() => done(), 500));

      IWalletJS.enable().then(function(account) {
        if(!account) return;

        document.getElementById('login').innerHTML = "login in with: " + account;

        const iost = IWalletJS.newIOST(IOST);

        transfer = function() {
          const tx = iost.callABI("token.iost", "transfer", ["iost", account, '2JPTB3X9ePHuKcWKZFCENUtsR4TRqnD1bBq6a45e4cAXhQodMAcqJghbrYUKgktUErUE434vZVrXiu9oMhJrKdnq', "10", "dapp test memo"]);
          tx.addApprove("iost", "10");

          console.log(tx.getApproveList());
          iost.signAndSend(tx)
            .on('pending', function(txid) {
              log("txid: " + txid);
				
				document.getElementById("txid").value =    txid   ;
			})
            .on('success', function(result) {
              log("res: " + JSON.stringify(result));
			
				document.getElementById("invoice_status").value =  'success'  ;
				var	object 	=	JSON.parse( JSON.stringify(failed) ) ;
				document.getElementById("message").innerHTML =    object.message   ;
            })
            .on('failed', function(failed) {
              log("failed: " + JSON.stringify(failed));
			  	
				document.getElementById("invoice_status").value =  'failed'  ;
			var	object 	=	JSON.parse( JSON.stringify(failed) ) ;
				document.getElementById("message").innerHTML =    object.message   ;

            })
        }
      })
    })
  </script>