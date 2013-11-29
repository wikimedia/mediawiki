<?php
/**
 * Collection of methods to generate HTML content
 *
 * Copyright © 2009 Aryeh Gregor
 * http://www.mediawiki.org/
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
 * This class is a collection of static functions that serve two purposes:
 *
 * 1) Implement any algorithms specified by HTML5, or other HTML
 * specifications, in a convenient and self-contained way.
 *
 * 2) Allow HTML elements to be conveniently and safely generated, like the
 * current Xml class but a) less confused (Xml supports HTML-specific things,
 * but only sometimes!) and b) not necessarily confined to XML-compatible
 * output.
 *
 * There are two important configuration options this class uses:
 *
 * $wgMimeType: If this is set to an xml mimetype then output should be
 *     valid XHTML5.
 * $wgWellFormedXml: If this is set to true, then all output should be
 *     well-formed XML (quotes on attributes, self-closing tags, etc.).
 *
 * This class is meant to be confined to utility functions that are called from
 * trusted code paths.  It does not do enforcement of policy like not allowing
 * <a> elements.
 *
 * @since 1.16
 */
class Html {
	// List of void elements from HTML5, section 8.1.2 as of 2011-08-12
	private static $voidElements = array(
		'area',
		'base',
		'br',
		'col',
		'command',
		'embed',
		'hr',
		'img',
		'input',
		'keygen',
		'link',
		'meta',
		'param',
		'source',
		'track',
		'wbr',
	);

	// Boolean attributes, which may have the value omitted entirely.  Manually
	// collected from the HTML5 spec as of 2011-08-12.
	private static $boolAttribs = array(
		'async',
		'autofocus',
		'autoplay',
		'checked',
		'controls',
		'default',
		'defer',
		'disabled',
		'formnovalidate',
		'hidden',
		'ismap',
		'itemscope',
		'loop',
		'multiple',
		'muted',
		'novalidate',
		'open',
		'pubdate',
		'readonly',
		'required',
		'reversed',
		'scoped',
		'seamless',
		'selected',
		'truespeed',
		'typemustmatch',
		// HTML5 Microdata
		'itemscope',
	);

	/**
	 * Returns an HTML element in a string.  The major advantage here over
	 * manually typing out the HTML is that it will escape all attribute
	 * values.  If you're hardcoding all the attributes, or there are none, you
	 * should probably just type out the html element yourself.
	 *
	 * This is quite similar to Xml::tags(), but it implements some useful
	 * HTML-specific logic.  For instance, there is no $allowShortTag
	 * parameter: the closing tag is magically omitted if $element has an empty
	 * content model.  If $wgWellFormedXml is false, then a few bytes will be
	 * shaved off the HTML output as well.
	 *
	 * @param string $element The element's name, e.g., 'a'
	 * @param array $attribs  Associative array of attributes, e.g., array(
	 *   'href' => 'http://www.mediawiki.org/' ). See expandAttributes() for
	 *   further documentation.
	 * @param string $contents The raw HTML contents of the element: *not*
	 *   escaped!
	 * @return string Raw HTML
	 */
	public static function rawElement( $element, $attribs = array(), $contents = '' ) {
		global $wgWellFormedXml;
		$start = self::openElement( $element, $attribs );
		if ( in_array( $element, self::$voidElements ) ) {
			if ( $wgWellFormedXml ) {
				// Silly XML.
				return substr( $start, 0, -1 ) . ' />';
			}
			return $start;
		} else {
			return "$start$contents" . self::closeElement( $element );
		}
	}

	/**
	 * Identical to rawElement(), but HTML-escapes $contents (like
	 * Xml::element()).
	 *
	 * @param $element string
	 * @param $attribs array
	 * @param $contents string
	 *
	 * @return string
	 */
	public static function element( $element, $attribs = array(), $contents = '' ) {
		return self::rawElement( $element, $attribs, strtr( $contents, array(
			// There's no point in escaping quotes, >, etc. in the contents of
			// elements.
			'&' => '&amp;',
			'<' => '&lt;'
		) ) );
	}

