<?php

require_once( "LanguageUtf8.php" );

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
