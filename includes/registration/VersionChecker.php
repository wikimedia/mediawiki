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
 *
 * @author Legoktm
 * @author Florian Schmidt
 */

use Composer\Semver\VersionParser;
use Composer\Semver\Constraint\Constraint;

/**
 * Provides functions to check a set of extensions with dependencies against
 * a set of loaded extensions and given version information.
 *
 * @since 1.29
 */
class VersionChecker {
	/**
	 * @var Constraint|bool representing $wgVersion
	 */
	private $coreVersion = false;

	/**
	 * @var VersionParser
	 */
	private $versionParser;

	public function __construct() {
		$this->versionParser = new VersionParser();
	}

	/**
	 * Set MediaWiki core version.
	 *
	 * @param string $coreVersion Current version of core
	 * @return VersionChecker $this
	 */
	public function setCoreVersion( $coreVersion ) {
		try {
			$this->coreVersion = new Constraint(
				'==',
				$this->versionParser->normalize( $coreVersion )
			);
			$this->coreVersion->setPrettyString( $coreVersion );
		} catch ( UnexpectedValueException $e ) {
			// Non-parsable version, don't fatal.
		}

		return $this;
	}

	/**
	 * Check all given dependencies if they are compatible with the named
	 * installed extensions in the $credits array.
	 *
	 * Example $extDependencies:
	 *	{
	 *		'GoogleAPIClient' => {
	 *			'MediaWiki' => '>= 1.25.0'
	 *		}
	 *	}
	 *
	 * @param array $extDependencies All extensions that depend on other ones
	 * @return array
	 */
	public function checkArray( array $extDependencies ) {
		$errors = [];
		foreach ( $extDependencies as $extension => $dependencies ) {
			foreach ( $dependencies as $dependencyType => $values ) {
				switch ( $dependencyType ) {
					case ExtensionRegistry::MEDIAWIKI_CORE:
						$errors = array_merge(
							$errors,
							$this->handleMediaWikiDependency( $values, $extension )
						);
						break;
					default:
						throw new UnexpectedValueException( 'Dependency type ' . $dependencyType .
							' unknown in ' . $extension );
				}
			}
		}

		return $errors;
	}

	/**
	 * Handle a dependency to MediaWiki core. It will check, if a MediaWiki version constraint was
	 * set with self::setCoreVersion before this call (if not, it will return an empty array) and
	 * checks the version constraint given against it.
	 *
	 * @param string $constraint The required version constraint for this dependency
	 * @param string $checkedExt The Extension, which depends on this dependency
	 * @return array An empty array, if MediaWiki version is compatible with $constraint, an array
	 *  with an error message, otherwise.
	 */
	private function handleMediaWikiDependency( $constraint, $checkedExt ) {
		if ( $this->coreVersion === false ) {
			// Couldn't parse the core version, so we can't check anything
			return [];
		}

		// if the installed and required version are compatible, return an empty array
		if ( $this->versionParser->parseConstraints( $constraint )
			->matches( $this->coreVersion ) ) {
			return [];
		}
		// otherwise mark this as incompatible.
		return [ "{$checkedExt} is not compatible with the current "
		         . "MediaWiki core (version {$this->coreVersion->getPrettyString()}), it requires: " . $constraint
		         . '.' ];
	}
}
