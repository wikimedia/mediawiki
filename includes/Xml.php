<?php
/**
 * Methods to generate XML.
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
 * Module of static functions for generating XML
 */
class Xml {
	/**
	 * Format an XML element with given attributes and, optionally, text content.
	 * Element and attribute names are assumed to be ready for literal inclusion.
	 * Strings are assumed to not contain XML-illegal characters; special
	 * characters (<, >, &) are escaped but illegals are not touched.
	 *
	 * @param string $element Element name
	 * @param array $attribs Name=>value pairs. Values will be escaped.
	 * @param string $contents Null to make an open tag only; '' for a contentless closed tag (default)
	 * @param bool $allowShortTag Whether '' in $contents will result in a contentless closed tag
	 * @return string
	 */
	public static function element( $element, $attribs = null, $contents = '',
		$allowShortTag = true
	) {
		$out = '<' . $element;
		if ( !is_null( $attribs ) ) {
			$out .= self::expandAttributes( $attribs );
		}
		if ( is_null( $contents ) ) {
			$out .= '>';
		} else {
			if ( $allowShortTag && $contents === '' ) {
				$out .= ' />';
			} else {
				$out .= '>' . htmlspecialchars( $contents ) . "</$element>";
			}
		}
		return $out;
	}

	/**
	 * Given an array of ('attributename' => 'value'), it generates the code
	 * to set the XML attributes : attributename="value".
	 * The values are passed to Sanitizer::encodeAttribute.
	 * Returns null or empty string if no attributes given.
	 * @param array|null $attribs Array of attributes for an XML element
	 * @throws MWException
	 * @return null|string
	 */
	public static function expandAttributes( $attribs ) {
		$out = '';
		if ( is_null( $attribs ) ) {
			return null;
		} elseif ( is_array( $attribs ) ) {
			foreach ( $attribs as $name => $val ) {
				$out .= " {$name}=\"" . Sanitizer::encodeAttribute( $val ) . '"';
			}
			return $out;
		} else {
			throw new MWException( 'Expected attribute array, got something else in ' . __METHOD__ );
		}
	}

	/**
	 * Format an XML element as with self::element(), but run text through the
	 * $wgContLang->normalize() validator first to ensure that no invalid UTF-8
	 * is passed.
	 *
	 * @param string $element
	 * @param array $attribs Name=>value pairs. Values will be escaped.
	 * @param string $contents Null to make an open tag only; '' for a contentless closed tag (default)
	 * @return string
	 */
	public static function elementClean( $element, $attribs = [], $contents = '' ) {
		global $wgContLang;
		if ( $attribs ) {
			$attribs = array_map( [ 'UtfNormal\Validator', 'cleanUp' ], $attribs );
		}
		if ( $contents ) {
			$contents = $wgContLang->normalize( $contents );
		}
		return self::element( $element, $attribs, $contents );
	}

	/**
	 * This opens an XML element
	 *
	 * @param string $element Name of the element
	 * @param array $attribs Array of attributes, see Xml::expandAttributes()
	 * @return string
	 */
	public static function openElement( $element, $attribs = null ) {
		return '<' . $element . self::expandAttributes( $attribs ) . '>';
	}

	/**
	 * Shortcut to close an XML element
	 * @param string $element Element name
	 * @return string
	 */
	public static function closeElement( $element ) {
		return "</$element>";
	}

	/**
	 * Same as Xml::element(), but does not escape contents. Handy when the
	 * content you have is already valid xml.
	 *
	 * @param string $element Element name
	 * @param array $attribs Array of attributes
	 * @param string $contents Content of the element
	 * @return string
	 */
	public static function tags( $element, $attribs = null, $contents ) {
		return self::openElement( $element, $attribs ) . $contents . "</$element>";
	}

	/**
	 * Create a date selector
	 *
	 * @param string $selected The month which should be selected, default ''.
	 * @param string $allmonths Value of a special item denoting all month.
	 *   Null to not include (default).
	 * @param string $id Element identifier
	 * @return string Html string containing the month selector
	 */
	public static function monthSelector( $selected = '', $allmonths = null, $id = 'month' ) {
		global $wgLang;
		$options = [];
		$data = new XmlSelect( 'month', $id, $selected );
		if ( is_null( $selected ) ) {
			$selected = '';
		}
		if ( !is_null( $allmonths ) ) {
			$options[wfMessage( 'monthsall' )->text()] = $allmonths;
		}
		for ( $i = 1; $i < 13; $i++ ) {
			$options[$wgLang->getMonthName( $i )] = $i;
		}
		$data->addOptions( $options );
		$data->setAttribute( 'class', 'mw-month-selector' );
		return $data->getHTML();
	}

