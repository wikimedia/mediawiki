<?php

/**
 * Provides access to MediaWiki's version without requiring MediaWiki (or anything else)
 * being loaded first.
 *
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MediaWikiVersionFetcher {

	/**
	 * Get the MediaWiki version, extracted from the PHP source file where it is defined.
	 *
	 * @return string
	 * @throws RuntimeException
	 */
	public function fetchVersion() {
		$code = file_get_contents( __DIR__ . '/Defines.php' );

		$matches = [];
		preg_match( "/define\( 'MW_VERSION', '([^']+)'/", $code, $matches );

		if ( count( $matches ) !== 2 ) {
			throw new RuntimeException( 'Could not extract the MediaWiki version from Defines.php' );
		}

		return $matches[1];
	}

}
