<?php
	
	if (!$_POST['searchterm']) die();
	
	# //open connection  
	$ch = curl_init();
	
	# //$url = 'http://search.twitter.com/search.json?q='.urlencode($_POST['searchterm']).'&rpp=7&lang=en';
	
	$nt_search = (urlencode(stripslashes($_POST['searchterm'])));
	$nt_rpp = ($_POST['rpp']);

	$url = 'http://search.twitter.com/search.json?q='.$nt_search.'&rpp='.$nt_rpp.'&lang=en';
        
	# //set the url, number of POST vars, POST data  
	curl_setopt($ch,CURLOPT_URL,$url); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

	//execute post  
	$result = curl_exec($ch);  
	echo $result;
	//close connection  
	curl_close($ch);
?>
