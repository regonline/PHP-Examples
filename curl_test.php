<html>
 <head>
  <title>PHP CURL Test</title>
  <style type="text/css">
	body, html {font-family: Helvetica, Verdana, Arial; margin:10px; padding:0px; font-size:12px;}
	div.xml {height:200px;width:100%;overflow:auto;border:solid 1px #999;}
  </style>
 </head>
 <body>

 <?php 
	
	function t($err)
	{
		echo "<h3>Error</h3>" . $err;
	}

	$uname = "<USERNAME>";
	$pass = "<PASSWORD>";
	$event = <EVENTID>;
  
	$ch = curl_init('https://www.regonline.com/api/default.asmx/Login');
	$params = 'username=' . urlencode($uname) . '&';
	$params .= 'password=' . urlencode($pass);
  
	curl_setopt($ch, CURLOPT_POSTFIELDS,  $params);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$xml_response = curl_exec($ch);
 
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if($http_code >= 400) { 
	return array('success' => FALSE, 'message' => t($xml_response));
	}
	curl_close($ch);
  
	$data = simplexml_load_string($xml_response);

	$api_token = $data->Data[0]->APIToken;
	if (!$api_token) {
		return array('success' => FALSE, 'message' => t($data->Message));
	}

	$curl_get = curl_init('https://www.regonline.com/api/default.asmx/GetRegistrationsForEvent');
	$params = 'eventID=' . urlencode($event);
	$params .= '&filter=';
	$params .= '&orderBy=';
 
	$header = array( 'Content-Length: ' . strlen($params),
							'Content-Type: application/x-www-form-urlencoded',
							'APIToken: ' . $api_token );
	curl_setopt($curl_get, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl_get, CURLOPT_POSTFIELDS,  $params);
	curl_setopt($curl_get, CURLOPT_POST, 1);
	curl_setopt($curl_get, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_get, CURLOPT_SSL_VERIFYPEER, false);
	$xml_response = curl_exec($curl_get);
 
	$http_code = curl_getinfo($curl_get, CURLINFO_HTTP_CODE);
	if($http_code >= 400) { 
		return array('success' => FALSE, 'message' => t('Error getting registrations'));
	}

	curl_close($curl_get);
?>

<h2>PHP CURL Test</h2>
<p>
	This is a sample demonstration of calling the RegOnline API using curl in PHP. It first calls the Login method to obtain the API Token 
	and then calls GetRegistrationsForEvent to obtain an event's registrations.
</p>

<h3>RESPONSE</h3>
<div class="xml">
	<?php
		echo  htmlentities($xml_response);
	?>
</div>

 </body>
</html>

