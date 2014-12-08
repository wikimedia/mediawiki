<?php

class ComposerLockComparer {

	/**
	 * @var string
	 */
	private $lock;

	/**
	 * @var string
	 */
	private $json;

	private $jsonContents;

	private $lockContents;

	/**
	 * @param string $json Path to composer.json
	 * @param string $lock Path to composer.lock
	 */
	public function __construct( $json, $lock ) {
		$this->json = $json;
		$this->lock = $lock;
		$this->jsonContents = json_decode( file_get_contents( $this->json ), true );
		$this->lockContents = json_decode( file_get_contents( $this->lock ), true );
	}

	/**
	 * Whether composer.lock's hash is up to date
	 *
	 * @return bool
	 */
	public function isHashUpToDate() {
		return md5_file( $this->json ) === $this->lockContents['hash'];
	}

	/**
	 * Dependencies currently installed according to composer.lock
	 *
	 * @return array
	 */
	public function getInstalledDependencies() {
		$deps = array();
		foreach ( $this->lockContents['packages'] as $installed ) {
			$deps[$installed['name']] = $this->normalizeVersion( $installed['version'] );
		}

		return $deps;
	}

	/**
	 * Dependencies as specified by composer.json
	 *
	 * @return array
	 */
	public function getRequiredDependencies() {
		$deps = array();
		foreach ( $this->jsonContents['require'] as $package => $version ) {
			if ( $package !== "php" ) {
				$deps[$package] = $this->normalizeVersion( $version );
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
	private function normalizeVersion( $version ) {
		if ( strpos( $version, 'v' ) === 0 ) {
			// Composer auto-strips the "v" in front of the tag name
			$version = ltrim( $version, 'v' );
		}

		return $version;
	}
}