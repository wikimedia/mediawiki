<?php
/**
 * Provide things related to namespaces
 * @file
 */

/**
 * Definitions of the NS_ constants are in Defines.php
 * @private
 */
$wgCanonicalNamespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	NS_PROJECT          => 'Project',
	NS_PROJECT_TALK     => 'Project_talk',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'File_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY         => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk',
);

/// @todo UGLY UGLY
if( is_array( $wgExtraNamespaces ) ) {
	$wgCanonicalNamespaceNames = $wgCanonicalNamespaceNames + $wgExtraNamespaces;
}

/**
 * This is a utility class with only static functions
 * for dealing with namespaces that encodes all the
 * "magic" behaviors of them based on index.  The textual
 * names of the namespaces are handled by Language.php.
 *
 * These are synonyms for the names given in the language file
 * Users and translators should not change them
 *
 */

class MWNamespace {

	/**
	 * These namespaces should always be first-letter capitalized, now and
	 * forevermore. Historically, they could've probably been lowercased too,
	 * but some things are just too ingrained now. :)
	 */
	private static $alwaysCapitalizedNamespaces = array( NS_SPECIAL, NS_USER, NS_MEDIAWIKI );

	/**
	 * Can pages in the given namespace be moved?
	 *
	 * @param $index Int: namespace index
	 * @return bool
	 */
	public static function isMovable( $index ) {
		global $wgAllowImageMoving;
		return !( $index < NS_MAIN || ($index == NS_FILE && !$wgAllowImageMoving)  || $index == NS_CATEGORY );
	}

	/**
	 * Is the given namespace is a subject (non-talk) namespace?
	 *
	 * @param $index Int: namespace index
	 * @return bool
	 */
	public static function isMain( $index ) {
		return !self::isTalk( $index );
	}

	/**
	 * Is the given namespace a talk namespace?
	 *
	 * @param $index Int: namespace index
	 * @return bool
	 */
	public static function isTalk( $index ) {
		return $index > NS_MAIN
			&& $index % 2;
	}

	/**
	 * Get the talk namespace index for a given namespace
	 *
	 * @param $index Int: namespace index
	 * @return int
	 */
	public static function getTalk( $index ) {
		return self::isTalk( $index )
			? $index
			: $index + 1;
	}

	/**
	 * Get the subject namespace index for a given namespace
	 *
	 * @param $index Int: Namespace index
	 * @return int
	 */
	public static function getSubject( $index ) {
		return self::isTalk( $index )
			? $index - 1
			: $index;
	}

	/**
	 * Returns whether the specified namespace exists
	 */
	public static function exists( $index ) {
		$nslist = self::getCanonicalNamespaces();
		return isset( $nslist[$index] );
	}

	/**
	 * Returns array of all defined namespaces with their canonical
	 * (English) names.
	 *
	 * @return \array
	 * @since 1.17
	 */
	public static function getCanonicalNamespaces() {
		static $namespaces = null;
		if ( $namespaces === null ) {
			global $wgExtraNamespaces, $wgCanonicalNamespaceNames;
			$namespaces = array( NS_MAIN => '' ) + $wgCanonicalNamespaceNames;
			if ( is_array( $wgExtraNamespaces ) ) {
				$namespaces += $wgExtraNamespaces;
			}
			wfRunHooks( 'CanonicalNamespaces', array( &$namespaces ) );
		}
		return $namespaces;
	}

	/**
	 * Returns the canonical (English) name for a given index
	 *
	 * @param $index Int: namespace index
	 * @return string or false if no canonical definition.
	 */
	public static function getCanonicalName( $index ) {
		$nslist = self::getCanonicalNamespaces();
		if( isset( $nslist[$index] ) ) {
			return $nslist[$index];
		} else {
			return false;
		}
	}

	/**
	 * Returns the index for a given canonical name, or NULL
	 * The input *must* be converted to lower case first
	 *
	 * @param $name String: namespace name
	 * @return int
	 */
	public static function getCanonicalIndex( $name ) {
		static $xNamespaces = false;
		if ( $xNamespaces === false ) {
			$xNamespaces = array();
			foreach ( self::getCanonicalNamespaces() as $i => $text ) {
				$xNamespaces[strtolower($text)] = $i;
			}
		}
		if ( array_key_exists( $name, $xNamespaces ) ) {
			return $xNamespaces[$name];
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
		static $mValidNamespaces = null;

		if ( is_null( $mValidNamespaces ) ) {
			foreach ( array_keys( self::getCanonicalNamespaces() ) as $ns ) {
				if ( $ns >= 0 ) {
					$mValidNamespaces[] = $ns;
				}
			}
		}

		return $mValidNamespaces;
	}

	/**
	 * Can this namespace ever have a talk namespace?
	 *
	 * @param $index Int: namespace index
	 * @return bool
	 */
	 public static function canTalk( $index ) {
		return $index >= NS_MAIN;
	 }

	/**
	 * Does this namespace contain content, for the purposes of calculating
	 * statistics, etc?
	 *
	 * @param $index Int: index to check
	 * @return bool
	 */
	public static function isContent( $index ) {
		global $wgContentNamespaces;
		return $index == NS_MAIN || in_array( $index, $wgContentNamespaces );
	}

	/**
	 * Can pages in a namespace be watched?
	 *
	 * @param $index Int
	 * @return bool
	 */
	public static function isWatchable( $index ) {
		return $index >= NS_MAIN;
	}

	/**
	 * Does the namespace allow subpages?
	 *
	 * @param $index int Index to check
	 * @return bool
	 */
	public static function hasSubpages( $index ) {
		global $wgNamespacesWithSubpages;
		return !empty( $wgNamespacesWithSubpages[$index] );
	}

	/**
	 * Is the namespace first-letter capitalized?
	 *
	 * @param $index int Index to check
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
		if ( isset( $wgCapitalLinkOverrides[ $index ] ) ) {
			// $wgCapitalLinkOverrides is explicitly set
			return $wgCapitalLinkOverrides[ $index ];
		}
		// Default to the global setting
		return $wgCapitalLinks;
	}
}