	/**
	 * Identical to rawElement(), but has no third parameter and omits the end
	 * tag (and the self-closing '/' in XML mode for empty elements).
	 *
	 * @param $element string
	 * @param $attribs array
	 *
	 * @return string
	 */
	public static function openElement( $element, $attribs = array() ) {
		global $wgWellFormedXml;
		$attribs = (array)$attribs;
		// This is not required in HTML5, but let's do it anyway, for
		// consistency and better compression.
		$element = strtolower( $element );

		// In text/html, initial <html> and <head> tags can be omitted under
		// pretty much any sane circumstances, if they have no attributes.  See:
		// <http://www.whatwg.org/html/syntax.html#optional-tags>
		if ( !$wgWellFormedXml && !$attribs
		&& in_array( $element, array( 'html', 'head' ) ) ) {
			return '';
		}

		// Remove invalid input types
		if ( $element == 'input' ) {
			$validTypes = array(
				'hidden',
				'text',
				'password',
				'checkbox',
				'radio',
				'file',
				'submit',
				'image',
				'reset',
				'button',

				// HTML input types
				'datetime',
				'datetime-local',
				'date',
				'month',
				'time',
				'week',
				'number',
				'range',
				'email',
				'url',
				'search',
				'tel',
				'color',
			);
			if ( isset( $attribs['type'] )
			&& !in_array( $attribs['type'], $validTypes ) ) {
				unset( $attribs['type'] );
			}
		}

		// According to standard the default type for <button> elements is "submit".
		// Depending on compatibility mode IE might use "button", instead.
		// We enforce the standard "submit".
		if ( $element == 'button' && !isset( $attribs['type'] ) ) {
			$attribs['type'] = 'submit';
		}

		return "<$element" . self::expandAttributes(
			self::dropDefaults( $element, $attribs ) ) . '>';
	}

	/**
	 * Returns "</$element>", except if $wgWellFormedXml is off, in which case
	 * it returns the empty string when that's guaranteed to be safe.
	 *
	 * @since 1.17
	 * @param string $element Name of the element, e.g., 'a'
	 * @return string A closing tag, if required
	 */
	public static function closeElement( $element ) {
		global $wgWellFormedXml;

		$element = strtolower( $element );

		// Reference:
		// http://www.whatwg.org/html/syntax.html#optional-tags
		if ( !$wgWellFormedXml && in_array( $element, array(
			'html',
			'head',
			'body',
			'li',
			'dt',
			'dd',
			'tr',
			'td',
			'th',
		) ) ) {
			return '';
		}
		return "</$element>";
	}

