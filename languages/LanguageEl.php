<?php

require_once( "LanguageUtf8.php" );

/* private */ $wgWeekdayNamesEl = array(
	"Κυριακή", "Δευτέρα", "Τρίτη", "Τετάρτη", "Πέμπτη",
	"Παρασκευή", "Σαββάτο"
);

/* private */ $wgMonthNamesEl = array(
	"Ιανουάριος", "Φεβρουάριος", "Μάρτιος", "Απρίλιος", "Μάϊος", "Ιούνιος",
	"Ιούλιος", "Αύγουστος", "Σεπτέμβριος", "Οκτώβριος", "Νοέμβριος",
	"Δεκέμβριος" 
);

/* private */ $wgMonthAbbreviationsEl = array(
	"Ιαν". "Φεβρ", "Μάρτ", "Απρ", "Μάïος", "Ιούν", "Ιούν",
	"Ιούλ", "Αύγ", "Σεπτ", "Οκτώβ", "Νοέμβ", "Δεκέμ"
);

class LanguageEl extends LanguageUtf8 {
	function fallback8bitEncoding() {
		return "windows-1253";
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesEl;
		return $wgMonthNamesEl[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsEl;
		return $wgMonthAbbreviationsEl[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesEl;
		return $wgWeekdayNamesEl[$key-1];
	}

}

?>
