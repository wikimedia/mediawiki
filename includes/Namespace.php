<?
# This is a utility class with only static functions
# for dealing with namespaces that encodes all the
# "magic" behaviors of them based on index.  The textual
# names of the namespaces are handled by Language.php.

class Namespace {

	function getSpecial() { return -1; }
	function getUser() { return 2; }
	function getWikipedia() { return 4; }
	function getImage() { return 6; }

	function isMovable( $index )
	{
		if ( $index < 0 || $index > 5 ) { return false; }
		return true;
	}

	function isTalk( $index )
	{
		if ( 1 == $index || 3 == $index || 5 == $index || 7 == $index ) {
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
