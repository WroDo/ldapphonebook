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
foreach ($gLdapConnections as $gLdapConnectionNumber => $gLdapConnectionArray)
{
	$lLdapServer=$gLdapConnectionArray['server'];
	$lLdapPort=$gLdapConnectionArray['port'];
	$lLdapUser=$gLdapConnectionArray['user'];
	$lLdapPassword=$gLdapConnectionArray['password'];
	$lLdapBaseDn=$gLdapConnectionArray['basedn'];
	#$lLdapFilter=$gLdapConnectionArray['filter'];
	#$lLdapAttributes=$gLdapConnectionArray['attributes'];

	#$lLdapFilter='(cn=*)';
	$lLdapFilter='(cn=*tschm*)';
	
	/* (Un)main */
	if ($lLdapConnection = ldap_connect($lLdapServer, $lLdapPort)) // does not actually connect, bind does!
	{
		ldap_set_option($lLdapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($lLdapConnection, LDAP_OPT_NETWORK_TIMEOUT, $gLdapTimeout); // default timeout is about 2 minutes, setting it to five seconds

		if (ldap_bind($lLdapConnection, $lLdapUser, $lLdapPassword)) // bind
		{
			if($lLdapSearchResult = ldap_search($lLdapConnection, $lLdapBaseDn, $lLdapFilter)) //, $lLdapAttributes)) // initiate search
			{
				#var_dump($lLdapSearchResult);
				$lLdapSearchResultEntries=ldap_get_entries($lLdapConnection, $lLdapSearchResult);
				say("lLdapSearchResultEntries count: " . count($lLdapSearchResultEntries), __FILE__, __FUNCTION__, __LINE__, 2);
				//for($i=0; $i<count($lLdapSearchResults); $i++) // walk search results
				foreach ($lLdapSearchResultEntries as $lLdapSearchResultEntry)
				{
					var_dump($lLdapSearchResultEntry);
					if (is_array($lLdapSearchResultEntry))
					{
						print_r($lLdapSearchResultEntry);
						print "\n";
						say("lLdapSearchResultEntry: ", __FILE__, __FUNCTION__, __LINE__, 2);
						sayArray($lLdapSearchResultEntry, __FILE__, __FUNCTION__, __LINE__, 2);
					}
					else
					{
						say("lLdapSearchResultEntry is no array (result count?)", __FILE__, __FUNCTION__, __LINE__, 1);
						$lLdapSearchResultEntriesCount=$lLdapSearchResultEntry;
						say("lLdapSearchResultEntriesCount: $lLdapSearchResultEntriesCount", __FILE__, __FUNCTION__, __LINE__, 1);
					}
				} // walk search results
			}
			else
			{
				say("LDAP search failed (gLdapBaseDn: $lLdapBaseDn, gLdapFilter: $lLdapFilter, gLdapAttributes: implode(',' $lLdapAttributes))", __FILE__, __FUNCTION__, __LINE__, 0);
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
