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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

/**
 * Class for handling an array of magic words
 * @ingroup Parser
 */
class MagicWordArray {
	/** @var string[] */
	public $names = [];

	/** @var MagicWordFactory */
	private $factory;

	/** @var array */
	private $hash;

	private $baseRegex;

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
	public function add( $name ) {
		$this->names[] = $name;
		$this->hash = $this->baseRegex = $this->regex = null;
	}

	/**
	 * Add a number of magic words by name
	 *
	 * @param string[] $names
	 */
	public function addArray( $names ) {
		$this->names = array_merge( $this->names, array_values( $names ) );
		$this->hash = $this->baseRegex = $this->regex = null;
	}

	/**
	 * Get a 2-d hashtable for this array
	 * @return array
	 */
	public function getHash() {
		if ( is_null( $this->hash ) ) {
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
	 * @return string[]
	 */
	public function getBaseRegex() {
		if ( is_null( $this->baseRegex ) ) {
			$this->baseRegex = [ 0 => '', 1 => '' ];
			$allGroups = [];
			foreach ( $this->names as $name ) {
				$magic = $this->factory->get( $name );
				$case = intval( $magic->isCaseSensitive() );
				foreach ( $magic->getSynonyms() as $i => $syn ) {
					// Group name must start with a non-digit in PCRE 8.34+
					$it = strtr( $i, '0123456789', 'abcdefghij' );
					$groupName = $it . '_' . $name;
					$group = '(?P<' . $groupName . '>' . preg_quote( $syn, '/' ) . ')';
					// look for same group names to avoid same named subpatterns in the regex
					if ( isset( $allGroups[$groupName] ) ) {
						throw new MWException(
							__METHOD__ . ': duplicate internal name in magic word array: ' . $name
						);
					}
					$allGroups[$groupName] = true;
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
	 * @return string[]
	 */
	public function getRegex() {
		if ( is_null( $this->regex ) ) {
			$base = $this->getBaseRegex();
			$this->regex = [ '', '' ];
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
	 * @return string[]
	 */
	public function getVariableRegex() {
		return str_replace( "\\$1", "(.*?)", $this->getRegex() );
	}

	/**
	 * Get a regex anchored to the start of the string that does not match parameters
	 *
	 * @return string[]
	 */
	public function getRegexStart() {
		$base = $this->getBaseRegex();
		$newRegex = [ '', '' ];
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
	 * @return string[]
	 */
	public function getVariableStartToEndRegex() {
		$base = $this->getBaseRegex();
		$newRegex = [ '', '' ];
		if ( $base[0] !== '' ) {
			$newRegex[0] = str_replace( "\\$1", "(.*?)", "/^(?:{$base[0]})$/iuS" );
		}
		if ( $base[1] !== '' ) {
			$newRegex[1] = str_replace( "\\$1", "(.*?)", "/^(?:{$base[1]})$/S" );
		}
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
	 * Returns array(magic word ID, parameter value)
	 * If there is no parameter value, that element will be false.
	 *
	 * @param array $m
	 *
	 * @throws MWException
	 * @return array
	 */
	public function parseMatch( $m ) {
		reset( $m );
		while ( ( $key = key( $m ) ) !== null ) {
			$value = current( $m );
			next( $m );
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
			return [ $magicName, $paramValue ];
		}
		// This shouldn't happen either
		throw new MWException( __METHOD__ . ': parameter not found' );
	}

	/**
	 * Match some text, with parameter capture
	 * Returns an array with the magic word name in the first element and the
	 * parameter in the second element.
	 * Both elements are false if there was no match.
	 *
	 * @param string $text
	 *
	 * @return array
	 */
	public function matchVariableStartToEnd( $text ) {
		$regexes = $this->getVariableStartToEndRegex();
		foreach ( $regexes as $regex ) {
			if ( $regex !== '' ) {
				$m = [];
				if ( preg_match( $regex, $text, $m ) ) {
					return $this->parseMatch( $m );
				}
			}
		}
		return [ false, false ];
	}

	/**
	 * Match some text, without parameter capture
	 * Returns the magic word name, or false if there was no capture
	 *
	 * @param string $text
	 *
	 * @return string|bool False on failure
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
	 * Returns an associative array, ID => param value, for all items that match
	 * Removes the matched items from the input string (passed by reference)
	 *
	 * @param string &$text
	 *
	 * @return array
	 */
	public function matchAndRemove( &$text ) {
		$found = [];
		$regexes = $this->getRegex();
		foreach ( $regexes as $regex ) {
			if ( $regex === '' ) {
				continue;
			}
			$matches = [];
			$res = preg_match_all( $regex, $text, $matches, PREG_SET_ORDER );
			if ( $res === false ) {
				LoggerFactory::getInstance( 'parser' )->warning( 'preg_match_all returned false', [
					'code' => preg_last_error(),
					'regex' => $regex,
					'text' => $text,
				] );
			} elseif ( $res ) {
				foreach ( $matches as $m ) {
					list( $name, $param ) = $this->parseMatch( $m );
					$found[$name] = $param;
				}
			}
			$res = preg_replace( $regex, '', $text );
			if ( $res === null ) {
				LoggerFactory::getInstance( 'parser' )->warning( 'preg_replace returned null', [
					'code' => preg_last_error(),
					'regex' => $regex,
					'text' => $text,
				] );
			}
			$text = $res;
		}
		return $found;
	}

	/**
	 * Return the ID of the magic word at the start of $text, and remove
	 * the prefix from $text.
	 * Return false if no match found and $text is not modified.
	 * Does not match parameters.
	 *
	 * @param string &$text
	 *
	 * @return int|bool False on failure
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
