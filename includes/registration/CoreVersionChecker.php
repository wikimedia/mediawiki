<?php

use Composer\Semver\VersionParser;
use Composer\Semver\Constraint\VersionConstraint;

/**
 * @since 1.26
 */
class CoreVersionChecker {

	/**
	 * @var VersionConstraint|bool representing $wgVersion
	 */
	private $coreVersion = false;

	/**
	 * @var VersionParser
	 */
	private $versionParser;

	/**
	 * @param string $coreVersion Current version of core
	 */
	public function __construct( $coreVersion ) {
		$this->versionParser = new VersionParser();
		try {
			$this->coreVersion = new VersionConstraint(
				'==',
				$this->versionParser->normalize( $coreVersion )
			);
		} catch ( UnexpectedValueException $e ) {
			// Non-parsable version, don't fatal.
		}
	}

	/**
	 * Check that the provided constraint is compatible with the current version of core
	 *
	 * @param string $constraint Something like ">= 1.26"
	 * @return bool
	 */
	public function check( $constraint ) {
		if ( $this->coreVersion === false ) {
			// Couldn't parse the core version, so we can't check anything
			return true;
		}

		return $this->versionParser->parseConstraints( $constraint )
			->matches( $this->coreVersion );
	}
}
