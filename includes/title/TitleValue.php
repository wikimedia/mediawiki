<?php
/**
 * Representation of a page title within MediaWiki.
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
 * @author Daniel Kinzler
 */
use MediaWiki\Linker\LinkTarget;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\ParameterTypeException;

/**
 * Represents a page (or page fragment) title within MediaWiki.
 *
 * @note In contrast to Title, this is designed to be a plain value object. That is,
 * it is immutable, does not use global state, and causes no side effects.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 * @since 1.23
 */
class TitleValue implements LinkTarget {

	/**
	 * @deprecated in 1.31. This class is immutable. Use the getter for access.
	 * @var int
	 */
	protected $namespace;

	/**
	 * @deprecated in 1.31. This class is immutable. Use the getter for access.
	 * @var string
	 */
	protected $dbkey;

	/**
	 * @deprecated in 1.31. This class is immutable. Use the getter for access.
	 * @var string
	 */
	protected $fragment;

	/**
	 * @deprecated in 1.31. This class is immutable. Use the getter for access.
	 * @var string
	 */
	protected $interwiki;

	/**
	 * Text form including namespace/interwiki, initialised on demand
	 *
	 * Only public to share cache with TitleFormatter
	 *
	 * @private
	 * @var string
	 */
	public $prefixedText = null;

	/**
	 * Constructs a TitleValue.
	 *
	 * @note TitleValue expects a valid namespace and name; typically, a TitleValue is constructed
	 * either from a database entry, or by a TitleParser. For constructing a TitleValue from user
	 * input or external sources, use a TitleParser.
	 *
	 * @param int $namespace The namespace ID. This is not validated.
	 * @param string $title The page title in either DBkey or text form. No normalization is applied
	 *   beyond underscore/space conversion.
	 * @param string $fragment The fragment title. Use '' to represent the whole page.
	 *   No validation or normalization is applied.
	 * @param string $interwiki The interwiki component
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( $namespace, $title, $fragment = '', $interwiki = '' ) {
		if ( !is_int( $namespace ) ) {
			throw new ParameterTypeException( '$namespace', 'int' );
		}
		if ( !is_string( $title ) ) {
			throw new ParameterTypeException( '$title', 'string' );
		}
		if ( !is_string( $fragment ) ) {
			throw new ParameterTypeException( '$fragment', 'string' );
		}
		if ( !is_string( $interwiki ) ) {
			throw new ParameterTypeException( '$interwiki', 'string' );
		}

		// Sanity check, no full validation or normalization applied here!
		Assert::parameter( !preg_match( '/^[_ ]|[\r\n\t]|[_ ]$/', $title ), '$title',
			"invalid name '$title'" );
		Assert::parameter(
			$title !== '' ||
				( $namespace === NS_MAIN && ( $fragment !== '' || $interwiki !== '' ) ),
			'$title',
			'should not be empty unless namespace is main and fragment or interwiki is non-empty'
		);

		$this->namespace = $namespace;
		$this->dbkey = strtr( $title, ' ', '_' );
		$this->fragment = $fragment;
		$this->interwiki = $interwiki;
	}

	/**
	 * @since 1.23
	 * @return int
	 */
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	 * @since 1.27
	 * @param int $ns
	 * @return bool
	 */
	public function inNamespace( $ns ) {
		return $this->namespace == $ns;
	}

	/**
	 * @since 1.23
	 * @return string
	 */
	public function getFragment() {
		return $this->fragment;
	}

	/**
	 * @since 1.27
	 * @return bool
	 */
	public function hasFragment() {
		return $this->fragment !== '';
	}

	/**
	 * Returns the title's DB key, as supplied to the constructor,
	 * without namespace prefix or fragment.
	 * @since 1.23
	 *
	 * @return string
	 */
	public function getDBkey() {
		return $this->dbkey;
	}

	/**
	 * Returns the title in text form,
	 * without namespace prefix or fragment.
	 * @since 1.23
	 *
	 * This is computed from the DB key by replacing any underscores with spaces.
	 *
	 * @note To get a title string that includes the namespace and/or fragment,
	 *       use a TitleFormatter.
	 *
	 * @return string
	 */
	public function getText() {
		return str_replace( '_', ' ', $this->dbkey );
	}

	/**
	 * Creates a new TitleValue for a different fragment of the same page.
	 *
	 * @since 1.27
	 * @param string $fragment The fragment name, or "" for the entire page.
	 *
	 * @return TitleValue
	 */
	public function createFragmentTarget( $fragment ) {
		return new TitleValue(
			$this->namespace,
			$this->dbkey,
			$fragment,
			$this->interwiki
		);
	}

	/**
	 * Whether it has an interwiki part
	 *
	 * @since 1.27
	 * @return bool
	 */
	public function isExternal() {
		return $this->interwiki !== '';
	}

	/**
	 * Returns the interwiki part
	 *
	 * @since 1.27
	 * @return string
	 */
	public function getInterwiki() {
		return $this->interwiki;
	}

	/**
	 * Returns a string representation of the title, for logging. This is purely informative
	 * and must not be used programmatically. Use the appropriate TitleFormatter to generate
	 * the correct string representation for a given use.
	 * @since 1.23
	 *
	 * @return string
	 */
	public function __toString() {
		$name = $this->namespace . ':' . $this->dbkey;

		if ( $this->fragment !== '' ) {
			$name .= '#' . $this->fragment;
		}

		if ( $this->interwiki !== '' ) {
			$name = $this->interwiki . ':' . $name;
		}

		return $name;
	}
}
