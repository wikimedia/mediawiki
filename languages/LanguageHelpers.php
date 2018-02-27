<?php

class LanguageHelpers {

	public static function isRTL( $code ) {
		// @todo: file_get_contents call should happen once.
		global $IP;
		$metadata = unserialize( file_get_contents( "$IP/languages/metadata.php" ) );
		return array_search( $code, $metadata['isRTL'] );
	}
}
