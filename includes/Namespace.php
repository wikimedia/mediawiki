<?php

# This is not a valid entry point, perform no further processing unless MEDIAWIKI is defined
if( defined( 'MEDIAWIKI' ) ) {

# This is a utility class with only static functions
# for dealing with namespaces that encodes all the
# "magic" behaviors of them based on index.  The textual
# names of the namespaces are handled by Language.php.

# Definitions of the NS_ constants are in Defines.php

# These are synonyms for the names given in the language file
# Users and translators should not change them
/* private */ $wgCanonicalNamespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK	            => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	NS_WIKIPEDIA        => 'Project',
	NS_WIKIPEDIA_TALK   => 'Project_talk',
	NS_IMAGE            => 'Image',
	NS_IMAGE_TALK       => 'Image_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY	        => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk'
);

class Namespace {

	/* These functions are deprecated */
	function getSpecial() { return NS_SPECIAL; }
	function getUser() { return NS_USER; }
	function getWikipedia() { return NS_WP; }
	function getImage() { return NS_IMAGE; }
	function getMedia() { return NS_MEDIA; }
	function getCategory() { return NS_CATEGORY; }

	function isMovable( $index )
	{
		if ( $index < NS_MAIN || $index == NS_IMAGE  || $index == NS_CATEGORY ) { 
			return false; 
		}
		return true;
	}

	function isTalk( $index )
	{
		if ( NS_TALK == $index || NS_USER_TALK == $index || NS_WP_TALK
	== $index || NS_IMAGE_TALK == $index || NS_MEDIAWIKI_TALK == $index ||
	NS_TEMPLATE_TALK == $index || NS_HELP_TALK == $index ||
	NS_CATEGORY_TALK == $index ) {
			return true;
		}
		return false;
	}

	# Get the talk namespace corresponding to the given index
	#
	function getTalk( $index )
	{
		if ( Namespace::isTalk( $index ) ) {
			return $index;
		} else {
			# FIXME
			return $index + 1;
		}
	}

	function getSubject( $index )
	{
		if ( Namespace::isTalk( $index ) ) {
			return $index - 1;
		} else {
			return $index;
		}
	}

	# Returns the canonical (English Wikipedia) name for a given index
	function &getCanonicalName( $index )
	{
		global $wgCanonicalNamespaceNames;
		return $wgCanonicalNamespaceNames[$index];
	}

	# Returns the index for a given canonical name, or NULL
	# The input *must* be converted to lower case first
	function &getCanonicalIndex( $name )
	{
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
