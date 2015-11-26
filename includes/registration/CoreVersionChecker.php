<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

use Composer\Semver\VersionParser;
use Composer\Semver\Constraint\Constraint;

/**
 * @since 1.26
 */
class CoreVersionChecker {

	/**
	 * @var Constraint|bool representing $wgVersion
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
			$this->coreVersion = new Constraint(
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