	/**
	 * @param int $year
	 * @param int $month
	 * @return string Formatted HTML
	 */
	public static function dateMenu( $year, $month ) {
		# Offset overrides year/month selection
		if ( $month && $month !== -1 ) {
			$encMonth = intval( $month );
		} else {
			$encMonth = '';
		}
		if ( $year ) {
			$encYear = intval( $year );
		} elseif ( $encMonth ) {
			$timestamp = MWTimestamp::getInstance();
			$thisMonth = intval( $timestamp->format( 'n' ) );
			$thisYear = intval( $timestamp->format( 'Y' ) );
			if ( intval( $encMonth ) > $thisMonth ) {
				$thisYear--;
			}
			$encYear = $thisYear;
		} else {
			$encYear = '';
		}
		$inputAttribs = [ 'id' => 'year', 'maxlength' => 4, 'size' => 7 ];
		return self::label( wfMessage( 'year' )->text(), 'year' ) . ' ' .
			Html::input( 'year', $encYear, 'number', $inputAttribs ) . ' ' .
			self::label( wfMessage( 'month' )->text(), 'month' ) . ' ' .
			self::monthSelector( $encMonth, -1 );
	}

	/**
	 * Construct a language selector appropriate for use in a form or preferences
	 *
	 * @param string $selected The language code of the selected language
	 * @param bool $customisedOnly If true only languages which have some content are listed
	 * @param string $inLanguage The ISO code of the language to display the select list in (optional)
	 * @param array $overrideAttrs Override the attributes of the select tag (since 1.20)
	 * @param Message|null $msg Label message key (since 1.20)
	 * @return array Array containing 2 items: label HTML and select list HTML
	 */
	public static function languageSelector( $selected, $customisedOnly = true,
		$inLanguage = null, $overrideAttrs = [], Message $msg = null
	) {
		global $wgLanguageCode;

		$include = $customisedOnly ? 'mwfile' : 'mw';
		$languages = Language::fetchLanguageNames( $inLanguage, $include );

		// Make sure the site language is in the list;
		// a custom language code might not have a defined name...
		if ( !array_key_exists( $wgLanguageCode, $languages ) ) {
			$languages[$wgLanguageCode] = $wgLanguageCode;
		}

		ksort( $languages );

		/**
		 * If a bogus value is set, default to the content language.
		 * Otherwise, no default is selected and the user ends up
		 * with Afrikaans since it's first in the list.
		 */
		$selected = isset( $languages[$selected] ) ? $selected : $wgLanguageCode;
		$options = "\n";
		foreach ( $languages as $code => $name ) {
			$options .= Xml::option( "$code - $name", $code, $code == $selected ) . "\n";
		}

		$attrs = [ 'id' => 'wpUserLanguage', 'name' => 'wpUserLanguage' ];
		$attrs = array_merge( $attrs, $overrideAttrs );

		if ( $msg === null ) {
			$msg = wfMessage( 'yourlanguage' );
		}
		return [
			Xml::label( $msg->text(), $attrs['id'] ),
			Xml::tags( 'select', $attrs, $options )
		];
	}

	/**
	 * Shortcut to make a span element
	 * @param string $text Content of the element, will be escaped
	 * @param string $class Class name of the span element
	 * @param array $attribs Other attributes
	 * @return string
	 */
	public static function span( $text, $class, $attribs = [] ) {
		return self::element( 'span', [ 'class' => $class ] + $attribs, $text );
	}

	/**
	 * Shortcut to make a specific element with a class attribute
	 * @param string $text Content of the element, will be escaped
	 * @param string $class Class name of the span element
	 * @param string $tag Element name
	 * @param array $attribs Other attributes
	 * @return string
	 */
	public static function wrapClass( $text, $class, $tag = 'span', $attribs = [] ) {
		return self::tags( $tag, [ 'class' => $class ] + $attribs, $text );
	}

	/**
	 * Convenience function to build an HTML text input field
	 * @param string $name Value of the name attribute
	 * @param int $size Value of the size attribute
	 * @param mixed $value Value of the value attribute
	 * @param array $attribs Other attributes
	 * @return string HTML
	 */
	public static function input( $name, $size = false, $value = false, $attribs = [] ) {
		$attributes = [ 'name' => $name ];

		if ( $size ) {
			$attributes['size'] = $size;
		}

		if ( $value !== false ) { // maybe 0
			$attributes['value'] = $value;
		}

		return self::element( 'input',
			Html::getTextInputAttributes( $attributes + $attribs ) );
	}

