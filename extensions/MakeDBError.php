<?php

$wgExtensionFunctions[] = "wfMakeDBErrorExt";

function wfMakeDBErrorExt() {

require_once( "SpecialPage.php" );

class MakeDBErrorPage extends UnlistedSpecialPage
{
	function MakeDBErrorPage() {
		UnlistedSpecialPage::UnlistedSpecialPage("MakeDBError");
	}

	function execute( $par ) {
		$this->setHeaders();
		wfQuery( "test", DB_READ );
	}
}

SpecialPage::addPage( new MakeDBErrorPage );

} # End of extension function

?>
