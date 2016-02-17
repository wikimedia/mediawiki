<?php

/**
 * Reads a composer.json file and provides accessors to get
 * its hash and the required dependencies
 *
 * @since 1.25
 */
class ComposerJson {

	/**
	 * @param string $location
	 */
	public function __construct( $location ) {
		$this->hash = md5_file( $location );
		$this->contents = json_decode( file_get_contents( $location ), true );
	}

	public function getHash() {
		return $this->hash;
	}

	/**
	 * Dependencies as specified by composer.json
	 *
	 * @return array
	 */
	public function getRequiredDependencies() {
		$deps = [];
		if ( isset( $this->contents['require'] ) ) {
			foreach ( $this->contents['require'] as $package => $version ) {
				if ( $package !== "php" && strpos( $package, 'ext-' ) !== 0 ) {
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
		if ( strpos( $version, 'v' ) === 0 ) {
			// Composer auto-strips the "v" in front of the tag name
			$version = ltrim( $version, 'v' );
		}

		return $version;
	}

}
