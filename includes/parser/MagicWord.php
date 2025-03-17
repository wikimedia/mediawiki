<?php
/**
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
 */

namespace MediaWiki\Parser;

use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use StringUtils;
use UnexpectedValueException;

/**
 * This class encapsulates "magic words" such as "#redirect", __NOTOC__, etc.
 *
 * See docs/magicword.md.
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
 * @since 1.1
 * @ingroup Parser
 */
class MagicWord {

	/** @var string|null Potentially null for a short time before {@see load} is called */
	public $mId;

	/** @var string[] */
	public array $mSynonyms;

	/** @var bool */
	public $mCaseSensitive;

	private ?string $mBaseRegex = null;

	private Language $contLang;

	/**
	 * @internal Use {@see MagicWordFactory::get} instead
	 * @param string|null $id Preload internal name of the magic word
	 * @param string[]|string $syn Preload synonyms for the magic word
	 * @param bool $cs If magic word is case sensitive
	 * @param Language|null $contentLanguage
	 */
	public function __construct( $id = null, $syn = [], $cs = false, ?Language $contentLanguage = null ) {
		$this->mId = $id;
		$this->mSynonyms = (array)$syn;
		$this->mCaseSensitive = $cs;
		$this->contLang = $contentLanguage ?: MediaWikiServices::getInstance()->getContentLanguage();
	}

	/**
	 * Load synonym data from {@see LocalisationCache}.
	 *
	 * @internal For use by {@see MagicWordFactory::get} only
	 * @since 1.1
	 * @param string $id
	 */
	public function load( $id ): void {
		$this->mId = $id;
		$this->contLang->getMagic( $this );
		if ( !$this->mSynonyms ) {
			throw new UnexpectedValueException( "Error: invalid magic word '$id'" );
		}
	}

	/**
	 * Create a regex to match the magic word in wikitext
	 *
	 * @since 1.1
	 * @return string
	 */
	public function getRegex(): string {
		return '/' . $this->getBaseRegex() . '/' . $this->getRegexCase();
	}

	/**
	 * Get the regexp case modifier ("iu" or empty string).
	 *
	 * This is for building custom regexes that include {@see getBaseRegex}.
	 * The other getter methods return complete expressions that include this already.
	 *
	 * @internal Exposed for {@see Parser::cleanSig} only
	 * @return string
	 */
	public function getRegexCase(): string {
		return $this->mCaseSensitive ? '' : 'iu';
	}

	/**
	 * Create a regex to match the word at the start of a line in wikitext
	 *
	 * @since 1.1
	 * @return string
	 */
	public function getRegexStart(): string {
		return '/^(?:' . $this->getBaseRegex() . ')/' . $this->getRegexCase();
	}

	/**
	 * Create a regex to match the word as the only thing on a line of wikitext
	 *
	 * @since 1.23
	 * @return string
	 */
	public function getRegexStartToEnd(): string {
		return '/^(?:' . $this->getBaseRegex() . ')$/' . $this->getRegexCase();
	}

	/**
	 * Get the middle of {@see getRegex}, without the surrounding slashes or modifiers
	 *
	 * @internal Exposed for {@see Parser::cleanSig} only
	 * @since 1.1
	 * @return string
	 */
	public function getBaseRegex(): string {
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
	 * Check if given wikitext contains the magic word
	 *
	 * @since 1.1
	 * @param string $text
	 * @return bool
	 */
	public function match( $text ): bool {
		return (bool)preg_match( $this->getRegex(), $text ?? '' );
	}

	/**
	 * Check if given wikitext contains the word as the only thing on a line
	 *
	 * @param string $text
	 * @return bool
	 * @since 1.23
	 */
	public function matchStartToEnd( $text ): bool {
		return (bool)preg_match( $this->getRegexStartToEnd(), $text ?? '' );
	}

	/**
	 * Remove any matches of this magic word from a given text
	 *
	 * Returns true if the text contains one or more matches, and alters the
	 * input string to remove all instances of the magic word.
	 *
	 * @since 1.1
	 * @param string &$text
	 * @return bool
	 */
	public function matchAndRemove( &$text ): bool {
		$text = preg_replace( $this->getRegex(), '', $text ?? '', -1, $count );
		return (bool)$count;
	}

	/**
	 * @param string &$text
	 * @return bool
	 */
	public function matchStartAndRemove( &$text ): bool {
		$text = preg_replace( $this->getRegexStart(), '', $text ?? '', -1, $count );
		return (bool)$count;
	}

	/**
	 * Replace any matches of this word with something else
	 *
	 * @since 1.1
	 * @param string $replacement
	 * @param string $subject
	 * @param int $limit
	 * @return string
	 */
	public function replace( $replacement, $subject, $limit = -1 ) {
		$res = preg_replace(
			$this->getRegex(),
			StringUtils::escapeRegexReplacement( $replacement ),
			$subject ?? '',
			$limit
		);
		return $res;
	}

	/**
	 * Get one of the synonyms
	 *
	 * This exists primarily for calling `getSynonym( 0 )`, which is how
	 * you can obtain the preferred name of a magic word according to the
	 * current wiki's content language. For example, when demonstrating or
	 * semi-automatically creating content that uses a given magic word.
	 *
	 * This works because {@see LocalisationCache} merges magic word data by
	 * appending fallback languages (i.e. "en") after to the language's
	 * own data, and each language's `Messages*.php` file lists the
	 * preferred/canonical form as the first value.
	 *
	 * Calling this with a number other than 0 is unsupported and may
	 * fail.
	 *
	 * @since 1.1
	 * @param int $i
	 * @return string
	 */
	public function getSynonym( $i ) {
		return $this->mSynonyms[$i];
	}

	/**
	 * Get full list of synonyms
	 *
	 * @since 1.7
	 * @return string[]
	 */
	public function getSynonyms(): array {
		return $this->mSynonyms;
	}

	/**
	 * @since 1.7
	 * @return bool
	 */
	public function isCaseSensitive() {
		return $this->mCaseSensitive;
	}

	/**
	 * @return string
	 * @deprecated since 1.42 Internal method should not be used
	 */
	public function getId() {
		wfDeprecated( __METHOD__, '1.42' );
		return $this->mId;
	}
}

/** @deprecated class alias since 1.40 */
class_alias( MagicWord::class, 'MagicWord' );
