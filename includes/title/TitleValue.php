<?php
/**
 * Representation of a page title within %MediaWiki.
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
 * @license GPL 2+
 * @author Daniel Kinzler
 */

/**
 * Represents a page (or page fragment) title within %MediaWiki.
 *
 * @note In contrast to Title, this is designed to be a plain value object. That is,
 * it is immutable, does not use global state, and causes no side effects.
 *
 * @note TitleValue represents the title of a local page (or fragment of a page).
 * It does not represent a link, and does not support interwiki prefixes etc.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 */
class TitleValue {

	/**
	 * @var int
	 */
	protected $namespace;

	/**
	 * @var string
	 */
	protected $dbkey;

	/**
	 * @var string
	 */
	protected $fragment;

	/**
	 * Constructs a TitleValue.
	 *
	 * @note: TitleValue expects a valid DB key; typically, a TitleValue is constructed either
	 * from a database entry, or by a TitleParser. We could apply "some" normalization here,
	 * such as substituting spaces by underscores, but that would encourage the use of
	 * un-normalized text when constructing TitleValues. For constructing a TitleValue from
	 * user input or external sources, use a TitleParser.
	 *
	 * @param $namespace int The namespace ID. This is not validated.
	 * @param $dbkey string The page title in valid DBkey form. No normalization is applied.
	 * @param $fragment string The fragment title. Use '' to represent the whole page.
	 *        No validation or normalization is applied.
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( $namespace, $dbkey, $fragment = '' ) {
		if ( !is_int( $namespace ) ) {
			throw new InvalidArgumentException( '$namespace must be an integer' );
		}

		if ( !is_string( $dbkey ) ) {
			throw new InvalidArgumentException( '$dbkey must be a string' );
		}

		// Sanity check, no full validation or normalization applied here!
		if ( preg_match( '/^_|[ \r\n\t]|_$/', $dbkey ) ) {
			throw new InvalidArgumentException( '$dbkey must be a valid DB key: ' . $dbkey );
		}

		if ( !is_string( $fragment ) ) {
			throw new InvalidArgumentException( '$fragment must be a string' );
		}

		if ( $dbkey === '' ) {
			throw new InvalidArgumentException( '$dbkey must not be empty' );
		}

		$this->namespace = $namespace;
		$this->dbkey = $dbkey;
		$this->fragment = $fragment;
	}

	/**
	 * @return int
	 */
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	 * @return string
	 */
	public function getFragment() {
		return $this->fragment;
	}

	/**
	 * Returns the title's DB key, as supplied to the constructor,
	 * without namespace prefix or fragment.
	 *
	 * @return string
	 */
	public function getDBkey() {
		return $this->dbkey;
	}

	/**
	 * Returns the title in text form,
	 * without namespace prefix or fragment.
	 *
	 * This is computed from the DB key by replacing any underscores with spaces.
	 *
	 * @note: To get a title string that includes the namespace and/or fragment,
	 *        use a TitleFormatter.
	 *
	 * @return string
	 */
	public function getText() {
		return str_replace( '_', ' ', $this->getDBkey() );
	}

	/**
	 * Creates a new TitleValue for a different fragment of the same page.
	 *
	 * @param string $fragment The fragment name, or "" for the entire page.
	 *
	 * @return TitleValue
	 */
	public function createFragmentTitle( $fragment ) {
		return new TitleValue( $this->namespace, $this->dbkey, $fragment );
	}

	/**
	 * Returns a string representation of the title, for logging. This is purely informative
	 * and must not be used programmatically. Use the appropriate TitleFormatter to generate
	 * the correct string representation for a given use.
	 *
	 * @return string
	 */
	public function __toString() {
		$name = $this->namespace . ':' . $this->dbkey;

		if ( $this->fragment !== '' )  {
			$name .= '#' . $this->fragment;
		}

		return $name;
	}
}
