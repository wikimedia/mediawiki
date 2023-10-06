<?php
/**
 * See docs/magicword.md.
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

namespace MediaWiki\Parser;

use Language;
use MediaWiki\MediaWikiServices;
use MWException;
use StringUtils;

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
 * For magic words which name Parser double underscore names, add a
 * GetDoubleUnderscoreIDs hook. Use string keys.
 *
 * For magic words which name Parser magic variables, add a GetMagicVariableIDs
 * hook. Use string keys.
 *
 * @ingroup Parser
 */
class MagicWord {
	/** #@- */

	/** @var string|null Potentially null for a short time before {@see load} is called */
	public $mId;

	/** @var string[] */
	public $mSynonyms;

	/** @var bool */
	public $mCaseSensitive;

	private ?string $mBaseRegex = null;

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
	 * Initialises this object with an ID
	 *
	 * @param string $id
	 * @throws MWException
	 */
	public function load( $id ) {
		$this->mId = $id;
		$this->contLang->getMagic( $this );
		if ( !$this->mSynonyms ) {
			throw new MWException( "Error: invalid magic word '$id'" );
		}
	}

	/**
	 * Gets a regex representing matching the word
	 *
	 * @return string
	 */
	public function getRegex() {
		return '/' . $this->getBaseRegex() . '/' . $this->getRegexCase();
	}

	/**
	 * Gets the regexp case modifier to use, i.e. i or nothing, to be used if
	 * one is using MagicWord::getBaseRegex(), otherwise it'll be included in
	 * the complete expression
	 *
	 * @return string
	 */
	public function getRegexCase() {
		return $this->mCaseSensitive ? '' : 'iu';
	}

	/**
	 * Gets a regex matching the word, if it is at the string start
	 *
	 * @return string
	 */
	public function getRegexStart() {
		return '/^(?:' . $this->getBaseRegex() . ')/' . $this->getRegexCase();
	}

	/**
	 * Gets a regex matching the word from start to end of a string
	 *
	 * @return string
	 * @since 1.23
	 */
	public function getRegexStartToEnd() {
		return '/^(?:' . $this->getBaseRegex() . ')$/' . $this->getRegexCase();
	}

	/**
	 * regex without the slashes and what not
	 *
	 * @return string
	 */
	public function getBaseRegex() {
		if ( $this->mBaseRegex === null ) {
			// Sort the synonyms by length, descending, so that the longest synonym
			// matches in precedence to the shortest
			$synonyms = $this->mSynonyms;
			usort( $synonyms, static fn ( $a, $b ) => strlen( $b ) <=> strlen( $a ) );
			foreach ( $synonyms as &$synonym ) {
				$synonym = preg_quote( $synonym, '/' );
			}
			$this->mBaseRegex = implode( '|', $synonyms );
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
	 * Returns true if the text matches the word, and alters the
	 * input string, removing all instances of the word
	 *
	 * @param string &$text
	 *
	 * @return bool
	 */
	public function matchAndRemove( &$text ) {
		$text = preg_replace( $this->getRegex(), '', $text, -1, $count );
		return (bool)$count;
	}

	/**
	 * @param string &$text
	 * @return bool
	 */
	public function matchStartAndRemove( &$text ) {
		$text = preg_replace( $this->getRegexStart(), '', $text, -1, $count );
		return (bool)$count;
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
		return $res;
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

/**
 * @deprecated since 1.40
 */
class_alias( MagicWord::class, 'MagicWord' );
