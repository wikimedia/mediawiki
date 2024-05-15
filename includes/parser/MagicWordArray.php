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

use LogicException;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

/**
 * Class for handling an array of magic words
 *
 * See docs/magicword.md.
 *
 * @since 1.11
 * @ingroup Parser
 */
class MagicWordArray {

	/** @var string[] */
	public $names = [];
	private MagicWordFactory $factory;

	/** @var array<int,array<string,string>>|null */
	private $hash;

	/** @var string[]|null */
	private $baseRegex;

	/** @var string[]|null */
	private $regex;

	/**
	 * @param string[] $names
	 * @param MagicWordFactory|null $factory
	 */
	public function __construct( $names = [], MagicWordFactory $factory = null ) {
		$this->names = $names;
		$this->factory = $factory ?: MediaWikiServices::getInstance()->getMagicWordFactory();
	}

	/**
	 * Add a magic word by name
	 *
	 * @param string $name
	 */
	public function add( $name ): void {
		$this->names[] = $name;
		$this->hash = $this->baseRegex = $this->regex = null;
	}

	/**
	 * Get a 2-d hashtable for this array
	 *
	 * @return array<int,array<string,string>>
	 */
	public function getHash(): array {
		if ( $this->hash === null ) {
			$this->hash = [ 0 => [], 1 => [] ];
			foreach ( $this->names as $name ) {
				$magic = $this->factory->get( $name );
				$case = intval( $magic->isCaseSensitive() );
				foreach ( $magic->getSynonyms() as $syn ) {
					if ( !$case ) {
						$syn = $this->factory->getContentLanguage()->lc( $syn );
					}
					$this->hash[$case][$syn] = $name;
				}
			}
		}
		return $this->hash;
	}

	/**
	 * Get the base regex
	 *
	 * @internal For use in {@see Parser} only
	 * @param bool $capture Set to false to suppress the capture groups,
	 *  which can cause unexpected conflicts when this regexp is embedded in
	 *  other regexps with similar constructs.
	 * @param string $delimiter The delimiter which will be used for the
	 *  eventual regexp.
	 * @return array<int,string>
	 */
	public function getBaseRegex( bool $capture = true, string $delimiter = '/' ): array {
		if ( $capture && $delimiter === '/' && $this->baseRegex !== null ) {
			return $this->baseRegex;
		}
		$regex = [ 0 => [], 1 => [] ];
		foreach ( $this->names as $name ) {
			$magic = $this->factory->get( $name );
			$case = $magic->isCaseSensitive() ? 1 : 0;
			foreach ( $magic->getSynonyms() as $i => $syn ) {
				if ( $capture ) {
					// Group name must start with a non-digit in PCRE 8.34+
					$it = strtr( $i, '0123456789', 'abcdefghij' );
					$groupName = $it . '_' . $name;
					$group = '(?P<' . $groupName . '>' . preg_quote( $syn, $delimiter ) . ')';
					$regex[$case][] = $group;
				} else {
					$regex[$case][] = preg_quote( $syn, $delimiter );
				}
			}
		}
		'@phan-var array<int,string[]> $regex';
		foreach ( $regex as $case => &$re ) {
			$re = count( $re ) ? implode( '|', $re ) : '(?!)';
			if ( !$case ) {
				$re = "(?i:{$re})";
			}
		}
		'@phan-var array<int,string> $regex';

		if ( $capture && $delimiter === '/' ) {
			$this->baseRegex = $regex;
		}
		return $regex;
	}

	/**
	 * Get an unanchored regex that does not match parameters
	 *
	 * @return array<int,string>
	 */
	private function getRegex(): array {
		if ( $this->regex === null ) {
			$this->regex = [];
			$base = $this->getBaseRegex( true, '/' );
			foreach ( $base as $case => $re ) {
				$this->regex[$case] = "/$re/JS";
			}
			// As a performance optimization, turn on unicode mode only for
			// case-insensitive matching.
			$this->regex[0] .= 'u';
		}
		return $this->regex;
	}

	/**
	 * Get a regex anchored to the start of the string that does not match parameters
	 *
	 * @return array<int,string>
	 */
	private function getRegexStart(): array {
		$newRegex = [];
		$base = $this->getBaseRegex( true, '/' );
		foreach ( $base as $case => $re ) {
			$newRegex[$case] = "/^(?:$re)/JS";
		}
		// As a performance optimization, turn on unicode mode only for
		// case-insensitive matching.
		$newRegex[0] .= 'u';
		return $newRegex;
	}

