<?php
/**
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Composer;

use Composer\Composer;
use RuntimeException;

/**
 * Checks the version of Composer that this is being run on, and aborts if not version 2 or later
 *
 * @internal
 * @since 1.42
 */
class VersionChecker {
	public static function onEvent() {
		$version = Composer::VERSION;
		if ( $version === '@package_version@' ) {
			// In Composer 1.9+, unreleased git branches have this value in Composer::VERSION,
			// and Composer::getVersion() was introduced to work around this.
			$version = Composer::getVersion();
		}
		if ( version_compare( $version, '2.0.0', '<' ) ) {
			throw new RuntimeException(
				"MediaWiki requires Composer version 2 or later; version 1"
				. " has been considered end of life since October 2020!"
			);
		}
	}
}
