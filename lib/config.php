<?php
// config

// length of the passphrase to generate
$CRED_LENGTH = 24;

// add a separator in the pasephrase every N chars 
// (set to 0 for no separator)
$CRED_SEPLEN = 4;

// characters to use in the passphrase
$CRED_CHARS = '0123456789~!@#$%^&*()_+={}[]|:;<>,./?'
	.'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

// length of reference code
$CRED_REFLEN = 6;

// MySql credentials
$MYSQL_HOST = 'localhost';
$MYSQL_USER = 'user';
$MYSQL_PASS = 'gehe1m';
$MYSQL_DB   = 'credentials';

// Timezone to use for displaying dates etc
$TZ = 'Europe/Amsterdam';

// expirations
// credentials expire in two different ways:
//  - a number of DAYS after they are generated
//  - a number of MINUTES after they are viewed by an admin
// (whichever comes first)
$CRED_EXP_GENERATE = 14; // in DAYS!
$CRED_EXP_VIEW     = 15; // in MINUTES!

?>