	/**
	 * Convenience function to build an HTML password input field
	 * @param string $name Value of the name attribute
	 * @param int $size Value of the size attribute
	 * @param mixed $value Value of the value attribute
	 * @param array $attribs Other attributes
	 * @return string HTML
	 */
	public static function password( $name, $size = false, $value = false,
		$attribs = []
	) {
		return self::input( $name, $size, $value,
			array_merge( $attribs, [ 'type' => 'password' ] ) );
	}

	/**
	 * Internal function for use in checkboxes and radio buttons and such.
	 *
	 * @param string $name
	 * @param bool $present
	 *
	 * @return array
	 */
	public static function attrib( $name, $present = true ) {
		return $present ? [ $name => $name ] : [];
	}

	/**
	 * Convenience function to build an HTML checkbox
	 * @param string $name Value of the name attribute
	 * @param bool $checked Whether the checkbox is checked or not
	 * @param array $attribs Array other attributes
	 * @return string HTML
	 */
	public static function check( $name, $checked = false, $attribs = [] ) {
		return self::element( 'input', array_merge(
			[
				'name' => $name,
				'type' => 'checkbox',
				'value' => 1 ],
			self::attrib( 'checked', $checked ),
			$attribs ) );
	}

	/**
	 * Convenience function to build an HTML radio button
	 * @param string $name Value of the name attribute
	 * @param string $value Value of the value attribute
	 * @param bool $checked Whether the checkbox is checked or not
	 * @param array $attribs Other attributes
	 * @return string HTML
	 */
	public static function radio( $name, $value, $checked = false, $attribs = [] ) {
		return self::element( 'input', [
			'name' => $name,
			'type' => 'radio',
			'value' => $value ] + self::attrib( 'checked', $checked ) + $attribs );
	}

	/**
	 * Convenience function to build an HTML form label
	 * @param string $label Text of the label
	 * @param string $id
	 * @param array $attribs An attribute array.  This will usually be
	 *     the same array as is passed to the corresponding input element,
	 *     so this function will cherry-pick appropriate attributes to
	 *     apply to the label as well; only class and title are applied.
	 * @return string HTML
	 */
	public static function label( $label, $id, $attribs = [] ) {
		$a = [ 'for' => $id ];

		foreach ( [ 'class', 'title' ] as $attr ) {
			if ( isset( $attribs[$attr] ) ) {
				$a[$attr] = $attribs[$attr];
			}
		}

		return self::element( 'label', $a, $label );
	}

	/**
	 * Convenience function to build an HTML text input field with a label
	 * @param string $label Text of the label
	 * @param string $name Value of the name attribute
	 * @param string $id Id of the input
	 * @param int|bool $size Value of the size attribute
	 * @param string|bool $value Value of the value attribute
	 * @param array $attribs Other attributes
	 * @return string HTML
	 */
	public static function inputLabel( $label, $name, $id, $size = false,
		$value = false, $attribs = []
	) {
		list( $label, $input ) = self::inputLabelSep( $label, $name, $id, $size, $value, $attribs );
		return $label . '&#160;' . $input;
	}

	/**
	 * Same as Xml::inputLabel() but return input and label in an array
	 *
	 * @param string $label
	 * @param string $name
	 * @param string $id
	 * @param int|bool $size
	 * @param string|bool $value
	 * @param array $attribs
	 *
	 * @return array
	 */
	public static function inputLabelSep( $label, $name, $id, $size = false,
		$value = false, $attribs = []
	) {
		return [
			Xml::label( $label, $id, $attribs ),
			self::input( $name, $size, $value, [ 'id' => $id ] + $attribs )
		];
	}

	/**
	 * Convenience function to build an HTML checkbox with a label
	 *
	 * @param string $label
	 * @param string $name
	 * @param string $id
	 * @param bool $checked
	 * @param array $attribs
	 *
	 * @return string HTML
	 */
	public static function checkLabel( $label, $name, $id, $checked = false, $attribs = [] ) {
		global $wgUseMediaWikiUIEverywhere;
		$chkLabel = self::check( $name, $checked, [ 'id' => $id ] + $attribs ) .
			'&#160;' .
			self::label( $label, $id, $attribs );

		if ( $wgUseMediaWikiUIEverywhere ) {
			$chkLabel = self::openElement( 'div', [ 'class' => 'mw-ui-checkbox' ] ) .
				$chkLabel . self::closeElement( 'div' );
		}
		return $chkLabel;
	}

