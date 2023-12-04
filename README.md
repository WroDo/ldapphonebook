# ldapphonebook
## About
Web tool to query your LDAP/AD directory. Might be a useful addition to your company's intranet. :-)

## ToDo
- add query form
- query (multiple) ldap servers
- take over the world, Pinky!


## Known issues
If you get something like this...
````
PHP Fatal error:  Uncaught Error: Call to undefined function ldap_connect() in /srv/www/htdocs/ldapphonebook/sandbox.php:12
Stack trace:
#0 {main}
  thrown in /srv/www/htdocs/ldapphonebook/sandbox.php on line 12
````
...make sure you enable ldap in your php.ini after something like ```zypper in php-ldap```.

#EOF
