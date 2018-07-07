<?php
/**
 * Provide things related to namespaces.
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
 * This is a utility class with only static functions
 * for dealing with namespaces that encodes all the
 * "magic" behaviors of them based on index.  The textual
 * names of the namespaces are handled by Language.php.
 *
 * These are synonyms for the names given in the language file
 * Users and translators should not change them
 */
class MWNamespace {

	/**
	 * These namespaces should always be first-letter capitalized, now and
	 * forevermore. Historically, they could've probably been lowercased too,
	 * but some things are just too ingrained now. :)
	 */
	private static $alwaysCapitalizedNamespaces = [ NS_SPECIAL, NS_USER, NS_MEDIAWIKI ];

	/** @var string[]|null Canonical namespaces cache */
	private static $canonicalNamespaces = null;

	/** @var array|false Canonical namespaces index cache */
	private static $namespaceIndexes = false;

	/** @var int[]|null Valid namespaces cache */
	private static $validNamespaces = null;

	/**
	 * Throw an exception when trying to get the subject or talk page
	 * for a given namespace where it does not make sense.
	 * Special namespaces are defined in includes/Defines.php and have
	 * a value below 0 (ex: NS_SPECIAL = -1 , NS_MEDIA = -2)
	 *
	 * @param int $index
	 * @param string $method
	 *
	 * @throws MWException
	 * @return bool
	 */
	private static function isMethodValidFor( $index, $method ) {
		if ( $index < NS_MAIN ) {
			throw new MWException( "$method does not make any sense for given namespace $index" );
		}
		return true;
	}

	/**
	 * Clear internal caches
	 *
	 * For use in unit testing when namespace configuration is changed.
	 *
	 * @since 1.31
	 */
	public static function clearCaches() {
		self::$canonicalNamespaces = null;
		self::$namespaceIndexes = false;
		self::$validNamespaces = null;
	}

	/**
	 * Can pages in the given namespace be moved?
	 *
	 * @param int $index Namespace index
	 * @return bool
	 */
	public static function isMovable( $index ) {
		global $wgAllowImageMoving;

		$result = !( $index < NS_MAIN || ( $index == NS_FILE && !$wgAllowImageMoving ) );

		/**
		 * @since 1.20
		 */
		Hooks::run( 'NamespaceIsMovable', [ $index, &$result ] );

		return $result;
	}

	/**
	 * Is the given namespace is a subject (non-talk) namespace?
	 *
	 * @param int $index Namespace index
	 * @return bool
	 * @since 1.19
	 */
	public static function isSubject( $index ) {
		return !self::isTalk( $index );
	}

	/**
	 * Is the given namespace a talk namespace?
	 *
	 * @param int $index Namespace index
	 * @return bool
	 */
	public static function isTalk( $index ) {
		return $index > NS_MAIN
			&& $index % 2;
	}

	/**
	 * Get the talk namespace index for a given namespace
	 *
	 * @param int $index Namespace index
	 * @return int
	 */
	public static function getTalk( $index ) {
		self::isMethodValidFor( $index, __METHOD__ );
		return self::isTalk( $index )
			? $index
			: $index + 1;
	}

	/**
	 * Get the subject namespace index for a given namespace
	 * Special namespaces (NS_MEDIA, NS_SPECIAL) are always the subject.
	 *
	 * @param int $index Namespace index
	 * @return int
	 */
	public static function getSubject( $index ) {
		# Handle special namespaces
		if ( $index < NS_MAIN ) {
			return $index;
		}

		return self::isTalk( $index )
			? $index - 1
			: $index;
	}

	/**
	 * Get the associated namespace.
	 * For talk namespaces, returns the subject (non-talk) namespace
	 * For subject (non-talk) namespaces, returns the talk namespace
	 *
	 * @param int $index Namespace index
	 * @return int|null If no associated namespace could be found
	 */
	public static function getAssociated( $index ) {
		self::isMethodValidFor( $index, __METHOD__ );

		if ( self::isSubject( $index ) ) {
			return self::getTalk( $index );
		} elseif ( self::isTalk( $index ) ) {
			return self::getSubject( $index );
		} else {
			return null;
		}
	}

