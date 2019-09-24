<?php

 $dbhost =  "localhost";
 $dbuser =  "root";
 $dbpass =  "123456";
 $db 	 = 	"oscommerce";
 

 $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n". $conn -> error);


?>