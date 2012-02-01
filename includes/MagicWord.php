<?php
/**
 * File for magic words
 *
 * See docs/magicword.txt
 *
 * @file
 * @ingroup Parser
 */

/**
 * This class encapsulates "magic words" such as #redirect, __NOTOC__, etc.
 *
 * @par Usage:
 * @code
 *     if (MagicWord::get( 'redirect' )->match( $text ) ) {
 *       // some code
 *     }
 * @endcode
 *
 * Possible future improvements:
 *   * Simultaneous searching for a number of magic words
 *   * MagicWord::$mObjects in shared memory
 *
 * Please avoid reading the data out of one of these objects and then writing
 * special case code. If possible, add another match()-like function here.
 *
 * To add magic words in an extension, use $magicWords in a file listed in
 * $wgExtensionMessagesFiles[].
 * 
 * @par Example:
 * @code
 * $magicWords = array();
 *
 * $magicWords['en'] = array(
 * 	'magicwordkey' => array( 0, 'case_insensitive_magic_word' ),
 * 	'magicwordkey2' => array( 1, 'CASE_sensitive_magic_word2' ),
 * );
 * @endcode
 *
 * For magic words which are also Parser variables, add a MagicWordwgVariableIDs
 * hook. Use string keys.
 *
 * @ingroup Parser
 */
class MagicWord {
	/**#@+
	 * @private
	 */
	var $mId, $mSynonyms, $mCaseSensitive;
	var $mRegex = '';
	var $mRegexStart = '';
	var $mBaseRegex = '';
	var $mVariableRegex = '';
	var $mVariableStartToEndRegex = '';
	var $mModified = false;
	var $mFound = false;

	static public $mVariableIDsInitialised = false;
	static public $mVariableIDs = array(
		'currentmonth',
		'currentmonth1',
		'currentmonthname',
		'currentmonthnamegen',
		'currentmonthabbrev',
		'currentday',
		'currentday2',
		'currentdayname',
		'currentyear',
		'currenttime',
		'currenthour',
		'localmonth',
		'localmonth1',
		'localmonthname',
		'localmonthnamegen',
		'localmonthabbrev',
		'localday',
		'localday2',
		'localdayname',
		'localyear',
		'localtime',
		'localhour',
		'numberofarticles',
		'numberoffiles',
		'numberofedits',
		'articlepath',
		'sitename',
		'server',
		'servername',
		'scriptpath',
		'stylepath',
		'pagename',
		'pagenamee',
		'fullpagename',
		'fullpagenamee',
		'namespace',
		'namespacee',
		'currentweek',
		'currentdow',
		'localweek',
		'localdow',
		'revisionid',
		'revisionday',
		'revisionday2',
		'revisionmonth',
		'revisionmonth1',
		'revisionyear',
		'revisiontimestamp',
		'revisionuser',
		'subpagename',
		'subpagenamee',
		'talkspace',
		'talkspacee',
		'subjectspace',
		'subjectspacee',
		'talkpagename',
		'talkpagenamee',
		'subjectpagename',
		'subjectpagenamee',
		'numberofusers',
		'numberofactiveusers',
		'numberofpages',
		'currentversion',
		'basepagename',
		'basepagenamee',
		'currenttimestamp',
		'localtimestamp',
		'directionmark',
		'contentlanguage',
		'numberofadmins',
		'numberofviews',
	);

	/* Array of caching hints for ParserCache */
	static public $mCacheTTLs = array (
		'currentmonth' => 86400,
		'currentmonth1' => 86400,
		'currentmonthname' => 86400,
		'currentmonthnamegen' => 86400,
		'currentmonthabbrev' => 86400,
		'currentday' => 3600,
		'currentday2' => 3600,
		'currentdayname' => 3600,
		'currentyear' => 86400,
		'currenttime' => 3600,
		'currenthour' => 3600,
		'localmonth' => 86400,
		'localmonth1' => 86400,
		'localmonthname' => 86400,
		'localmonthnamegen' => 86400,
		'localmonthabbrev' => 86400,
		'localday' => 3600,
		'localday2' => 3600,
		'localdayname' => 3600,
		'localyear' => 86400,
		'localtime' => 3600,
		'localhour' => 3600,
		'numberofarticles' => 3600,
		'numberoffiles' => 3600,
		'numberofedits' => 3600,
		'currentweek' => 3600,
		'currentdow' => 3600,
		'localweek' => 3600,
		'localdow' => 3600,
		'numberofusers' => 3600,
		'numberofactiveusers' => 3600,
		'numberofpages' => 3600,
		'currentversion' => 86400,
		'currenttimestamp' => 3600,
		'localtimestamp' => 3600,
		'pagesinnamespace' => 3600,
		'numberofadmins' => 3600,
		'numberofviews' => 3600,
		'numberingroup' => 3600,
		);

