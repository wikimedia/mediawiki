<?php
/**
 * Collection of methods to generate HTML content
 *
 * Copyright © 2009 Aryeh Gregor
 * https://www.mediawiki.org/
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

namespace MediaWiki\Html;

use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Request\ContentSecurityPolicy;
use UnexpectedValueException;

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
 * $wgMimeType: If this is set to an xml MIME type then output should be
 *     valid XHTML5.
 *
 * This class is meant to be confined to utility functions that are called from
 * trusted code paths.  It does not do enforcement of policy like not allowing
 * <a> elements.
 *
 * @since 1.16
 */
class Html {
	/** @var bool[] List of void elements from HTML5, section 8.1.2 as of 2016-09-19 */
	private static $voidElements = [
		'area' => true,
		'base' => true,
		'br' => true,
		'col' => true,
		'embed' => true,
		'hr' => true,
		'img' => true,
		'input' => true,
		'keygen' => true,
		'link' => true,
		'meta' => true,
		'param' => true,
		'source' => true,
		'track' => true,
		'wbr' => true,
	];

	/**
	 * Boolean attributes, which may have the value omitted entirely.  Manually
	 * collected from the HTML5 spec as of 2011-08-12.
	 * @var bool[]
	 */
	private static $boolAttribs = [
		'async' => true,
		'autofocus' => true,
		'autoplay' => true,
		'checked' => true,
		'controls' => true,
		'default' => true,
		'defer' => true,
		'disabled' => true,
		'formnovalidate' => true,
		'hidden' => true,
		'ismap' => true,
		'itemscope' => true,
		'loop' => true,
		'multiple' => true,
		'muted' => true,
		'novalidate' => true,
		'open' => true,
		'pubdate' => true,
		'readonly' => true,
		'required' => true,
		'reversed' => true,
		'scoped' => true,
		'seamless' => true,
		'selected' => true,
		'truespeed' => true,
		'typemustmatch' => true,
	];

	/**
	 * Modifies a set of attributes meant for button elements.
	 *
	 * @param array $attrs HTML attributes in an associative array
	 * @param string[] $modifiers Unused
	 * @return array Modified attributes array
	 * @deprecated since 1.42 No-op
	 */
	public static function buttonAttributes( array $attrs, array $modifiers = [] ) {
		wfDeprecated( __METHOD__, '1.42' );
		return $attrs;
	}

	/**
	 * Modifies a set of attributes meant for text input elements.
	 *
	 * @param array $attrs An attribute array.
	 * @return array Modified attributes array
	 * @deprecated since 1.42 No-op
	 */
	public static function getTextInputAttributes( array $attrs ) {
		wfDeprecated( __METHOD__, '1.42' );
		return $attrs;
	}

	/**
	 * Returns an HTML link element in a string.
	 *
	 * @param string $text The text of the element. Will be escaped (not raw HTML)
	 * @param array $attrs Associative array of attributes, e.g., [
	 *   'href' => 'https://www.mediawiki.org/' ]. See expandAttributes() for
	 *   further documentation.
	 * @param string[] $modifiers Unused
	 * @return string Raw HTML
	 */
	public static function linkButton( $text, array $attrs, array $modifiers = [] ) {
		return self::element(
			'a',
			$attrs,
			$text
		);
	}

	/**
	 * Returns an HTML input element in a string.
	 *
	 * @param string $contents Plain text label for the button value
	 * @param array $attrs Associative array of attributes, e.g., [
	 *   'href' => 'https://www.mediawiki.org/' ]. See expandAttributes() for
	 *   further documentation.
	 * @param string[] $modifiers Unused
	 * @return string Raw HTML
	 */
	public static function submitButton( $contents, array $attrs = [], array $modifiers = [] ) {
		$attrs['type'] = 'submit';
		$attrs['value'] = $contents;
		return self::element( 'input', $attrs );
	}

	/**
	 * Returns an HTML element in a string.  The major advantage here over
	 * manually typing out the HTML is that it will escape all attribute
	 * values.
	 *
	 * This is quite similar to Xml::tags(), but it implements some useful
	 * HTML-specific logic.  For instance, there is no $allowShortTag
	 * parameter: the closing tag is magically omitted if $element has an empty
	 * content model.
	 *
	 * @param string $element The element's name, e.g., 'a'
	 * @param-taint $element tainted
	 * @param array $attribs Associative array of attributes, e.g., [
	 *   'href' => 'https://www.mediawiki.org/' ]. See expandAttributes() for
	 *   further documentation.
	 * @param-taint $attribs escapes_html
	 * @param string $contents The raw HTML contents of the element: *not*
	 *   escaped!
	 * @param-taint $contents tainted
	 * @return string Raw HTML
	 * @return-taint escaped
	 */
	public static function rawElement( $element, $attribs = [], $contents = '' ) {
		$start = self::openElement( $element, $attribs );
		if ( isset( self::$voidElements[$element] ) ) {
			return $start;
		} else {
			$contents = Sanitizer::escapeCombiningChar( $contents ?? '' );
			return $start . $contents . self::closeElement( $element );
		}
	}

