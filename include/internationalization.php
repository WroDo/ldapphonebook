<?php
if ($gSiteLanguage=="de")
{ // german
	$gSiteName				=	"Telefonbuch";
	$gShortIntro			=	"<br/>
	Du kannst hier nach Name, Telefonnummer und Abteilung suchen.<br/>
	<br/>";
	
	/* index.php Buttons */
	$gIntButtonSearchLabel		=	"Such!";
	$gIntSearchStringLabel		=	"Suchbegriff:";
}
elseif ($gSiteLanguage=="en")
{ 
	$gSiteName				=	"Phonebook";
	$gShortIntro			=	"<br/>
	Search for name, phonenumber or department.<br/>";
	
	/* index.php Buttons */
	$gIntButtonSearchLabel		=	"Search!";
	$gIntSearchStringLabel		=	"search pattern:";
}
// add other languages here

?>