	/**
	 * Convenience function to build an HTML radio button with a label
	 *
	 * @param string $label
	 * @param string $name
	 * @param string $value
	 * @param string $id
	 * @param bool $checked
	 * @param array $attribs
	 *
	 * @return string HTML
	 */
	public static function radioLabel( $label, $name, $value, $id,
		$checked = false, $attribs = []
	) {
		return self::radio( $name, $value, $checked, [ 'id' => $id ] + $attribs ) .
			'&#160;' .
			self::label( $label, $id, $attribs );
	}

	/**
	 * Convenience function to build an HTML submit button
	 * When $wgUseMediaWikiUIEverywhere is true it will default to a progressive button
	 * @param string $value Label text for the button
	 * @param array $attribs Optional custom attributes
	 * @return string HTML
	 */
	public static function submitButton( $value, $attribs = [] ) {
		global $wgUseMediaWikiUIEverywhere;
		$baseAttrs = [
			'type' => 'submit',
			'value' => $value,
		];
		// Done conditionally for time being as it is possible
		// some submit forms
		// might need to be mw-ui-destructive (e.g. delete a page)
		if ( $wgUseMediaWikiUIEverywhere ) {
			$baseAttrs['class'] = 'mw-ui-button mw-ui-progressive';
		}
		// Any custom attributes will take precendence of anything in baseAttrs e.g. override the class
		$attribs = $attribs + $baseAttrs;
		return Html::element( 'input', $attribs );
	}

	/**
	 * Convenience function to build an HTML drop-down list item.
	 * @param string $text Text for this item. Will be HTML escaped
	 * @param string $value Form submission value; if empty, use text
	 * @param bool $selected If true, will be the default selected item
	 * @param array $attribs Optional additional HTML attributes
	 * @return string HTML
	 */
	public static function option( $text, $value = null, $selected = false,
			$attribs = [] ) {
		if ( !is_null( $value ) ) {
			$attribs['value'] = $value;
		}
		if ( $selected ) {
			$attribs['selected'] = 'selected';
		}
		return Html::element( 'option', $attribs, $text );
	}

	/**
	 * Build a drop-down box from a textual list.
	 *
	 * @param string $name Name and id for the drop-down
	 * @param string $list Correctly formatted text (newline delimited) to be
	 *   used to generate the options.
	 * @param string $other Text for the "Other reasons" option
	 * @param string $selected Option which should be pre-selected
	 * @param string $class CSS classes for the drop-down
	 * @param int $tabindex Value of the tabindex attribute
	 * @return string
	 */
	public static function listDropDown( $name = '', $list = '', $other = '',
		$selected = '', $class = '', $tabindex = null
	) {
		$optgroup = false;

		$options = self::option( $other, 'other', $selected === 'other' );

		foreach ( explode( "\n", $list ) as $option ) {
			$value = trim( $option );
			if ( $value == '' ) {
				continue;
			} elseif ( substr( $value, 0, 1 ) == '*' && substr( $value, 1, 1 ) != '*' ) {
				// A new group is starting ...
				$value = trim( substr( $value, 1 ) );
				if ( $optgroup ) {
					$options .= self::closeElement( 'optgroup' );
				}
				$options .= self::openElement( 'optgroup', [ 'label' => $value ] );
				$optgroup = true;
			} elseif ( substr( $value, 0, 2 ) == '**' ) {
				// groupmember
				$value = trim( substr( $value, 2 ) );
				$options .= self::option( $value, $value, $selected === $value );
			} else {
				// groupless reason list
				if ( $optgroup ) {
					$options .= self::closeElement( 'optgroup' );
				}
				$options .= self::option( $value, $value, $selected === $value );
				$optgroup = false;
			}
		}

		if ( $optgroup ) {
			$options .= self::closeElement( 'optgroup' );
		}

		$attribs = [];

		if ( $name ) {
			$attribs['id'] = $name;
			$attribs['name'] = $name;
		}

		if ( $class ) {
			$attribs['class'] = $class;
		}

		if ( $tabindex ) {
			$attribs['tabindex'] = $tabindex;
		}

		return Xml::openElement( 'select', $attribs )
			. "\n"
			. $options
			. "\n"
			. Xml::closeElement( 'select' );
	}

