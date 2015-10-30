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
 * @since 1.27
 */
class VersionChecker {
	/**
	 * @var Constraint|bool representing $wgVersion
	 */
	private $coreVersion = false;

	/**
	 * @var array Loaded extensions
	 */
	private $loaded = array();

	/**
	 * @var ExtensionRegistry
	 */
	private $versionParser;

	public function __construct() {
		$this->versionParser = new VersionParser();
	}

	/**
	 * Set an array with credits of all loaded extensions.
	 *
	 * @param array $credits An array of installed extensions with credits of them
	 */
	public function setLoaded( array $credits ) {
		$this->loaded = $credits;

		return $this;
	}

	/**
	 * Set MediaWiki core version.
	 *
	 * @param string $coreVersion Current version of core
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
	 *	array (
	 *		'GoogleAPIClient' => array(
	 *			'MediaWiki' => '>= 1.25.0',
	 *			'Extension:FakeExtension' => '>= 1.25.0',
	 *		)
	 *	)
	 *
	 * @param array $extDependencies All extensions, that depends on another one
	 * @return bool
	 */
	public function checkArray( array $extDependencies ) {
		$errors = array();
		foreach ( $extDependencies as $extension => $dependencies ) {
			foreach ( $dependencies as $dependency => $constraint ) {
				if ( $dependency === ExtensionRegistry::MEDIAWIKI_CORE ) {
					// special case for MediaWiki core, it doesn't has an explicit type
					$dependencyArray = array( 'MediaWiki', 'MediaWiki' );
				} else {
					// get the type of this dependency and the name of it
					$dependencyArray = explode( ':', $dependency );
					// protect ExtensionRegistry::MEDIAWIKI_CORE type against other
					// usages and conflicts with the typeless "MediaWiki" core requirement.
					if (
						isset( $dependencyArray[0] ) &&
						$dependencyArray[0] === ExtensionRegistry::MEDIAWIKI_CORE
					) {
						$dependencyArray[0] = '';
					}
				}
				// if the there is no type specification, skip this dependency.
				if ( isset( $dependencyArray[1] ) ) {
					// decide, what to do with this dependency
					switch ( $dependencyArray[0] ) {
						case 'MediaWiki':
							$method = 'handleMediaWikiDependency';
							break;
						case 'Extension':
							$method = 'handleExtensionDependency';
							break;
						default:
							$method = 'handleBogusDependency';
							break;
					}
					$errors = array_merge(
						$errors,
						$this->$method( $dependencyArray[1], $constraint, $extension )
					);
				}
			}
		}

		return $errors;
	}

	/**
	 * Handle dependency, which type is not known.
	 *
	 * @param string $dependencyName The name of the dependency
	 * @param string $constraint The required version constraint for this dependency
	 * @param string $checkedExt The Extension, which depends on this dependency
	 * @return array An array with an error message, that the dependency isn't known
	 */
	private function handleBogusDependency( $dependencyName, $constraint, $checkedExt ) {
		return array( "Dependency type of dependency {$dependencyName} not known for {$checkedExt}." );
	}

	/**
	 * Handle a dependency to MediaWiki core. It will check, if a MediaWiki version constraint was
	 * set with self::setCoreVersion before this call (if not, it will return an empty array) and
	 * checks the version constraint given against it.
	 *
	 * @param string $dependencyName The name of the dependency
	 * @param string $constraint The required version constraint for this dependency
	 * @param string $checkedExt The Extension, which depends on this dependency
	 * @return array An empty array, if MediaWiki version is compatible with $constraint, an array
	 *  with an error message, otherwise.
	 */
	private function handleMediaWikiDependency( $dependencyName, $constraint, $checkedExt ) {
		if ( $this->coreVersion === false ) {
			// Couldn't parse the core version, so we can't check anything
			return array();
		}

		// if the installed and required version are compatible, return an empty array
		if ( $this->versionParser->parseConstraints( $constraint )
			->matches( $this->coreVersion ) ) {
			return array();
		}
		// otherwise mark this as incompatible.
		return array( "{$checkedExt} is not compatible with the current "
			. "MediaWiki core (version {$this->coreVersion->getPrettyString()}), it requires: " . $constraint
			. '.' );
	}

	/**
	 * Handle a dependency to another extension. It will check, if the dependency is installed
	 * and provides a version. If not, and the extension does not specify '*' as a version, it
	 * will return an array with an error message, that the dependency isn't compatible with the
	 * extension.
	 *
	 * Otherwise, it will try to parse the current version of the dependency and checks it against
	 * the required version. If they are compatible, it will return an empty array, otherwise
	 * an array with an error message.
	 *
	 * @param string $dependencyName The name of the dependency
	 * @param string $constraint The required version constraint for this dependency
	 * @param string $checkedExt The Extension, which depends on this dependency
	 * @return array An empty array, if MediaWiki version is compatible with $constraint, an array
	 *  with an error message, otherwise.
	 */
	private function handleExtensionDependency( $dependencyName, $constraint, $checkedExt ) {
		$incompatible = array();
		// check, if the dependency is installed or not
		if ( !isset( $this->loaded[$dependencyName] ) ) {
			$incompatible[] = "{$checkedExt} requires {$dependencyName} to be installed.";
			return $incompatible;
		}
		// check, if the extension has it's version mentioned in extension.json
		// and set as incompatible if not
		if ( !isset( $this->loaded[$dependencyName]['version'] ) ) {
			// if the extension doesn't need a special version of the dependency,
			// just log an info, that the version is missing
			if ( $constraint === '*' ) {
				wfDebug( "{$dependencyName} does not expose it's version, but {$checkedExt}
					mentions it with constraint '*'. Assume it's ok so." );
			} else {
				// otherwise: mark as incompatible
				$incompatible[] = "{$dependencyName} does not expose it's version, but {$checkedExt}
					requires: {$constraint}.";
			}
		} else {
			// try to get a constraint for the dependency version
			try {
				$installedVersion = new Constraint(
					'==',
					$this->versionParser->normalize( $this->loaded[$dependencyName]['version'] )
				);
			} catch ( UnexpectedValueException $e ) {
				// Non-parsable version, don't fatal.
			}
			// finally, check constraint against dependency version
			// check and parse constraint
			if ( !$this->versionParser->parseConstraints( $constraint )->matches( $installedVersion ) ) {
				$incompatible[] = "{$checkedExt} is not compatible with the current "
					. "installed version of {$dependencyName} "
					. "(version {$this->loaded[$dependencyName]['version']}), "
					. "it requires: " . $constraint . '.';
			}
		}
		return $incompatible;
	}
}
