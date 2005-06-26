<?php
/**
 * File for magic words
 * @package MediaWiki
 * @subpackage Parser
 */

/**
 * private
 */
$wgMagicFound = false;

/** Actual keyword to be used is set in Language.php */
define('MAG_REDIRECT',			0);
define('MAG_NOTOC',			1);
define('MAG_START',			2);
define('MAG_CURRENTMONTH',		3);
define('MAG_CURRENTMONTHNAME',		4);
define('MAG_CURRENTMONTHNAMEGEN',	5);
define('MAG_CURRENTMONTHABBREV',	6);
define('MAG_CURRENTDAY',		7);
define('MAG_CURRENTDAYNAME',		8);
define('MAG_CURRENTYEAR',		9);
define('MAG_CURRENTTIME',		10);
define('MAG_NUMBEROFARTICLES',		11);
define('MAG_SUBST',			12);
define('MAG_MSG',			13);
define('MAG_MSGNW',			14);
define('MAG_NOEDITSECTION',		15);
define('MAG_END',			16);
define('MAG_IMG_THUMBNAIL',		17);
define('MAG_IMG_RIGHT',			18);
define('MAG_IMG_LEFT',			19);
define('MAG_IMG_NONE',			20);
define('MAG_IMG_WIDTH',			21);
define('MAG_IMG_CENTER',		22);
define('MAG_INT',			23);
define('MAG_FORCETOC',			24);
define('MAG_SITENAME',			25);
define('MAG_NS',			26);
define('MAG_LOCALURL',			27);
define('MAG_LOCALURLE',			28);
define('MAG_SERVER',			29);
define('MAG_IMG_FRAMED',		30);
define('MAG_PAGENAME',			31);
define('MAG_PAGENAMEE',			32);
define('MAG_NAMESPACE',			33);
define('MAG_TOC',			34);
define('MAG_GRAMMAR',			35);
define('MAG_NOTITLECONVERT',		36);
define('MAG_NOCONTENTCONVERT',		37);
define('MAG_CURRENTWEEK',		38);
define('MAG_CURRENTDOW',		39);
define('MAG_REVISIONID',		40);
define('MAG_SCRIPTPATH',		41);
define('MAG_SERVERNAME',		42);
define('MAG_NUMBEROFFILES',		43);

$wgVariableIDs = array(
	MAG_CURRENTMONTH,
	MAG_CURRENTMONTHNAME,
	MAG_CURRENTMONTHNAMEGEN,
	MAG_CURRENTMONTHABBREV,
	MAG_CURRENTDAY,
	MAG_CURRENTDAYNAME,
	MAG_CURRENTYEAR,
	MAG_CURRENTTIME,
	MAG_NUMBEROFARTICLES,
	MAG_NUMBEROFFILES,
	MAG_SITENAME,
	MAG_SERVER,
	MAG_SERVERNAME,
	MAG_SCRIPTPATH,
	MAG_PAGENAME,
	MAG_PAGENAMEE,
	MAG_NAMESPACE,
	MAG_CURRENTWEEK,
	MAG_CURRENTDOW,
	MAG_REVISIONID,
);

/**
 * This class encapsulates "magic words" such as #redirect, __NOTOC__, etc.
 * Usage:
 *     if (MagicWord::get( MAG_REDIRECT )->match( $text ) )
 * 
 * Possible future improvements: 
 *   * Simultaneous searching for a number of magic words
 *   * $wgMagicWords in shared memory
 *
 * Please avoid reading the data out of one of these objects and then writing 
 * special case code. If possible, add another match()-like function here.
 *
 * @package MediaWiki
 */
class MagicWord {
	/**#@+
	 * @access private
	 */
	var $mId, $mSynonyms, $mCaseSensitive, $mRegex;
	var $mRegexStart, $mBaseRegex, $mVariableRegex;
	var $mModified;	
	/**#@-*/

	function MagicWord($id = 0, $syn = '', $cs = false) {
		$this->mId = $id;
		$this->mSynonyms = (array)$syn;
		$this->mCaseSensitive = $cs;
		$this->mRegex = '';
		$this->mRegexStart = '';
		$this->mVariableRegex = '';
		$this->mVariableStartToEndRegex = '';
		$this->mModified = false;
	}

	/**
	 * Factory: creates an object representing an ID
	 * @static
	 */
	function &get( $id ) {
		global $wgMagicWords;
		
		if ( !is_array( $wgMagicWords ) ) {
			wfDebugDieBacktrace( "Incorrect initialisation order, \$wgMagicWords does not exist\n" );
		}
		if (!array_key_exists( $id, $wgMagicWords ) ) {
			$mw = new MagicWord();
			$mw->load( $id );
			$wgMagicWords[$id] = $mw;
		}
		return $wgMagicWords[$id];
	}
	
	# Initialises this object with an ID
	function load( $id ) {
		global $wgContLang;		
		$this->mId = $id;
		$wgContLang->getMagic( $this );
	}
	