	/**
	 * Given an element name and an associative array of element attributes,
	 * return an array that is functionally identical to the input array, but
	 * possibly smaller.  In particular, attributes might be stripped if they
	 * are given their default values.
	 *
	 * This method is not guaranteed to remove all redundant attributes, only
	 * some common ones and some others selected arbitrarily at random.  It
	 * only guarantees that the output array should be functionally identical
	 * to the input array (currently per the HTML 5 draft as of 2009-09-06).
	 *
	 * @param string $element Name of the element, e.g., 'a'
	 * @param array $attribs  Associative array of attributes, e.g., array(
	 *   'href' => 'http://www.mediawiki.org/' ).  See expandAttributes() for
	 *   further documentation.
	 * @return array An array of attributes functionally identical to $attribs
	 */
	private static function dropDefaults( $element, $attribs ) {

		// Whenever altering this array, please provide a covering test case
		// in HtmlTest::provideElementsWithAttributesHavingDefaultValues
		static $attribDefaults = array(
			'area' => array( 'shape' => 'rect' ),
			'button' => array(
				'formaction' => 'GET',
				'formenctype' => 'application/x-www-form-urlencoded',
			),
			'canvas' => array(
				'height' => '150',
				'width' => '300',
			),
			'command' => array( 'type' => 'command' ),
			'form' => array(
				'action' => 'GET',
				'autocomplete' => 'on',
				'enctype' => 'application/x-www-form-urlencoded',
			),
			'input' => array(
				'formaction' => 'GET',
				'type' => 'text',
			),
			'keygen' => array( 'keytype' => 'rsa' ),
			'link' => array( 'media' => 'all' ),
			'menu' => array( 'type' => 'list' ),
			// Note: the use of text/javascript here instead of other JavaScript
			// MIME types follows the HTML5 spec.
			'script' => array( 'type' => 'text/javascript' ),
			'style' => array(
				'media' => 'all',
				'type' => 'text/css',
			),
			'textarea' => array( 'wrap' => 'soft' ),
		);

		$element = strtolower( $element );

		foreach ( $attribs as $attrib => $value ) {
			$lcattrib = strtolower( $attrib );
			if ( is_array( $value ) ) {
				$value = implode( ' ', $value );
			} else {
				$value = strval( $value );
			}

			// Simple checks using $attribDefaults
			if ( isset( $attribDefaults[$element][$lcattrib] ) &&
			$attribDefaults[$element][$lcattrib] == $value ) {
				unset( $attribs[$attrib] );
			}

			if ( $lcattrib == 'class' && $value == '' ) {
				unset( $attribs[$attrib] );
			}
		}

		// More subtle checks
		if ( $element === 'link' && isset( $attribs['type'] )
		&& strval( $attribs['type'] ) == 'text/css' ) {
			unset( $attribs['type'] );
		}
		if ( $element === 'input' ) {
			$type = isset( $attribs['type'] ) ? $attribs['type'] : null;
			$value = isset( $attribs['value'] ) ? $attribs['value'] : null;
			if ( $type === 'checkbox' || $type === 'radio' ) {
				// The default value for checkboxes and radio buttons is 'on'
				// not ''. By stripping value="" we break radio boxes that
				// actually wants empty values.
				if ( $value === 'on' ) {
					unset( $attribs['value'] );
				}
			} elseif ( $type === 'submit' ) {
				// The default value for submit appears to be "Submit" but
				// let's not bother stripping out localized text that matches
				// that.
			} else {
				// The default value for nearly every other field type is ''
				// The 'range' and 'color' types use different defaults but
				// stripping a value="" does not hurt them.
				if ( $value === '' ) {
					unset( $attribs['value'] );
				}
			}
		}
		if ( $element === 'select' && isset( $attribs['size'] ) ) {
			if ( in_array( 'multiple', $attribs )
				|| ( isset( $attribs['multiple'] ) && $attribs['multiple'] !== false )
			) {
				// A multi-select
				if ( strval( $attribs['size'] ) == '4' ) {
					unset( $attribs['size'] );
				}
			} else {
				// Single select
				if ( strval( $attribs['size'] ) == '1' ) {
					unset( $attribs['size'] );
				}
			}
		}

		return $attribs;
	}

