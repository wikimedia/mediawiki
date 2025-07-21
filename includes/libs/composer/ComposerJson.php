<?php

namespace Wikimedia\Composer;

/**
 * Reads a composer.json file and provides accessors to get
 * its hash and the required dependencies
 *
 * @since 1.25
 */
class ComposerJson {
	/**
	 * @var array[]
	 */
	private $contents;

	/**
	 * @param string $location
	 */
	public function __construct( $location ) {
		$this->contents = json_decode( file_get_contents( $location ), true );
	}

	/**
	 * Dependencies as specified by composer.json
	 *
	 * @return string[]
	 */
	public function getRequiredDependencies() {
		$deps = [];
		if ( isset( $this->contents['require'] ) ) {
			foreach ( $this->contents['require'] as $package => $version ) {
				// Examples of package dependencies that don't have a / in the name:
				// php, ext-xml, composer-plugin-api
				if ( str_contains( $package, '/' ) ) {
					$deps[$package] = self::normalizeVersion( $version );
				}
			}
		}

		return $deps;
	}

	/**
	 * Strip a leading "v" from the version name
	 *
	 * @param string $version
	 * @return string
	 */
	public static function normalizeVersion( $version ) {
		// Composer auto-strips the "v" in front of the tag name
		return ltrim( $version, 'v' );
	}

}
