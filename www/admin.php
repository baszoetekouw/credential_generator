<?php

require_once( dirname(__FILE__) . "/../lib/config.php" );
require_once( dirname(__FILE__) . "/../lib/credentials.php" );

# autothorization
function do_authz()
{
	# use simplesaml
	require_once( dirname(__FILE__) . "/../../simplesamlphp/lib/_autoload.php");

	# require login via management VO
	$as = new SimpleSAML_Auth_Simple('vo-sp');
	$as->requireAuth();

	# make sure we have an eppn
	$eppn = 'urn:mace:dir:attribute-def:eduPersonPrincipalName';
	$attr = $as->getAttributes();
	if ( !array_key_exists($eppn,$attr) || !isset($attr[$eppn])
   		|| !is_array($attr[$eppn]) || count($attr[$eppn])<1 )
	{
		print "<pre>Error: no eduPersonPrincipleName received from IdP</pre>";
		exit();
	}
	return $attr[$eppn][0];
}

function cleanse_refcode($refcode)
{
	// should be exactly 6 chars in set [0-9a-z]
	$refcode = substr($refcode,0,6);
	$refcode = str_pad($refcode,'0',STR_PAD_LEFT);
	$refcode = strtolower($refcode);
	$refcode = preg_replace('/[^0-9a-z]/','_',$refcode);
	return $refcode;
}

// default values
$refcode  = "&ndash;";
$pass     = "&ndash;";
$ldate    = "&ndash;";
$ip       = "&ndash;";
$viewedby = "&ndash;";
$lvdate   = "&ndash;";
$user     = htmlspecialchars(do_authz());

if ( isset($_POST['refcode'] ) )
{
	$refcode = cleanse_refcode($_POST['refcode']);
	$data = credential_fetch($refcode,$user);

	if (!$data['error'])
	{
		$ip       = htmlspecialchars($data['ip']);
		$date     = $data['date'];
		$viewdate = $data['view_date'];

		if ($data['passphrase'])
			$pass = htmlspecialchars($data['passphrase']);
		else
			$pass = '[expired]';
		if ($data['viewed_by']) 
			$viewedby = htmlspecialchars($data['viewed_by']);

		# fix date formatting
		$ldate = new DateTime( $date, new DateTimeZone($TZ) );
		$ldate = $ldate->format('Y-m-d H:i:s T');
		$ldate = htmlspecialchars($ldate);

		if ($viewdate) 
		{
			$lvdate = new DateTime( $viewdate, new DateTimeZone($TZ) );
			$lvdate = $lvdate->format('Y-m-d H:i:s T');
			$lvdate = htmlspecialchars($lvdate);
		}

	}
	else
	{
		$pass  = "Not found";
	}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>SURFconext passphrase administration</title>
	<link rel="stylesheet" href="res/style.css"/>
</head>

<body class="home">
<div class="wrapper">

	<h1>SURFconext passphrase administration</h1>

	<form method="post">
	<div class="main">
		<h2>Credential lookup</h2>
		<p>
			Enter reference code: <input type="text" name="refcode" id="cred_refcode"/>
			<input type="submit" id="submit_button"/>
		</p>
	</div>
	</form>

	<div class="main">
		<h2>Results</h2>
		<table id="cred_results">
		<tr><th>Refcode</th>        <td id="cred_ref" ><?php print $refcode; ?></td></tr>
		<tr><th>Passphrase</th>     <td id="cred_pass"><?php print $pass;    ?></td></tr>
		<tr><th>Generation date</th><td id="cred_date"><?php print $ldate;   ?></td></tr>
		<tr><th>Client IP</th>      <td id="cred_ip"  ><?php print $ip;      ?></td></tr>
		<tr><th>Viewed by</th>      <td id="cred_vb"  ><?php print $viewedby;?></td></tr>
		<tr><th>View date</th>      <td id="cred_vd"  ><?php print $lvdate;  ?></td></tr>
		</table>
	</div>
		

	<div class="main">
		<h2>More information</h2>
		<address>
		For more information, please contact <a
			href="mailto:support@surfconext.nl">support@surfconext.nl</a>.
		</address>
	</div>
</body>
</html>