	/**
	 * Given an associative array of element attributes, generate a string
	 * to stick after the element name in HTML output.  Like array( 'href' =>
	 * 'http://www.mediawiki.org/' ) becomes something like
	 * ' href="http://www.mediawiki.org"'.  Again, this is like
	 * Xml::expandAttributes(), but it implements some HTML-specific logic.
	 * For instance, it will omit quotation marks if $wgWellFormedXml is false,
	 * and will treat boolean attributes specially.
	 *
	 * Attributes that should contain space-separated lists (such as 'class') array
	 * values are allowed as well, which will automagically be normalized
	 * and converted to a space-separated string. In addition to a numerical
	 * array, the attribute value may also be an associative array. See the
	 * example below for how that works.
	 *
	 * @par Numerical array
	 * @code
	 *     Html::element( 'em', array(
	 *         'class' => array( 'foo', 'bar' )
	 *     ) );
	 *     // gives '<em class="foo bar"></em>'
	 * @endcode
	 *
	 * @par Associative array
	 * @code
	 *     Html::element( 'em', array(
	 *         'class' => array( 'foo', 'bar', 'foo' => false, 'quux' => true )
	 *     ) );
	 *     // gives '<em class="bar quux"></em>'
	 * @endcode
	 *
	 * @param array $attribs Associative array of attributes, e.g., array(
	 *   'href' => 'http://www.mediawiki.org/' ).  Values will be HTML-escaped.
	 *   A value of false means to omit the attribute.  For boolean attributes,
	 *   you can omit the key, e.g., array( 'checked' ) instead of
	 *   array( 'checked' => 'checked' ) or such.
	 * @return string HTML fragment that goes between element name and '>'
	 *   (starting with a space if at least one attribute is output)
	 */
	public static function expandAttributes( $attribs ) {
		global $wgWellFormedXml;

		$ret = '';
		$attribs = (array)$attribs;
		foreach ( $attribs as $key => $value ) {
			// Support intuitive array( 'checked' => true/false ) form
			if ( $value === false || is_null( $value ) ) {
				continue;
			}

			// For boolean attributes, support array( 'foo' ) instead of
			// requiring array( 'foo' => 'meaningless' ).
			if ( is_int( $key )
			&& in_array( strtolower( $value ), self::$boolAttribs ) ) {
				$key = $value;
			}

			// Not technically required in HTML5 but we'd like consistency
			// and better compression anyway.
			$key = strtolower( $key );

			// Bug 23769: Blacklist all form validation attributes for now.  Current
			// (June 2010) WebKit has no UI, so the form just refuses to submit
			// without telling the user why, which is much worse than failing
			// server-side validation.  Opera is the only other implementation at
			// this time, and has ugly UI, so just kill the feature entirely until
			// we have at least one good implementation.

			// As the default value of "1" for "step" rejects decimal
			// numbers to be entered in 'type="number"' fields, allow
			// the special case 'step="any"'.

			if ( in_array( $key, array( 'max', 'min', 'pattern', 'required' ) )
				|| $key === 'step' && $value !== 'any' ) {
				continue;
			}

			// http://www.w3.org/TR/html401/index/attributes.html ("space-separated")
			// http://www.w3.org/TR/html5/index.html#attributes-1 ("space-separated")
			$spaceSeparatedListAttributes = array(
				'class', // html4, html5
				'accesskey', // as of html5, multiple space-separated values allowed
				// html4-spec doesn't document rel= as space-separated
				// but has been used like that and is now documented as such
				// in the html5-spec.
				'rel',
			);

			// Specific features for attributes that allow a list of space-separated values
			if ( in_array( $key, $spaceSeparatedListAttributes ) ) {
				// Apply some normalization and remove duplicates

				// Convert into correct array. Array can contain space-separated
				// values. Implode/explode to get those into the main array as well.
				if ( is_array( $value ) ) {
					// If input wasn't an array, we can skip this step
					$newValue = array();
					foreach ( $value as $k => $v ) {
						if ( is_string( $v ) ) {
							// String values should be normal `array( 'foo' )`
							// Just append them
							if ( !isset( $value[$v] ) ) {
								// As a special case don't set 'foo' if a
								// separate 'foo' => true/false exists in the array
								// keys should be authoritative
								$newValue[] = $v;
							}
						} elseif ( $v ) {
							// If the value is truthy but not a string this is likely
							// an array( 'foo' => true ), falsy values don't add strings
							$newValue[] = $k;
						}
					}
					$value = implode( ' ', $newValue );
				}
				$value = explode( ' ', $value );

				// Normalize spacing by fixing up cases where people used
				// more than 1 space and/or a trailing/leading space
				$value = array_diff( $value, array( '', ' ' ) );

				// Remove duplicates and create the string
				$value = implode( ' ', array_unique( $value ) );
			}

			// See the "Attributes" section in the HTML syntax part of HTML5,
			// 9.1.2.3 as of 2009-08-10.  Most attributes can have quotation
			// marks omitted, but not all.  (Although a literal " is not
			// permitted, we don't check for that, since it will be escaped
			// anyway.)
			#
			// See also research done on further characters that need to be
			// escaped: http://code.google.com/p/html5lib/issues/detail?id=93
			$badChars = "\\x00- '=<>`/\x{00a0}\x{1680}\x{180e}\x{180F}\x{2000}\x{2001}"
				. "\x{2002}\x{2003}\x{2004}\x{2005}\x{2006}\x{2007}\x{2008}\x{2009}"
				. "\x{200A}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}";
			if ( $wgWellFormedXml || $value === ''
			|| preg_match( "![$badChars]!u", $value ) ) {
				$quote = '"';
			} else {
				$quote = '';
			}

			if ( in_array( $key, self::$boolAttribs ) ) {
				// In HTML5, we can leave the value empty. If we don't need
				// well-formed XML, we can omit the = entirely.
				if ( !$wgWellFormedXml ) {
					$ret .= " $key";
				} else {
					$ret .= " $key=\"\"";
				}
			} else {
				// Apparently we need to entity-encode \n, \r, \t, although the
				// spec doesn't mention that.  Since we're doing strtr() anyway,
				// and we don't need <> escaped here, we may as well not call
				// htmlspecialchars().
				// @todo FIXME: Verify that we actually need to
				// escape \n\r\t here, and explain why, exactly.
				#
				// We could call Sanitizer::encodeAttribute() for this, but we
				// don't because we're stubborn and like our marginal savings on
				// byte size from not having to encode unnecessary quotes.
				$map = array(
					'&' => '&amp;',
					'"' => '&quot;',
					"\n" => '&#10;',
					"\r" => '&#13;',
					"\t" => '&#9;'
				);
				if ( $wgWellFormedXml ) {
					// This is allowed per spec: <http://www.w3.org/TR/xml/#NT-AttValue>
					// But reportedly it breaks some XML tools?
					// @todo FIXME: Is this really true?
					$map['<'] = '&lt;';
				}
				$ret .= " $key=$quote" . strtr( $value, $map ) . $quote;
			}
		}
		return $ret;
	}

