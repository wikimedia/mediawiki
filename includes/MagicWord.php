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
	/*private*/ var $mRegexStart, $mBaseRegex;
	
	function MagicWord($id = 0, $syn = "", $cs = false) 
	{
		$this->mId = $id;
		$this->mSynonyms = (array)$syn;
		$this->mCaseSensitive = $cs;
		$this->mRegex = "";
		$this->mRegexStart = "";
	}

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
	
	function load( $id )
	{
		global $wgLang;
		
		$this->mId = $id;
		$wgLang->getMagic( $this );
	}
	
	/* private */ function initRegex()
	{
		$escSyn = array_map( "preg_quote", $this->mSynonyms );
		$this->mBaseRegex = implode( "|", $escSyn );
		$case = $this->mCaseSensitive ? "" : "i";
		$this->mRegex = "/{$this->mBaseRegex}/{$case}";
		$this->mRegexStart = "/^{$this->mBaseRegex}/{$case}";
	}
	
	function getRegex()
	{
		if ($this->mRegex == "" ) {
			$this->initRegex();
		}
		return $this->mRegex;
	}

	function getRegexStart()
	{
		if ($this->mRegex == "" ) {
			$this->initRegex();
		}
		return $this->mRegexStart;
	}
	
	function getBaseRegex()
	{
		if ($this->mRegex == "") {
			$this->initRegex();
		}
		return $this->mBaseRegex;
	}
		
	function match( $text ) {
		return preg_match( $this->getRegex(), $text );
	}

	function matchStart( $text ) 
	{
		return preg_match( $this->getRegexStart(), $text );
	}

	function matchAndRemove( &$text )
	{
		global $wgMagicFound;
		$wgMagicFound = false;
		$text = preg_replace_callback( $this->getRegex(), "pregRemoveAndRecord", $text );
		return $wgMagicFound;
	}

	function replace( $replacement, $subject )
	{
		return preg_replace( $this->getRegex(), $replacement, $subject );
	}
}

/*private*/ function pregRemoveAndRecord( $match )
{
	global $wgMagicFound;
	$wgMagicFound = true;
	return "";
}

?>
