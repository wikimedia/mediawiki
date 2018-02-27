<?php

require __DIR__ . '/metadata.php';

class LanguageHelpers {

	public static function isRTL( $code ) {
		return array_search( $code, LANGUAGE_CONSTANTS['isRTL'] );
	}
}