	/**
	 * Output a "<script>" tag with the given contents.
	 *
	 * @todo do some useful escaping as well, like if $contents contains
	 * literal "</script>" or (for XML) literal "]]>".
	 *
	 * @param string $contents JavaScript
	 * @return string Raw HTML
	 */
	public static function inlineScript( $contents ) {
		global $wgWellFormedXml;

		$attrs = array();

		if ( $wgWellFormedXml && preg_match( '/[<&]/', $contents ) ) {
			$contents = "/*<![CDATA[*/$contents/*]]>*/";
		}

		return self::rawElement( 'script', $attrs, $contents );
	}

	/**
	 * Output a "<script>" tag linking to the given URL, e.g.,
	 * "<script src=foo.js></script>".
	 *
	 * @param $url string
	 * @return string Raw HTML
	 */
	public static function linkedScript( $url ) {
		$attrs = array( 'src' => $url );

		return self::element( 'script', $attrs );
	}

	/**
	 * Output a "<style>" tag with the given contents for the given media type
	 * (if any).  TODO: do some useful escaping as well, like if $contents
	 * contains literal "</style>" (admittedly unlikely).
	 *
	 * @param string $contents CSS
	 * @param $media mixed A media type string, like 'screen'
	 * @return string Raw HTML
	 */
	public static function inlineStyle( $contents, $media = 'all' ) {
		global $wgWellFormedXml;

		if ( $wgWellFormedXml && preg_match( '/[<&]/', $contents ) ) {
			$contents = "/*<![CDATA[*/$contents/*]]>*/";
		}

		return self::rawElement( 'style', array(
			'type' => 'text/css',
			'media' => $media,
		), $contents );
	}

	/**
	 * Output a "<link rel=stylesheet>" linking to the given URL for the given
	 * media type (if any).
	 *
	 * @param $url string
	 * @param $media mixed A media type string, like 'screen'
	 * @return string Raw HTML
	 */
	public static function linkedStyle( $url, $media = 'all' ) {
		return self::element( 'link', array(
			'rel' => 'stylesheet',
			'href' => $url,
			'type' => 'text/css',
			'media' => $media,
		) );
	}

	/**
	 * Convenience function to produce an "<input>" element.  This supports the
	 * new HTML5 input types and attributes.
	 *
	 * @param $name    string name attribute
	 * @param $value   mixed  value attribute
	 * @param $type    string type attribute
	 * @param array $attribs  Associative array of miscellaneous extra
	 *   attributes, passed to Html::element()
	 * @return string Raw HTML
	 */
	public static function input( $name, $value = '', $type = 'text', $attribs = array() ) {
		$attribs['type'] = $type;
		$attribs['value'] = $value;
		$attribs['name'] = $name;

		return self::element( 'input', $attribs );
	}

	/**
	 * Convenience function to produce an input element with type=hidden
	 *
	 * @param $name    string name attribute
	 * @param $value   string value attribute
	 * @param array $attribs  Associative array of miscellaneous extra
	 *   attributes, passed to Html::element()
	 * @return string Raw HTML
	 */
	public static function hidden( $name, $value, $attribs = array() ) {
		return self::input( $name, $value, 'hidden', $attribs );
	}

