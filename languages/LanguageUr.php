<?php

# Stub for Urdu

require_once( "LanguageUtf8.php" );

class LanguageUr extends LanguageUtf8 {

	function getDefaultUserOptions() {
		$opt = Language::getDefaultUserOptions();
		$opt["quickbar"] = 2; # Right-to-left
		$opt["underline"] = 0; # Underline is hard to read in Arabic script
		return $opt;
	}

	# For right-to-left language support
	function isRTL() {
		return true;
	}
}

?>
