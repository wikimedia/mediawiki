<?php
/**
 * Provide things related to namespaces
 * @package MediaWiki
 */

/**
 * This is not a valid entry point, perform no further processing unless MEDIAWIKI is defined
 */
if( defined( 'MEDIAWIKI' ) ) {


/**
 * Definitions of the NS_ constants are in Defines.php
 * @private
 */
$wgCanonicalNamespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK	            => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	NS_PROJECT          => 'Project',
	NS_PROJECT_TALK     => 'Project_talk',
	NS_IMAGE            => 'Image',
	NS_IMAGE_TALK       => 'Image_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY	    => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk',
);

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
 * @package MediaWiki
 */
class Namespace {

	/**
	 * Check if the given namespace might be moved
	 * @return bool
	 */
	function isMovable( $index ) {
		return !( $index < NS_MAIN || $index == NS_IMAGE  || $index == NS_CATEGORY );
	}

	/**
	 * Check if the given namespace is not a talk page
	 * @return bool
	 */
	function isMain( $index ) {
		return ! Namespace::isTalk( $index );
	}

	/**
	 * Check if the give namespace is a talk page
	 * @return bool
	 */
	function isTalk( $index ) {
		return ($index > NS_MAIN)  // Special namespaces are negative
			&& ($index % 2); // Talk namespaces are odd-numbered
	}

	/**
	 * Get the talk namespace corresponding to the given index
	 */
	function getTalk( $index ) {
		if ( Namespace::isTalk( $index ) ) {
			return $index;
		} else {
			# FIXME
			return $index + 1;
		}
	}

	function getSubject( $index ) {
		if ( Namespace::isTalk( $index ) ) {
			return $index - 1;
		} else {
			return $index;
		}
	}

	/**
	 * Returns the canonical (English Wikipedia) name for a given index
	 */
	function getCanonicalName( $index ) {
		global $wgCanonicalNamespaceNames;
		return $wgCanonicalNamespaceNames[$index];
	}

	/**
	 * Returns the index for a given canonical name, or NULL
	 * The input *must* be converted to lower case first
	 */
	function getCanonicalIndex( $name ) {
		global $wgCanonicalNamespaceNames;
		static $xNamespaces = false;
		if ( $xNamespaces === false ) {
			$xNamespaces = array();
			foreach ( $wgCanonicalNamespaceNames as $i => $text ) {
				$xNamespaces[strtolower($text)] = $i;
			}
		}
		if ( array_key_exists( $name, $xNamespaces ) ) {
			return $xNamespaces[$name];
		} else {
			return NULL;
		}
	}
}

}
?>
