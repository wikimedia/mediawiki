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
 * @since 1.27
 */
class ExtVersionChecker {
	/**
	 * @var ExtensionRegistry
	 */
	private $versionParser;

	/**
	 * @param string $coreVersion Current version of core
	 */
	public function __construct() {
		$this->versionParser = new VersionParser();
	}

	/**
	 * Check all given dependencies if they are compatible with the named
	 * installed extensions in the $credits array.
	 *
	 * Example $extDependencies:
	 *	array (
	 *		'GoogleAPIClient' => array(
	 *			'ext-FakeExtension' => '>= 1.25.0'
	 *		)
	 *	)
	 *
	 * @param array $extDependencies All extensions, that depends on another one
	 * @param array $credits An array of installed extensions with the credits of them
	 * @return bool
	 */
	public function checkArray( array $extDependencies, array $credits ) {
		$incompatible = array();
		foreach ( $extDependencies as $extension => $dependencies ) {
			foreach ( $dependencies as $dependency => $constraint ) {
				// limit to dependencies, that are extensions
				$dependencyName = str_replace( 'ext-', '', $dependency, $replaceCount );
				if ( $replaceCount !== 1 ) {
					// extension dependencies need to be prefixed with "ext-"
					continue;
				}
				// check, if the dependency is installed or not
				if ( !isset( $credits[$dependencyName] ) ) {
					$incompatible[] = "{$extension} requires {$dependencyName} to be installed.";
					continue;
				}
				// check, if the extension has it's version mentioned in extension.json
				// and set as incompatible if not
				if ( !isset( $credits[$dependencyName]['version'] ) ) {
					// if the extension doesn't need a special version of the dependency,
					// just log an info, that the version is missing
					if ( $constraint === '*' ) {
						wfDebug( "{$dependencyName} does not expose it's version, but {$extension}
							mentions it with constraint '*'. Assume it's ok so." );
					} else {
						// otherwise: mark as incompatible
						$incompatible[] = "{$dependencyName} does not expose it's version, but {$extension}
							requires: {$constraint}.";
					}
				}

				// try to get a constraint for the dependency version
				try {
					$installedVersion = new Constraint(
						'==',
						$this->versionParser->normalize( $credits[$dependencyName]['version'] )
					);
				} catch ( UnexpectedValueException $e ) {
					// Non-parsable version, don't fatal.
					continue;
				}
				// finally, check constraint against dependency version
				// check and parse constraint
				if ( !$this->versionParser->parseConstraints( $constraint )->matches( $installedVersion ) ) {
					$incompatible[] = "{$extension} is not compatible with the current "
						. "installed version of {$dependencyName} (version {$credits[$dependencyName]['version']}),"
						. "it requires: " . $constraint . '.';
				}
			}
		}

		return $incompatible;
	}
}
