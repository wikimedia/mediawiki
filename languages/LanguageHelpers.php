<?php

require __DIR__ . '/metadata.php';

class LanguageHelpers {

	public static function isRTL( $code ) {
		global $wgLanguageMetaData;
		return array_search( $code, $wgLanguageMetaData['isRTL'] );
	}
}
