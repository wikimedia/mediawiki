<?php

# Stub for Yiddish

require_once( "LanguageUtf8.php" );

class LanguageYi extends LanguageUtf8 {

	function getDefaultUserOptions() {
		$opt = Language::getDefaultUserOptions();
		$opt["quickbar"] = 2; # Right-to-left
		return $opt;
	}

	# For right-to-left language support
	function isRTL() {
		return true;
	}
}

?>
