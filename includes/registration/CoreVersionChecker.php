<?php

use Composer\Package\Version\VersionParser;
use Composer\Package\LinkConstraint\VersionConstraint;

/**
 * @since 1.26
 */
class CoreVersionChecker {

	/**
	 * @var VersionConstraint representing $wgVersion
	 */
	private $coreVersion;

	/**
	 * @var VersionParser
	 */
	private $versionParser;

	/**
	 * @param string $coreVersion Current version of core
	 */
	public function __construct( $coreVersion ) {
		$this->coreVersion = new VersionConstraint( '=', $coreVersion );
		$this->versionParser = new VersionParser();
	}

	/**
	 * Check that the provided constraint is compatible with the current version of core
	 *
	 * @param string $constraint
	 * @return bool
	 */
	public function check( $constraint ) {
		$constraint = $this->versionParser->parseConstraints( $constraint );
		return $constraint->matches( $this->coreVersion );
	}
}
