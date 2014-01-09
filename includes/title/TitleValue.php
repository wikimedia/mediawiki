<?php
/**
 * Representation of a title within %MediaWiki.
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
 * Represents a title within %MediaWiki, as stored in the database.
 *
 * @note In contrast to Title, this is designed to be a plain value object. That is,
 * it is immutable, does not use global state, and causes no side effects.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 */
class TitleValue {

	/**
	 * Constant representing the DBKey form of a title, as used in the database
	 * and in URLs.
	 */
	const DBKEY_FORM = 'dbkey';

	/**
	 * Constant representing the canonical title form of a title, as used
	 * for page titles.
	 */
	const TITLE_FORM = 'title';

	/**
	 * Constant representing the "unknown" form of a title, to be used
	 * for titles from an "insecure" source, such as user input.
	 */
	const UNKNOWN_FORM = 'unknown';

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
	 * The form the title is in, using the XXX_FROM constants.
	 *
	 * @var string
	 */
	protected $form;

	/**
	 * Constructs a TitleValue. Typically, this is called with information
	 * retrieved from the database.
	 *
	 * @param $form string The form of the title (use the XXX_FORM constants)
	 * @param $namespace int The namespace ID
	 * @param $text string The page title. No normalization is applied or expected,
	 *        TitleValue is agnostic about the form of the title used.
	 *        Typically, this will be the DBKey form of the title, but that is not a requirement.
	 * @param $section string The section title. Use '' to represent the whole page.
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( $form, $namespace, $text, $section = '' ) {
		if ( !is_int( $namespace ) ) {
			throw new InvalidArgumentException( '$namespace must be an integer' );
		}

		if ( !is_string( $form ) ) {
			throw new InvalidArgumentException( '$form must be a string' );
		}

		if ( !is_string( $text ) ) {
			throw new InvalidArgumentException( '$text must be a string' );
		}

		if ( $text === '' ) {
			throw new InvalidArgumentException( '$text must not be empty' );
		}

		if ( !is_string( $section ) ) {
			throw new InvalidArgumentException( '$section must be a string' );
		}

		$this->form = $form;
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
	public function getForm() {
		return $this->form;
	}

	/**
	 * Checks that this TitleValue has the expected form (as specified in the constructor),
	 * and throws an exception if it doesn't.
	 *
	 * @param string $requiredForm use the XXX_FORM constants
	 * @throws MWException
	 */
	public function checkForm( $requiredForm ) {
		if ( $this->form !== $requiredForm ) {
			throw new MWException( "Bad title form: expected $requiredForm, got {$this->form}" );
		}
	}

	/**
	 * Returns the title text, as supplied to the constructor, without prefix or section.
	 * No normalization is applied, TitleValue is agnostic about the form of the title used.
	 *
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
		return new TitleValue( $this->form, $this->namespace, $this->text, $section );
	}

	/**
	 * Returns a string representation of the title, for logging. This is purely informative
	 * and must not be used programmatically. Use the appropriate TitleFormatter to generate
	 * the correct string representation for a given use.
	 *
	 * @return string
	 */
	public function __toString() {
		return 'ns' . $this->namespace . ':' . $this->text . '#' . $this->section;
	}
}