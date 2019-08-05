<?php
/**
 * See docs/magicword.txt.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Parser
 */

use MediaWiki\MediaWikiServices;

/**
 * This class encapsulates "magic words" such as "#redirect", __NOTOC__, etc.
 *
 * @par Usage:
 * @code
 *     if ( $magicWordFactory->get( 'redirect' )->match( $text ) ) {
 *       // some code
 *     }
 * @endcode
 *
 * Please avoid reading the data out of one of these objects and then writing
 * special case code. If possible, add another match()-like function here.
 *
 * To add magic words in an extension, use $magicWords in a file listed in
 * $wgExtensionMessagesFiles[].
 *
 * @par Example:
 * @code
 * $magicWords = [];
 *
 * $magicWords['en'] = [
 *   'magicwordkey' => [ 0, 'case_insensitive_magic_word' ],
 *   'magicwordkey2' => [ 1, 'CASE_sensitive_magic_word2' ],
 * ];
 * @endcode
 *
 * For magic words which are also Parser variables, add a MagicWordwgVariableIDs
 * hook. Use string keys.
 *
 * @ingroup Parser
 */
class MagicWord {
	/** #@- */

	/** @var string */
	public $mId;

	/** @var string[] */
	public $mSynonyms;

	/** @var bool */
	public $mCaseSensitive;

	/** @var string */
	private $mRegex = '';

	/** @var string */
	private $mRegexStart = '';

	/** @var string */
	private $mRegexStartToEnd = '';

	/** @var string */
	private $mBaseRegex = '';

	/** @var string */
	private $mVariableRegex = '';

	/** @var string */
	private $mVariableStartToEndRegex = '';

	/** @var bool */
	private $mModified = false;

	/** @var bool */
	private $mFound = false;

	/** @var Language */
	private $contLang;

	/** #@- */

	/**
	 * Create a new MagicWord object
	 *
	 * Use factory instead: MagicWordFactory::get
	 *
	 * @param string|null $id The internal name of the magic word
	 * @param string[]|string $syn synonyms for the magic word
	 * @param bool $cs If magic word is case sensitive
	 * @param Language|null $contLang Content language
	 */
	public function __construct( $id = null, $syn = [], $cs = false, Language $contLang = null ) {
		$this->mId = $id;
		$this->mSynonyms = (array)$syn;
		$this->mCaseSensitive = $cs;
		$this->contLang = $contLang ?: MediaWikiServices::getInstance()->getContentLanguage();
	}

	/**
	 * Factory: creates an object representing an ID
	 *
	 * @param string $id The internal name of the magic word
	 *
	 * @return MagicWord
	 * @deprecated since 1.32, use MagicWordFactory::get
	 */
	public static function get( $id ) {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getMagicWordFactory()->get( $id );
	}

	/**
	 * Get an array of parser variable IDs
	 *
	 * @return string[]
	 * @deprecated since 1.32, use MagicWordFactory::getVariableIDs
	 */
	public static function getVariableIDs() {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getMagicWordFactory()->getVariableIDs();
	}

	/**
	 * Get an array of parser substitution modifier IDs
	 * @return string[]
	 * @deprecated since 1.32, use MagicWordFactory::getSubstIDs
	 */
	public static function getSubstIDs() {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getMagicWordFactory()->getSubstIDs();
	}

	/**
	 * Allow external reads of TTL array
	 *
	 * @param string $id
	 * @return int
	 * @deprecated since 1.32, use MagicWordFactory::getCacheTTL
	 */
	public static function getCacheTTL( $id ) {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getMagicWordFactory()->getCacheTTL( $id );
	}

	/**
	 * Get a MagicWordArray of double-underscore entities
	 *
	 * @return MagicWordArray
	 * @deprecated since 1.32, use MagicWordFactory::getDoubleUnderscoreArray
	 */
	public static function getDoubleUnderscoreArray() {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getMagicWordFactory()->getDoubleUnderscoreArray();
	}

	/**
	 * Initialises this object with an ID
	 *
	 * @param string $id
	 * @throws MWException
	 */
	public function load( $id ) {
		$this->mId = $id;
		$this->contLang->getMagic( $this );
		if ( !$this->mSynonyms ) {
			$this->mSynonyms = [ 'brionmademeputthishere' ];
			throw new MWException( "Error: invalid magic word '$id'" );
		}
	}

	/**
	 * Preliminary initialisation
	 * @private
	 */
	public function initRegex() {
		// Sort the synonyms by length, descending, so that the longest synonym
		// matches in precedence to the shortest
		$synonyms = $this->mSynonyms;
		usort( $synonyms, [ $this, 'compareStringLength' ] );

		$escSyn = [];
		foreach ( $synonyms as $synonym ) {
			// In case a magic word contains /, like that's going to happen;)
			$escSyn[] = preg_quote( $synonym, '/' );
		}
		$this->mBaseRegex = implode( '|', $escSyn );

		$case = $this->mCaseSensitive ? '' : 'iu';
		$this->mRegex = "/{$this->mBaseRegex}/{$case}";
		$this->mRegexStart = "/^(?:{$this->mBaseRegex})/{$case}";
		$this->mRegexStartToEnd = "/^(?:{$this->mBaseRegex})$/{$case}";
		$this->mVariableRegex = str_replace( "\\$1", "(.*?)", $this->mRegex );
		$this->mVariableStartToEndRegex = str_replace( "\\$1", "(.*?)",
			"/^(?:{$this->mBaseRegex})$/{$case}" );
	}

	/**
	 * A comparison function that returns -1, 0 or 1 depending on whether the
	 * first string is longer, the same length or shorter than the second
	 * string.
	 *
	 * @param string $s1
	 * @param string $s2
	 *
	 * @return int
	 */
	public function compareStringLength( $s1, $s2 ) {
		$l1 = strlen( $s1 );
		$l2 = strlen( $s2 );
		return $l2 <=> $l1; // descending
	}

