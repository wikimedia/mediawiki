<?

# This class encapsulates "magic words" such as #redirect, __NOTOC__, etc.
# Usage:
#     if (MagicWord::get( MAG_REDIRECT )->match( $text ) )
# 
# Possible future improvements: 
#   * Simultaneous searching for a number of magic words
#   * $wgMagicWords in shared memory
#
# Please avoid reading the data out of one of these objects and then writing 
# special case code. If possible, add another match()-like function here.

/*private*/ $wgMagicFound = false;

class MagicWord {
	/*private*/ var $mId, $mSynonyms, $mCaseSensitive, $mRegex;
	/*private*/ var $mRegexStart, $mBaseRegex, $mVariableRegex;
	
	function MagicWord($id = 0, $syn = "", $cs = false) 
	{
		$this->mId = $id;
		$this->mSynonyms = (array)$syn;
		$this->mCaseSensitive = $cs;
		$this->mRegex = "";
		$this->mRegexStart = "";
		$this->mVariableRegex = "";
	}

	# Factory: creates an object representing an ID
	/*static*/ function &get( $id )
	{
		global $wgMagicWords;
		
		if (!array_key_exists( $id, $wgMagicWords ) ) {
			$mw = new MagicWord();
			$mw->load( $id );
			$wgMagicWords[$id] = $mw;
		}
		return $wgMagicWords[$id];
	}
	
	# Initialises this object with an ID
	function load( $id )
	{
		global $wgLang;
		
		$this->mId = $id;
		$wgLang->getMagic( $this );
	}
	
	# Preliminary initialisation
	/* private */ function initRegex()
	{
		$escSyn = array_map( "preg_quote", $this->mSynonyms );
		$this->mBaseRegex = implode( "|", $escSyn );
		$case = $this->mCaseSensitive ? "" : "i";
		$this->mRegex = "/{$this->mBaseRegex}/{$case}";
		$this->mRegexStart = "/^{$this->mBaseRegex}/{$case}";
		$this->mVariableRegex = str_replace( "\\$1", "([A-Za-z0-9_\-]*)", $this->mRegex );
	}
	
	# Gets a regex representing matching the word
	function getRegex()
	{
		if ($this->mRegex == "" ) {
			$this->initRegex();
		}
		return $this->mRegex;
	}

	# Gets a regex matching the word, if it is at the 
	# string start
	function getRegexStart()
	{
		if ($this->mRegex == "" ) {
			$this->initRegex();
		}
		return $this->mRegexStart;
	}
	
	# regex without the slashes and what not
	function getBaseRegex()
	{
		if ($this->mRegex == "") {
			$this->initRegex();
		}
		return $this->mBaseRegex;
	}
		
	# Returns true if the text contains the word
	function match( $text ) {
		return preg_match( $this->getRegex(), $text );
	}

	# Returns true if the text starts with the word
	function matchStart( $text ) 
	{
		return preg_match( $this->getRegexStart(), $text );
	}

	# Returns true if the text matches the word, and alters the
	# input string, removing all instances of the word
	function matchAndRemove( &$text )
	{
		global $wgMagicFound;
		$wgMagicFound = false;
		$text = preg_replace_callback( $this->getRegex(), "pregRemoveAndRecord", $text );
		return $wgMagicFound;
	}

	# Replaces the word with something else
	function replace( $replacement, $subject )
	{
		return preg_replace( $this->getRegex(), $replacement, $subject );
	}

	# Variable handling: {{SUBST:xxx}} style words
	# Calls back a function to determine what to replace xxx with
	# Input word must contain $1
	function substituteCallback( $text, $callback ) {
		$regex = $this->getVariableRegex();
		return preg_replace_callback( $this->getVariableRegex(), $callback, $text );
	}

	# Matches the word, where $1 is a wildcard
	function getVariableRegex()
	{
		if ( $this->mVariableRegex == "" ) {
			$this->initRegex();
		} 
		return $this->mVariableRegex;
	}

	# Accesses the synonym list directly
	function getSynonym( $i ) {
		return $this->mSynonyms[$i];
	}
}

# Used in matchAndRemove()
/*private*/ function pregRemoveAndRecord( $match )
{
	global $wgMagicFound;
	$wgMagicFound = true;
	return "";
}

?>
