<?php
# This is a utility class with only static functions
# for dealing with namespaces that encodes all the
# "magic" behaviors of them based on index.  The textual
# names of the namespaces are handled by Language.php.

# Virtual namespaces; these don't appear in the page database:
define("NS_MEDIA", -2);
define("NS_SPECIAL", -1);

# Real namespaces:
define("NS_MAIN", 0);
define("NS_TALK", 1);
define("NS_USER", 2);
define("NS_USER_TALK", 3);
define("NS_WP", 4);
define("NS_WP_TALK", 5);
define("NS_IMAGE", 6);
define("NS_IMAGE_TALK", 7);
define("NS_MEDIAWIKI", 8);
define("NS_MEDIAWIKI_TALK", 9);

class Namespace {

	/* These functions are deprecated */
	function getSpecial() { return NS_SPECIAL; }
	function getUser() { return NS_USER; }
	function getWikipedia() { return NS_WP; }
	function getImage() { return NS_IMAGE; }
	function getMedia() { return NS_MEDIA; }

	function isMovable( $index )
	{
		if ( $index < NS_MAIN || $index == NS_IMAGE ) { 
			return false; 
		}
		return true;
	}

	function isTalk( $index )
	{
		if ( NS_TALK == $index || NS_USER_TALK == $index || NS_WP_TALK == $index || NS_IMAGE_TALK == $index || NS_MEDIAWIKI_TALK == $index ) {
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
}

?>