	/**
	 * Convenience function to produce a <textarea> element.
	 *
	 * This supports leaving out the cols= and rows= which Xml requires and are
	 * required by HTML4/XHTML but not required by HTML5.
	 *
	 * @param $name    string name attribute
	 * @param $value   string value attribute
	 * @param array $attribs  Associative array of miscellaneous extra
	 *   attributes, passed to Html::element()
	 * @return string Raw HTML
	 */
	public static function textarea( $name, $value = '', $attribs = array() ) {
		$attribs['name'] = $name;

		if ( substr( $value, 0, 1 ) == "\n" ) {
			// Workaround for bug 12130: browsers eat the initial newline
			// assuming that it's just for show, but they do keep the later
			// newlines, which we may want to preserve during editing.
			// Prepending a single newline
			$spacedValue = "\n" . $value;
		} else {
			$spacedValue = $value;
		}
		return self::element( 'textarea', $attribs, $spacedValue );
	}
	/**
	 * Build a drop-down box for selecting a namespace
	 *
	 * @param $params array:
	 * - selected: [optional] Id of namespace which should be pre-selected
	 * - all: [optional] Value of item for "all namespaces". If null or unset, no "<option>" is generated to select all namespaces
	 * - label: text for label to add before the field
	 * - exclude: [optional] Array of namespace ids to exclude
	 * - disable: [optional] Array of namespace ids for which the option should be disabled in the selector
	 * @param array $selectAttribs HTML attributes for the generated select element.
	 * - id:   [optional], default: 'namespace'
	 * - name: [optional], default: 'namespace'
	 * @return string HTML code to select a namespace.
	 */
	public static function namespaceSelector( array $params = array(), array $selectAttribs = array() ) {
		global $wgContLang;

		ksort( $selectAttribs );

		// Is a namespace selected?
		if ( isset( $params['selected'] ) ) {
			// If string only contains digits, convert to clean int. Selected could also
			// be "all" or "" etc. which needs to be left untouched.
			// PHP is_numeric() has issues with large strings, PHP ctype_digit has other issues
			// and returns false for already clean ints. Use regex instead..
			if ( preg_match( '/^\d+$/', $params['selected'] ) ) {
				$params['selected'] = intval( $params['selected'] );
			}
			// else: leaves it untouched for later processing
		} else {
			$params['selected'] = '';
		}

		if ( !isset( $params['exclude'] ) || !is_array( $params['exclude'] ) ) {
			$params['exclude'] = array();
		}
		if ( !isset( $params['disable'] ) || !is_array( $params['disable'] ) ) {
			$params['disable'] = array();
		}

		// Associative array between option-values and option-labels
		$options = array();

		if ( isset( $params['all'] ) ) {
			// add an option that would let the user select all namespaces.
			// Value is provided by user, the name shown is localized for the user.
			$options[$params['all']] = wfMessage( 'namespacesall' )->text();
		}
		// Add all namespaces as options (in the content language)
		$options += $wgContLang->getFormattedNamespaces();

		// Convert $options to HTML and filter out namespaces below 0
		$optionsHtml = array();
		foreach ( $options as $nsId => $nsName ) {
			if ( $nsId < NS_MAIN || in_array( $nsId, $params['exclude'] ) ) {
				continue;
			}
			if ( $nsId === NS_MAIN ) {
				// For other namespaces use use the namespace prefix as label, but for
				// main we don't use "" but the user message describing it (e.g. "(Main)" or "(Article)")
				$nsName = wfMessage( 'blanknamespace' )->text();
			} elseif ( is_int( $nsId ) ) {
				$nsName = $wgContLang->convertNamespace( $nsId );
			}
			$optionsHtml[] = Html::element(
				'option', array(
					'disabled' => in_array( $nsId, $params['disable'] ),
					'value' => $nsId,
					'selected' => $nsId === $params['selected'],
				), $nsName
			);
		}

		if ( !array_key_exists( 'id', $selectAttribs ) ) {
			$selectAttribs['id'] = 'namespace';
		}

		if ( !array_key_exists( 'name', $selectAttribs ) ) {
			$selectAttribs['name'] = 'namespace';
		}

		$ret = '';
		if ( isset( $params['label'] ) ) {
			$ret .= Html::element(
				'label', array(
					'for' => isset( $selectAttribs['id'] ) ? $selectAttribs['id'] : null,
				), $params['label']
			) . '&#160;';
		}

		// Wrap options in a <select>
		$ret .= Html::openElement( 'select', $selectAttribs )
			. "\n"
			. implode( "\n", $optionsHtml )
			. "\n"
			. Html::closeElement( 'select' );

		return $ret;
	}