	static public $mDoubleUnderscoreIDs = array(
		'notoc',
		'nogallery',
		'forcetoc',
		'toc',
		'noeditsection',
		'newsectionlink',
		'nonewsectionlink',
		'hiddencat',
		'index',
		'noindex',
		'staticredirect',
		'notitleconvert',
		'nocontentconvert',
	);

	static public $mSubstIDs = array(
		'subst',
		'safesubst',
	);

	static public $mObjects = array();
	static public $mDoubleUnderscoreArray = null;

	/**#@-*/

	function __construct($id = 0, $syn = array(), $cs = false) {
		$this->mId = $id;
		$this->mSynonyms = (array)$syn;
		$this->mCaseSensitive = $cs;
	}

	/**
	 * Factory: creates an object representing an ID
	 *
	 * @param $id
	 *
	 * @return MagicWord
	 */
	static function &get( $id ) {
		if ( !isset( self::$mObjects[$id] ) ) {
			$mw = new MagicWord();
			$mw->load( $id );
			self::$mObjects[$id] = $mw;
		}
		return self::$mObjects[$id];
	}

	/**
	 * Get an array of parser variable IDs
	 *
	 * @return array
	 */
	static function getVariableIDs() {
		if ( !self::$mVariableIDsInitialised ) {
			# Get variable IDs
			wfRunHooks( 'MagicWordwgVariableIDs', array( &self::$mVariableIDs ) );
			self::$mVariableIDsInitialised = true;
		}
		return self::$mVariableIDs;
	}

	/**
	 * Get an array of parser substitution modifier IDs
	 * @return array
	 */
	static function getSubstIDs() {
		return self::$mSubstIDs;
	}

	/**
	 * Allow external reads of TTL array
	 *
	 * @param $id int
	 * @return array
	 */
	static function getCacheTTL( $id ) {
		if ( array_key_exists( $id, self::$mCacheTTLs ) ) {
			return self::$mCacheTTLs[$id];
		} else {
			return -1;
		}
	}

	/**
	 * Get a MagicWordArray of double-underscore entities
	 *
	 * @return MagicWordArray
	 */
	static function getDoubleUnderscoreArray() {
		if ( is_null( self::$mDoubleUnderscoreArray ) ) {
			self::$mDoubleUnderscoreArray = new MagicWordArray( self::$mDoubleUnderscoreIDs );
		}
		return self::$mDoubleUnderscoreArray;
	}

	/**
	 * Clear the self::$mObjects variable
	 * For use in parser tests
	 */
	public static function clearCache() {
		self::$mObjects = array();
	}

