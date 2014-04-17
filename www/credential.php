<?php

include( dirname(__FILE__) . '/../lib/credentials.php' );

// poor man's access control
$referer  = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
$remoteip = $_SERVER['REMOTE_ADDR'];
$myserver = $_SERVER['SERVER_NAME'];
$refdata  = parse_url($referer);

if ($remoteip!=='127.0.0.1' && $remoteip!=='::1' 
	&& (!$referer || $refdata['host']!==$myserver) )
{
	http_response_code(403);
	exit();
}


// generate a new credential
$cred = credential_generate($remoteip);
if ($cred['error'])
{
	http_response_code(500);
	exit();
}

// return result
header("Content-Type: application/json; charset=us-ascii");
print json_encode($cred, JSON_UNESCAPED_SLASHES |  JSON_PRETTY_PRINT);
print "\n";


?>
