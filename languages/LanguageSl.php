<?
# See language.doc
global $IP;
include_once("$IP/Utf8Case.php");

class LanguageSl extends LanguageUtf8 {
	# Inherit everything

	function fallback8bitEncoding() {
		return "windows-1250"; /* or iso 8859-2? */
	}
}

?>