	/**
	 * Get an anchored regex for matching variables with parameters
	 *
	 * @return array<int,string>
	 */
	private function getVariableStartToEndRegex(): array {
		$newRegex = [];
		$base = $this->getBaseRegex( true, '/' );
		foreach ( $base as $case => $re ) {
			$newRegex[$case] = str_replace( '\$1', '(.*?)', "/^(?:$re)$/JS" );
		}
		// As a performance optimization, turn on unicode mode only for
		// case-insensitive matching.
		$newRegex[0] .= 'u';
		return $newRegex;
	}

	/**
	 * @since 1.20
	 * @return string[]
	 */
	public function getNames() {
		return $this->names;
	}

	/**
	 * Parse a match array from preg_match
	 *
	 * @param array<string|int,string> $matches
	 * @return array{0:string,1:string|false} Pair of (magic word ID, parameter value),
	 *  where the latter is instead false if there is no parameter value.
	 */
	private function parseMatch( array $matches ): array {
		$magicName = null;
		foreach ( $matches as $key => $match ) {
			if ( $magicName !== null ) {
				// The structure we found at this point is [ …,
				//     'a_magicWordName' => 'matchedSynonym',
				//     n                 => 'matchedSynonym (again)',
				//     n + 1             => 'parameterValue',
				// … ]
				return [ $magicName, $matches[$key + 1] ?? false ];
			}
			// Skip the initial full match and any non-matching group
			if ( $match !== '' && $key !== 0 ) {
				$parts = explode( '_', $key, 2 );
				if ( !isset( $parts[1] ) ) {
					throw new LogicException( 'Unexpected group name' );
				}
				$magicName = $parts[1];
			}
		}
		throw new LogicException( 'Unexpected $m array with no match' );
	}

	/**
	 * Match some text, with parameter capture
	 *
	 * @param string $text
	 * @return (string|false)[] Magic word name in the first element and the parameter in the second
	 *  element. Both elements are false if there was no match.
	 */
	public function matchVariableStartToEnd( $text ): array {
		$regexes = $this->getVariableStartToEndRegex();
		foreach ( $regexes as $regex ) {
			$m = [];
			if ( preg_match( $regex, $text, $m ) ) {
				return $this->parseMatch( $m );
			}
		}
		return [ false, false ];
	}

	/**
	 * Match some text, without parameter capture
	 *
	 * @see MagicWord::matchStartToEnd
	 * @param string $text
	 * @return string|false The magic word name, or false if there was no capture
	 */
	public function matchStartToEnd( $text ) {
		$hash = $this->getHash();
		if ( isset( $hash[1][$text] ) ) {
			return $hash[1][$text];
		}
		$lc = $this->factory->getContentLanguage()->lc( $text );
		return $hash[0][$lc] ?? false;
	}

	/**
	 * Return an associative array for all items that match.
	 *
	 * Cannot be used for magic words with parameters.
	 * Removes the matched items from the input string (passed by reference)
	 *
	 * @see MagicWord::matchAndRemove
	 * @param string &$text
	 * @return array<string,false> Keyed by magic word ID
	 */
	public function matchAndRemove( &$text ): array {
		$found = [];
		$regexes = $this->getRegex();
		$res = preg_replace_callback( $regexes, function ( $m ) use ( &$found ) {
			[ $name, $param ] = $this->parseMatch( $m );
			$found[$name] = $param;
			return '';
		}, $text );
		// T321234: Don't try to fix old revisions with broken UTF-8, just return $text as is
		if ( $res === null ) {
			$error = preg_last_error();
			$errorText = preg_last_error_msg();
			LoggerFactory::getInstance( 'parser' )->warning( 'preg_match_all error: {code} {errorText}', [
				'code' => $error,
				'regex' => $regexes,
				'text' => $text,
				'errorText' => $errorText
			] );
			if ( $error !== PREG_BAD_UTF8_ERROR ) {
				throw new LogicException( "preg_match_all error $error: $errorText" );
			}
		} else {
			$text = $res;
		}
		return $found;
	}

	/**
	 * Return the ID of the magic word at the start of $text, and remove
	 * the prefix from $text.
	 *
	 * Does not match parameters.
	 *
	 * @see MagicWord::matchStartAndRemove
	 * @param string &$text Unmodified if no match is found.
	 * @return string|false False if no match is found.
	 */
	public function matchStartAndRemove( &$text ) {
		$regexes = $this->getRegexStart();
		foreach ( $regexes as $regex ) {
			if ( preg_match( $regex, $text, $m ) ) {
				[ $id, ] = $this->parseMatch( $m );
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

/** @deprecated class alias since 1.40 */
class_alias( MagicWordArray::class, 'MagicWordArray' );
