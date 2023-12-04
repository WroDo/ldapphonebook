#!/usr/bin/php
<?php

/* Includes */
require_once('etc/globals.php');
require_once('include/commonFunctions.php');
require_once('include/commonLogging.php');
require_once('include/commonFiles.php');
require_once('include/internationalization.php');

/* (Un)main */
if ($lLdapConnection = ldap_connect($gLdapServerHost, $gLdapServerPort)) // connect
{
	ldap_set_option($lLdapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);

	if (ldap_bind($lLdapConnection, $gLdapBindUser, $gLdapBindPassword)) // bind
	{
		if($lLdapSearch = ldap_search($lLdapConnection, $gLdapBaseDn, $gLdapFilter, $gLdapAttributes)) // initiate search
		{
			for($i=0; $i<count($lLdapSearch); $i++) // walk search results
			{
				$lLdapSearchResult=ldap_get_entries($lLdapConnection, $lLdapSearch[$i]);
				print_r($lLdapSearchResult);
				print "\n";
				say("lLdapSearchResult: ", __FILE__, __FUNCTION__, __LINE__, 2);
				sayArray($lLdapSearchResult, __FILE__, __FUNCTION__, __LINE__, 2);
			}
		}
		else
		{
			say("LDAP search failed (gLdapBaseDn: $gLdapBaseDn, gLdapFilter: $gLdapFilter, gLdapAttributes: $gLdapAttributes)", __FILE__, __FUNCTION__, __LINE__, 0);
		}
	}
	else
	{
		say("LDAP bind for user \"$gLdapBindUser\" failed.", __FILE__, __FUNCTION__, __LINE__, 0);
	}
	ldap_close($lLdapConnection);
}
else
{
	say("Connecting \"$gLdapServerHost:$gLdapServerPort\" failed.", __FILE__, __FUNCTION__, __LINE__, 0);
}



?>
