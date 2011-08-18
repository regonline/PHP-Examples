<html>
 <head>
  <title>PHP SOAP Test</title>
  <style type="text/css">
	body, html {font-family: Helvetica, Verdana, Arial; margin:10px; padding:0px; font-size:12px;}
	div.xml {height:200px;width:100%;overflow:auto;border:solid 1px #999;}
  </style>
 </head>
 <body>

 <?php 

	$wsdl = "https://www.regonline.com/api/default.asmx?wsdl";
		
    $client = new SoapClient($wsdl, 
	array(
		  "trace"      => 1,		// enable trace to view what is happening
		  "exceptions" => 0,		// disable exceptions
		  "cache_wsdl" => 0) 		// disable any caching on the wsdl, encase you alter the wsdl server
	);

    // Login
	$result = $client->Login(array("username" => "<USERNAME>", "password" =>"<PASSWORD>"));

	// Get the API Token
	$apiToken = $result->LoginResult->Data->APIToken;

	// Setup the SOAP Header
	$authHeader = new AuthHeader();
	$authHeader->APIToken = $apiToken;

	$header[] = new SoapHeader('http://www.regonline.com/api', 
                            'TokenHeader',
                            $authHeader, false
							);
		
	$client->__setSoapHeaders($header);

	// Makes the call to GetEvents
	$events = $client->GetEvents(array("filter" => 'Title.Contains("Testing")', "orderBy" => ""));

	// TODO: Do something with the results

	// Class for passing the APIToken
	class AuthHeader
	{
	  var $APIToken;
  
	  function __construct()
	  {

	  }
	}

?>

<h2>PHP SOAP Test</h2>
<p>
	This is a sample demonstration of calling the RegOnline API using the PHP SoapClient. It first calls the Login method to get the API Token 
	and then passes the token to the GetEvents method via a SOAP Header. 
</p>

<h3>REQUEST</h3>
<div class="xml">
	<?php
		echo  htmlentities($client->__getLastRequest());
	?>
</div>

<h3>RESPONSE</h3>
<div class="xml">
	<?php
		echo  htmlentities($client->__getLastResponse());
	?>
</div>

 </body>
</html>

