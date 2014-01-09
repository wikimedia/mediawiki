<?php
/**
 * Representation a title within %MediaWiki.
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
 * Represents a title within MediaWiki, as stored in the database.
 *
 * @note In contrast to Title, this is designed to be a true value object. That is,
 * it is immutable, does not use global state, and causes no side effects.
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
	protected $text;

	/**
	 * @var string
	 */
	protected $section;

	/**
	 * Constructs a TitleValue. Typically, this is called with information
	 * retrieved from the database.
	 *
	 * @param $namespace int The namespace ID
	 * @param $text string The (normalized) page title, without prefix or section (the DBKey).
	 * @param $section string The (normalized) section title.
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( $namespace, $text, $section = '' ) {
		if ( !is_int( $namespace ) ) {
			throw new InvalidArgumentException( '$namespace must be an integer' );
		}

		if ( !is_string( $text ) ) {
			throw new InvalidArgumentException( '$text must be a string' );
		}

		if ( $text === '' ) {
			throw new InvalidArgumentException( '$text must not be emopty' );
		}

		if ( !is_string( $section ) ) {
			throw new InvalidArgumentException( '$section must be a string' );
		}

		$this->namespace = $namespace;
		$this->text = $text;
		$this->section = $section;
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
	public function getSection() {
		return $this->section;
	}

	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * Creates a new TitleValue for a different section of the same page.
	 *
	 * @param string $section The section name, or "" for the entire page.
	 *
	 * @return TitleValue
	 */
	public function createSectionTitle( $section ) {
		return new TitleValue( $this->namespace, $this->text, $section );
	}

}
 