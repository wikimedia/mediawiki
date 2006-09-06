<?php

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
	 * @param $element String:
	 * @param $attribs Array: Name=>value pairs. Values will be escaped.
	 * @param $contents String: NULL to make an open tag only; '' for a contentless closed tag (default)
	 * @return string
	 */
	public static function element( $element, $attribs = null, $contents = '') {
		$out = '<' . $element;
		if( !is_null( $attribs ) ) {
			foreach( $attribs as $name => $val ) {
				$out .= ' ' . $name . '="' . Sanitizer::encodeAttribute( $val ) . '"';
			}
		}
		if( is_null( $contents ) ) {
			$out .= '>';
		} else {
			if( $contents === '' ) {
				$out .= ' />';
			} else {
				$out .= '>' . htmlspecialchars( $contents ) . "</$element>";
			}
		}
		return $out;
	}

	/**
	 * Format an XML element as with self::element(), but run text through the
	 * UtfNormal::cleanUp() validator first to ensure that no invalid UTF-8
	 * is passed.
	 *
	 * @param $element String:
	 * @param $attribs Array: Name=>value pairs. Values will be escaped.
	 * @param $contents String: NULL to make an open tag only; '' for a contentless closed tag (default)
	 * @return string
	 */
	public static function elementClean( $element, $attribs = array(), $contents = '') {
		if( $attribs ) {
			$attribs = array_map( array( 'UtfNormal', 'cleanUp' ), $attribs );
		}
		if( $contents ) {
			$contents = UtfNormal::cleanUp( $contents );
		}
		return self::element( $element, $attribs, $contents );
	}

	// Shortcuts
	public static function openElement( $element, $attribs = null ) { return self::element( $element, $attribs, null ); }
	public static function closeElement( $element ) { return "</$element>"; }

	/**
	 * Create a namespace selector
	 *
	 * @param $selected Mixed: the namespace which should be selected, default ''
	 * @param $allnamespaces String: value of a special item denoting all namespaces. Null to not include (default)
	 * @param $includehidden Bool: include hidden namespaces?
	 * @return String: Html string containing the namespace selector
	 */
	public static function &namespaceSelector($selected = '', $allnamespaces = null, $includehidden=false) {
		global $wgContLang;
		if( $selected !== '' ) {
			if( is_null( $selected ) ) {
				// No namespace selected; let exact match work without hitting Main
				$selected = '';
			} else {
				// Let input be numeric strings without breaking the empty match.
				$selected = intval( $selected );
			}
		}
		$s = "\n<select id='namespace' name='namespace' class='namespaceselector'>\n";
		$arr = $wgContLang->getFormattedNamespaces();
		if( !is_null($allnamespaces) ) {
			$arr = array($allnamespaces => wfMsg('namespacesall')) + $arr;
		}
		foreach ($arr as $index => $name) {
			if ($index < NS_MAIN) continue;

			$name = $index !== 0 ? $name : wfMsg('blanknamespace');

			if ($index === $selected) {
				$s .= "\t" . self::element("option",
						array("value" => $index, "selected" => "selected"),
						$name) . "\n";
			} else {
				$s .= "\t" . self::element("option", array("value" => $index), $name) . "\n";
			}
		}
		$s .= "</select>\n";
		return $s;
	}

	public static function span( $text, $class, $attribs=array() ) {
		return self::element( 'span', array( 'class' => $class ) + $attribs, $text );
	}

	/**
	 * Convenience function to build an HTML text input field
	 * @return string HTML
	 */
	public static function input( $name, $size=false, $value=false, $attribs=array() ) {
		return self::element( 'input', array(
			'name' => $name,
			'size' => $size,
			'value' => $value ) + $attribs );
	}

	/**
	 * Internal function for use in checkboxes and radio buttons and such.
	 * @return array
	 */
	public static function attrib( $name, $present = true ) {
		return $present ? array( $name => $name ) : array();
	}

	/**
	 * Convenience function to build an HTML checkbox
	 * @return string HTML
	 */
	public static function check( $name, $checked=false, $attribs=array() ) {
		return self::element( 'input', array(
			'name' => $name,
			'type' => 'checkbox',
			'value' => 1 ) + self::attrib( 'checked', $checked ) +  $attribs );
	}

	/**
	 * Convenience function to build an HTML radio button
	 * @return string HTML
	 */
	public static function radio( $name, $value, $checked=false, $attribs=array() ) {
		return self::element( 'input', array(
			'name' => $name,
			'type' => 'radio',
			'value' => $value ) + self::attrib( 'checked', $checked ) + $attribs );
	}

	/**
	 * Convenience function to build an HTML form label
	 * @return string HTML
	 */
	public static function label( $label, $id ) {
		return self::element( 'label', array( 'for' => $id ), $label );
	}

	/**
	 * Convenience function to build an HTML text input field with a label
	 * @return string HTML
	 */
	public static function inputLabel( $label, $name, $id, $size=false, $value=false, $attribs=array() ) {
		return Xml::label( $label, $id ) .
			'&nbsp;' .
			self::input( $name, $size, $value, array( 'id' => $id ) + $attribs );
	}

	/**
	 * Convenience function to build an HTML checkbox with a label
	 * @return string HTML
	 */
	public static function checkLabel( $label, $name, $id, $checked=false, $attribs=array() ) {
		return self::check( $name, $checked, array( 'id' => $id ) + $attribs ) .
			'&nbsp;' .
			self::label( $label, $id );
	}

	/**
	 * Convenience function to build an HTML radio button with a label
	 * @return string HTML
	 */
	public static function radioLabel( $label, $name, $value, $id, $checked=false, $attribs=array() ) {
		return self::radio( $name, $value, $checked, array( 'id' => $id ) + $attribs ) .
			'&nbsp;' .
			self::label( $label, $id );
	}

	/**
	 * Convenience function to build an HTML submit button
	 * @param $value String: label text for the button
	 * @param $attribs Array: optional custom attributes
	 * @return string HTML
	 */
	public static function submitButton( $value, $attribs=array() ) {
		return self::element( 'input', array( 'type' => 'submit', 'value' => $value ) + $attribs );
	}

	/**
	 * Convenience function to build an HTML hidden form field.
	 * @todo Document $name parameter.
	 * @param $name FIXME
	 * @param $value String: label text for the button
	 * @param $attribs Array: optional custom attributes
	 * @return string HTML
	 */
	public static function hidden( $name, $value, $attribs=array() ) {
		return self::element( 'input', array(
			'name' => $name,
			'type' => 'hidden',
			'value' => $value ) + $attribs );
	}
	
	/**
	 * Convenience function to build an HTML drop-down list item.
	 * @param $text String: text for this item
	 * @param $value String: form submission value; if empty, use text
	 * @param $selected boolean: if true, will be the default selected item
	 * @param $attribs array: optional additional HTML attributes
	 * @return string HTML
	 */
	public static function option( $text, $value=null, $selected=false,
			$attribs=array() ) {
		if( !is_null( $value ) ) {
			$attribs['value'] = $value;
		}
		if( $selected ) {
			$attribs['selected'] = 'selected';
		}
		return self::element( 'option', $attribs, $text );
	}

	/**
	 * Returns an escaped string suitable for inclusion in a string literal
	 * for JavaScript source code.
	 * Illegal control characters are assumed not to be present.
	 *
	 * @param string $string
	 * @return string
	 */
	public static function escapeJsString( $string ) {
		// See ECMA 262 section 7.8.4 for string literal format
		$pairs = array(
			"\\" => "\\\\",
			"\"" => "\\\"",
			'\'' => '\\\'',
			"\n" => "\\n",
			"\r" => "\\r",

			# To avoid closing the element or CDATA section
			"<" => "\\x3c",
			">" => "\\x3e",

			# To avoid any complaints about bad entity refs                        
			"&" => "\\x26",
		);
		return strtr( $string, $pairs );
	}

	/**
	 * Check if a string is well-formed XML.
	 * Must include the surrounding tag.
	 *
	 * @param $text String: string to test.
	 * @return bool
	 *
	 * @todo Error position reporting return
	 */
	public static function isWellFormed( $text ) {
		$parser = xml_parser_create( "UTF-8" );

		# case folding violates XML standard, turn it off
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, false );

		if( !xml_parse( $parser, $text, true ) ) {
			$err = xml_error_string( xml_get_error_code( $parser ) );
			$position = xml_get_current_byte_index( $parser );
			//$fragment = $this->extractFragment( $html, $position );
			//$this->mXmlError = "$err at byte $position:\n$fragment";
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
	 * @param $text String:
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
}
?>
