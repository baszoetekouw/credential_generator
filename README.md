# credential_generator
Simple web interface for decentralized passphrase generation

This web application is useful if you regularly need to communicate secure tokens or passphrases over unsecure channels (such as email). 
Instead of sending a passphrase over mail, the application offers a web interface where the user can generate their own passphrase;  
in addition, he is given a reference code.  This reference code can only be 
linked to the original passphrase by the owner of the credential generator, so it is safe to send the reference code over an insecure 
channel.  Furthermore, it is relatively short, so can also be easily conveyed over the phone.

Once the user sends the reference code back to the owner of the credential generator, the owner can log in to the credential generator 
admin interface to look up the corresponding passphrase.  Once it has been looked up once, it will be deleted from the database, so 
even if the application is compromized later, previous passphrases can no longer be obtained.

To install this application, follow the following steps:
 - put this repository somewhere on your webserver (outside the webroot)
 - create a database using the schema in `misc/schema.sql`.  I've only tested MySQL, but I have no reason to believe that other SQL 
   databases won't work.
 - edit `lib/config.php` to your liking
 - add a cronjob to regularly (every 5 or 10 minutes) run `misc/credentials_expire.php`
 - install/config SimpleSAMLphp to handle authorization for the admin interface, and check that the path to your SimpleSAML instance in 
   `/www/admin.php` is correct;  alternatively, you could replace the `do_authz()` function in `www/admin.php` with a custom function 
   (for example, checking the `REMOTE_USER` variable); make sure that the function only returns if an administrator is correctly 
   authenticated and authorized to read passphrases, and that the return value is the username of the logged in user.
 - change the config of the webserver to make the `www/` subdir availabe on the web (see `misc/apache.conf` for inspiration)
 
 After that, pointing your browser to `register.html` should allow you to generate new passphrases, and going to `admin.php` 
 should allow you to look up passphrases for a specific reference code.  Don't forget test that authentication and authorization 
 for `admin.php` work correctly, to make sure no unauthorized used have access to the passphrases!