	/**
	 * Constructs the opening html-tag with necessary doctypes depending on
	 * global variables.
	 *
	 * @param array $attribs  Associative array of miscellaneous extra
	 *   attributes, passed to Html::element() of html tag.
	 * @return string  Raw HTML
	 */
	public static function htmlHeader( $attribs = array() ) {
		$ret = '';

		global $wgHtml5Version, $wgMimeType, $wgXhtmlNamespaces;

		$isXHTML = self::isXmlMimeType( $wgMimeType );

		if ( $isXHTML ) { // XHTML5
			// XML mimetyped markup should have an xml header.
			// However a DOCTYPE is not needed.
			$ret .= "<?xml version=\"1.0\" encoding=\"UTF-8\" ?" . ">\n";

			// Add the standard xmlns
			$attribs['xmlns'] = 'http://www.w3.org/1999/xhtml';

			// And support custom namespaces
			foreach ( $wgXhtmlNamespaces as $tag => $ns ) {
				$attribs["xmlns:$tag"] = $ns;
			}
		} else { // HTML5
			// DOCTYPE
			$ret .= "<!DOCTYPE html>\n";
		}

		if ( $wgHtml5Version ) {
			$attribs['version'] = $wgHtml5Version;
		}

		$html = Html::openElement( 'html', $attribs );

		if ( $html ) {
			$html .= "\n";
		}

		$ret .= $html;

		return $ret;
	}

	/**
	 * Determines if the given mime type is xml.
	 *
	 * @param $mimetype    string MimeType
	 * @return Boolean
	 */
	public static function isXmlMimeType( $mimetype ) {
		# http://www.whatwg.org/html/infrastructure.html#xml-mime-type
		# * text/xml
		# * application/xml
		# * Any mimetype with a subtype ending in +xml (this implicitly includes application/xhtml+xml)
		return (bool)preg_match( '!^(text|application)/xml$|^.+/.+\+xml$!', $mimetype );
	}

	/**
	 * Get HTML for an info box with an icon.
	 *
	 * @param string $text wikitext, get this with wfMessage()->plain()
	 * @param string $icon icon name, file in skins/common/images
	 * @param string $alt alternate text for the icon
	 * @param string $class additional class name to add to the wrapper div
	 * @param $useStylePath
	 *
	 * @return string
	 */
	static function infoBox( $text, $icon, $alt, $class = false, $useStylePath = true ) {
		global $wgStylePath;

		if ( $useStylePath ) {
			$icon = $wgStylePath . '/common/images/' . $icon;
		}

		$s = Html::openElement( 'div', array( 'class' => "mw-infobox $class" ) );

		$s .= Html::openElement( 'div', array( 'class' => 'mw-infobox-left' ) ) .
				Html::element( 'img',
					array(
						'src' => $icon,
						'alt' => $alt,
					)
				) .
				Html::closeElement( 'div' );

		$s .= Html::openElement( 'div', array( 'class' => 'mw-infobox-right' ) ) .
				$text .
				Html::closeElement( 'div' );
		$s .= Html::element( 'div', array( 'style' => 'clear: left;' ), ' ' );

		$s .= Html::closeElement( 'div' );

		$s .= Html::element( 'div', array( 'style' => 'clear: left;' ), ' ' );

		return $s;
	}

	/**
	 * Generate a srcset attribute value from an array mapping pixel densities
	 * to URLs. Note that srcset supports width and height values as well, which
	 * are not used here.
	 *
	 * @param array $urls
	 * @return string
	 */
	static function srcSet( $urls ) {
		$candidates = array();
		foreach ( $urls as $density => $url ) {
			// Image candidate syntax per current whatwg live spec, 2012-09-23:
			// http://www.whatwg.org/html/embedded-content-1.html#attr-img-srcset
			$candidates[] = "{$url} {$density}x";
		}
		return implode( ", ", $candidates );
	}
}
