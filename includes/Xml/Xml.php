<?php
/**
 * Methods to generate XML.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Xml;

use MediaWiki\Html\Html;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Sanitizer;
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
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal $contents is non-nullable
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
			$attribs = array_map( Validator::cleanUp( ... ), $attribs );
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
	 * Convenience function to build an HTML form label
	 * @param string $label Text of the label
	 * @param string $id
	 * @param array $attribs An attribute array.  This will usually be
	 *     the same array as is passed to the corresponding input element,
	 *     so this function will cherry-pick appropriate attributes to
	 *     apply to the label as well; only class and title are applied.
	 * @return string HTML
	 *
	 * @deprecated since 1.42, use {@see Html::label} instead; emiting warnings since 1.46
	 */
	public static function label( $label, $id, $attribs = [] ) {
		wfDeprecated( __METHOD__, '1.42' );

		$a = [ 'for' => $id ];

		foreach ( [ 'class', 'title' ] as $attr ) {
			if ( isset( $attribs[$attr] ) ) {
				$a[$attr] = $attribs[$attr];
			}
		}

		return self::element( 'label', $a, $label );
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
			if ( str_starts_with( $value, '*' ) && substr( $value, 1, 1 ) != '*' ) {
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
			} elseif ( str_starts_with( $value, '**' ) ) {
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
			return false;
		}
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
			$form .= self::tags( 'td', [ 'class' => 'mw-input' ], $input );
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
}
/** @deprecated class alias since 1.43 */
class_alias( Xml::class, 'Xml' );
