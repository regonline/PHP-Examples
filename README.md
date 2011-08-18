INTRODUCTION
===========================================================================
This project contains several working PHP examples using the RegOnline
API. There is an example using cURL and one using SOAP which passes the API 
Token in via a HTTP Header.  These examples are detailed below.

A few notes about the examples:

* These examples are just for demonstration purposes. Please make sure your 
actual implementation has better error handling, caching, logging, etc.  
* In order for these to work, please make sure and update the <USERNAME>, 
<PASSWORD>, and <EVENTID> variables where appropriate. 
* One stumbling block we ran across getting the PHP examples working 
related to using cURL to access the API over SSL (which is required). There 
are 2 options to get around this problem. You can either set the option to
not verify the certificate curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
or you can download the certificate and add it to the cURL reqquest. You 
can read more about this here: 
http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/

  
CURL EXAMPLE (CURL_TEST.PHP)
============================================================================
This example shows how to use cURL to call the Login method to obtain your 
API Token and then call the GetRegistrationsForEvent method to pull back 
registration records for a given event ID.  

The API Token is passed in via the HTTP Header on the cURL request.


  
SOAP EXAMPLE USING HTTP HEADERS (SOAP_HTTPHEADER_TEST.PHP)
============================================================================
This example shows how to construct a SOAP request using the SoapClient 
class. It first calls the Login method to obtain an API Token. 

Once we have the API Token we call the GetEvents method to pull back events 
for the given user's account.

The PHP SoapClient doesn't natively support HTTP Headers so the workaround 
for this is to extend the SoapClient class and override the __doRequest 
method to do a cURL request.  This allows you to append HTTP Headers to the 
request in order to pass in the API Token.

SOAP HEADERS (SOAP_TEST.PHP)
============================================================================
This example demonstrates calling the RegOnline API using SOAP Headers and the 
default SoapClient class in PHP.

The example is similar to SOAP_HTTPHEADER_TEST.PHP except it uses SOAP Headers 
to pass in the API Token instead of HTTP Headers.
