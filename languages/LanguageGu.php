<?php

require_once( "LanguageUtf8.php" );

class LanguageGu extends LanguageUtf8 {
	var $digitTransTable = array(
		"0" => "૦",
		"1" => "૧",
		"2" => "૨",
		"3" => "૩",
		"4" => "૪",
		"5" => "૫",
		"6" => "૬",
		"7" => "૭",
		"8" => "૮",
		"9" => "૯"
	);

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