	/**
	 * Returns whether the specified namespace exists
	 *
	 * @param int $index
	 *
	 * @return bool
	 * @since 1.19
	 */
	public static function exists( $index ) {
		$nslist = self::getCanonicalNamespaces();
		return isset( $nslist[$index] );
	}

	/**
	 * Returns whether the specified namespaces are the same namespace
	 *
	 * @note It's possible that in the future we may start using something
	 * other than just namespace indexes. Under that circumstance making use
	 * of this function rather than directly doing comparison will make
	 * sure that code will not potentially break.
	 *
	 * @param int $ns1 The first namespace index
	 * @param int $ns2 The second namespace index
	 *
	 * @return bool
	 * @since 1.19
	 */
	public static function equals( $ns1, $ns2 ) {
		return $ns1 == $ns2;
	}

	/**
	 * Returns whether the specified namespaces share the same subject.
	 * eg: NS_USER and NS_USER wil return true, as well
	 *     NS_USER and NS_USER_TALK will return true.
	 *
	 * @param int $ns1 The first namespace index
	 * @param int $ns2 The second namespace index
	 *
	 * @return bool
	 * @since 1.19
	 */
	public static function subjectEquals( $ns1, $ns2 ) {
		return self::getSubject( $ns1 ) == self::getSubject( $ns2 );
	}

	/**
	 * Returns array of all defined namespaces with their canonical
	 * (English) names.
	 *
	 * @param bool $rebuild Rebuild namespace list (default = false). Used for testing.
	 *  Deprecated since 1.31, use self::clearCaches() instead.
	 *
	 * @return array
	 * @since 1.17
	 */
	public static function getCanonicalNamespaces( $rebuild = false ) {
		if ( $rebuild ) {
			self::clearCaches();
		}

		if ( self::$canonicalNamespaces === null ) {
			global $wgExtraNamespaces, $wgCanonicalNamespaceNames;
			self::$canonicalNamespaces = [ NS_MAIN => '' ] + $wgCanonicalNamespaceNames;
			// Add extension namespaces
			self::$canonicalNamespaces +=
				ExtensionRegistry::getInstance()->getAttribute( 'ExtensionNamespaces' );
			if ( is_array( $wgExtraNamespaces ) ) {
				self::$canonicalNamespaces += $wgExtraNamespaces;
			}
			Hooks::run( 'CanonicalNamespaces', [ &self::$canonicalNamespaces ] );
		}
		return self::$canonicalNamespaces;
	}

	/**
	 * Returns the canonical (English) name for a given index
	 *
	 * @param int $index Namespace index
	 * @return string|bool If no canonical definition.
	 */
	public static function getCanonicalName( $index ) {
		$nslist = self::getCanonicalNamespaces();
		return $nslist[$index] ?? false;
	}

	/**
	 * Returns the index for a given canonical name, or NULL
	 * The input *must* be converted to lower case first
	 *
	 * @param string $name Namespace name
	 * @return int
	 */
	public static function getCanonicalIndex( $name ) {
		if ( self::$namespaceIndexes === false ) {
			self::$namespaceIndexes = [];
			foreach ( self::getCanonicalNamespaces() as $i => $text ) {
				self::$namespaceIndexes[strtolower( $text )] = $i;
			}
		}
		if ( array_key_exists( $name, self::$namespaceIndexes ) ) {
			return self::$namespaceIndexes[$name];
		} else {
			return null;
		}
	}

	/**
	 * Returns an array of the namespaces (by integer id) that exist on the
	 * wiki. Used primarily by the api in help documentation.
	 * @return array
	 */
	public static function getValidNamespaces() {
		if ( is_null( self::$validNamespaces ) ) {
			foreach ( array_keys( self::getCanonicalNamespaces() ) as $ns ) {
				if ( $ns >= 0 ) {
					self::$validNamespaces[] = $ns;
				}
			}
			// T109137: sort numerically
			sort( self::$validNamespaces, SORT_NUMERIC );
		}

		return self::$validNamespaces;
	}