	/**
	 * Initialises this object with an ID
	 *
	 * @param $id
	 */
	function load( $id ) {
		global $wgContLang;
		wfProfileIn( __METHOD__ );
		$this->mId = $id;
		$wgContLang->getMagic( $this );
		if ( !$this->mSynonyms ) {
			$this->mSynonyms = array( 'dkjsagfjsgashfajsh' );
			#throw new MWException( "Error: invalid magic word '$id'" );
			wfDebugLog( 'exception', "Error: invalid magic word '$id'\n" );
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Preliminary initialisation
	 * @private
	 */
	function initRegex() {
		// Sort the synonyms by length, descending, so that the longest synonym
		// matches in precedence to the shortest
		$synonyms = $this->mSynonyms;
		usort( $synonyms, array( $this, 'compareStringLength' ) );

		$escSyn = array();
		foreach ( $synonyms as $synonym )
			// In case a magic word contains /, like that's going to happen;)
			$escSyn[] = preg_quote( $synonym, '/' );
		$this->mBaseRegex = implode( '|', $escSyn );

		$case = $this->mCaseSensitive ? '' : 'iu';
		$this->mRegex = "/{$this->mBaseRegex}/{$case}";
		$this->mRegexStart = "/^(?:{$this->mBaseRegex})/{$case}";
		$this->mVariableRegex = str_replace( "\\$1", "(.*?)", $this->mRegex );
		$this->mVariableStartToEndRegex = str_replace( "\\$1", "(.*?)",
			"/^(?:{$this->mBaseRegex})$/{$case}" );
	}

	/**
	 * A comparison function that returns -1, 0 or 1 depending on whether the
	 * first string is longer, the same length or shorter than the second
	 * string.
	 *
	 * @param $s1 string
	 * @param $s2 string
	 *
	 * @return int
	 */
	function compareStringLength( $s1, $s2 ) {
		$l1 = strlen( $s1 );
		$l2 = strlen( $s2 );
		if ( $l1 < $l2 ) {
			return 1;
		} elseif ( $l1 > $l2 ) {
			return -1;
		} else {
			return 0;
		}
	}

	/**
	 * Gets a regex representing matching the word
	 *
	 * @return string
	 */
	function getRegex() {
		if ($this->mRegex == '' ) {
			$this->initRegex();
		}
		return $this->mRegex;
	}

	/**
	 * Gets the regexp case modifier to use, i.e. i or nothing, to be used if
	 * one is using MagicWord::getBaseRegex(), otherwise it'll be included in
	 * the complete expression
	 *
	 * @return string
	 */
	function getRegexCase() {
		if ( $this->mRegex === '' )
			$this->initRegex();

		return $this->mCaseSensitive ? '' : 'iu';
	}

	/**
	 * Gets a regex matching the word, if it is at the string start
	 *
	 * @return string
	 */
	function getRegexStart() {
		if ($this->mRegex == '' ) {
			$this->initRegex();
		}
		return $this->mRegexStart;
	}

	/**
	 * regex without the slashes and what not
	 *
	 * @return string
	 */
	function getBaseRegex() {
		if ($this->mRegex == '') {
			$this->initRegex();
		}
		return $this->mBaseRegex;
	}

	/**
	 * Returns true if the text contains the word
	 *
	 * @param $text string
	 *
	 * @return bool
	 */
	function match( $text ) {
		return (bool)preg_match( $this->getRegex(), $text );
	}

	/**
	 * Returns true if the text starts with the word
	 *
	 * @param $text string
	 *
	 * @return bool
	 */
	function matchStart( $text ) {
		return (bool)preg_match( $this->getRegexStart(), $text );
	}

	/**
	 * Returns NULL if there's no match, the value of $1 otherwise
	 * The return code is the matched string, if there's no variable
	 * part in the regex and the matched variable part ($1) if there
	 * is one.
	 *
	 * @param $text string
	 *
	 * @return string
	 */
	function matchVariableStartToEnd( $text ) {
		$matches = array();
		$matchcount = preg_match( $this->getVariableStartToEndRegex(), $text, $matches );
		if ( $matchcount == 0 ) {
			return null;
		} else {
			# multiple matched parts (variable match); some will be empty because of
			# synonyms. The variable will be the second non-empty one so remove any
			# blank elements and re-sort the indices.
			# See also bug 6526

			$matches = array_values(array_filter($matches));

			if ( count($matches) == 1 ) {
				return $matches[0];
			} else {
				return $matches[1];
			}
		}
	}


	/**
	 * Returns true if the text matches the word, and alters the
	 * input string, removing all instances of the word
	 *
	 * @param $text string
	 *
	 * @return bool
	 */
	function matchAndRemove( &$text ) {
		$this->mFound = false;
		$text = preg_replace_callback( $this->getRegex(), array( &$this, 'pregRemoveAndRecord' ), $text );
		return $this->mFound;
	}

	/**
	 * @param  $text
	 * @return bool
	 */
	function matchStartAndRemove( &$text ) {
		$this->mFound = false;
		$text = preg_replace_callback( $this->getRegexStart(), array( &$this, 'pregRemoveAndRecord' ), $text );
		return $this->mFound;
	}

	/**
	 * Used in matchAndRemove()
	 *
	 * @return string
	 */
	function pregRemoveAndRecord() {
		$this->mFound = true;
		return '';
	}

	/**
	 * Replaces the word with something else
	 *
	 * @param $replacement
	 * @param $subject
	 * @param $limit int
	 *
	 * @return string
	 */
	function replace( $replacement, $subject, $limit = -1 ) {
		$res = preg_replace( $this->getRegex(), StringUtils::escapeRegexReplacement( $replacement ), $subject, $limit );
		$this->mModified = !($res === $subject);
		return $res;
	}

	/**
	 * Variable handling: {{SUBST:xxx}} style words
	 * Calls back a function to determine what to replace xxx with
	 * Input word must contain $1
	 *
	 * @param $text string
	 * @param $callback
	 *
	 * @return string
	 */
	function substituteCallback( $text, $callback ) {
		$res = preg_replace_callback( $this->getVariableRegex(), $callback, $text );
		$this->mModified = !($res === $text);
		return $res;
	}

	/**
	 * Matches the word, where $1 is a wildcard
	 *
	 * @return string
	 */
	function getVariableRegex()	{
		if ( $this->mVariableRegex == '' ) {
			$this->initRegex();
		}
		return $this->mVariableRegex;
	}

	/**
	 * Matches the entire string, where $1 is a wildcard
	 *
	 * @return string
	 */
	function getVariableStartToEndRegex() {
		if ( $this->mVariableStartToEndRegex == '' ) {
			$this->initRegex();
		}
		return $this->mVariableStartToEndRegex;
	}

	/**
	 * Accesses the synonym list directly
	 *
	 * @param $i int
	 *
	 * @return string
	 */
	function getSynonym( $i ) {
		return $this->mSynonyms[$i];
	}

	/**
	 * @return array
	 */
	function getSynonyms() {
		return $this->mSynonyms;
	}

	/**
	 * Returns true if the last call to replace() or substituteCallback()
	 * returned a modified text, otherwise false.
	 *
	 * @return bool
	 */
	function getWasModified(){
		return $this->mModified;
	}

	/**
	 * $magicarr is an associative array of (magic word ID => replacement)
	 * This method uses the php feature to do several replacements at the same time,
	 * thereby gaining some efficiency. The result is placed in the out variable
	 * $result. The return value is true if something was replaced.
	 * @todo Should this be static? It doesn't seem to be used at all
	 *
	 * @param $magicarr
	 * @param $subject
	 * @param $result
	 *
	 * @return bool
	 */
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
	 *
	 * @param $array
	 * @param $value
	 */
	function addToArray( &$array, $value ) {
		global $wgContLang;
		foreach ( $this->mSynonyms as $syn ) {
			$array[$wgContLang->lc($syn)] = $value;
		}
	}

	/**
	 * @return bool
	 */
	function isCaseSensitive() {
		return $this->mCaseSensitive;
	}

	/**
	 * @return int
	 */
	function getId() {
		return $this->mId;
	}
}

/**
 * Class for handling an array of magic words
 * @ingroup Parser
 */
class MagicWordArray {
	var $names = array();
	var $hash;
	var $baseRegex, $regex;
	var $matches;

	function __construct( $names = array() ) {
		$this->names = $names;
	}

	/**
	 * Add a magic word by name
	 *
	 * @param $name string
	 */
	public function add( $name ) {
		$this->names[] = $name;
		$this->hash = $this->baseRegex = $this->regex = null;
	}

	/**
	 * Add a number of magic words by name
	 *
	 * @param $names array
	 */
	public function addArray( $names ) {
		$this->names = array_merge( $this->names, array_values( $names ) );
		$this->hash = $this->baseRegex = $this->regex = null;
	}

	/**
	 * Get a 2-d hashtable for this array
	 */
	function getHash() {
		if ( is_null( $this->hash ) ) {
			global $wgContLang;
			$this->hash = array( 0 => array(), 1 => array() );
			foreach ( $this->names as $name ) {
				$magic = MagicWord::get( $name );
				$case = intval( $magic->isCaseSensitive() );
				foreach ( $magic->getSynonyms() as $syn ) {
					if ( !$case ) {
						$syn = $wgContLang->lc( $syn );
					}
					$this->hash[$case][$syn] = $name;
				}
			}
		}
		return $this->hash;
	}

	/**
	 * Get the base regex
	 */
	function getBaseRegex() {
		if ( is_null( $this->baseRegex ) ) {
			$this->baseRegex = array( 0 => '', 1 => '' );
			foreach ( $this->names as $name ) {
				$magic = MagicWord::get( $name );
				$case = intval( $magic->isCaseSensitive() );
				foreach ( $magic->getSynonyms() as $i => $syn ) {
					$group = "(?P<{$i}_{$name}>" . preg_quote( $syn, '/' ) . ')';
					if ( $this->baseRegex[$case] === '' ) {
						$this->baseRegex[$case] = $group;
					} else {
						$this->baseRegex[$case] .= '|' . $group;
					}
				}
			}
		}
		return $this->baseRegex;
	}

	/**
	 * Get an unanchored regex that does not match parameters
	 */
	function getRegex() {
		if ( is_null( $this->regex ) ) {
			$base = $this->getBaseRegex();
			$this->regex = array( '', '' );
			if ( $this->baseRegex[0] !== '' ) {
				$this->regex[0] = "/{$base[0]}/iuS";
			}
			if ( $this->baseRegex[1] !== '' ) {
				$this->regex[1] = "/{$base[1]}/S";
			}
		}
		return $this->regex;
	}

	/**
	 * Get a regex for matching variables with parameters
	 *
	 * @return string
	 */
	function getVariableRegex() {
		return str_replace( "\\$1", "(.*?)", $this->getRegex() );
	}

	/**
	 * Get a regex anchored to the start of the string that does not match parameters
	 *
	 * @return array
	 */
	function getRegexStart() {
		$base = $this->getBaseRegex();
		$newRegex = array( '', '' );
		if ( $base[0] !== '' ) {
			$newRegex[0] = "/^(?:{$base[0]})/iuS";
		}
		if ( $base[1] !== '' ) {
			$newRegex[1] = "/^(?:{$base[1]})/S";
		}
		return $newRegex;
	}

	/**
	 * Get an anchored regex for matching variables with parameters
	 *
	 * @return array
	 */
	function getVariableStartToEndRegex() {
		$base = $this->getBaseRegex();
		$newRegex = array( '', '' );
		if ( $base[0] !== '' ) {
			$newRegex[0] = str_replace( "\\$1", "(.*?)", "/^(?:{$base[0]})$/iuS" );
		}
		if ( $base[1] !== '' ) {
			$newRegex[1] = str_replace( "\\$1", "(.*?)", "/^(?:{$base[1]})$/S" );
		}
		return $newRegex;
	}

	/**
	 * Parse a match array from preg_match
	 * Returns array(magic word ID, parameter value)
	 * If there is no parameter value, that element will be false.
	 *
	 * @param $m array
	 *
	 * @return array
	 */
	function parseMatch( $m ) {
		reset( $m );
		while ( list( $key, $value ) = each( $m ) ) {
			if ( $key === 0 || $value === '' ) {
				continue;
			}
			$parts = explode( '_', $key, 2 );
			if ( count( $parts ) != 2 ) {
				// This shouldn't happen
				// continue;
				throw new MWException( __METHOD__ . ': bad parameter name' );
			}
			list( /* $synIndex */, $magicName ) = $parts;
			$paramValue = next( $m );
			return array( $magicName, $paramValue );
		}
		// This shouldn't happen either
		throw new MWException( __METHOD__.': parameter not found' );
	}

	/**
	 * Match some text, with parameter capture
	 * Returns an array with the magic word name in the first element and the
	 * parameter in the second element.
	 * Both elements are false if there was no match.
	 *
	 * @param $text string
	 *
	 * @return array
	 */
	public function matchVariableStartToEnd( $text ) {
		$regexes = $this->getVariableStartToEndRegex();
		foreach ( $regexes as $regex ) {
			if ( $regex !== '' ) {
				$m = false;
				if ( preg_match( $regex, $text, $m ) ) {
					return $this->parseMatch( $m );
				}
			}
		}
		return array( false, false );
	}

	/**
	 * Match some text, without parameter capture
	 * Returns the magic word name, or false if there was no capture
	 *
	 * @param $text string
	 *
	 * @return string|false
	 */
	public function matchStartToEnd( $text ) {
		$hash = $this->getHash();
		if ( isset( $hash[1][$text] ) ) {
			return $hash[1][$text];
		}
		global $wgContLang;
		$lc = $wgContLang->lc( $text );
		if ( isset( $hash[0][$lc] ) ) {
			return $hash[0][$lc];
		}
		return false;
	}

	/**
	 * Returns an associative array, ID => param value, for all items that match
	 * Removes the matched items from the input string (passed by reference)
	 *
	 * @param $text string
	 *
	 * @return array
	 */
	public function matchAndRemove( &$text ) {
		$found = array();
		$regexes = $this->getRegex();
		foreach ( $regexes as $regex ) {
			if ( $regex === '' ) {
				continue;
			}
			preg_match_all( $regex, $text, $matches, PREG_SET_ORDER );
			foreach ( $matches as $m ) {
				list( $name, $param ) = $this->parseMatch( $m );
				$found[$name] = $param;
			}
			$text = preg_replace( $regex, '', $text );
		}
		return $found;
	}

	/**
	 * Return the ID of the magic word at the start of $text, and remove
	 * the prefix from $text.
	 * Return false if no match found and $text is not modified.
	 * Does not match parameters.
	 *
	 * @param $text string
	 *
	 * @return int|false
	 */
	public function matchStartAndRemove( &$text ) {
		$regexes = $this->getRegexStart();
		foreach ( $regexes as $regex ) {
			if ( $regex === '' ) {
				continue;
			}
			if ( preg_match( $regex, $text, $m ) ) {
				list( $id, ) = $this->parseMatch( $m );
				if ( strlen( $m[0] ) >= strlen( $text ) ) {
					$text = '';
				} else {
					$text = substr( $text, strlen( $m[0] ) );
				}
				return $id;
			}
		}
		return false;
	}
}