	/**
	 * Preliminary initialisation
	 * @private
	 */
	function initRegex() {
		#$variableClass = Title::legalChars();
		# This was used for matching "$1" variables, but different uses of the feature will have
		# different restrictions, which should be checked *after* the MagicWord has been matched,
		# not here. - IMSoP
		$escSyn = array_map( 'preg_quote', $this->mSynonyms );
		$this->mBaseRegex = implode( '|', $escSyn );
		$case = $this->mCaseSensitive ? '' : 'i';
		$this->mRegex = "/{$this->mBaseRegex}/{$case}";
		$this->mRegexStart = "/^(?:{$this->mBaseRegex})/{$case}";
		$this->mVariableRegex = str_replace( "\\$1", "(.*?)", $this->mRegex );
		$this->mVariableStartToEndRegex = str_replace( "\\$1", "(.*?)", 
			"/^(?:{$this->mBaseRegex})$/{$case}" );
	}
	
	/**
	 * Gets a regex representing matching the word
	 */
	function getRegex() {
		if ($this->mRegex == '' ) {
			$this->initRegex();
		}
		return $this->mRegex;
	}

	/**
	 * Gets a regex matching the word, if it is at the string start
	 */
	function getRegexStart() {
		if ($this->mRegex == '' ) {
			$this->initRegex();
		}
		return $this->mRegexStart;
	}

	/**
	 * regex without the slashes and what not
	 */
	function getBaseRegex() {
		if ($this->mRegex == '') {
			$this->initRegex();
		}
		return $this->mBaseRegex;
	}
		
	/**
	 * Returns true if the text contains the word
	 * @return bool
	 */
	function match( $text ) {
		return preg_match( $this->getRegex(), $text );
	}

	/**
	 * Returns true if the text starts with the word
	 * @return bool
	 */
	function matchStart( $text ) {
		return preg_match( $this->getRegexStart(), $text );
	}

	/**
	 * Returns NULL if there's no match, the value of $1 otherwise
	 * The return code is the matched string, if there's no variable
	 * part in the regex and the matched variable part ($1) if there
	 * is one.
	 */
	function matchVariableStartToEnd( $text ) {
		$matchcount = preg_match( $this->getVariableStartToEndRegex(), $text, $matches );
		if ( $matchcount == 0 ) {
			return NULL;
		} elseif ( count($matches) == 1 ) {
			return $matches[0];
		} else {
			# multiple matched parts (variable match); some will be empty because of synonyms
			# the variable will be the second non-empty one so remove any blank elements and re-sort the indices
			$matches = array_values(array_filter($matches));
			return $matches[1];
		}
	}


	/**
	 * Returns true if the text matches the word, and alters the
	 * input string, removing all instances of the word
	 */
	function matchAndRemove( &$text ) {
		global $wgMagicFound;
		$wgMagicFound = false;
		$text = preg_replace_callback( $this->getRegex(), 'pregRemoveAndRecord', $text );
		return $wgMagicFound;
	}

	function matchStartAndRemove( &$text ) {
		global $wgMagicFound;
		$wgMagicFound = false;
		$text = preg_replace_callback( $this->getRegexStart(), 'pregRemoveAndRecord', $text );
		return $wgMagicFound;
	}		


	/**
	 * Replaces the word with something else
	 */
	function replace( $replacement, $subject ) {
		$res = preg_replace( $this->getRegex(), $replacement, $subject );
		$this->mModified = !($res === $subject);
		return $res;
	}

	/**
	 * Variable handling: {{SUBST:xxx}} style words
	 * Calls back a function to determine what to replace xxx with
	 * Input word must contain $1
	 */
	function substituteCallback( $text, $callback ) {
		$regex = $this->getVariableRegex();
		$res = preg_replace_callback( $this->getVariableRegex(), $callback, $text );
		$this->mModified = !($res === $text);
		return $res;
	}

	/**
	 * Matches the word, where $1 is a wildcard
	 */
	function getVariableRegex()	{
		if ( $this->mVariableRegex == '' ) {
			$this->initRegex();
		} 
		return $this->mVariableRegex;
	}

	/**
	 * Matches the entire string, where $1 is a wildcard
	 */
	function getVariableStartToEndRegex() {
		if ( $this->mVariableStartToEndRegex == '' ) {
			$this->initRegex();
		} 
		return $this->mVariableStartToEndRegex;
	}

	/**
	 * Accesses the synonym list directly
	 */
	function getSynonym( $i ) {
		return $this->mSynonyms[$i];
	}

	/**
	 * Returns true if the last call to replace() or substituteCallback() 
	 * returned a modified text, otherwise false.
	 */
	function getWasModified(){
		return $this->mModified;
	}

	/**
	 * $magicarr is an associative array of (magic word ID => replacement)
	 * This method uses the php feature to do several replacements at the same time,
	 * thereby gaining some efficiency. The result is placed in the out variable
	 * $result. The return value is true if something was replaced.
	 * @static 
	 **/
	function replaceMultiple( $magicarr, $subject, &$result ){
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

	/**
	 * Adds all the synonyms of this MagicWord to an array, to allow quick
	 * lookup in a list of magic words
	 */
	function addToArray( &$array, $value ) {
		foreach ( $this->mSynonyms as $syn ) {
			$array[$syn] = $value;
		}
	}
}

/**
 * Used in matchAndRemove()
 * @private
 **/
function pregRemoveAndRecord( $match ) {
	global $wgMagicFound;
	$wgMagicFound = true;
	return '';
}

?>