	/**
	 * Shortcut for creating fieldsets.
	 *
	 * @param string|bool $legend Legend of the fieldset. If evaluates to false,
	 *   legend is not added.
	 * @param string $content Pre-escaped content for the fieldset. If false,
	 *   only open fieldset is returned.
	 * @param array $attribs Any attributes to fieldset-element.
	 *
	 * @return string
	 */
	public static function fieldset( $legend = false, $content = false, $attribs = [] ) {
		$s = Xml::openElement( 'fieldset', $attribs ) . "\n";

		if ( $legend ) {
			$s .= Xml::element( 'legend', null, $legend ) . "\n";
		}

		if ( $content !== false ) {
			$s .= $content . "\n";
			$s .= Xml::closeElement( 'fieldset' ) . "\n";
		}

		return $s;
	}

	/**
	 * Shortcut for creating textareas.
	 *
	 * @param string $name The 'name' for the textarea
	 * @param string $content Content for the textarea
	 * @param int $cols The number of columns for the textarea
	 * @param int $rows The number of rows for the textarea
	 * @param array $attribs Any other attributes for the textarea
	 *
	 * @return string
	 */
	public static function textarea( $name, $content, $cols = 40, $rows = 5, $attribs = [] ) {
		return self::element( 'textarea',
					Html::getTextInputAttributes(
						[
							'name' => $name,
							'id' => $name,
							'cols' => $cols,
							'rows' => $rows
						] + $attribs
					),
					$content, false );
	}

	/**
	 * Encode a variable of arbitrary type to JavaScript.
	 * If the value is an XmlJsCode object, pass through the object's value verbatim.
	 *
	 * @note Only use this function for generating JavaScript code. If generating output
	 *       for a proper JSON parser, just call FormatJson::encode() directly.
	 *
	 * @param mixed $value The value being encoded. Can be any type except a resource.
	 * @param bool $pretty If true, add non-significant whitespace to improve readability.
	 * @return string|bool String if successful; false upon failure
	 */
	public static function encodeJsVar( $value, $pretty = false ) {
		if ( $value instanceof XmlJsCode ) {
			return $value->value;
		}
		return FormatJson::encode( $value, $pretty, FormatJson::UTF8_OK );
	}

	/**
	 * Create a call to a JavaScript function. The supplied arguments will be
	 * encoded using Xml::encodeJsVar().
	 *
	 * @since 1.17
	 * @param string $name The name of the function to call, or a JavaScript expression
	 *    which evaluates to a function object which is called.
	 * @param array $args The arguments to pass to the function.
	 * @param bool $pretty If true, add non-significant whitespace to improve readability.
	 * @return string|bool String if successful; false upon failure
	 */
	public static function encodeJsCall( $name, $args, $pretty = false ) {
		foreach ( $args as &$arg ) {
			$arg = Xml::encodeJsVar( $arg, $pretty );
			if ( $arg === false ) {
				return false;
			}
		}

		return "$name(" . ( $pretty
			? ( ' ' . implode( ', ', $args ) . ' ' )
			: implode( ',', $args )
		) . ");";
	}

	/**
	 * Check if a string is well-formed XML.
	 * Must include the surrounding tag.
	 * This function is a DoS vector if an attacker can define
	 * entities in $text.
	 *
	 * @param string $text String to test.
	 * @return bool
	 *
	 * @todo Error position reporting return
	 */
	private static function isWellFormed( $text ) {
		$parser = xml_parser_create( "UTF-8" );

		# case folding violates XML standard, turn it off
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, false );

		if ( !xml_parse( $parser, $text, true ) ) {
			// $err = xml_error_string( xml_get_error_code( $parser ) );
			// $position = xml_get_current_byte_index( $parser );
			// $fragment = $this->extractFragment( $html, $position );
			// $this->mXmlError = "$err at byte $position:\n$fragment";
			xml_parser_free( $parser );
			return false;
		}

		xml_parser_free( $parser );