	/**
	 * Gets a regex representing matching the word
	 *
	 * @return string
	 */
	public function getRegex() {
		if ( $this->mRegex == '' ) {
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
	public function getRegexCase() {
		if ( $this->mRegex === '' ) {
			$this->initRegex();
		}

		return $this->mCaseSensitive ? '' : 'iu';
	}

	/**
	 * Gets a regex matching the word, if it is at the string start
	 *
	 * @return string
	 */
	public function getRegexStart() {
		if ( $this->mRegex == '' ) {
			$this->initRegex();
		}
		return $this->mRegexStart;
	}

	/**
	 * Gets a regex matching the word from start to end of a string
	 *
	 * @return string
	 * @since 1.23
	 */
	public function getRegexStartToEnd() {
		if ( $this->mRegexStartToEnd == '' ) {
			$this->initRegex();
		}
		return $this->mRegexStartToEnd;
	}

	/**
	 * regex without the slashes and what not
	 *
	 * @return string
	 */
	public function getBaseRegex() {
		if ( $this->mRegex == '' ) {
			$this->initRegex();
		}
		return $this->mBaseRegex;
	}

	/**
	 * Returns true if the text contains the word
	 *
	 * @param string $text
	 *
	 * @return bool
	 */
	public function match( $text ) {
		return (bool)preg_match( $this->getRegex(), $text );
	}

	/**
	 * Returns true if the text starts with the word
	 *
	 * @param string $text
	 *
	 * @return bool
	 */
	public function matchStart( $text ) {
		return (bool)preg_match( $this->getRegexStart(), $text );
	}

	/**
	 * Returns true if the text matched the word
	 *
	 * @param string $text
	 *
	 * @return bool
	 * @since 1.23
	 */
	public function matchStartToEnd( $text ) {
		return (bool)preg_match( $this->getRegexStartToEnd(), $text );
	}

	/**
	 * Returns NULL if there's no match, the value of $1 otherwise
	 * The return code is the matched string, if there's no variable
	 * part in the regex and the matched variable part ($1) if there
	 * is one.
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function matchVariableStartToEnd( $text ) {
		$matches = [];
		$matchcount = preg_match( $this->getVariableStartToEndRegex(), $text, $matches );
		if ( $matchcount == 0 ) {
			return null;
		} else {
			# multiple matched parts (variable match); some will be empty because of
			# synonyms. The variable will be the second non-empty one so remove any
			# blank elements and re-sort the indices.
			# See also T8526

			$matches = array_values( array_filter( $matches ) );

			if ( count( $matches ) == 1 ) {
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
	 * @param string &$text
	 *
	 * @return bool
	 */
	public function matchAndRemove( &$text ) {
		$this->mFound = false;
		$text = preg_replace_callback(
			$this->getRegex(),
			[ $this, 'pregRemoveAndRecord' ],
			$text
		);

		return $this->mFound;
	}

	/**
	 * @param string &$text
	 * @return bool
	 */
	public function matchStartAndRemove( &$text ) {
		$this->mFound = false;
		$text = preg_replace_callback(
			$this->getRegexStart(),
			[ $this, 'pregRemoveAndRecord' ],
			$text
		);

		return $this->mFound;
	}

	/**
	 * Used in matchAndRemove()
	 *
	 * @return string
	 */
	public function pregRemoveAndRecord() {
		$this->mFound = true;
		return '';
	}

	/**
	 * Replaces the word with something else
	 *
	 * @param string $replacement
	 * @param string $subject
	 * @param int $limit
	 *
	 * @return string
	 */
	public function replace( $replacement, $subject, $limit = -1 ) {
		$res = preg_replace(
			$this->getRegex(),
			StringUtils::escapeRegexReplacement( $replacement ),
			$subject,
			$limit
		);
		$this->mModified = $res !== $subject;
		return $res;
	}

	/**
	 * Variable handling: {{SUBST:xxx}} style words
	 * Calls back a function to determine what to replace xxx with
	 * Input word must contain $1
	 *
	 * @param string $text
	 * @param callable $callback
	 *
	 * @return string
	 */
	public function substituteCallback( $text, $callback ) {
		$res = preg_replace_callback( $this->getVariableRegex(), $callback, $text );
		$this->mModified = $res !== $text;
		return $res;
	}

	/**
	 * Matches the word, where $1 is a wildcard
	 *
	 * @return string
	 */
	public function getVariableRegex() {
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
	public function getVariableStartToEndRegex() {
		if ( $this->mVariableStartToEndRegex == '' ) {
			$this->initRegex();
		}
		return $this->mVariableStartToEndRegex;
	}

	/**
	 * Accesses the synonym list directly
	 *
	 * @param int $i
	 *
	 * @return string
	 */
	public function getSynonym( $i ) {
		return $this->mSynonyms[$i];
	}

	/**
	 * @return string[]
	 */
	public function getSynonyms() {
		return $this->mSynonyms;
	}

	/**
	 * Returns true if the last call to replace() or substituteCallback()
	 * returned a modified text, otherwise false.
	 *
	 * @return bool
	 */
	public function getWasModified() {
		return $this->mModified;
	}

	/**
	 * Adds all the synonyms of this MagicWord to an array, to allow quick
	 * lookup in a list of magic words
	 *
	 * @param string[] &$array
	 * @param string $value
	 */
	public function addToArray( &$array, $value ) {
		foreach ( $this->mSynonyms as $syn ) {
			$array[$this->contLang->lc( $syn )] = $value;
		}
	}

	/**
	 * @return bool
	 */
	public function isCaseSensitive() {
		return $this->mCaseSensitive;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->mId;
	}
}
