<!DOCTYPE html>


<?php
/* Includes */
require_once('etc/globals.php');
require_once('include/commonFunctions.php');
require_once('include/commonLogging.php');
require_once('include/commonFiles.php');
require_once('include/internationalization.php');


/* Some Debugging */
//$gSearchString="";
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


//include('cleanupinc.php'); // clean our session files
//include('springcleaning.php'); // cleanup everyone's files

include('header.php'); // insert header incl. <body>-tag

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

			/* Get the connections and walk thru them */
			foreach ($gLdapConnections as $gLdapConnectionNumber => $gLdapConnectionArray)
			{
				$lLdapServer=$gLdapConnectionArray['server'];
				$lLdapPort=$gLdapConnectionArray['port'];
				$lLdapUser=$gLdapConnectionArray['user'];
				$lLdapPassword=$gLdapConnectionArray['password'];
				$lLdapBaseDn=$gLdapConnectionArray['basedn'];
				$lLdapFilterFormat=$gLdapConnectionArray['filter'];
				#$lLdapAttributes=$gLdapConnectionArray['attributes'];

				#$lLdapFilter='(cn=*)';
				#$lLdapFilter="(cn=*$gSearchString*)"; // searching just for common name
				$lLdapFilter=sprintf($lLdapFilterFormat, $gSearchString); // insert searchstring into format
				say("lLdapFilter: $lLdapFilter", __FILE__, __FUNCTION__, __LINE__, 2);

				/* (Un)main */
				if ($lLdapConnection = ldap_connect($lLdapServer, $lLdapPort)) // does not actually connect, bind does!
				{
					ldap_set_option($lLdapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
					ldap_set_option($lLdapConnection, LDAP_OPT_NETWORK_TIMEOUT, $gLdapTimeout); // default timeout is about 2 minutes, setting it to five seconds

					if (ldap_bind($lLdapConnection, $lLdapUser, $lLdapPassword)) // bind
					{
						/* Create Table Head */
						printf($gTableHeadFormat, $gIntTableHeadImage, $gIntTableHeadName, $gIntTableHeadDepartment, $gIntTableHeadPhone, $gIntTableHeadMail);
				
						if($lLdapSearchResult = ldap_search($lLdapConnection, $lLdapBaseDn, $lLdapFilter)) //, $lLdapAttributes)) // initiate search
						{
							#var_dump($lLdapSearchResult);
							$lLdapSearchResultEntries=ldap_get_entries($lLdapConnection, $lLdapSearchResult);
							say("lLdapSearchResultEntries count: " . count($lLdapSearchResultEntries), __FILE__, __FUNCTION__, __LINE__, 2);
							//for($i=0; $i<count($lLdapSearchResults); $i++) // walk search results
							foreach ($lLdapSearchResultEntries as $lLdapSearchResultEntry)
							{
								#var_dump($lLdapSearchResultEntry);
								if (is_array($lLdapSearchResultEntry))
								{
									#print_r($lLdapSearchResultEntry);
									#print "\n";
									// SOMEHOW NULL var_dump($lLdapSearchResultEntry['thumbnailPhoto'][0]);
									#var_dump($lLdapSearchResultEntry);
									if (isset($lLdapSearchResultEntry['thumbnailphoto'][0]))
									{
										$lLdapSearchResultUserImageLink="<img src=\"data:image/jpeg;base64," . base64_encode($lLdapSearchResultEntry['thumbnailphoto'][0]) . "\" >"; /**/
										/*$lLdapSearchResultUserImageLink="<img src=\"data:image/jpeg;base64," . base64_encode($lLdapSearchResultEntry['thumbnailphoto'][0]) . " ?>\" />"; /**/
										/* $lLdapSearchResultUserImageLink="<img src=\"data:image/jpeg;base64," . $lLdapSearchResultEntry['thumbnailphoto'][0] . " ?>\" />"; /**/
									}
									else
									{
										if (!$gUseSiteLogoForMissingThumbnails)
										{
											$lLdapSearchResultUserImageLink="";
										}
										else
										{
											$lLdapSearchResultUserImageLink="<img src=\"$gSiteLogo\">";
										}
									}


									//printf($gTableRowFormat, $lLdapSearchResultUserimageLink, $lLdapSearchResultEntry['cn'][0], $lLdapSearchResultEntry['department'][0], $lLdapSearchResultEntry['telephonenumber'][0], $lLdapSearchResultEntry['mail'][0], $lLdapSearchResultEntry['mail'][0]);

									//printf($gTableRowFormat, $lLdapSearchResultUserimageLink, mergeValues($lLdapSearchResultEntry['cn']), mergeValues($lLdapSearchResultEntry['department']), mergeValues($lLdapSearchResultEntry['telephonenumber']), $lLdapSearchResultEntry['mail'][0], $lLdapSearchResultEntry['mail'][0]);

									printf($gTableRowFormat, $lLdapSearchResultUserImageLink, mergeValues($lLdapSearchResultEntry, 'cn'), mergeValues($lLdapSearchResultEntry, 'department'), mergeValues($lLdapSearchResultEntry, 'telephonenumber'), getFirstValue($lLdapSearchResultEntry, 'mail'), getFirstValue($lLdapSearchResultEntry, 'mail'));

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

						/* Create Table Foot */
						printf($gTableFootFormat); //, $gIntTableHeadName, $gIntTableHeadDepartment, $gIntTableHeadPhone, $gIntTableHeadFax,$gIntTableHeadmail);

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
			echo("	</div>");
		}
		else
		{
			echo("	<div style=\"text-align:center\">");
				echo($gIntSearchStringTooShort);
			echo("	</div>");
		}
	}
	

function mergeValues($aArray, $aKey)
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

// https://stackoverflow.com/questions/16937863/display-thumbnailphoto-from-active-directory-in-php/16948219#16948219
// https://stackoverflow.com/questions/16937863/display-thumbnailphoto-from-active-directory-in-php/16948219#16948219
#function saveThumbnail($aArray)
#{
#	$tempFile = tempnam(sys_get_temp_dir(), 'image');
#	file_put_contents($tempFile, $imageString);
#	$finfo = new finfo(FILEINFO_MIME_TYPE);
#	$mime  = explode(';', $finfo->file($tempFile));
#	echo '<img src="data:' . $mime[0] . ';base64,' . base64_encode($imageString) . '"/>';
#
#}


include("footer.php");

?>
