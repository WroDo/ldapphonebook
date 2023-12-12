<?php
echo("
<html>
	<head>   
		<meta charset=\"utf-8\">
		<title>$gSiteName</title>
		<link rel=\"stylesheet\" href=\"main.css\" type=\"text/css\">
");
if (strlen($gCustomHeaderLines)>0) { echo($gCustomHeaderLines); }
echo("
	</head>
	<body>
		<img style=\"float: right; margin: 0px 0px 15px 15px;\" src=\"$gSiteLogo\" height=\"60\" />
		<center><h1>$gSiteName</h1></center>
");
?>
