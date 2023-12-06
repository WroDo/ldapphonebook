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
	$gIntSearchStringTooShort	=	"Suchbegriff muss mindestens $gSearchStringMinLen Zeichen lang sein!";
	$gIntSearchResultSummary	=	"Die Suche ergab %d Treffer:<br/>";
}
elseif ($gSiteLanguage=="en")
{ 
	$gSiteName				=	"Phonebook";
	$gShortIntro			=	"<br/>
	Search for name, phonenumber or department.<br/>";
	
	/* index.php Buttons */
	$gIntButtonSearchLabel		=	"Search!";
	$gIntSearchStringLabel		=	"search pattern:";
	$gIntSearchStringTooShort	=	"Search string must be at least $gSearchStringMinLen characters long!";
	$gIntSearchResultSummary	=	"The search returned %d results:<br/>";
}
// add other languages here

?>
