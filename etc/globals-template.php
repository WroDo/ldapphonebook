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
//$basedn=array('dmdName=users,dc=foo,dc=fr','dmdName=users,dc=bar,dc=com');  // two basedn
$gLdapBaseDn		=	'TBD';
$gLdapFilter		=	'(&(objectClass=inetOrgPerson)(uid=*))';  // single filter
$gLdapAttributes	=	array('dn','uid','sn');
$gLdapBindUser		=	", , ";
$gLdapBindPassword	=	'something cryptic';
$gLdapServerHost	=	'buead1.intern.hcsn.de';
$gLdapServerPort	=	389;

$gLdapConnections	=	array(
	0	=>	array(
		'server'=>'buead1.intern.hcsn.de', 'port'=>389, 'user'=>'ldap.blah', 'password'=>'SECRET', 'basedn'='TBD', 'filter'='(&(objectClass=inetOrgPerson)(uid=*))', 'attributes'=>array('dn','uid','sn')
				 ),
	# Add more connections if you have multiple directories...
);


# MAKE MULTIPLE SERVERS POSSIBLE!
# COPY TO TEMPLATE!

?>
