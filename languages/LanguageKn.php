<?php

require_once( "LanguageUtf8.php" );

/* private */ $wgMonthNamesKn = array(
	'ಜನವರಿ',
	'ಫೆಬ್ರುವರಿ',
	'ಮಾರ್ಚ್',
	'ಎಪ್ರಿಲ್',
	'ಮೇ',
	'ಜೂನ್',
	'ಜುಲೈ',
	'ಆಗಸ್ಟ್',
	'ಸೆಪ್ಟೆಂಬರ್',
	'ಅಕ್ಟೋಬರ್',
	'ನವೆಂಬರ್',
	'ಡಿಸೆಂಬರ್' );

class LanguageKn extends LanguageUtf8 {
	var $digitTransTable = array(
		"0" => "೦",
		"1" => "೧",
		"2" => "೨",
		"3" => "೩",
		"4" => "೪",
		"5" => "೫",
		"6" => "೬",
		"7" => "೭",
		"8" => "೮",
		"9" => "೯"
	);

	function getMonthName( $key ) {
		global $wgMonthNamesKn;
		return $wgMonthNamesKn[$key-1];
	}
	
	function getMonthAbbreviation( $key ) {
		return $this->getMonthName( $key );
	}
	
	function formatNum( $number ) {
		global $wgTranslateNumerals;
		if( $wgTranslateNumerals ) {
			return strtr( $number, $this->digitTransTable );
		} else {
			return $number;
		}
	}
}

?>
