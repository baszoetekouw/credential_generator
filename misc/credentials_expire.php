#!/usr/bin/env php
<?php

require_once( dirname(__FILE__) . '/../lib/credentials.php' );

$num = credential_expire();

print "Expired $num credentials from db\n";
exit(0);

?>
