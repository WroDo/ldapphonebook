<!DOCTYPE html>


<?php
/* Includes */
require_once('etc/globals.php');
require_once('include/commonFunctions.php');
require_once('include/commonLogging.php');
require_once('include/commonFiles.php');
require_once('include/internationalization.php');


/* Some Debugging */
session_start(); /* https://www.php.net/manual/en/function.session-start.php */
say("session_id: " . session_id(), __FILE__, __FUNCTION__, __LINE__, 2);
say("_REQUEST:", __FILE__, __FUNCTION__, __LINE__, 2);
sayArray($_REQUEST, __FILE__, __FUNCTION__, __LINE__, 2);
say("_POST:", __FILE__, __FUNCTION__, __LINE__, 2);
sayArray($_POST, __FILE__, __FUNCTION__, __LINE__, 2);
say("_FILES:", __FILE__, __FUNCTION__, __LINE__, 2);
sayArray($_FILES, __FILE__, __FUNCTION__, __LINE__, 2);


//include('cleanupinc.php'); // clean our session files
//include('springcleaning.php'); // cleanup everyone's files

include('header.php'); // insert header incl. <body>-tag

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
");

include("footer.php");

?>
