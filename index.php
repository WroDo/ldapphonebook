<!DOCTYPE html>


<?php
/* Includes */
require_once('etc/globals.php');
require_once('include/commonFunctions.php');
require_once('include/commonLogging.php');
require_once('include/commonFiles.php');
require_once('include/internationalization.php');

//	Echo "Foo";
# https://startutorial.com/view/dropzonejs-php-how-to-build-a-file-upload-form
session_start(); /* https://www.php.net/manual/en/function.session-start.php */
say("session_id: " . session_id(), __FILE__, __FUNCTION__, __LINE__, 2);

//include('cleanupinc.php'); // clean our session files
//include('springcleaning.php'); // cleanup everyone's files

include('header.php'); // insert header incl. <body>-tag

	echo("<div style=\"text-align:center\">
	$gShortIntro
	<br/>
		<a href=\"index.php\"><button style=\"background:green;color:white;\">$gIntButtonSearchLabel</button></a>
	</div>
");

include("footer.php");

?>
