<?php

include_once( "LanguageUtf8.php" );

class LanguageEl extends LanguageUtf8 {
	function fallback8bitEncoding() {
		return "windows-1253";
	}
}

?>