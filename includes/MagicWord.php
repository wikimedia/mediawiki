<?php

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
	/*private*/ var $mModified;	

	function MagicWord($id = 0, $syn = "", $cs = false) 
	{
		$this->mId = $id;
		$this->mSynonyms = (array)$syn;
		$this->mCaseSensitive = $cs;
		$this->mRegex = "";
		$this->mRegexStart = "";
		$this->mVariableRegex = "";
		$this->mVariableStartToEndRegex = "";
		$this->mModified = false;
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
		$variableClass = "A-Za-z0-9_\-\x80-\xff";
		$escSyn = array_map( "preg_quote", $this->mSynonyms );
		$this->mBaseRegex = implode( "|", $escSyn );
		$case = $this->mCaseSensitive ? "" : "i";
		$this->mRegex = "/{$this->mBaseRegex}/{$case}";
		$this->mRegexStart = "/^{$this->mBaseRegex}/{$case}";
		$this->mVariableRegex = str_replace( "\\$1", "([$variableClass]*)", $this->mRegex );
		$this->mVariableStartToEndRegex = str_replace( "\\$1", "([$variableClass]*)", 
			"/^{$this->mBaseRegex}$/{$case}" );
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

	# Returns NULL if there's no match, the value of $1 otherwise
	# The return code is the matched string, if there's no variable
	# part in the regex and the matched variable part ($1) if there
	# is one.
	function matchVariableStartToEnd( $text ) {
		$matchcount = preg_match( $this->getVariableStartToEndRegex(), $text, $matches );
		if ( $matchcount == 0 ) {
			return NULL;
		} elseif ( count($matches) == 1 ) {
			return $matches[0];
		} else {
			return $matches[1];
		}
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
		$res = preg_replace( $this->getRegex(), $replacement, $subject );
		$this->mModified = !($res === $subject);
		return $res;
	}

	# Variable handling: {{SUBST:xxx}} style words
	# Calls back a function to determine what to replace xxx with
	# Input word must contain $1
	function substituteCallback( $text, $callback ) {
		$regex = $this->getVariableRegex();
		$res = preg_replace_callback( $this->getVariableRegex(), $callback, $text );
		$this->mModified = !($res === $text);
		return $res;
	}

	# Matches the word, where $1 is a wildcard
	function getVariableRegex()
	{
		if ( $this->mVariableRegex == "" ) {
			$this->initRegex();
		} 
		return $this->mVariableRegex;
	}

	# Matches the entire string, where $1 is a wildcard
	function getVariableStartToEndRegex()
	{
		if ( $this->mVariableStartToEndRegex == "" ) {
			$this->initRegex();
		} 
		return $this->mVariableStartToEndRegex;
	}

	# Accesses the synonym list directly
	function getSynonym( $i ) {
		return $this->mSynonyms[$i];
	}

	# Returns true if the last call to replace() or substituteCallback() 
	# returned a modified text, otherwise false.
	function getWasModified(){
		return $this->mModified;
	}

	# $magicarr is an associative array of (magic word ID => replacement)
	# This method uses the php feature to do several replacements at the same time,
	# thereby gaining some efficiency. The result is placed in the out variable
	# $result. The return value is true if something was replaced.

	/* static */ function replaceMultiple( $magicarr, $subject, &$result ){
		$search = array();
		$replace = array();
		foreach( $magicarr as $id => $replacement ){
			$mw = MagicWord::get( $id );
			$search[] = $mw->getRegex();
			$replace[] = $replacement;
		}

		$result = preg_replace( $search, $replace, $subject );
		return !($result === $subject);
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
