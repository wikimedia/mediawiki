<?php

# This is a simple example of a special page module
# Given a string in UTF-8, it converts it to HTML entities suitable for 
# an ISO 8859-1 web page.

$wgExtensionFunctions[] = "wfUnicodeConverter";

function wfUnicodeConverter() {

require_once( "includes/SpecialPage.php" );

class UnicodeConverter extends SpecialPage
{
	function UnicodeConverter() {
		SpecialPage::SpecialPage("UnicodeConverter");
	}

	function execute( $par ) {
		global $wgRequest, $wgOut, $wgTitle;

		$this->setHeaders();

		$q = $wgRequest->getText( 'q' );
		$encQ = htmlspecialchars( $q );
		$action = $wgTitle->escapeLocalUrl();
		$ok = htmlspecialchars( wfMsg( "ok" ) );

		$wgOut->addHTML( <<<END
<form name="ucf" method="post" action="$action">
<textarea rows="15" cols="80" name="q">$encQ</textarea><br />
<input type="submit" name="submit" value="$ok" /><br /><br />
</form>
END
);

		if ( !is_null( $q ) ) {
			$html = wfUtf8ToHTML( htmlspecialchars( $q ) );
			$wgOut->addHTML( "\n\n\n" . nl2br( $html ) . "\n<hr />\n" .
			  nl2br( htmlspecialchars( $html ) ) . "\n\n" );
		}
	}
}

global $wgMessageCache;
SpecialPage::addPage( new UnicodeConverter );
$wgMessageCache->addMessage( "unicodeconverter", "Unicode Converter" );

} # End of extension function
?>
