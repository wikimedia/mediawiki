<?php

require_once( "LanguageUtf8.php" );

/* private */ $wgAllMessagesKn = array(
'jan' => "ಜನವರಿ",
'feb' => "ಫೆಬ್ರುವರಿ",
'mar' => "ಮಾರ್ಚ್",
'apr' => "ಎಪ್ರಿಲ್",
'may' => "ಮೇ",
'jun' => "ಜೂನ್",
'jul' => "ಜುಲೈ",
'aug' => "ಆಗಸ್ಟ್",
'sep' => "ಸೆಪ್ಟೆಂಬರ್",
'oct' => "ಅಕ್ಟೋಬರ್",
'nov' => "ನವೆಂಬರ್",
'dec' => "ಡಿಸೆಂಬರ್",
);

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

	function getMessage( $key ) {
		global $wgAllMessagesKn;
		if( array_key_exists( $key, $wgAllMessagesKn ) )
			return $wgAllMessagesKn[$key];
		else
			return Language::getMessage($key);
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
