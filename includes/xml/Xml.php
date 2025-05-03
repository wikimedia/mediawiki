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

namespace MediaWiki\Xml;

use MediaWiki\Html\Html;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Utils\MWTimestamp;
use UtfNormal\Validator;

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
	 * @param-taint $element tainted
	 * @param array|null $attribs Name=>value pairs. Values will be escaped.
	 * @param-taint $attribs escapes_html
	 * @param string|null $contents Null to make an open tag only; '' for a contentless closed tag (default)
	 * @param-taint $contents escapes_html
	 * @param bool $allowShortTag Whether '' in $contents will result in a contentless closed tag
	 * @return string
	 * @return-taint escaped
	 */
	public static function element( $element, $attribs = null, $contents = '',
		$allowShortTag = true
	) {
		$out = '<' . $element;
		if ( $attribs !== null ) {
			$out .= self::expandAttributes( $attribs );
		}
		if ( $contents === null ) {
			$out .= '>';
		} elseif ( $allowShortTag && $contents === '' ) {
			$out .= ' />';
		} else {
			$out .= '>' . htmlspecialchars( $contents, ENT_NOQUOTES ) . "</$element>";
		}
		return $out;
	}

	/**
	 * Given an array of ('attributename' => 'value'), it generates the code
	 * to set the XML attributes : attributename="value".
	 * The values are passed to Sanitizer::encodeAttribute.
	 * Returns null or empty string if no attributes given.
	 * @param array|null $attribs Array of attributes for an XML element
	 * @return null|string
	 */
	public static function expandAttributes( ?array $attribs ) {
		if ( $attribs === null ) {
			return null;
		}
		$out = '';
		foreach ( $attribs as $name => $val ) {
			$out .= " {$name}=\"" . Sanitizer::encodeAttribute( $val ) . '"';
		}
		return $out;
	}

	/**
	 * Format an XML element as with self::element(), but run text through the content language's
	 * normalize() validator first to ensure that no invalid UTF-8 is passed.
	 *
	 * @param string $element
	 * @param array|null $attribs Name=>value pairs. Values will be escaped.
	 * @param string|null $contents Null to make an open tag only; '' for a contentless closed tag (default)
	 * @return string
	 * @param-taint $attribs escapes_html
	 * @param-taint $contents escapes_html
	 */
	public static function elementClean( $element, $attribs = [], $contents = '' ) {
		if ( $attribs ) {
			$attribs = array_map( [ Validator::class, 'cleanUp' ], $attribs );
		}
		if ( $contents ) {
			$contents =
				MediaWikiServices::getInstance()->getContentLanguage()->normalize( $contents );
		}
		return self::element( $element, $attribs, $contents );
	}

	/**
	 * This opens an XML element
	 *
	 * @param string $element Name of the element
	 * @param array|null $attribs Array of attributes, see Xml::expandAttributes()
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
	 * @param-taint $element tainted
	 * @param array|null $attribs Array of attributes
	 * @param-taint $attribs escapes_html
	 * @param string $contents Content of the element
	 * @param-taint $contents tainted
	 * @return string
	 * @return-taint escaped
	 */
	public static function tags( $element, $attribs, $contents ) {
		return self::openElement( $element, $attribs ) . $contents . "</$element>";
	}

	/**
	 * Create a date selector
	 *
	 * @param string|null $selected The month which should be selected, default ''.
	 * @param string|null $allmonths Value of a special item denoting all month.
	 *   Null to not include (default).
	 * @param string $id Element identifier
	 * @return string Html string containing the month selector
	 *
	 * @deprecated since 1.42
	 */
	public static function monthSelector( $selected = '', $allmonths = null, $id = 'month' ) {
		wfDeprecated( __METHOD__, '1.42' );

		global $wgLang;
		$options = [];

		$data = new XmlSelect( 'month', $id, $selected ?? '' );

		if ( $allmonths !== null ) {
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
	 * @param int|string $year Use '' or 0 to start with no year preselected.
	 * @param int|string $month A month in the 1..12 range. Use '', 0 or -1 to start with no month
	 *  preselected.
	 * @return string Formatted HTML
	 *
	 * @deprecated since 1.42
	 */
	public static function dateMenu( $year, $month ) {
		wfDeprecated( __METHOD__, '1.42' );
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
			if ( $encMonth > $thisMonth ) {
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
			self::monthSelector( $encMonth, '-1' );
	}

	/**
	 * Construct a language selector appropriate for use in a form or preferences
	 *
	 * @param string $selected The language code of the selected language
	 * @param bool $customisedOnly If true only languages which have some content are listed
	 * @param string|null $inLanguage The ISO code of the language to display the select list in
	 * @param array $overrideAttrs Override the attributes of the select tag (since 1.20)
	 * @param Message|null $msg Label message key (since 1.20)
	 * @return array Array containing 2 items: label HTML and select list HTML
	 *
	 * @deprecated since 1.42
	 */
	public static function languageSelector( $selected, $customisedOnly = true,
		$inLanguage = null, $overrideAttrs = [], ?Message $msg = null
	) {
		wfDeprecated( __METHOD__, '1.42' );
		$languageCode = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::LanguageCode );

		$include = $customisedOnly ? LanguageNameUtils::SUPPORTED : LanguageNameUtils::DEFINED;
		$languages = MediaWikiServices::getInstance()
			->getLanguageNameUtils()
			->getLanguageNames( $inLanguage, $include );

		// Make sure the site language is in the list;
		// a custom language code might not have a defined name...
		if ( !array_key_exists( $languageCode, $languages ) ) {
			$languages[$languageCode] = $languageCode;
			// Sort the array again
			ksort( $languages );
		}

		/**
		 * If a bogus value is set, default to the content language.
		 * Otherwise, no default is selected and the user ends up
		 * with Afrikaans since it's first in the list.
		 */
		$selected = isset( $languages[$selected] ) ? $selected : $languageCode;
		$options = "\n";
		foreach ( $languages as $code => $name ) {
			$options .= self::option( "$code - $name", $code, $code == $selected ) . "\n";
		}

		$attrs = [ 'id' => 'wpUserLanguage', 'name' => 'wpUserLanguage' ];
		$attrs = array_merge( $attrs, $overrideAttrs );

		$msg ??= wfMessage( 'yourlanguage' );
		return [
			self::label( $msg->text(), $attrs['id'] ),
			self::tags( 'select', $attrs, $options )
		];
	}

	/**
	 * Shortcut to make a span element
	 * @param string $text Content of the element, will be escaped
	 * @param string $class Class name of the span element
	 * @param array $attribs Other attributes
	 * @return string
	 *
	 * @deprecated since 1.42, use {@see Html::element} instead; emiting deprecation warnings since 1.44
	 */
	public static function span( $text, $class, $attribs = [] ) {
		wfDeprecated( __METHOD__, '1.42' );

		return self::element( 'span', [ 'class' => $class ] + $attribs, $text );
	}

	/**
	 * Shortcut to make a specific element with a class attribute
	 *
	 * @param string $text Content of the element, will be escaped
	 * @param string $class Class name of the span element
	 * @param string $tag Element name
	 * @param array $attribs Other attributes
	 * @return string
	 *
	 * @deprecated since 1.42, use {@see Xml::tags} instead
	 */
	public static function wrapClass( $text, $class, $tag = 'span', $attribs = [] ) {
		wfDeprecated( __METHOD__, '1.42' );
		return self::tags( $tag, [ 'class' => $class ] + $attribs, $text );
	}

	/**
	 * Convenience function to build an HTML text input field
	 * @param string $name Value of the name attribute
	 * @param int|false $size Value of the size attribute
	 * @param string|false $value Value of the value attribute
	 * @param array $attribs Other attributes
	 * @return string HTML
	 *
	 * @deprecated since 1.42, use {@see Html::input} instead
	 */
	public static function input( $name, $size = false, $value = false, $attribs = [] ) {
		$attributes = [ 'name' => $name ];

		if ( $size ) {
			$attributes['size'] = $size;
		}

		if ( $value !== false ) { // maybe 0
			$attributes['value'] = $value;
		}

		return self::element( 'input', $attributes + $attribs );
	}

	/**
	 * Convenience function to build an HTML password input field
	 * @param string $name Value of the name attribute
	 * @param int|false $size Value of the size attribute
	 * @param string|false $value Value of the value attribute
	 * @param array $attribs Other attributes
	 * @return string HTML
	 *
	 * @deprecated since 1.42, use {@see Html::input} instead; emiting deprecation warnings since 1.44
	 */
	public static function password( $name, $size = false, $value = false,
		$attribs = []
	) {
		wfDeprecated( __METHOD__, '1.42' );

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
	 *
	 * @deprecated since 1.42; only for use in methods being deprecated
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
	 *
	 * @deprecated since 1.42, use {@see Html::check} instead; emiting warnings since 1.44
	 */
	public static function check( $name, $checked = false, $attribs = [] ) {
		wfDeprecated( __METHOD__, '1.42' );

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
	 *
	 * @deprecated since 1.42, use {@see Html::radio} instead
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
	 *
	 * @deprecated since 1.42, use {@see Html::label} instead
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
	 * @param int|false $size Value of the size attribute
	 * @param string|false $value Value of the value attribute
	 * @param array $attribs Other attributes
	 * @return string HTML
	 *
	 * @deprecated since 1.42, use {@see Html::input} and {@see Html::label} instead
	 */
	public static function inputLabel( $label, $name, $id, $size = false,
		$value = false, $attribs = []
	) {
		[ $label, $input ] = self::inputLabelSep( $label, $name, $id, $size, $value, $attribs );
		return $label . "\u{00A0}" . $input;
	}

	/**
	 * Same as Xml::inputLabel() but return input and label in an array
	 *
	 * @param string $label
	 * @param string $name
	 * @param string $id
	 * @param int|false $size
	 * @param string|false $value
	 * @param array $attribs
	 * @return array
	 *
	 * @deprecated since 1.42, use {@see Html::input} and {@see Html::label} instead
	 */
	public static function inputLabelSep( $label, $name, $id, $size = false,
		$value = false, $attribs = []
	) {
		return [
			self::label( $label, $id, $attribs ),
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
	 * @return string HTML
	 *
	 * @deprecated since 1.42, use {@see Html::check} and {@see Html::label} instead
	 */
	public static function checkLabel( $label, $name, $id, $checked = false, $attribs = [] ) {
		return self::check( $name, $checked, [ 'id' => $id ] + $attribs ) .
			"\u{00A0}" .
			self::label( $label, $id, $attribs );
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
	 * @return string HTML
	 *
	 * @deprecated since 1.42, use {@see Html::radio} and {@see Html::label} instead
	 */
	public static function radioLabel( $label, $name, $value, $id,
		$checked = false, $attribs = []
	) {
		return self::radio( $name, $value, $checked, [ 'id' => $id ] + $attribs ) .
			"\u{00A0}" .
			self::label( $label, $id, $attribs );
	}

	/**
	 * Convenience function to build an HTML submit button.
	 *
	 * @param string $value Label text for the button (unescaped)
	 * @param array $attribs Optional custom attributes
	 * @return string HTML
	 *
	 * @deprecated since 1.42, use {@see Html::submitButton} instead; emitting deprecation warnings since 1.44
	 */
	public static function submitButton( $value, $attribs = [] ) {
		wfDeprecated( __METHOD__, '1.42' );

		$attribs += [
			'type' => 'submit',
			'value' => $value,
		];
		return Html::element( 'input', $attribs );
	}

	/**
	 * Convenience function to build an HTML drop-down list item.
	 * @param string $text Text for this item. Will be HTML escaped
	 * @param string|null $value Form submission value; if empty, use text
	 * @param bool $selected If true, will be the default selected item
	 * @param array $attribs Optional additional HTML attributes
	 * @return string HTML
	 *
	 * @deprecated since 1.42, use {@see Html::element} instead
	 */
	public static function option( $text, $value = null, $selected = false,
			$attribs = [] ) {
		if ( $value !== null ) {
			$attribs['value'] = $value;
		}
		if ( $selected ) {
			$attribs['selected'] = 'selected';
		}
		return Html::element( 'option', $attribs, $text );
	}

	/**
	 * Build a drop-down box from a textual list. This is a wrapper
	 * for Xml::listDropdownOptions() plus the XmlSelect class.
	 *
	 * @param string $name Name and id for the drop-down
	 * @param string $list Correctly formatted text (newline delimited) to be
	 *   used to generate the options.
	 * @param string $other Text for the "Other reasons" option
	 * @param string $selected Option which should be pre-selected
	 * @param string $class CSS classes for the drop-down
	 * @param int|null $tabindex Value of the tabindex attribute
	 * @return string
	 *
	 * @deprecated since 1.42; use the equivalent methods in Html without a wrapper
	 */
	public static function listDropdown( $name = '', $list = '', $other = '',
		$selected = '', $class = '', $tabindex = null
	) {
		$options = self::listDropdownOptions( $list, [ 'other' => $other ] );

		$xmlSelect = new XmlSelect( $name, $name, $selected );
		$xmlSelect->addOptions( $options );

		if ( $class ) {
			$xmlSelect->setAttribute( 'class', $class );
		}
		if ( $tabindex ) {
			$xmlSelect->setAttribute( 'tabindex', $tabindex );
		}

		return $xmlSelect->getHTML();
	}

	/**
	 * Build options for a drop-down box from a textual list.
	 *
	 * The result of this function can be passed to XmlSelect::addOptions()
	 * (to render a plain `<select>` dropdown box) or to Xml::listDropdownOptionsOoui()
	 * and then OOUI\DropdownInputWidget() (to render a pretty one).
	 *
	 * @param string $list Correctly formatted text (newline delimited) to be
	 *   used to generate the options.
	 * @param array $params Extra parameters:
	 *   - string $params['other'] If set, add an option with this as text and a value of 'other'
	 * @return array Array keys are textual labels, values are internal values
	 *
	 * @deprecated since 1.42; use the equivalent method in Html
	 */
	public static function listDropdownOptions( $list, $params = [] ) {
		$options = [];

		if ( isset( $params['other'] ) ) {
			$options[ $params['other'] ] = 'other';
		}

		$optgroup = false;
		foreach ( explode( "\n", $list ) as $option ) {
			$value = trim( $option );
			if ( $value == '' ) {
				continue;
			}
			if ( substr( $value, 0, 1 ) == '*' && substr( $value, 1, 1 ) != '*' ) {
				# A new group is starting...
				$value = trim( substr( $value, 1 ) );
				if ( $value !== '' &&
					// Do not use the value for 'other' as option group - T251351
					( !isset( $params['other'] ) || $value !== $params['other'] )
				) {
					$optgroup = $value;
				} else {
					$optgroup = false;
				}
			} elseif ( substr( $value, 0, 2 ) == '**' ) {
				# groupmember
				$opt = trim( substr( $value, 2 ) );
				if ( $optgroup === false ) {
					$options[$opt] = $opt;
				} else {
					$options[$optgroup][$opt] = $opt;
				}
			} else {
				# groupless reason list
				$optgroup = false;
				$options[$option] = $option;
			}
		}

		return $options;
	}

	/**
	 * Convert options for a drop-down box into a format accepted by OOUI\DropdownInputWidget etc.
	 *
	 * TODO Find a better home for this function.
	 *
	 * @param array $options Options, as returned e.g. by Xml::listDropdownOptions()
	 * @return array
	 *
	 * @deprecated since 1.42; use the equivalent method in Html
	 */
	public static function listDropdownOptionsOoui( $options ) {
		$optionsOoui = [];

		foreach ( $options as $text => $value ) {
			if ( is_array( $value ) ) {
				$optionsOoui[] = [ 'optgroup' => (string)$text ];
				foreach ( $value as $text2 => $value2 ) {
					$optionsOoui[] = [ 'data' => (string)$value2, 'label' => (string)$text2 ];
				}
			} else {
				$optionsOoui[] = [ 'data' => (string)$value, 'label' => (string)$text ];
			}
		}

		return $optionsOoui;
	}

	/**
	 * Shortcut for creating fieldsets.
	 *
	 * @param string|false $legend Legend of the fieldset. If evaluates to false,
	 *   legend is not added.
	 * @param string|false $content Pre-escaped content for the fieldset. If false,
	 *   only open fieldset is returned.
	 * @param array $attribs Any attributes to fieldset-element.
	 * @return string
	 *
	 * @deprecated since 1.42, use {@see Html::element} instead
	 */
	public static function fieldset( $legend = false, $content = false, $attribs = [] ) {
		$s = self::openElement( 'fieldset', $attribs ) . "\n";

		if ( $legend ) {
			$s .= self::element( 'legend', null, $legend ) . "\n";
		}

		if ( $content !== false ) {
			$s .= $content . "\n";
			$s .= self::closeElement( 'fieldset' ) . "\n";
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
	 * @return string
	 *
	 * @deprecated since 1.42, use {@see Html::textarea} instead; emiting deprecation warnings since 1.44
	 */
	public static function textarea( $name, $content, $cols = 40, $rows = 5, $attribs = [] ) {
		wfDeprecated( __METHOD__, '1.42' );

		return self::element( 'textarea',
					[
						'name' => $name,
						'id' => $name,
						'cols' => $cols,
						'rows' => $rows
					] + $attribs,
					$content, false );
	}

	/**
	 * Encode a variable of arbitrary type to JavaScript.
	 * If the value is an HtmlJsCode object, pass through the object's value verbatim.
	 *
	 * @note Only use this function for generating JavaScript code. If generating output
	 *       for a proper JSON parser, just call FormatJson::encode() directly.
	 *
	 * @param mixed $value The value being encoded. Can be any type except a resource.
	 * @param-taint $value escapes_html
	 * @param bool $pretty If true, add non-significant whitespace to improve readability.
	 * @return string|false String if successful; false upon failure
	 * @return-taint none
	 *
	 * @deprecated since 1.41, use {@see Html::encodeJsVar} instead; emiting deprecation warnings since 1.44
	 */
	public static function encodeJsVar( $value, $pretty = false ) {
		wfDeprecated( __METHOD__, '1.41' );

		return Html::encodeJsVar( $value, $pretty );
	}

	/**
	 * Create a call to a JavaScript function. The supplied arguments will be
	 * encoded using Xml::encodeJsVar().
	 *
	 * @since 1.17
	 * @param string $name The name of the function to call, or a JavaScript expression
	 *    which evaluates to a function object which is called.
	 * @param-taint $name tainted
	 * @param array $args The arguments to pass to the function.
	 * @param-taint $args escapes_html
	 * @param bool $pretty If true, add non-significant whitespace to improve readability.
	 * @return string|false String if successful; false upon failure
	 * @return-taint none
	 *
	 * @deprecated since 1.41, use {@see Html::encodeJsCall} instead; emiting deprecation warnings since 1.44
	 */
	public static function encodeJsCall( $name, $args, $pretty = false ) {
		wfDeprecated( __METHOD__, '1.41' );

		return Html::encodeJsCall( $name, $args, $pretty );
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
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );

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

		return self::isWellFormed( $html );
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
	 * @param string|null $submitLabel The name of a message containing a label for
	 *   the submit button.
	 * @param array $submitAttribs The attributes to add to the submit button
	 * @return string HTML form.
	 *
	 * @deprecated since 1.42, use OOUI or Codex widgets instead
	 */
	public static function buildForm( $fields, $submitLabel = null, $submitAttribs = [] ) {
		$form = '';
		$form .= "<table><tbody>";

		foreach ( $fields as $labelmsg => $input ) {
			$id = "mw-$labelmsg";
			$form .= self::openElement( 'tr', [ 'id' => $id ] );

			// TODO use a <label> here for accessibility purposes - will need
			// to either not use a table to build the form, or find the ID of
			// the input somehow.

			$form .= self::tags( 'td', [ 'class' => 'mw-label' ], wfMessage( $labelmsg )->parse() );
			$form .= self::openElement( 'td', [ 'class' => 'mw-input' ] )
				. $input . self::closeElement( 'td' );
			$form .= self::closeElement( 'tr' );
		}

		if ( $submitLabel ) {
			$form .= self::openElement( 'tr' );
			$form .= self::tags( 'td', [], '' );
			$form .= self::openElement( 'td', [ 'class' => 'mw-submit' ] )
				. Html::element(
					'input',
					$submitAttribs + [
						'type' => 'submit',
						'value' => wfMessage( $submitLabel )->text(),
					]
				)
				. self::closeElement( 'td' );
			$form .= self::closeElement( 'tr' );
		}

		$form .= "</tbody></table>";

		return $form;
	}

	/**
	 * @param string[][] $rows
	 * @param array|null $attribs An array of attributes to apply to the table tag
	 * @param array|null $headers An array of strings to use as table headers
	 * @return string
	 *
	 * @deprecated since 1.42; use OOUI or Codex widgets instead; emiting deprecation warnings since 1.44
	 */
	public static function buildTable( $rows, $attribs = [], $headers = null ) {
		wfDeprecated( __METHOD__, '1.42' );

		$s = self::openElement( 'table', $attribs );

		if ( is_array( $headers ) ) {
			$s .= self::openElement( 'thead', $attribs );

			foreach ( $headers as $id => $header ) {
				$attribs = [];

				if ( is_string( $id ) ) {
					$attribs['id'] = $id;
				}

				$s .= self::element( 'th', $attribs, $header );
			}
			$s .= self::closeElement( 'thead' );
		}

		foreach ( $rows as $id => $row ) {
			$attribs = [];

			if ( is_string( $id ) ) {
				$attribs['id'] = $id;
			}

			$s .= self::buildTableRow( $attribs, $row );
		}

		$s .= self::closeElement( 'table' );

		return $s;
	}

	/**
	 * Build a row for a table
	 * @param array|null $attribs An array of attributes to apply to the tr tag
	 * @param string[] $cells An array of strings to put in <td>
	 * @return string
	 *
	 * @deprecated since 1.42; use OOUI or Codex widgets instead; emiting deprecation warnings since 1.44
	 */
	public static function buildTableRow( $attribs, $cells ) {
		wfDeprecated( __METHOD__, '1.42' );

		$s = self::openElement( 'tr', $attribs );

		foreach ( $cells as $id => $cell ) {
			$attribs = [];

			if ( is_string( $id ) ) {
				$attribs['id'] = $id;
			}

			$s .= self::element( 'td', $attribs, $cell );
		}

		$s .= self::closeElement( 'tr' );

		return $s;
	}
}
/** @deprecated class alias since 1.43 */
class_alias( Xml::class, 'Xml' );
