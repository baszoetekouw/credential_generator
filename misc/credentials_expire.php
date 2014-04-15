#!/usr/bin/env php
<?php

require('../lib/credentials.php');

$num = credential_expire();

print "Expired $num credentials from db\n";
exit(0);

?>
