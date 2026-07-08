<?php
/**
 * Methods to generate XML.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Xml;

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

}
/** @deprecated class alias since 1.43 */
class_alias( Xml::class, 'Xml' );
