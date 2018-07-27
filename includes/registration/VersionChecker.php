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
	 * @var array Loaded extensions
	 */
	private $loaded = [];

	/**
	 * @var VersionParser
	 */
	private $versionParser;

	/**
	 * @param string $coreVersion Current version of core
	 */
	public function __construct( $coreVersion ) {
		$this->versionParser = new VersionParser();
		$this->setCoreVersion( $coreVersion );
	}

	/**
	 * Set an array with credits of all loaded extensions and skins.
	 *
	 * @param array $credits An array of installed extensions with credits of them
	 * @return VersionChecker $this
	 */
	public function setLoadedExtensionsAndSkins( array $credits ) {
		$this->loaded = $credits;

		return $this;
	}

	/**
	 * Set MediaWiki core version.
	 *
	 * @param string $coreVersion Current version of core
	 */
	private function setCoreVersion( $coreVersion ) {
		try {
			$this->coreVersion = new Constraint(
				'==',
				$this->versionParser->normalize( $coreVersion )
			);
			$this->coreVersion->setPrettyString( $coreVersion );
		} catch ( UnexpectedValueException $e ) {
			// Non-parsable version, don't fatal.
		}
	}

	/**
	 * Check all given dependencies if they are compatible with the named
	 * installed extensions in the $credits array.
	 *
	 * Example $extDependencies:
	 *     {
	 *       'FooBar' => {
	 *         'MediaWiki' => '>= 1.25.0',
	 *         'extensions' => {
	 *           'FooBaz' => '>= 1.25.0'
	 *         },
	 *         'skins' => {
	 *           'BazBar' => '>= 1.0.0'
	 *         }
	 *       }
	 *     }
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
						$mwError = $this->handleMediaWikiDependency( $values, $extension );
						if ( $mwError !== false ) {
							$errors[] = [
								'msg' => $mwError,
								'type' => 'incompatible-core',
							];
						}
						break;
					case 'extensions':
					case 'skins':
						foreach ( $values as $dependency => $constraint ) {
							$extError = $this->handleExtensionDependency(
								$dependency, $constraint, $extension, $dependencyType
							);
							if ( $extError !== false ) {
								$errors[] = $extError;
							}
						}
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
	 * @return bool|string false if no error, or a string with the message
	 */
	private function handleMediaWikiDependency( $constraint, $checkedExt ) {
		if ( $this->coreVersion === false ) {
			// Couldn't parse the core version, so we can't check anything
			return false;
		}

		// if the installed and required version are compatible, return an empty array
		if ( $this->versionParser->parseConstraints( $constraint )
			->matches( $this->coreVersion ) ) {
			return false;
		}
		// otherwise mark this as incompatible.
		return "{$checkedExt} is not compatible with the current "
			. "MediaWiki core (version {$this->coreVersion->getPrettyString()}), it requires: "
			. "$constraint.";
	}

	/**
	 * Handle a dependency to another extension.
	 *
	 * @param string $dependencyName The name of the dependency
	 * @param string $constraint The required version constraint for this dependency
	 * @param string $checkedExt The Extension, which depends on this dependency
	 * @param string $type Either 'extensions' or 'skins'
	 * @return bool|array false for no errors, or an array of info
	 */
	private function handleExtensionDependency( $dependencyName, $constraint, $checkedExt,
		$type
	) {
		// Check if the dependency is even installed
		if ( !isset( $this->loaded[$dependencyName] ) ) {
			return [
				'msg' => "{$checkedExt} requires {$dependencyName} to be installed.",
				'type' => "missing-$type",
				'missing' => $dependencyName,
			];
		}
		// Check if the dependency has specified a version
		if ( !isset( $this->loaded[$dependencyName]['version'] ) ) {
			// If we depend upon any version, and none is set, that's fine.
			if ( $constraint === '*' ) {
				wfDebug( "{$dependencyName} does not expose its version, but {$checkedExt}"
					. " mentions it with constraint '*'. Assume it's ok so." );
				return false;
			} else {
				// Otherwise, mark it as incompatible.
				$msg = "{$dependencyName} does not expose its version, but {$checkedExt}"
					. " requires: {$constraint}.";
				return [
					'msg' => $msg,
					'type' => "incompatible-$type",
					'incompatible' => $checkedExt,
				];
			}
		} else {
			// Try to get a constraint for the dependency version
			try {
				$installedVersion = new Constraint(
					'==',
					$this->versionParser->normalize( $this->loaded[$dependencyName]['version'] )
				);
			} catch ( UnexpectedValueException $e ) {
				// Non-parsable version, output an error message that the version
				// string is invalid
				return [
					'msg' => "$dependencyName does not have a valid version string.",
					'type' => 'invalid-version',
				];
			}
			// Check if the constraint actually matches...
			if (
				!$this->versionParser->parseConstraints( $constraint )->matches( $installedVersion )
			) {
				$msg = "{$checkedExt} is not compatible with the current "
					. "installed version of {$dependencyName} "
					. "({$this->loaded[$dependencyName]['version']}), "
					. "it requires: " . $constraint . '.';
				return [
					'msg' => $msg,
					'type' => "incompatible-$type",
					'incompatible' => $checkedExt,
				];
			}
		}

		return false;
	}
}
