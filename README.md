# ldapphonebook
## About
Web tool to query your LDAP/AD directory. Might be a useful addition to your company's intranet. :-)

## ToDo
- filter by OU/mail whatever to reduce results (f.e. admin-accounts)
- take over the world, Pinky!

## Installation
- set up apache2, nginx with php and php-ldap
- ```git clone git@github.com:/WroDo/ldapphonebook```
- edit etc/globals.php
- upload a nice company logo
- add some .css
- enjoy!


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