	/**
	 * Identical to rawElement(), but HTML-escapes $contents (like
	 * Xml::element()).
	 *
	 * @param string $element Name of the element, e.g., 'a'
	 * @param-taint $element tainted
	 * @param array $attribs Associative array of attributes, e.g., [
	 *   'href' => 'https://www.mediawiki.org/' ]. See expandAttributes() for
	 *   further documentation.
	 * @param-taint $attribs escapes_html
	 * @param string $contents
	 * @param-taint $contents escapes_html
	 *
	 * @return string
	 * @return-taint escaped
	 */
	public static function element( $element, $attribs = [], $contents = '' ) {
		return self::rawElement(
			$element,
			$attribs,
			strtr( $contents ?? '', [
				// There's no point in escaping quotes, >, etc. in the contents of
				// elements.
				'&' => '&amp;',
				'<' => '&lt;',
			] )
		);
	}

	/**
	 * Identical to rawElement(), but has no third parameter and omits the end
	 * tag (and the self-closing '/' in XML mode for empty elements).
	 *
	 * @param string $element Name of the element, e.g., 'a'
	 * @param array $attribs Associative array of attributes, e.g., [
	 *   'href' => 'https://www.mediawiki.org/' ]. See expandAttributes() for
	 *   further documentation.
	 *
	 * @return string
	 */
	public static function openElement( $element, $attribs = [] ) {
		$attribs = (array)$attribs;
		// This is not required in HTML5, but let's do it anyway, for
		// consistency and better compression.
		$element = strtolower( $element );

		// Some people were abusing this by passing things like
		// 'h1 id="foo" to $element, which we don't want.
		if ( str_contains( $element, ' ' ) ) {
			wfWarn( __METHOD__ . " given element name with space '$element'" );
		}

		// Remove invalid input types
		if ( $element == 'input' ) {
			$validTypes = [
				'hidden' => true,
				'text' => true,
				'password' => true,
				'checkbox' => true,
				'radio' => true,
				'file' => true,
				'submit' => true,
				'image' => true,
				'reset' => true,
				'button' => true,

				// HTML input types
				'datetime' => true,
				'datetime-local' => true,
				'date' => true,
				'month' => true,
				'time' => true,
				'week' => true,
				'number' => true,
				'range' => true,
				'email' => true,
				'url' => true,
				'search' => true,
				'tel' => true,
				'color' => true,
			];
			if ( isset( $attribs['type'] ) && !isset( $validTypes[$attribs['type']] ) ) {
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
	 * Returns "</$element>"
	 *
	 * @since 1.17
	 * @param string $element Name of the element, e.g., 'a'
	 * @return string A closing tag
	 */
	public static function closeElement( $element ) {
		$element = strtolower( $element );

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
	 * @param array $attribs Associative array of attributes, e.g., [
	 *   'href' => 'https://www.mediawiki.org/' ].  See expandAttributes() for
	 *   further documentation.
	 * @return array An array of attributes functionally identical to $attribs
	 */
	private static function dropDefaults( $element, array $attribs ) {
		// Whenever altering this array, please provide a covering test case
		// in HtmlTest::provideElementsWithAttributesHavingDefaultValues
		static $attribDefaults = [
			'area' => [ 'shape' => 'rect' ],
			'button' => [
				'formaction' => 'GET',
				'formenctype' => 'application/x-www-form-urlencoded',
			],
			'canvas' => [
				'height' => '150',
				'width' => '300',
			],
			'form' => [
				'action' => 'GET',
				'autocomplete' => 'on',
				'enctype' => 'application/x-www-form-urlencoded',
			],
			'input' => [
				'formaction' => 'GET',
				'type' => 'text',
			],
			'keygen' => [ 'keytype' => 'rsa' ],
			'link' => [ 'media' => 'all' ],
			'menu' => [ 'type' => 'list' ],
			'script' => [ 'type' => 'text/javascript' ],
			'style' => [
				'media' => 'all',
				'type' => 'text/css',
			],
			'textarea' => [ 'wrap' => 'soft' ],
		];

		foreach ( $attribs as $attrib => $value ) {
			if ( $attrib === 'class' ) {
				if ( $value === '' || $value === [] || $value === [ '' ] ) {
					unset( $attribs[$attrib] );
				}
			} elseif ( isset( $attribDefaults[$element][$attrib] ) ) {
				if ( is_array( $value ) ) {
					$value = implode( ' ', $value );
				} else {
					$value = strval( $value );
				}
				if ( $attribDefaults[$element][$attrib] == $value ) {
					unset( $attribs[$attrib] );
				}
			}
		}

		// More subtle checks
		if ( $element === 'link'
			&& isset( $attribs['type'] ) && strval( $attribs['type'] ) == 'text/css'
		) {
			unset( $attribs['type'] );
		}
		if ( $element === 'input' ) {
			$type = $attribs['type'] ?? null;
			$value = $attribs['value'] ?? null;
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
			$multiple = ( $attribs['multiple'] ?? false ) !== false ||
				in_array( 'multiple', $attribs );
			$default = $multiple ? 4 : 1;
			if ( (int)$attribs['size'] === $default ) {
				unset( $attribs['size'] );
			}
		}

		return $attribs;
	}

	/**
	 * Given an associative array of element attributes, generate a string
	 * to stick after the element name in HTML output.  Like [ 'href' =>
	 * 'https://www.mediawiki.org/' ] becomes something like
	 * ' href="https://www.mediawiki.org"'.  Again, this is like
	 * Xml::expandAttributes(), but it implements some HTML-specific logic.
	 *
	 * Attributes that can contain space-separated lists ('class', 'accesskey' and 'rel') array
	 * values are allowed as well, which will automagically be normalized
	 * and converted to a space-separated string. In addition to a numerical
	 * array, the attribute value may also be an associative array. See the
	 * example below for how that works.
	 *
	 * @par Numerical array
	 * @code
	 *     Html::element( 'em', [
	 *         'class' => [ 'foo', 'bar' ]
	 *     ] );
	 *     // gives '<em class="foo bar"></em>'
	 * @endcode
	 *
	 * @par Associative array
	 * @code
	 *     Html::element( 'em', [
	 *         'class' => [ 'foo', 'bar', 'foo' => false, 'quux' => true ]
	 *     ] );
	 *     // gives '<em class="bar quux"></em>'
	 * @endcode
	 *
	 * @param array $attribs Associative array of attributes, e.g., [
	 *   'href' => 'https://www.mediawiki.org/' ].  Values will be HTML-escaped.
	 *   A value of false or null means to omit the attribute.  For boolean attributes,
	 *   you can omit the key, e.g., [ 'checked' ] instead of
	 *   [ 'checked' => 'checked' ] or such.
	 *
	 * @return string HTML fragment that goes between element name and '>'
	 *   (starting with a space if at least one attribute is output)
	 */
	public static function expandAttributes( array $attribs ) {
		$ret = '';
		foreach ( $attribs as $key => $value ) {
			// Support intuitive [ 'checked' => true/false ] form
			if ( $value === false || $value === null ) {
				continue;
			}

			// For boolean attributes, support [ 'foo' ] instead of
			// requiring [ 'foo' => 'meaningless' ].
			if ( is_int( $key ) && isset( self::$boolAttribs[strtolower( $value )] ) ) {
				$key = $value;
			}

			// Not technically required in HTML5 but we'd like consistency
			// and better compression anyway.
			$key = strtolower( $key );

			// https://www.w3.org/TR/html401/index/attributes.html ("space-separated")
			// https://www.w3.org/TR/html5/index.html#attributes-1 ("space-separated")
			$spaceSeparatedListAttributes = [
				'class' => true, // html4, html5
				'accesskey' => true, // as of html5, multiple space-separated values allowed
				// html4-spec doesn't document rel= as space-separated
				// but has been used like that and is now documented as such
				// in the html5-spec.
				'rel' => true,
			];

			// Specific features for attributes that allow a list of space-separated values
			if ( isset( $spaceSeparatedListAttributes[$key] ) ) {
				// Apply some normalization and remove duplicates

				// Convert into correct array. Array can contain space-separated
				// values. Implode/explode to get those into the main array as well.
				if ( is_array( $value ) ) {
					// If input wasn't an array, we can skip this step
					$arrayValue = [];
					foreach ( $value as $k => $v ) {
						if ( is_string( $v ) ) {
							// String values should be normal `[ 'foo' ]`
							// Just append them
							if ( !isset( $value[$v] ) ) {
								// As a special case don't set 'foo' if a
								// separate 'foo' => true/false exists in the array
								// keys should be authoritative
								foreach ( explode( ' ', $v ) as $part ) {
									// Normalize spacing by fixing up cases where people used
									// more than 1 space and/or a trailing/leading space
									if ( $part !== '' && $part !== ' ' ) {
										$arrayValue[] = $part;
									}
								}
							}
						} elseif ( $v ) {
							// If the value is truthy but not a string this is likely
							// an [ 'foo' => true ], falsy values don't add strings
							$arrayValue[] = $k;
						}
					}
				} else {
					$arrayValue = explode( ' ', $value );
					// Normalize spacing by fixing up cases where people used
					// more than 1 space and/or a trailing/leading space
					$arrayValue = array_diff( $arrayValue, [ '', ' ' ] );
				}

				// Remove duplicates and create the string
				$value = implode( ' ', array_unique( $arrayValue ) );

				// Optimization: Skip below boolAttribs check and jump straight
				// to its `else` block. The current $spaceSeparatedListAttributes
				// block is mutually exclusive with $boolAttribs.
				// phpcs:ignore Generic.PHP.DiscourageGoto
				goto not_bool; // NOSONAR
			} elseif ( is_array( $value ) ) {
				throw new UnexpectedValueException( "HTML attribute $key can not contain a list of values" );
			}

			if ( isset( self::$boolAttribs[$key] ) ) {
				$ret .= " $key=\"\"";
			} else {
				// phpcs:ignore Generic.PHP.DiscourageGoto
				not_bool:
				// Inlined from Sanitizer::encodeAttribute() for improved performance
				$encValue = htmlspecialchars( $value, ENT_QUOTES );
				// Whitespace is normalized during attribute decoding,
				// so if we've been passed non-spaces we must encode them
				// ahead of time or they won't be preserved.
				$encValue = strtr( $encValue, [
					"\n" => '&#10;',
					"\r" => '&#13;',
					"\t" => '&#9;',
				] );
				$ret .= " $key=\"$encValue\"";
			}
		}
		return $ret;
	}

	/**
	 * Output an HTML script tag with the given contents.
	 *
	 * It is unsupported for the contents to contain the sequence `<script` or `</script`
	 * (case-insensitive). This ensures the script can be terminated easily and consistently.
	 * It is the responsibility of the caller to avoid such character sequence by escaping
	 * or avoiding it. If found at run-time, the contents are replaced with a comment, and
	 * a warning is logged server-side.
	 *
	 * @param string $contents JavaScript
	 * @param string|null $nonce Unused
	 * @return string Raw HTML
	 */
	public static function inlineScript( $contents, $nonce = null ) {
		if ( preg_match( '/<\/?script/i', $contents ) ) {
			wfLogWarning( __METHOD__ . ': Illegal character sequence found in inline script.' );
			$contents = '/* ERROR: Invalid script */';
		}

		return self::rawElement( 'script', [], $contents );
	}

	/**
	 * Output a "<script>" tag linking to the given URL, e.g.,
	 * "<script src=foo.js></script>".
	 *
	 * @param string $url
	 * @param string|null $nonce Nonce for CSP header, from OutputPage->getCSP()->getNonce()
	 * @return string Raw HTML
	 */
	public static function linkedScript( $url, $nonce = null ) {
		$attrs = [ 'src' => $url ];
		if ( $nonce !== null ) {
			$attrs['nonce'] = $nonce;
		} elseif ( ContentSecurityPolicy::isNonceRequired( MediaWikiServices::getInstance()->getMainConfig() ) ) {
			wfWarn( "no nonce set on script. CSP will break it" );
		}

		return self::element( 'script', $attrs );
	}

	/**
	 * Output a "<style>" tag with the given contents for the given media type
	 * (if any).  TODO: do some useful escaping as well, like if $contents
	 * contains literal "</style>" (admittedly unlikely).
	 *
	 * @param string $contents CSS
	 * @param string $media A media type string, like 'screen'
	 * @param array $attribs (since 1.31) Associative array of attributes, e.g., [
	 *   'href' => 'https://www.mediawiki.org/' ]. See expandAttributes() for
	 *   further documentation.
	 * @return string Raw HTML
	 */
	public static function inlineStyle( $contents, $media = 'all', $attribs = [] ) {
		// Don't escape '>' since that is used
		// as direct child selector.
		// Remember, in css, there is no "x" for hexadecimal escapes, and
		// the space immediately after an escape sequence is swallowed.
		$contents = strtr( $contents, [
			'<' => '\3C ',
			// CDATA end tag for good measure, but the main security
			// is from escaping the '<'.
			']]>' => '\5D\5D\3E '
		] );

		if ( preg_match( '/[<&]/', $contents ) ) {
			$contents = "/*<![CDATA[*/$contents/*]]>*/";
		}

		return self::rawElement( 'style', [
			'media' => $media,
		] + $attribs, $contents );
	}

	/**
	 * Output a "<link rel=stylesheet>" linking to the given URL for the given
	 * media type (if any).
	 *
	 * @param string $url
	 * @param string $media A media type string, like 'screen'
	 * @return string Raw HTML
	 */
	public static function linkedStyle( $url, $media = 'all' ) {
		return self::element( 'link', [
			'rel' => 'stylesheet',
			'href' => $url,
			'media' => $media,
		] );
	}

	/**
	 * Convenience function to produce an `<input>` element.  This supports the
	 * new HTML5 input types and attributes.
	 *
	 * @param string $name Name attribute
	 * @param string $value Value attribute
	 * @param string $type Type attribute
	 * @param array $attribs Associative array of miscellaneous extra
	 *   attributes, passed to Html::element()
	 * @return string Raw HTML
	 */
	public static function input( $name, $value = '', $type = 'text', array $attribs = [] ) {
		$attribs['type'] = $type;
		$attribs['value'] = $value;
		$attribs['name'] = $name;
		return self::element( 'input', $attribs );
	}

	/**
	 * Convenience function to produce a checkbox (input element with type=checkbox)
	 *
	 * @param string $name Name attribute
	 * @param bool $checked Whether the checkbox is checked or not
	 * @param array $attribs Array of additional attributes
	 * @return string Raw HTML
	 */
	public static function check( $name, $checked = false, array $attribs = [] ) {
		if ( isset( $attribs['value'] ) ) {
			$value = $attribs['value'];
			unset( $attribs['value'] );
		} else {
			$value = 1;
		}

		if ( $checked ) {
			$attribs[] = 'checked';
		}

		return self::input( $name, $value, 'checkbox', $attribs );
	}

	/**
	 * Return the HTML for a message box.
	 * @since 1.31
	 * @param string $html of contents of box
	 * @param-taint $html tainted
	 * @param string|array $className corresponding to box
	 * @param string $heading (optional)
	 * @param string $iconClassName (optional) corresponding to box icon
	 * @return string of HTML representing a box.
	 */
	private static function messageBox( $html, $className, $heading = '', $iconClassName = '' ) {
		if ( $heading !== '' ) {
			$html = self::element( 'h2', [], $heading ) . $html;
		}
		$coreClasses = [
			'cdx-message',
			'cdx-message--block'
		];
		if ( is_array( $className ) ) {
			$className = array_merge(
				$coreClasses,
				$className
			);
		} else {
			$className .= ' ' . implode( ' ', $coreClasses );
		}
		return self::rawElement( 'div', [ 'class' => $className ],
			self::element( 'span', [ 'class' => [
				'cdx-message__icon',
				$iconClassName
			] ] ) .
			self::rawElement( 'div', [
				'class' => 'cdx-message__content'
			], $html )
		);
	}

	/**
	 * Return the HTML for a notice message box.
	 * @since 1.38
	 * @param string $html of contents of notice
	 * @param-taint $html tainted
	 * @param string|array $className corresponding to notice
	 * @param string $heading (optional)
	 * @param string|array $iconClassName (optional) corresponding to notice icon
	 * @return string of HTML representing the notice
	 */
	public static function noticeBox( $html, $className, $heading = '', $iconClassName = '' ) {
		return self::messageBox( $html, [
			'cdx-message--notice',
			$className
		], $heading, $iconClassName );
	}

	/**
	 * Return a warning box.
	 * @since 1.31
	 * @since 1.34 $className optional parameter added
	 * @param string $html of contents of box
	 * @param-taint $html tainted
	 * @param string $className (optional) corresponding to box
	 * @return string of HTML representing a warning box.
	 */
	public static function warningBox( $html, $className = '' ) {
		return self::messageBox( $html, [
			'cdx-message--warning', $className ] );
	}

	/**
	 * Return an error box.
	 * @since 1.31
	 * @since 1.34 $className optional parameter added
	 * @param string $html of contents of error box
	 * @param-taint $html tainted
	 * @param string $heading (optional)
	 * @param string $className (optional) corresponding to box
	 * @return string of HTML representing an error box.
	 */
	public static function errorBox( $html, $heading = '', $className = '' ) {
		return self::messageBox( $html, [
			'cdx-message--error', $className ], $heading );
	}

	/**
	 * Return a success box.
	 * @since 1.31
	 * @since 1.34 $className optional parameter added
	 * @param string $html of contents of box
	 * @param-taint $html tainted
	 * @param string $className (optional) corresponding to box
	 * @return string of HTML representing a success box.
	 */
	public static function successBox( $html, $className = '' ) {
		return self::messageBox( $html, [
			'cdx-message--success', $className ] );
	}

	/**
	 * Convenience function to produce a radio button (input element with type=radio)
	 *
	 * @param string $name Name attribute
	 * @param bool $checked Whether the radio button is checked or not
	 * @param array $attribs Array of additional attributes
	 * @return string Raw HTML
	 */
	public static function radio( $name, $checked = false, array $attribs = [] ) {
		if ( isset( $attribs['value'] ) ) {
			$value = $attribs['value'];
			unset( $attribs['value'] );
		} else {
			$value = 1;
		}

		if ( $checked ) {
			$attribs[] = 'checked';
		}

		return self::input( $name, $value, 'radio', $attribs );
	}

	/**
	 * Convenience function for generating a label for inputs.
	 *
	 * @param string $label Contents of the label
	 * @param string $id ID of the element being labeled
	 * @param array $attribs Additional attributes
	 * @return string Raw HTML
	 */
	public static function label( $label, $id, array $attribs = [] ) {
		$attribs += [
			'for' => $id,
		];
		return self::element( 'label', $attribs, $label );
	}

	/**
	 * Convenience function to produce an input element with type=hidden
	 *
	 * @param string $name Name attribute
	 * @param mixed $value Value attribute
	 * @param array $attribs Associative array of miscellaneous extra
	 *   attributes, passed to Html::element()
	 * @return string Raw HTML
	 */
	public static function hidden( $name, $value, array $attribs = [] ) {
		return self::input( $name, $value, 'hidden', $attribs );
	}

	/**
	 * Convenience function to produce a <textarea> element.
	 *
	 * This supports leaving out the cols= and rows= which Xml requires and are
	 * required by HTML4/XHTML but not required by HTML5.
	 *
	 * @param string $name Name attribute
	 * @param string $value Value attribute
	 * @param array $attribs Associative array of miscellaneous extra
	 *   attributes, passed to Html::element()
	 * @return string Raw HTML
	 */
	public static function textarea( $name, $value = '', array $attribs = [] ) {
		$attribs['name'] = $name;

		if ( substr( $value ?? '', 0, 1 ) == "\n" ) {
			// Workaround for T14130: browsers eat the initial newline
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
	 * Helper for Html::namespaceSelector().
	 * @param array $params See Html::namespaceSelector()
	 * @return array
	 */
	public static function namespaceSelectorOptions( array $params = [] ) {
		if ( !isset( $params['exclude'] ) || !is_array( $params['exclude'] ) ) {
			$params['exclude'] = [];
		}

		if ( $params['in-user-lang'] ?? false ) {
			global $wgLang;
			$lang = $wgLang;
		} else {
			$lang = MediaWikiServices::getInstance()->getContentLanguage();
		}

		$optionsOut = [];
		if ( isset( $params['all'] ) ) {
			// add an option that would let the user select all namespaces.
			// Value is provided by user, the name shown is localized for the user.
			$optionsOut[$params['all']] = wfMessage( 'namespacesall' )->text();
		}
		// Add all namespaces as options
		$options = $lang->getFormattedNamespaces();
		// Filter out namespaces below 0 and massage labels
		foreach ( $options as $nsId => $nsName ) {
			if ( $nsId < NS_MAIN || in_array( $nsId, $params['exclude'] ) ) {
				continue;
			}
			if ( $nsId === NS_MAIN ) {
				// For other namespaces use the namespace prefix as label, but for
				// main we don't use "" but the user message describing it (e.g. "(Main)" or "(Article)")
				$nsName = wfMessage( 'blanknamespace' )->text();
			} elseif ( is_int( $nsId ) ) {
				$converter = MediaWikiServices::getInstance()->getLanguageConverterFactory()
					->getLanguageConverter( $lang );
				$nsName = $converter->convertNamespace( $nsId );
			}
			$optionsOut[$nsId] = $nsName;
		}

		return $optionsOut;
	}

	/**
	 * Build a drop-down box for selecting a namespace
	 *
	 * @param array $params Params to set.
	 * - selected: [optional] Id of namespace which should be pre-selected
	 * - all: [optional] Value of item for "all namespaces". If null or unset,
	 *   no "<option>" is generated to select all namespaces.
	 * - label: text for label to add before the field.
	 * - exclude: [optional] Array of namespace ids to exclude.
	 * - disable: [optional] Array of namespace ids for which the option should
	 *   be disabled in the selector.
	 * @param array $selectAttribs HTML attributes for the generated select element.
	 * - id:   [optional], default: 'namespace'.
	 * - name: [optional], default: 'namespace'.
	 * @return string HTML code to select a namespace.
	 */
	public static function namespaceSelector(
		array $params = [],
		array $selectAttribs = []
	) {
		ksort( $selectAttribs );

		// Is a namespace selected?
		if ( isset( $params['selected'] ) ) {
			// If string only contains digits, convert to clean int. Selected could also
			// be "all" or "" etc. which needs to be left untouched.
			if ( !is_int( $params['selected'] ) && ctype_digit( (string)$params['selected'] ) ) {
				$params['selected'] = (int)$params['selected'];
			}
			// else: leaves it untouched for later processing
		} else {
			$params['selected'] = '';
		}

		if ( !isset( $params['disable'] ) || !is_array( $params['disable'] ) ) {
			$params['disable'] = [];
		}

		// Associative array between option-values and option-labels
		$options = self::namespaceSelectorOptions( $params );

		// Convert $options to HTML
		$optionsHtml = [];
		foreach ( $options as $nsId => $nsName ) {
			$optionsHtml[] = self::element(
				'option',
				[
					'disabled' => in_array( $nsId, $params['disable'] ),
					'value' => $nsId,
					'selected' => $nsId === $params['selected'],
				],
				$nsName
			);
		}

		$selectAttribs['id'] ??= 'namespace';
		$selectAttribs['name'] ??= 'namespace';

		$ret = '';
		if ( isset( $params['label'] ) ) {
			$ret .= self::element(
				'label', [
					'for' => $selectAttribs['id'],
				], $params['label']
			) . "\u{00A0}";
		}

		// Wrap options in a <select>
		$ret .= self::openElement( 'select', $selectAttribs )
			. "\n"
			. implode( "\n", $optionsHtml )
			. "\n"
			. self::closeElement( 'select' );

		return $ret;
	}

	/**
	 * Constructs the opening html-tag with necessary doctypes depending on
	 * global variables.
	 *
	 * @param array $attribs Associative array of miscellaneous extra
	 *   attributes, passed to Html::element() of html tag.
	 * @return string Raw HTML
	 */
	public static function htmlHeader( array $attribs = [] ) {
		$ret = '';
		$mainConfig = MediaWikiServices::getInstance()->getMainConfig();
		$html5Version = $mainConfig->get( MainConfigNames::Html5Version );
		$mimeType = $mainConfig->get( MainConfigNames::MimeType );
		$xhtmlNamespaces = $mainConfig->get( MainConfigNames::XhtmlNamespaces );

		$isXHTML = self::isXmlMimeType( $mimeType );

		if ( $isXHTML ) { // XHTML5
			// XML MIME-typed markup should have an xml header.
			// However a DOCTYPE is not needed.
			$ret .= "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";

			// Add the standard xmlns
			$attribs['xmlns'] = 'http://www.w3.org/1999/xhtml';

			// And support custom namespaces
			foreach ( $xhtmlNamespaces as $tag => $ns ) {
				$attribs["xmlns:$tag"] = $ns;
			}
		} else { // HTML5
			$ret .= "<!DOCTYPE html>\n";
		}

		if ( $html5Version ) {
			$attribs['version'] = $html5Version;
		}

		$ret .= self::openElement( 'html', $attribs );

		return $ret;
	}

	/**
	 * Determines if the given MIME type is xml.
	 *
	 * @param string $mimetype
	 * @return bool
	 */
	public static function isXmlMimeType( $mimetype ) {
		# https://html.spec.whatwg.org/multipage/infrastructure.html#xml-mime-type
		# * text/xml
		# * application/xml
		# * Any MIME type with a subtype ending in +xml (this implicitly includes application/xhtml+xml)
		return (bool)preg_match( '!^(text|application)/xml$|^.+/.+\+xml$!', $mimetype );
	}

	/**
	 * Generate a srcset attribute value.
	 *
	 * Generates a srcset attribute value from an array mapping pixel densities
	 * to URLs. A trailing 'x' in pixel density values is optional.
	 *
	 * @note srcset width and height values are not supported.
	 *
	 * @see https://html.spec.whatwg.org/#attr-img-srcset
	 *
	 * @par Example:
	 * @code
	 *     Html::srcSet( [
	 *         '1x'   => 'standard.jpeg',
	 *         '1.5x' => 'large.jpeg',
	 *         '3x'   => 'extra-large.jpeg',
	 *     ] );
	 *     // gives 'standard.jpeg 1x, large.jpeg 1.5x, extra-large.jpeg 2x'
	 * @endcode
	 *
	 * @param string[] $urls
	 * @return string
	 */
	public static function srcSet( array $urls ) {
		$candidates = [];
		foreach ( $urls as $density => $url ) {
			// Cast density to float to strip 'x', then back to string to serve
			// as array index.
			$density = (string)(float)$density;
			$candidates[$density] = $url;
		}

		// Remove duplicates that are the same as a smaller value
		ksort( $candidates, SORT_NUMERIC );
		$candidates = array_unique( $candidates );

		// Append density info to the url
		foreach ( $candidates as $density => $url ) {
			$candidates[$density] = $url . ' ' . $density . 'x';
		}

		return implode( ", ", $candidates );
	}

	/**
	 * Encode a variable of arbitrary type to JavaScript.
	 * If the value is an HtmlJsCode object, pass through the object's value verbatim.
	 *
	 * @note Only use this function for generating JavaScript code. If generating output
	 *       for a proper JSON parser, just call FormatJson::encode() directly.
	 *
	 * @since 1.41 (previously on {@link Xml})
	 * @param mixed $value The value being encoded. Can be any type except a resource.
	 * @param-taint $value escapes_html
	 * @param bool $pretty If true, add non-significant whitespace to improve readability.
	 * @return string|false String if successful; false upon failure
	 * @return-taint none
	 */
	public static function encodeJsVar( $value, $pretty = false ) {
		if ( $value instanceof HtmlJsCode ) {
			return $value->value;
		}
		return FormatJson::encode( $value, $pretty, FormatJson::UTF8_OK );
	}

	/**
	 * Create a call to a JavaScript function. The supplied arguments will be
	 * encoded using Html::encodeJsVar().
	 *
	 * @since 1.41 (previously on {@link Xml} since 1.17)
	 * @param string $name The name of the function to call, or a JavaScript expression
	 *    which evaluates to a function object which is called.
	 * @param-taint $name tainted
	 * @param array $args The arguments to pass to the function.
	 * @param-taint $args escapes_html
	 * @param bool $pretty If true, add non-significant whitespace to improve readability.
	 * @return string|false String if successful; false upon failure
	 * @return-taint none
	 */
	public static function encodeJsCall( $name, $args, $pretty = false ) {
		$encodedArgs = self::encodeJsList( $args, $pretty );
		if ( $encodedArgs === false ) {
			return false;
		}
		return "$name($encodedArgs);";
	}

	/**
	 * Encode a JavaScript comma-separated list. The supplied items will be encoded using
	 * Html::encodeJsVar().
	 *
	 * @since 1.41.
	 * @param array $args The elements of the list.
	 * @param bool $pretty If true, add non-significant whitespace to improve readability.
	 * @return false|string String if successful; false upon failure
	 */
	public static function encodeJsList( $args, $pretty = false ) {
		foreach ( $args as &$arg ) {
			$arg = self::encodeJsVar( $arg, $pretty );
			if ( $arg === false ) {
				return false;
			}
		}
		if ( $pretty ) {
			return ' ' . implode( ', ', $args ) . ' ';
		} else {
			return implode( ',', $args );
		}
	}

	/**
	 * Build options for a drop-down box from a textual list.
	 *
	 * The result of this function can be passed to XmlSelect::addOptions()
	 * (to render a plain `<select>` dropdown box) or to Html::listDropdownOptionsOoui()
	 * and then OOUI\DropdownInputWidget() (to render a pretty one).
	 *
	 * @param string $list Correctly formatted text (newline delimited) to be
	 *   used to generate the options.
	 * @param array $params Extra parameters:
	 *   - string $params['other'] If set, add an option with this as text and a value of 'other'
	 * @return array Array keys are textual labels, values are internal values
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
	 * @param array $options Options, as returned e.g. by Html::listDropdownOptions()
	 * @return array
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
	 * Convert options for a drop-down box into a format accepted by OOUI\DropdownInputWidget etc.
	 *
	 * TODO Find a better home for this function.
	 *
	 * @param array $options Options, as returned e.g. by Html::listDropdownOptions()
	 * @return array
	 */
	public static function listDropdownOptionsCodex( $options ) {
		$optionsCodex = [];

		foreach ( $options as $text => $value ) {
			if ( is_array( $value ) ) {
				// No support for optgroups in Codex yet (T367241)
				$optionsCodex[] = [ 'label' => (string)$text, 'value' => '', 'disabled' => true ];
				foreach ( $value as $text2 => $value2 ) {
					$optionsCodex[] = [ 'label' => (string)$text2, 'value' => (string)$value2 ];
				}
			} else {
				$optionsCodex[] = [ 'label' => (string)$text, 'value' => (string)$value ];
			}
		}
		return $optionsCodex;
	}
}

/** @deprecated class alias since 1.40 */
class_alias( Html::class, 'Html' );
