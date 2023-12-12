<!DOCTYPE html>


<?php
/* Includes */
require_once('etc/globals.php');
require_once('etc/internationalization.php');
require_once('include/commonFunctions.php');
require_once('include/commonLogging.php');
require_once('include/commonFiles.php');


/* Some Debugging */
session_start(); /* https://www.php.net/manual/en/function.session-start.php */
say("session_id: " . session_id(), __FILE__, __FUNCTION__, __LINE__, 2);
say("_REQUEST:", __FILE__, __FUNCTION__, __LINE__, 2);
sayArray($_REQUEST, __FILE__, __FUNCTION__, __LINE__, 2);
say("_POST:", __FILE__, __FUNCTION__, __LINE__, 2);
sayArray($_POST, __FILE__, __FUNCTION__, __LINE__, 2);
if (array_key_exists('searchstring', $_POST))
{
	$gSearchString=$_POST['searchstring'];
	say("gSearchString: $gSearchString", __FILE__, __FUNCTION__, __LINE__, 2);
}
say("_FILES:", __FILE__, __FUNCTION__, __LINE__, 2);
sayArray($_FILES, __FILE__, __FUNCTION__, __LINE__, 2);

include('etc/header.php'); // insert header incl. <body>-tag

	/* Form */
	echo("<div style=\"text-align:center\">
	$gShortIntro
	<br/>
		<form method=\"post\">
		  <label>
			$gIntSearchStringLabel
			<input name=\"searchstring\" />
		  </label>
		  <button>$gIntButtonSearchLabel</button>
		</form>	
	</div>
	<!--	<a href=\"index.php\"><button style=\"background:green;color:white;\">$gIntButtonSearchLabel</button></a> -->
	
	</div>");

	/* Result */
	if (isset($gSearchString))
	{
		if(strlen($gSearchString) >= $gSearchStringMinLen)
		{
			echo("	<div style=\"text-align:center\">");

			/* Create Table Head */
			printf($gTableHeadFormat, $gIntTableHeadImage, $gIntTableHeadName, $gIntTableHeadDepartment, $gIntTableHeadPhone, $gIntTableHeadMail);

			/* Get the connections and walk thru them */
			foreach ($gLdapConnections as $gLdapConnectionNumber => $gLdapConnectionArray)
			{
				$lLdapServer=$gLdapConnectionArray['server'];
				$lLdapPort=$gLdapConnectionArray['port'];
				$lLdapUser=$gLdapConnectionArray['user'];
				$lLdapPassword=$gLdapConnectionArray['password'];
				$lLdapBaseDn=$gLdapConnectionArray['basedn'];
				$lLdapFilterFormat=$gLdapConnectionArray['filter'];
				$lLdapDefaultThumbnail=$gLdapConnectionArray['thumbnail'];

				$lLdapFilter=str_replace('%s', $gSearchString, $lLdapFilterFormat); // insert searchstring into format
				say("lLdapFilter: $lLdapFilter", __FILE__, __FUNCTION__, __LINE__, 2);

				/* (Un)main */
				if ($lLdapConnection = ldap_connect($lLdapServer, $lLdapPort)) // does not actually connect, bind does!
				{
					ldap_set_option($lLdapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
					ldap_set_option($lLdapConnection, LDAP_OPT_NETWORK_TIMEOUT, $gLdapTimeout); // default timeout is about 2 minutes, setting it to five seconds

					if (ldap_bind($lLdapConnection, $lLdapUser, $lLdapPassword)) // bind
					{
				
						if($lLdapSearchResult = ldap_search($lLdapConnection, $lLdapBaseDn, $lLdapFilter)) //, $lLdapAttributes)) // initiate search
						{
							$lLdapSearchResultEntries=ldap_get_entries($lLdapConnection, $lLdapSearchResult);
							say("lLdapSearchResultEntries count: " . count($lLdapSearchResultEntries), __FILE__, __FUNCTION__, __LINE__, 2);
							foreach ($lLdapSearchResultEntries as $lLdapSearchResultEntry)
							{
								if (is_array($lLdapSearchResultEntry))
								{
									if (isset($lLdapSearchResultEntry['thumbnailphoto'][0]))
									{
										$lLdapSearchResultUserImageLink="<img src=\"data:image/jpeg;base64," . base64_encode($lLdapSearchResultEntry['thumbnailphoto'][0]) . "\" width=\"$gThumbnailWidth\">"; /**/
									}
									else
									{
										if (!$gUseDefaultForMissingThumbnails)
										{
											$lLdapSearchResultUserImageLink="";
										}
										else
										{
											$lLdapSearchResultUserImageLink="<img src=\"$lLdapDefaultThumbnail\" width=\"$gThumbnailWidthDefault\" >";
										}
									}

									$lTelephonenumbers=arrayToString($lLdapSearchResultEntry, 'telephonenumber');
									$lEmails=getFirstValue($lLdapSearchResultEntry, 'mail');
									
									if (!$gOmitEntriesWithNoPhoneAndEmail || (strlen($lTelephonenumbers)>5 && strlen($lEmails)>5))
									{
										printf($gTableRowFormat, $lLdapSearchResultUserImageLink, arrayToString($lLdapSearchResultEntry, 'cn'), arrayToString($lLdapSearchResultEntry, 'department'), $lTelephonenumbers, $lEmails, $lEmails); //getFirstValue($lLdapSearchResultEntry, 'mail'), getFirstValue($lLdapSearchResultEntry, 'mail'));
									}

									say("lLdapSearchResultEntry: ", __FILE__, __FUNCTION__, __LINE__, 2);
									sayArray($lLdapSearchResultEntry, __FILE__, __FUNCTION__, __LINE__, 2);
								}
								else
								{
									say("lLdapSearchResultEntry is no array (result count?)", __FILE__, __FUNCTION__, __LINE__, 1);
									$lLdapSearchResultEntriesCount=$lLdapSearchResultEntry;
									say("lLdapSearchResultEntriesCount: $lLdapSearchResultEntriesCount", __FILE__, __FUNCTION__, __LINE__, 1);
									printf($gIntSearchResultSummary, $lLdapSearchResultEntriesCount);
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

			/* Create Table Foot */
			printf($gTableFootFormat); //, $gIntTableHeadName, $gIntTableHeadDepartment, $gIntTableHeadPhone, $gIntTableHeadFax,$gIntTableHeadmail);

			echo("	</div>");
		}
		else
		{
			echo("	<div style=\"text-align:center\">");
				echo($gIntSearchStringTooShort);
			echo("	</div>");
		}
	}
	

function arrayToString($aArray, $aKey)
{
	// ldap returns for every value an array, even if ther's only one (f.e. telephonenumbers)
	if (isset($aArray) && is_array($aArray) && array_key_exists($aKey, $aArray))
	{
		$aArray=$aArray[$aKey];
		unset($aArray['count']);

		if (count($aArray) > 1)
		{
			$lResultString=implode('<br/>', $aArray);
		}
		else if (count($aArray) == 0)
		{
			$lResultString="-/-";
		}
		else
		{
			$lResultString=$aArray[0];
		}
	}
	else
	{
		$lResultString="";
	}

	return($lResultString);
}

function getFirstValue($aArray, $aKey)
{
	// ldap returns for every value an array, even if ther's only one (f.e. telephonenumbers)
	if (isset($aArray) && is_array($aArray) && array_key_exists($aKey, $aArray))
	{
		$aArray=$aArray[$aKey];
		unset($aArray['count']);

		$lResultString=$aArray[0];
	}
	else
	{
		$lResultString="";
	}

	return($lResultString);
}


include("etc/footer.php");

?>
