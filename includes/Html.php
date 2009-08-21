<?php
# Copyright (C) 2009 Aryeh Gregor
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * This class is a collection of static functions that serve two purposes:
 *
 * 1) Implement any algorithms specified by HTML 5, or other HTML
 * specifications, in a convenient and self-contained way.
 *
 * 2) Allow HTML elements to be conveniently and safely generated, like the
 * current Xml class but a) less confused (Xml supports HTML-specific things,
 * but only sometimes!) and b) not necessarily confined to XML-compatible
 * output.
 *
 * There are two important configuration options this class uses:
 *
 * $wgHtml5: If this is set to false, then all output should be valid XHTML 1.0
 *     Transitional.
 * $wgWellFormedXml: If this is set to true, then all output should be
 *     well-formed XML (quotes on attributes, self-closing tags, etc.).
 *
 * This class is meant to be confined to utility functions that are called from
 * trusted code paths.  It does not do enforcement of policy like not allowing
 * <a> elements.
 */
class Html {
	# List of void elements from HTML 5, section 9.1.2 as of 2009-08-10
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
	);

	# Boolean attributes, which may have the value omitted entirely.  Manually
	# collected from the HTML 5 spec as of 2009-08-10.
	private static $boolAttribs = array(
		'async',
		'autobuffer',
		'autofocus',
		'autoplay',
		'checked',
		'controls',
		'defer',
		'disabled',
		'formnovalidate',
		'hidden',
		'ismap',
		'loop',
		'multiple',
		'novalidate',
		'open',
		'readonly',
		'required',
		'reversed',
		'scoped',
		'seamless',
	);

	/**
	 * Returns an HTML element in a string.  The major advantage here over
	 * manually typing out the HTML is that it will escape all attribute
	 * values.  If you're hardcoding all the attributes, or there are none, you
	 * should probably type out the string yourself.
	 *
	 * This is quite similar to Xml::tags(), but it implements some useful
	 * HTML-specific logic.  For instance, there is no $allowShortTag
	 * parameter: the closing tag is magically omitted if $element has an empty
	 * content model.  If $wgWellFormedXml is false, then a few bytes will be
	 * shaved off the HTML output as well.  In the future, other HTML-specific
	 * features might be added, like allowing arrays for the values of
	 * attributes like class= and media=.
	 *
	 * @param $element  string The element's name, e.g., 'a'
	 * @param $attribs  array  Associative array of attributes, e.g., array(
	 *   'href' => 'http://www.mediawiki.org/' ).  Values will be HTML-escaped.
	 * @param $contents string The raw HTML contents of the element: *not*
	 *   escaped!
	 * @return string Raw HTML
	 */
	public static function rawElement( $element, $attribs = array(), $contents = '' ) {
		global $wgHtml5, $wgWellFormedXml;
		# This is not required in HTML 5, but let's do it anyway, for
		# consistency and better compression.
		$element = strtolower( $element );

		# Element-specific hacks to slim down output and ensure validity
		if ( $element == 'input' ) {
			if ( !$wgHtml5 ) {
				# With $wgHtml5 off we want to validate as XHTML 1, so we
				# strip out any fancy HTML 5-only input types for now.
				#
				# Whitelist of valid types:
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
				);
				if ( isset( $attribs['type'] )
				&& !in_array( $attribs['type'], $validTypes ) ) {
					# Fall back to type=text, the default
					unset( $attribs['type'] );
				}
				# Here we're blacklisting some HTML5-only attributes...
				$html5attribs = array(
					'autocomplete',
					'autofocus',
					'max',
					'min',
					'multiple',
					'pattern',
					'placeholder',
					'required',
					'step',
				);
				foreach ( $html5attribs as $badAttr ) {
					unset( $attribs[$badAttr] );
				}
			}
		}

		$start = "<$element" . self::expandAttributes( $attribs );
		if ( in_array( $element, self::$voidElements ) ) {
			if ( $wgWellFormedXml ) {
				return "$start />";
			}
			return "$start>";
		} else {
			return "$start>$contents</$element>";
		}
	}

	/**
	 * Identical to rawElement(), but HTML-escapes $contents (like
	 * Xml::element()).
	 */
	public static function element( $element, $attribs = array(), $contents = '' ) {
		return self::rawElement( $element, $attribs, strtr( $contents, array(
			# There's no point in escaping quotes, >, etc. in the contents of
			# elements.
			'&' => '&amp;',
			'<' => '&lt;'
		) ) );
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
	 * @param $attribs array Associative array of attributes, e.g., array(
	 *   'href' => 'http://www.mediawiki.org/' ).  Values will be HTML-escaped.
	 * @return string HTML fragment that goes between element name and '>'
	 *   (starting with a space if at least one attribute is output)
	 */
	public static function expandAttributes( $attribs ) {
		global $wgHtml5, $wgWellFormedXml;

		$ret = '';
		foreach ( $attribs as $key => $value ) {
			# For boolean attributes, support array( 'foo' ) instead of
			# requiring array( 'foo' => 'meaningless' ).
			if ( is_int( $key )
			&& in_array( strtolower( $value ), self::$boolAttribs ) ) {
				$key = $value;
			}

			# Not technically required in HTML 5, but required in XHTML 1.0,
			# and we'd like consistency and better compression anyway.
			$key = strtolower( $key );

			# See the "Attributes" section in the HTML syntax part of HTML 5,
			# 9.1.2.3 as of 2009-08-10.  Most attributes can have quotation
			# marks omitted, but not all.  (Although a literal " is not
			# permitted, we don't check for that, since it will be escaped
			# anyway.)
			if ( $wgWellFormedXml || $value == ''
			|| preg_match( "/[ '=<>]/", $value ) ) {
				$quote = '"';
			} else {
				$quote = '';
			}

			if ( in_array( $key, self::$boolAttribs ) ) {
				# In XHTML 1.0 Transitional, the value needs to be equal to the
				# key.  In HTML 5, we can leave the value empty instead.  If we
				# don't need well-formed XML, we can omit the = entirely.
				if ( !$wgWellFormedXml ) {
					$ret .= " $key";
				} elseif ( $wgHtml5 ) {
					$ret .= " $key=\"\"";
				} else {
					$ret .= " $key=\"$key\"";
				}
			} else {
				# Apparently we need to entity-encode \n, \r, \t, although the
				# spec doesn't mention that.  Since we're doing strtr() anyway,
				# and we don't need <> escaped here, we may as well not call
				# htmlspecialchars().  FIXME: verify that we actually need to
				# escape \n\r\t here, and explain why, exactly.
				$ret .= " $key=$quote" . strtr( $value, array(
					'&' => '&amp;',
					'"' => '&quot;',
					"\n" => '&#10;',
					"\r" => '&#13;',
					"\t" => '&#9;'
				) ) . $quote;
			}
		}
		return $ret;
	}

	/**
	 * Output a <script> tag with the given contents.  TODO: do some useful
	 * escaping as well, like if $contents contains literal '</script>' or (for
	 * XML) literal "]]>".
	 *
	 * @param $contents string JavaScript
	 * @return string Raw HTML
	 */
	public static function inlineScript( $contents ) {
		global $wgHtml5, $wgJsMimeType;

		$attrs = array();
		if ( !$wgHtml5 ) {
			$attrs['type'] = $wgJsMimeType;
			$contents = "/*<![CDATA[*/$contents/*]]>*/";
		}
		return self::rawElement( 'script', $attrs, $contents );
	}

	/**
	 * Output a <script> tag linking to the given URL, e.g.,
	 * <script src=foo.js></script>.
	 *
	 * @param $url string
	 * @return string Raw HTML
	 */
	public static function linkedScript( $url ) {
		global $wgHtml5, $wgJsMimeType;

		$attrs = array( 'src' => $url );
		if ( !$wgHtml5 ) {
			$attrs['type'] = $wgJsMimeType;
		}
		return self::element( 'script', $attrs );
	}

	/**
	 * Output a <style> tag with the given contents for the given media type
	 * (if any).  TODO: do some useful escaping as well, like if $contents
	 * contains literal '</style>' (admittedly unlikely).
	 *
	 * @param $contents string CSS
	 * @param $media mixed A media type string, like 'screen', or null for all
	 *   media
	 * @return string Raw HTML
	 */
	public static function inlineStyle( $contents, $media = null ) {
		global $wgHtml5;

		$attrs = array();
		if ( !$wgHtml5 ) {
			# Technically we should probably add CDATA stuff here like with
			# scripts, but in practice, stylesheets tend not to have
			# problematic characters anyway.
			$attrs['type'] = 'text/css';
		}
		if ( $media !== null ) {
			$attrs['media'] = $media;
		}
		return self::rawElement( 'style', $attrs, $contents );
	}

	/**
	 * Output a <link rel=stylesheet> linking to the given URL for the given
	 * media type (if any).
	 *
	 * @param $url string
	 * @param $media mixed A media type string, like 'screen', or null for all
	 *   media
	 * @return string Raw HTML
	 */
	public static function linkedStyle( $url, $media = null ) {
		global $wgHtml5;

		$attrs = array( 'rel' => 'stylesheet', 'href' => $url );
		if ( !$wgHtml5 ) {
			$attrs['type'] = 'text/css';
		}
		if ( $media !== null ) {
			$attrs['media'] = $media;
		}
		return self::element( 'link', $attrs );
	}

	/**
	 * Convenience function to produce an <input> element.  This supports the
	 * new HTML 5 input types and attributes, and will silently strip them if
	 * $wgHtml5 is false.
	 *
	 * @param $name    string name attribute
	 * @param $value   mixed  value attribute (null = omit)
	 * @param $type    string type attribute
	 * @param $attribs array  Associative array of miscellaneous extra
	 *   attributes, passed to Html::element()
	 * @return string Raw HTML
	 */
	public static function input( $name, $value = null, $type = 'text', $attribs = array() ) {
		if ( $type != 'text' ) {
			$attribs['type'] = $type;
		}
		if ( $value !== null && $value !== '' ) {
			$attribs['value'] = $value;
		}
		$attribs['name'] = $name;

		return self::element( 'input', $attribs );
	}

	/**
	 * Convenience function to produce an input element with type=hidden, like
	 * Xml::hidden.
	 *
	 * @param $name    string name attribute
	 * @param $value   string value attribute
	 * @param $attribs array  Associative array of miscellaneous extra
	 *   attributes, passed to Html::element()
	 * @return string Raw HTML
	 */
	public static function hidden( $name, $value, $attribs = array() ) {
		return self::input( $name, $value, 'hidden', $attribs );
	}
}
