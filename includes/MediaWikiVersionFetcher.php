<?php

/**
 * Provides access to MediaWiki's version without requiring MediaWiki (or anything else)
 * being loaded first.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MediaWikiVersionFetcher {

	/**
	 * Returns the MediaWiki version, in the format used by MediaWiki's wgVersion global.
	 *
	 * @return string
	 * @throws RuntimeException
	 */
	public function fetchVersion() {
		$defaultSettings = file_get_contents( __DIR__ . '/DefaultSettings.php' );

		$matches = array();
		preg_match( "/wgVersion = '([-0-9a-zA-Z\.]+)';/", $defaultSettings, $matches );

		if ( count( $matches ) !== 2 ) {
			throw new RuntimeException( 'Could not extract the MediaWiki version from DefaultSettings.php' );
		}

		return $matches[1];
	}

}
