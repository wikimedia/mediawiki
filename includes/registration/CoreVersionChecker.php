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
		$this->coreVersion = $this->getCoreVersionConstraint( $coreVersion );
		$this->versionParser = new VersionParser();
	}

	/**
	 * @param string $coreVersion
	 * @return VersionConstraint
	 */
	private function getCoreVersionConstraint( $coreVersion ) {
		// @todo why is this normalizer necessary?
		$normalizer = new ComposerVersionNormalizer();
		$coreVersion = $normalizer->normalizeSuffix( $coreVersion );
		$coreVersion = $normalizer->normalizeLevelCount( $coreVersion );
		return new VersionConstraint( '==', $coreVersion );
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