		return true;
	}

	/**
	 * Check if a string is a well-formed XML fragment.
	 * Wraps fragment in an \<html\> bit and doctype, so it can be a fragment
	 * and can use HTML named entities.
	 *
	 * @param string $text
	 * @return bool
	 */
	public static function isWellFormedXmlFragment( $text ) {
		$html =
			Sanitizer::hackDocType() .
			'<html>' .
			$text .
			'</html>';

		return Xml::isWellFormed( $html );
	}

	/**
	 * Replace " > and < with their respective HTML entities ( &quot;,
	 * &gt;, &lt;)
	 *
	 * @param string $in Text that might contain HTML tags.
	 * @return string Escaped string
	 */
	public static function escapeTagsOnly( $in ) {
		return str_replace(
			[ '"', '>', '<' ],
			[ '&quot;', '&gt;', '&lt;' ],
			$in );
	}

	/**
	 * Generate a form (without the opening form element).
	 * Output optionally includes a submit button.
	 * @param array $fields Associative array, key is the name of a message that
	 *   contains a description for the field, value is an HTML string
	 *   containing the appropriate input.
	 * @param string $submitLabel The name of a message containing a label for
	 *   the submit button.
	 * @param array $submitAttribs The attributes to add to the submit button
	 * @return string HTML form.
	 */
	public static function buildForm( $fields, $submitLabel = null, $submitAttribs = [] ) {
		$form = '';
		$form .= "<table><tbody>";

		foreach ( $fields as $labelmsg => $input ) {
			$id = "mw-$labelmsg";
			$form .= Xml::openElement( 'tr', [ 'id' => $id ] );

			// TODO use a <label> here for accessibility purposes - will need
			// to either not use a table to build the form, or find the ID of
			// the input somehow.

			$form .= Xml::tags( 'td', [ 'class' => 'mw-label' ], wfMessage( $labelmsg )->parse() );
			$form .= Xml::openElement( 'td', [ 'class' => 'mw-input' ] )
				. $input . Xml::closeElement( 'td' );
			$form .= Xml::closeElement( 'tr' );
		}

		if ( $submitLabel ) {
			$form .= Xml::openElement( 'tr' );
			$form .= Xml::tags( 'td', [], '' );
			$form .= Xml::openElement( 'td', [ 'class' => 'mw-submit' ] )
				. Xml::submitButton( wfMessage( $submitLabel )->text(), $submitAttribs )
				. Xml::closeElement( 'td' );
			$form .= Xml::closeElement( 'tr' );
		}

		$form .= "</tbody></table>";

		return $form;
	}

	/**
	 * Build a table of data
	 * @param array $rows An array of arrays of strings, each to be a row in a table
	 * @param array $attribs An array of attributes to apply to the table tag [optional]
	 * @param array $headers An array of strings to use as table headers [optional]
	 * @return string
	 */
	public static function buildTable( $rows, $attribs = [], $headers = null ) {
		$s = Xml::openElement( 'table', $attribs );

		if ( is_array( $headers ) ) {
			$s .= Xml::openElement( 'thead', $attribs );

			foreach ( $headers as $id => $header ) {
				$attribs = [];

				if ( is_string( $id ) ) {
					$attribs['id'] = $id;
				}

				$s .= Xml::element( 'th', $attribs, $header );
			}
			$s .= Xml::closeElement( 'thead' );
		}

		foreach ( $rows as $id => $row ) {
			$attribs = [];

			if ( is_string( $id ) ) {
				$attribs['id'] = $id;
			}

			$s .= Xml::buildTableRow( $attribs, $row );
		}

		$s .= Xml::closeElement( 'table' );

		return $s;
	}

	/**
	 * Build a row for a table
	 * @param array $attribs An array of attributes to apply to the tr tag
	 * @param array $cells An array of strings to put in <td>
	 * @return string
	 */
	public static function buildTableRow( $attribs, $cells ) {
		$s = Xml::openElement( 'tr', $attribs );

		foreach ( $cells as $id => $cell ) {
			$attribs = [];

			if ( is_string( $id ) ) {
				$attribs['id'] = $id;
			}

			$s .= Xml::element( 'td', $attribs, $cell );
		}

		$s .= Xml::closeElement( 'tr' );

		return $s;
	}
}

/**
 * A wrapper class which causes Xml::encodeJsVar() and Xml::encodeJsCall() to
 * interpret a given string as being a JavaScript expression, instead of string
 * data.
 *
 * Example:
 *
 *    Xml::encodeJsVar( new XmlJsCode( 'a + b' ) );
 *
 * Returns "a + b".
 *
 * @note As of 1.21, XmlJsCode objects cannot be nested inside objects or arrays. The sole
 *       exception is the $args argument to Xml::encodeJsCall() because Xml::encodeJsVar() is
 *       called for each individual element in that array.
 *
 * @since 1.17
 */
class XmlJsCode {
	public $value;

	function __construct( $value ) {
		$this->value = $value;
	}
}
