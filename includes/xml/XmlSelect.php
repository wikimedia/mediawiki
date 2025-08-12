<?php
/**
 * Class for generating HTML <select> elements.
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
 */

namespace MediaWiki\Xml;

use MediaWiki\Html\Html;

/**
 * Class for generating HTML <select> or <datalist> elements.
 */
class XmlSelect {
	/** @var array<array<string,string|int|float|array>> */
	protected array $options = [];
	/** @var string|int|float|array|false */
	protected $default = false;
	protected string $tagName = 'select';
	/** @var (string|int)[] */
	protected array $attributes = [];

	/**
	 * @param string|false $name
	 * @param string|false $id
	 * @param string|int|float|array|false $default
	 */
	public function __construct( $name = false, $id = false, $default = false ) {
		if ( $name ) {
			$this->setAttribute( 'name', $name );
		}

		if ( $id ) {
			$this->setAttribute( 'id', $id );
		}

		if ( $default !== false ) {
			$this->default = $default;
		}
	}

	/**
	 * @param string|int|float|array $default
	 */
	public function setDefault( $default ): void {
		$this->default = $default;
	}

	/**
	 * @deprecated since 1.45
	 */
	public function setTagName( string $tagName ): void {
		wfDeprecated( __METHOD__, '1.45' );
		$this->tagName = $tagName;
	}

	/**
	 * @param string $name
	 * @param string|int $value
	 */
	public function setAttribute( string $name, $value ): void {
		$this->attributes[$name] = $value;
	}

	/**
	 * @param string $name
	 * @return string|int|null
	 */
	public function getAttribute( string $name ) {
		return $this->attributes[$name] ?? null;
	}

	/**
	 * @param string $label
	 * @param string|int|float|array|false $value If not given, assumed equal to $label
	 */
	public function addOption( string $label, $value = false ): void {
		$value = $value !== false ? $value : $label;
		$this->options[] = [ $label => $value ];
	}

	/**
	 * This accepts an array of form
	 * label => value
	 * label => ( label => value, label => value )
	 *
	 * @param array<string,string|int|float|array> $options
	 */
	public function addOptions( array $options ): void {
		$this->options[] = $options;
	}

	/**
	 * This accepts an array of form:
	 * label => value
	 * label => ( label => value, label => value )
	 *
	 * @param array<string,string|int|float|array> $options
	 * @param string|int|float|array|false $default
	 * @return string
	 */
	public static function formatOptions( array $options, $default = false ): string {
		$data = '';

		foreach ( $options as $label => $value ) {
			if ( is_array( $value ) ) {
				$contents = self::formatOptions( $value, $default );
				$data .= Html::rawElement( 'optgroup', [ 'label' => $label ], $contents ) . "\n";
			} else {
				// If $default is an array, then the <select> probably has the multiple attribute,
				// so we should check if each $value is in $default, rather than checking if
				// $value is equal to $default.
				$selected = is_array( $default ) ? in_array( $value, $default ) : $value === $default;
				$data .= Xml::option( $label, $value, $selected ) . "\n";
			}
		}

		return $data;
	}

	public function getHTML(): string {
		$contents = '';

		foreach ( $this->options as $options ) {
			$contents .= self::formatOptions( $options, $this->default );
		}

		return Html::rawElement( $this->tagName, $this->attributes, rtrim( $contents ) );
	}

	/**
	 * Parse labels and values out of a comma- and colon-separated list of options, such as is used for
	 * expiry and duration lists. Documentation of the format is on translatewiki.net.
	 * @since 1.35
	 * @link https://translatewiki.net/wiki/Template:Doc-mediawiki-options-list
	 * @param string $msg The message to parse.
	 * @return array<string,string> The options array, where keys are option labels (i.e. translations)
	 * and values are option values (i.e. untranslated).
	 */
	public static function parseOptionsMessage( string $msg ): array {
		$options = [];
		foreach ( explode( ',', $msg ) as $option ) {
			$parts = explode( ':', $option, 2 );
			// Normalize options that only have one part.
			$options[ trim( $parts[0] ) ] = trim( $parts[1] ?? $parts[0] );
		}
		return $options;
	}
}
/** @deprecated class alias since 1.43 */
class_alias( XmlSelect::class, 'XmlSelect' );
