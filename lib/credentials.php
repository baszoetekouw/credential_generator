<?php

require 'config.php';


// all credential handling is done in this file

// report an error
function _error($error,$throw=true)
{
	error_log($error);
	if ($throw) throw new Exception($error);
}

// generates a new passphrase ans saves it in the database
function _get_passphrase()
{
	global $CRED_LENGTH;
	global $CRED_SEPLEN;
	global $CRED_CHARS;

	$passphrase='';
	$numchars=strlen($CRED_CHARS);
	for ($i=0; $i<$CRED_LENGTH; $i++)
	{
		# add a separator every $CRED_SEPLEN chars
		if ($i!=0 && $CRED_SEPLEN>0 && $i%$CRED_SEPLEN==0) $passphrase.='-';

		# add random char
		$r = mt_rand(0,$numchars-1);
		$c = substr($CRED_CHARS,$r,1);
		$passphrase.=$c;
	}

	return $passphrase;
}

// calculate a reference code
function _get_refcode()
{
	global $CRED_REFLEN;

	$refcode='';
	for ($i=0; $i<$CRED_REFLEN; $i++)
	{
		$r = mt_rand(0,35);

		# use 0-9 and a-z
		if ($r<10) $c=$r;
		else       $c=chr(ord('a')+$r-10);

		$refcode="$refcode$c";
	}

	return $refcode;
}


function _db_open()
{
	global $MYSQL_HOST;
	global $MYSQL_USER;
	global $MYSQL_PASS;
	global $MYSQL_DB;

	$db = new mysqli($MYSQL_HOST,$MYSQL_USER,$MYSQL_PASS,$MYSQL_DB);
	if (mysqli_connect_error())
	{
		_error("Failed to connect to MySQL: " . mysqli_connect_error());
	}
	
	return $db;
}

function _db_store($db,$passphrase,$refcode,$ip)
{
	$sql = 'INSERT INTO credentials (passphrase,refcode,generation_ip) VALUES (?,?,?)';

	$st = $db->prepare($sql);
	if ( $st===false || $st->bind_param('sss',$passphrase,$refcode,$ip)==false )
	{
		_error("Failed to prepare INSERT query: " . $st->error);
	}

	$result = $st->execute();
	if (!$result)
	{
		_error("Failed to execute INSERT query: " . $st->error, false);
	}
	return $result;
}

function _db_fetch($db,$refcode)
{
	$sql = 'SELECT refcode,passphrase,generation_date,generation_ip '
	      .'FROM credentials WHERE refcode=?';

	$st = $db->prepare($sql);
	if ( $st===false )
		_error("Failed to prepare SELECT query: " . $db->error);

	if ( $st->bind_param('s',$refcode)===false )
		_error("Failed to bind SELECT query: " . $db->error);

	$result = $st->execute();
	if (!$result)
		_error("Failed to execute SELECT query: " . $st->error);

	$res = $st->bind_result($refcode,$pass,$date,$ip);
	if ($res===false)
		_error("Failed to execute SELECT query: " . $st->error);

	$res = $st->fetch();
	if ($res===false)
		_error("Failed to fetch SELECT query: " . $st->error);
	if ($res===null) 
		return array();

	return array(
		'passphrase' => $pass,
		'refcode'    => $refcode,
		'date'       => $date,
		'ip'         => $ip
	);
}

function _db_close($db)
{
	if ($db) $db->close();
}


function credential_generate($src_ip)
{
	$db=null;

	try 
	{
		$db = _db_open();

		// try to generate and store a unique pass/refcode for a number of 
		// times
		$i=1;
		do
		{
			// new credential/refcode
			$pass = _get_passphrase();
			$ref  = _get_refcode($pass);

			// store in the db 
			// result will be false if insert failed (i.e., refcode wasn't unique)
			$result = _db_store($db,$pass,$ref,$src_ip);

			if ($i++>=10)
			{
				$error = "Failed to generate unique pass/ref in ".($i-1)." tries, giving up";
				error_log($error);
				throw new Exception($error);
			}
		}
		while (!$result);

		$data = array( 'error' => false, 'passphrase' => $pass, 'refcode' => $ref );
	}
	catch (Exception $e)
	{
		$data = array( 'error' => true );
	}

	_db_close($db);
	return $data;
}

// fetched a credential with specified refcode
function credential_fetch($refcode)
{
	try
	{
		$db = _db_open();
		$data = _db_fetch($db,$refcode);

		$data['error'] = isset($data['passphrase']) ? 0 : 1;
	}
	catch (Exception $e)
	{
		$data = array( 'error' => 1 );
	}

	_db_close($db);
	return $data;
}

?>