	/**
	 * Does this namespace ever have a talk namespace?
	 *
	 * @deprecated since 1.30, use hasTalkNamespace() instead.
	 *
	 * @param int $index Namespace index
	 * @return bool True if this namespace either is or has a corresponding talk namespace.
	 */
	public static function canTalk( $index ) {
		return self::hasTalkNamespace( $index );
	}

	/**
	 * Does this namespace ever have a talk namespace?
	 *
	 * @since 1.30
	 *
	 * @param int $index Namespace ID
	 * @return bool True if this namespace either is or has a corresponding talk namespace.
	 */
	public static function hasTalkNamespace( $index ) {
		return $index >= NS_MAIN;
	}

	/**
	 * Does this namespace contain content, for the purposes of calculating
	 * statistics, etc?
	 *
	 * @param int $index Index to check
	 * @return bool
	 */
	public static function isContent( $index ) {
		global $wgContentNamespaces;
		return $index == NS_MAIN || in_array( $index, $wgContentNamespaces );
	}

	/**
	 * Might pages in this namespace require the use of the Signature button on
	 * the edit toolbar?
	 *
	 * @param int $index Index to check
	 * @return bool
	 */
	public static function wantSignatures( $index ) {
		global $wgExtraSignatureNamespaces;
		return self::isTalk( $index ) || in_array( $index, $wgExtraSignatureNamespaces );
	}

	/**
	 * Can pages in a namespace be watched?
	 *
	 * @param int $index
	 * @return bool
	 */
	public static function isWatchable( $index ) {
		return $index >= NS_MAIN;
	}

	/**
	 * Does the namespace allow subpages?
	 *
	 * @param int $index Index to check
	 * @return bool
	 */
	public static function hasSubpages( $index ) {
		global $wgNamespacesWithSubpages;
		return !empty( $wgNamespacesWithSubpages[$index] );
	}

	/**
	 * Get a list of all namespace indices which are considered to contain content
	 * @return array Array of namespace indices
	 */
	public static function getContentNamespaces() {
		global $wgContentNamespaces;
		if ( !is_array( $wgContentNamespaces ) || $wgContentNamespaces === [] ) {
			return [ NS_MAIN ];
		} elseif ( !in_array( NS_MAIN, $wgContentNamespaces ) ) {
			// always force NS_MAIN to be part of array (to match the algorithm used by isContent)
			return array_merge( [ NS_MAIN ], $wgContentNamespaces );
		} else {
			return $wgContentNamespaces;
		}
	}

	/**
	 * List all namespace indices which are considered subject, aka not a talk
	 * or special namespace. See also MWNamespace::isSubject
	 *
	 * @return array Array of namespace indices
	 */
	public static function getSubjectNamespaces() {
		return array_filter(
			self::getValidNamespaces(),
			'MWNamespace::isSubject'
		);
	}

	/**
	 * List all namespace indices which are considered talks, aka not a subject
	 * or special namespace. See also MWNamespace::isTalk
	 *
	 * @return array Array of namespace indices
	 */
	public static function getTalkNamespaces() {
		return array_filter(
			self::getValidNamespaces(),
			'MWNamespace::isTalk'
		);
	}

	/**
	 * Is the namespace first-letter capitalized?
	 *
	 * @param int $index Index to check
	 * @return bool
	 */
	public static function isCapitalized( $index ) {
		global $wgCapitalLinks, $wgCapitalLinkOverrides;
		// Turn NS_MEDIA into NS_FILE
		$index = $index === NS_MEDIA ? NS_FILE : $index;

		// Make sure to get the subject of our namespace
		$index = self::getSubject( $index );

		// Some namespaces are special and should always be upper case
		if ( in_array( $index, self::$alwaysCapitalizedNamespaces ) ) {
			return true;
		}
		if ( isset( $wgCapitalLinkOverrides[$index] ) ) {
			// $wgCapitalLinkOverrides is explicitly set
			return $wgCapitalLinkOverrides[$index];
		}
		// Default to the global setting
		return $wgCapitalLinks;
	}

