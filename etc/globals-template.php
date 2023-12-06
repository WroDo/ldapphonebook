<?php 

/* Globals */
$gRootPath          =   "/srv/www/htdocs/ldapphonebook"; /* The root of all evil :) */
$gFileLog           =   "$gRootPath/log/#log.log"; /* # Makes CVS ignore the file */
$gLogLevel          =   2;              /* As usual: 0 = only errors, 1 = warnings, 2 = everything, 3 = even more (floods the log :) ) */
$gLocaleWeb         =   "en_US.utf8";   /* See your LAMP's locale -a */
$gFileLogMaxSize    =   1024*1024*42;   /* 42MB :) */ 
$gSiteLogo			=	"images/companylogo.png";
$gCustomHeaderLines	=	"";
$gSiteLanguage		=	"de"; // switch here to en or make it dependant on browser's language
$gSearchStringMinLen	=	3;
$gLdapTimeout           =   5; // default timeout is about 2 minutes, setting it to five seconds

/* Add one ore more connections here. Might be useful if you have more than von directory (like 2 ADs while a company merge) */
$gLdapConnections	=	array(
	0	=>	array(
		'server'=>'SOMEHOST', 'port'=>389, 'user'=>'LDAPUSER', 'password'=>'SECRET', 'basedn'=>'TBD', 'filter'=>'(&(objectClass=inetOrgPerson)(uid=*))', 'attributes'=>array('dn','uid','sn')
				 ),
	# Add more connections if you have multiple directories...
);


?>
