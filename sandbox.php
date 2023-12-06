#!/usr/bin/php
<?php
/*set_time_limit(30);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);*/

/* Includes */
require_once('etc/globals.php');
require_once('include/commonFunctions.php');
require_once('include/commonLogging.php');
require_once('include/commonFiles.php');
require_once('include/internationalization.php');

/* Get the connections and walk thru them */
for ($gLdapConnections as $gLdapConnectionNumber => $gLdapConnectionArray)
{
	$lLdapServer=$gLdapConnectionArray['server'];
	$lLdapPort=$gLdapConnectionArray['port'];
	$lLdapUser=$gLdapConnectionArray['user'];
	$lLdapPassword=$gLdapConnectionArray['password'];
	$lLdapBaseDn=$gLdapConnectionArray['basedn'];
	$lLdapFilter=$gLdapConnectionArray['filter'];
	$lLdapAttributes=$gLdapConnectionArray['attributes'];
	
	/* (Un)main */
	if ($lLdapConnection = ldap_connect($lLdapServer, $lLdapPort)) // does not actually connect, bind does!
	{
		ldap_set_option($lLdapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($lLdapConnection, LDAP_OPT_NETWORK_TIMEOUT, $gLdapTimeout); // default timeout is about 2 minutes, setting it to five seconds

		if (ldap_bind($lLdapConnection, $lLdapUser, $lLdapPassword)) // bind
		{
			if($lLdapSearch = ldap_search($lLdapConnection, $lLdapBaseDn, $lLdapFilter, $lLdapAttributes)) // initiate search
			{
				for($i=0; $i<count($lLdapSearch); $i++) // walk search results
				{
					$lLdapSearchResult=ldap_get_entries($lLdapConnection, $lLdapSearch[$i]);
					print_r($lLdapSearchResult);
					print "\n";
					say("lLdapSearchResult: ", __FILE__, __FUNCTION__, __LINE__, 2);
					sayArray($lLdapSearchResult, __FILE__, __FUNCTION__, __LINE__, 2);
				} // walk search results
			}
			else
			{
				say("LDAP search failed (gLdapBaseDn: $gLdapBaseDn, gLdapFilter: $gLdapFilter, gLdapAttributes: $gLdapAttributes)", __FILE__, __FUNCTION__, __LINE__, 0);
			} // search
		}
		else
		{
			say("LDAP bind for user \"$lLdapUser\" failed.", __FILE__, __FUNCTION__, __LINE__, 0);
		} // bind
		ldap_close($lLdapConnection);
	}
	else
	{
		say("Connecting \"$lLdapServer:$lLdapPort\" failed.", __FILE__, __FUNCTION__, __LINE__, 0);
	} // connect

} // for connection





?>
