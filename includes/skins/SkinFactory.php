<?php

/**
 * Copyright 2014
 *
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
 * @file
 */

/**
 * Factory class to create Skin objects
 *
 * @since 1.24
 */
class SkinFactory {

	/**
	 * Map of name => callback
	 * @var array
	 */
	private $factoryFunctions = array();
	/**
	 * Map of name => fallback human-readable name, used when the 'skinname-<skin>' message is not
	 * available
	 *
	 * @var array
	 */
	private $displayNames = array();
	/**
	 * Map of name => class name without "Skin" prefix, for legacy skins using the autodiscovery
	 * mechanism
	 *
	 * @var array
	 */
	private $legacySkins = array();

	/**
	 * @var SkinFactory
	 */
	private static $self;

	public static function getDefaultInstance() {
		if ( !self::$self ) {
			self::$self = new self;
		}

		return self::$self;
	}

	/**
	 * Register a new Skin factory function.
	 *
	 * Will override if it's already registered.
	 *
	 * @param string $name Internal skin name. Should be all-lowercase (technically doesn't have
	 *     to be, but doing so would change the case of i18n message keys).
	 * @param string $displayName For backwards-compatibility with old skin loading system. This is
	 *     the text used as skin's human-readable name when the 'skinname-<skin>' message is not
	 *     available. It should be the same as the skin name provided in $wgExtensionCredits.
	 * @param callable $callback Callback that takes the skin name as an argument
	 * @throws InvalidArgumentException If an invalid callback is provided
	 */
	public function register( $name, $displayName, $callback ) {
		if ( !is_callable( $callback ) ) {
			throw new InvalidArgumentException( 'Invalid callback provided' );
		}
		$this->factoryFunctions[$name] = $callback;
		$this->displayNames[$name] = $displayName;
	}

	/**
	 * @return array
	 */
	private function getLegacySkinNames() {
		static $skinsInitialised = false;

		if ( !$skinsInitialised || !count( $this->legacySkins ) ) {
			# Get a list of available skins
			# Build using the regular expression '^(.*).php$'
			# Array keys are all lower case, array value keep the case used by filename
			#
			wfProfileIn( __METHOD__ . '-init' );

			global $wgStyleDirectory;

			$skinDir = dir( $wgStyleDirectory );

			if ( $skinDir !== false && $skinDir !== null ) {
				# while code from www.php.net
				while ( false !== ( $file = $skinDir->read() ) ) {
					// Skip non-PHP files, hidden files, and '.dep' includes
					$matches = array();

					if ( preg_match( '/^([^.]*)\.php$/', $file, $matches ) ) {
						$aSkin = $matches[1];

						// Explicitly disallow loading core skins via the autodiscovery mechanism.
						//
						// They should be loaded already (in a non-autodicovery way), but old files might still
						// exist on the server because our MW version upgrade process is widely documented as
						// requiring just copying over all files, without removing old ones.
						//
						// This is one of the reasons we should have never used autodiscovery in the first
						// place. This hack can be safely removed when autodiscovery is gone.
						if ( in_array( $aSkin, array( 'CologneBlue', 'Modern', 'MonoBook', 'Vector' ) ) ) {
							wfLogWarning(
								"An old copy of the $aSkin skin was found in your skins/ directory. " .
								"You should remove it to avoid problems in the future." .
								"See https://www.mediawiki.org/wiki/Manual:Skin_autodiscovery for details."
							);
							continue;
						}

						wfLogWarning(
							"A skin using autodiscovery mechanism, $aSkin, was found in your skins/ directory. " .
							"The mechanism will be removed in MediaWiki 1.25 and the skin will no longer be recognized. " .
							"See https://www.mediawiki.org/wiki/Manual:Skin_autodiscovery for information how to fix this."
						);
						$this->legacySkins[strtolower( $aSkin )] = $aSkin;
					}
				}
				$skinDir->close();
			}
			$skinsInitialised = true;
			wfProfileOut( __METHOD__ . '-init' );
		}
		return $this->legacySkins;

	}

	/**
	 * Returns an associative array of:
	 *  skin name => human readable name
	 *
	 * @return array
	 */
	public function getSkinNames() {
		return array_merge(
			$this->getLegacySkinNames(),
			$this->displayNames
		);
	}

	/**
	 * Get a legacy skin which uses the autodiscovery mechanism.
	 *
	 * @param string $name
	 * @return Skin|bool False if the skin couldn't be constructed
	 */
	private function getLegacySkin( $name ) {
		$skinNames = $this->getLegacySkinNames();
		if ( !isset( $skinNames[$name] ) ) {
			return false;
		}
		$skinName = $skinNames[$name];
		$className = "Skin{$skinName}";

		# Grab the skin class and initialise it.
		if ( !class_exists( $className ) ) {
			global $wgStyleDirectory;
			require_once "{$wgStyleDirectory}/{$skinName}.php";

			# Check if we got it
			if ( !class_exists( $className ) ) {
				# DO NOT die if the class isn't found. This breaks maintenance
				# scripts and can cause a user account to be unrecoverable
				# except by SQL manipulation if a previously valid skin name
				# is no longer valid.
				return false;
			}
		}
		$skin = new $className( $name );
		return $skin;

	}

	/**
	 * Create a given Skin using the registered callback for $name.
	 * @param string $name Name of the skin you want
	 * @throws SkinException If a factory function isn't registered for $name
	 * @throws UnexpectedValueException If the factory function returns a non-Skin object
	 * @return Skin
	 */
	public function makeSkin( $name ) {
		if ( !isset( $this->factoryFunctions[$name] ) ) {
			// Check the legacy autodiscovery method of skin loading
			$legacy = $this->getLegacySkin( $name );
			if ( $legacy ) {
				return $legacy;
			}
			throw new SkinException( "No registered builder available for $name." );
		}
		$skin = call_user_func( $this->factoryFunctions[$name], $name );
		if ( $skin instanceof Skin ) {
			return $skin;
		} else {
			throw new UnexpectedValueException( "The builder for $name returned a non-Skin object." );
		}
	}
}
