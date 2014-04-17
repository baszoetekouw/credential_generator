<?php

require_once( dirname(__FILE__) . "/../lib/credentials.php" );

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
$refcode = "&ndash;";
$pass    = "&ndash;";
$ldate   = "&ndash;";
$ip      = "&ndash;";

if ( isset($_POST['refcode'] ) )
{
	$refcode = cleanse_refcode($_POST['refcode']);
	$data = credential_fetch($refcode);

	if (!$data['error'])
	{
		$pass = $data['passphrase'];
		$date = $data['date'];
		$ip   = $data['ip'];

		# fix date (is stored as UTC in the db)
		$ldate = new DateTime( $date, new DateTimeZone("UTC") );
		$ldate->setTimezone( new DateTimeZone("Europe/Amsterdam") );
		$ldate = $ldate->format('Y-m-d H:i:s T');
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

<body id="home">

	<h1>SURFconext passphrase administration</h1>

	<form method="post">
	<div>
		Enter reference code: <input type="text" name="refcode" id="cred_refcode"/>
		<input type="submit" id="cred_button"/>
	</div>
	</form>

	<div>
		<table id="cred_results">
		<tr><th>Refcode</th>    <td id="cred_ref" ><?php print $refcode; ?></td></tr>
		<th>Passphrase</th>     <td id="cred_pass"><?php print $pass;    ?></td></tr>
		<th>Generation date</th><td id="cred_date"><?php print $ldate;   ?></td></tr>
		<th>Client IP</th>      <td id="cred_ip"  ><?php print $ip;      ?></td></tr>
		</table>
	</div>
		

	<div><address>
		For more information, please contact <a
			href="mailto:support@surfconext.nl">support@surfconext.nl</a>.
	</address></div>
</body>
</html>
