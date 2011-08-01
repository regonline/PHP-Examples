<html>
 <head>
  <title>PHP SOAP HTTP Header Test</title>
  <style type="text/css">
	body, html {font-family: Helvetica, Verdana, Arial; margin:10px; padding:0px; font-size:12px;}
	div.xml {height:200px;width:100%;overflow:auto;border:solid 1px #999;}
  </style>
 </head>
 <body>

 <?php 

	class MSSoapClient extends SoapClient {

		public $apiToken = '';

		function setToken($token) {
			$this->apiToken = $token;
		}

		function __doRequest($request,$location,$action,$version){

        
		$headers = array(
			'Method: POST',
			'Connection: Close',
			'User-Agent: YOUR-USER-AGENT',
			'Content-Type: text/xml',
			'SOAPAction: "'.$action.'"',
			'APIToken: "' . $this->apiToken .'"'
		);

		$ch = curl_init($location);
		curl_setopt_array($ch,array(
			CURLOPT_VERBOSE=>false,
			CURLOPT_RETURNTRANSFER=>true,
			CURLOPT_POST=>true,
			CURLOPT_POSTFIELDS=>$request,
			CURLOPT_HEADER=>false,
			CURLOPT_HTTPHEADER=>$headers,
			CURLOPT_SSL_VERIFYPEER=>false
		));
	
			$response = curl_exec($ch);

			return $response;
		}
	}


	$wsdl = "https://www.regonline.com/api/default.asmx?wsdl";
	$username = "<USERNAME>";
	$password = "<PASSWORD>";
		
    $client = new SoapClient($wsdl, 
	array(
		  "trace"      => 1,		// enable trace to view what is happening
		  "exceptions" => 0,		// disable exceptions
		  "cache_wsdl" => 0) 		// disable any caching on the wsdl, encase you alter the wsdl server
	);

    // Login
	$result = $client->Login(array("username" => $username, "password" =>$password));

	// Get the API Token
	$apiToken = $result->LoginResult->Data->APIToken;

	$auth_client = new MSSoapClient($wsdl, 
	array(
		  "trace"      => 1,		// enable trace to view what is happening
		  "exceptions" => 0,		// disable exceptions
		  "cache_wsdl" => 0) 		// disable any caching on the wsdl, encase you alter the wsdl server
	);

	$auth_client->setToken($apiToken);

	// Makes the call to GetEvents
	$events = $auth_client->GetEvents(array("filter" => 'Title.Contains("Testing")', "orderBy" => ""));

	// TODO: Do something with the results
	
?>

<h2>PHP SOAP Test (Using HTTP Headers)</h2>
<p>
	This is a sample demonstration of calling the RegOnline API using the PHP SoapClient. It first calls the Login method to get the API Token 
	and then passes the token to the GetEvents method via an HTTP Header.  This is accomplished by extending the SoapClient class and using curl behind the scenes
	to make the request so HTTP headers can be appended. 
</p>

<h3>REQUEST</h3>
<div class="xml">
	<?php
		echo  htmlentities($auth_client->__getLastRequest());
	?>
</div>

<h3>RESPONSE</h3>
<div class="xml">
	<?php
		echo  htmlentities($auth_client->__getLastResponse());
	?>
</div>

 </body>
</html>