	/**
	 * Does the namespace (potentially) have different aliases for different
	 * genders. Not all languages make a distinction here.
	 *
	 * @since 1.18
	 * @param int $index Index to check
	 * @return bool
	 */
	public static function hasGenderDistinction( $index ) {
		return $index == NS_USER || $index == NS_USER_TALK;
	}

	/**
	 * It is not possible to use pages from this namespace as template?
	 *
	 * @since 1.20
	 * @param int $index Index to check
	 * @return bool
	 */
	public static function isNonincludable( $index ) {
		global $wgNonincludableNamespaces;
		return $wgNonincludableNamespaces && in_array( $index, $wgNonincludableNamespaces );
	}

	/**
	 * Get the default content model for a namespace
	 * This does not mean that all pages in that namespace have the model
	 *
	 * @since 1.21
	 * @param int $index Index to check
	 * @return null|string Default model name for the given namespace, if set
	 */
	public static function getNamespaceContentModel( $index ) {
		global $wgNamespaceContentModels;
		return $wgNamespaceContentModels[$index] ?? null;
	}

	/**
	 * Determine which restriction levels it makes sense to use in a namespace,
	 * optionally filtered by a user's rights.
	 *
	 * @since 1.23
	 * @param int $index Index to check
	 * @param User|null $user User to check
	 * @return array
	 */
	public static function getRestrictionLevels( $index, User $user = null ) {
		global $wgNamespaceProtection, $wgRestrictionLevels;

		if ( !isset( $wgNamespaceProtection[$index] ) ) {
			// All levels are valid if there's no namespace restriction.
			// But still filter by user, if necessary
			$levels = $wgRestrictionLevels;
			if ( $user ) {
				$levels = array_values( array_filter( $levels, function ( $level ) use ( $user ) {
					$right = $level;
					if ( $right == 'sysop' ) {
						$right = 'editprotected'; // BC
					}
					if ( $right == 'autoconfirmed' ) {
						$right = 'editsemiprotected'; // BC
					}
					return ( $right == '' || $user->isAllowed( $right ) );
				} ) );
			}
			return $levels;
		}

		// First, get the list of groups that can edit this namespace.
		$namespaceGroups = [];
		$combine = 'array_merge';
		foreach ( (array)$wgNamespaceProtection[$index] as $right ) {
			if ( $right == 'sysop' ) {
				$right = 'editprotected'; // BC
			}
			if ( $right == 'autoconfirmed' ) {
				$right = 'editsemiprotected'; // BC
			}
			if ( $right != '' ) {
				$namespaceGroups = call_user_func( $combine, $namespaceGroups,
					User::getGroupsWithPermission( $right ) );
				$combine = 'array_intersect';
			}
		}

		// Now, keep only those restriction levels where there is at least one
		// group that can edit the namespace but would be blocked by the
		// restriction.
		$usableLevels = [ '' ];
		foreach ( $wgRestrictionLevels as $level ) {
			$right = $level;
			if ( $right == 'sysop' ) {
				$right = 'editprotected'; // BC
			}
			if ( $right == 'autoconfirmed' ) {
				$right = 'editsemiprotected'; // BC
			}
			if ( $right != '' && ( !$user || $user->isAllowed( $right ) ) &&
				array_diff( $namespaceGroups, User::getGroupsWithPermission( $right ) )
			) {
				$usableLevels[] = $level;
			}
		}

		return $usableLevels;
	}

	/**
	 * Returns the link type to be used for categories.
	 *
	 * This determines which section of a category page titles
	 * in the namespace will appear within.
	 *
	 * @since 1.32
	 * @param int $index Namespace index
	 * @return string One of 'subcat', 'file', 'page'
	 */
	public static function getCategoryLinkType( $index ) {
		self::isMethodValidFor( $index, __METHOD__ );

		if ( $index == NS_CATEGORY ) {
			return 'subcat';
		} elseif ( $index == NS_FILE ) {
			return 'file';
		} else {
			return 'page';
		}
	}
}
