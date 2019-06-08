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

/**
 * Class for generating HTML <select> or <datalist> elements.
 */
class XmlSelect {
	protected $options = [];
	protected $default = false;
	protected $tagName = 'select';
	protected $attributes = [];

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
	 * @param string|array $default
	 */
	public function setDefault( $default ) {
		$this->default = $default;
	}

	/**
	 * @param string|array $tagName
	 */
	public function setTagName( $tagName ) {
		$this->tagName = $tagName;
	}

	/**
	 * @param string $name
	 * @param string $value
	 */
	public function setAttribute( $name, $value ) {
		$this->attributes[$name] = $value;
	}

	/**
	 * @param string $name
	 * @return string|null
	 */
	public function getAttribute( $name ) {
		return $this->attributes[$name] ?? null;
	}

	/**
	 * @param string $label
	 * @param string|false $value If not given, assumed equal to $label
	 */
	public function addOption( $label, $value = false ) {
		$value = $value !== false ? $value : $label;
		$this->options[] = [ $label => $value ];
	}

	/**
	 * This accepts an array of form
	 * label => value
	 * label => ( label => value, label => value )
	 *
	 * @param array $options
	 */
	public function addOptions( $options ) {
		$this->options[] = $options;
	}

	/**
	 * This accepts an array of form:
	 * label => value
	 * label => ( label => value, label => value )
	 *
	 * @param array $options
	 * @param string|array|false $default
	 * @return string
	 */
	static function formatOptions( $options, $default = false ) {
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

	/**
	 * @return string
	 */
	public function getHTML() {
		$contents = '';

		foreach ( $this->options as $options ) {
			$contents .= self::formatOptions( $options, $this->default );
		}

		return Html::rawElement( $this->tagName, $this->attributes, rtrim( $contents ) );
	}
}
