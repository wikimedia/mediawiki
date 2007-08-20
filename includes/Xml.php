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
			$out .=  self::expandAttributes( $attribs );
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
	 * Given an array of ('attributename' => 'value'), it generates the code
	 * to set the XML attributes : attributename="value".
	 * The values are passed to Sanitizer::encodeAttribute.
	 * Return null if no attributes given.
	 * @param $attribs Array of attributes for an XML element
	 */
	private static function expandAttributes( $attribs ) {
		$out = '';
		if( is_array( $attribs ) ) {
			foreach( $attribs as $name => $val )
				$out .= " {$name}=\"" . Sanitizer::encodeAttribute( $val ) . '"';
		} else {
			throw new MWException( 'Expected attribute array, got something else in ' . __METHOD__ );
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
			wfProfileIn( __METHOD__ . '-norm' );
			$contents = UtfNormal::cleanUp( $contents );
			wfProfileOut( __METHOD__ . '-norm' );
		}
		return self::element( $element, $attribs, $contents );
	}

	/** This open an XML element */
	public static function openElement( $element, $attribs = null ) {
		return '<' . $element . self::expandAttributes( $attribs ) . '>';
	}

	// Shortcut
	public static function closeElement( $element ) { return "</$element>"; }

	/**
	 * Same as <link>element</link>, but does not escape contents. Handy when the
	 * content you have is already valid xml.
	 */
	public static function tags( $element, $attribs = null, $contents ) {
		return self::openElement( $element, $attribs ) . $contents . "</$element>";
	}

	/**
	 * Build a drop-down box for selecting a namespace
	 *
	 * @param mixed $selected Namespace which should be pre-selected
	 * @param mixed $all Value of an item denoting all namespaces, or null to omit
	 * @param bool $hidden Include hidden namespaces? [WTF? --RC]
	 * @return string
	 */
	public static function namespaceSelector( $selected = '', $all = null, $hidden = false ) {
		global $wgContLang;
		$namespaces = $wgContLang->getFormattedNamespaces();
		$options = array();
		
		if( !is_null( $all ) )
			$namespaces = array( $all => wfMsg( 'namespacesall' ) ) + $namespaces;
		foreach( $namespaces as $index => $name ) {
			if( $index < NS_MAIN )
				continue;
			if( $index === 0 )
				$name = wfMsg( 'blanknamespace' );
			$options[] = self::option( $name, $index, $index === $selected );
		}
		
		return Xml::openElement( 'select', array( 'id' => 'namespace', 'name' => 'namespace',
			'class' => 'namespaceselector' ) )
			. "\n"
			. implode( "\n", $options )
			. "\n"
			. Xml::closeElement( 'select' );
	}
		
	/**
	* Create a date selector 	 
	* 	 
	* @param $selected Mixed: the month which should be selected, default '' 	 
	* @param $allmonths String: value of a special item denoting all month. Null to not include (default) 	 
	* @param string $id Element identifier 	 
	* @return String: Html string containing the month selector 	 
	*/ 	 
	public static function monthSelector( $selected = '', $allmonths = null, $id = 'month' ) { 	 
		global $wgLang; 	 
		$options = array(); 	 
	    if( is_null( $selected ) ) 	 
			$selected = ''; 	 
	    if( !is_null( $allmonths ) ) 	 
			$options[] = self::option( wfMsg( 'monthsall' ), $allmonths, $selected === $allmonths ); 	 
		for( $i = 1; $i < 13; $i++ ) 	 
				$options[] = self::option( $wgLang->getMonthName( $i ), $i, $selected === $i ); 	 
		return self::openElement( 'select', array( 'id' => $id, 'name' => 'month' ) ) 	 
			. implode( "\n", $options ) 	 
			. self::closeElement( 'select' ); 	 
	}

	/**
	 *
	 * @param $language The language code of the selected language
	 * @param $customisedOnly If true only languages which have some content are listed
	 * @return array of label and select
	 */
	public static function languageSelector( $selected, $customisedOnly = true ) {
		global $wgContLanguageCode;
		/**
		 * Make sure the site language is in the list; a custom language code
		 * might not have a defined name...
		 */
		$languages = Language::getLanguageNames( $customisedOnly );
		if( !array_key_exists( $wgContLanguageCode, $languages ) ) {
			$languages[$wgContLanguageCode] = $wgContLanguageCode;
		}
		ksort( $languages );

		/**
		 * If a bogus value is set, default to the content language.
		 * Otherwise, no default is selected and the user ends up
		 * with an Afrikaans interface since it's first in the list.
		 */
		$selected = isset( $languages[$selected] ) ? $selected : $wgContLanguageCode;
		$options = "\n";
		foreach( $languages as $code => $name ) {
			$options .= Xml::option( "$code - $name", $code, ($code == $selected) ) . "\n";
		}

		return array(
			Xml::label( wfMsg('yourlanguage'), 'wpUserLanguage' ),
			Xml::tags( 'select',
				array( 'id' => 'wpUserLanguage', 'name' => 'wpUserLanguage' ),
				$options
			)
		);

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
	 * Convenience function to build an HTML password input field
	 * @return string HTML
	 */
	public static function password( $name, $size=false, $value=false, $attribs=array() ) {
		return self::input( $name, $size, $value, array_merge($attribs, array('type' => 'password')));
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
		return self::element( 'input', array_merge(
			array(
				'name' => $name,
				'type' => 'checkbox',
				'value' => 1 ),
			self::attrib( 'checked', $checked ),
			$attribs ) );
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
			
			# Work around https://bugzilla.mozilla.org/show_bug.cgi?id=274152
			# Encode certain Unicode formatting chars so affected
			# versions of Gecko don't misinterpret our strings;
			# this is a common problem with Farsi text.
			"\xe2\x80\x8c" => "\\u200c", // ZERO WIDTH NON-JOINER
			"\xe2\x80\x8d" => "\\u200d", // ZERO WIDTH JOINER
		);
		return strtr( $string, $pairs );
	}

	/**
	 * Encode a variable of unknown type to JavaScript.
	 * Arrays are converted to JS arrays, objects are converted to JS associative 
	 * arrays (objects). So cast your PHP associative arrays to objects before 
	 * passing them to here.
	 */
	public static function encodeJsVar( $value ) {
		if ( is_bool( $value ) ) {
			$s = $value ? 'true' : 'false';
		} elseif ( is_null( $value ) ) {
			$s = 'null';
		} elseif ( is_int( $value ) ) {
			$s = $value;
		} elseif ( is_array( $value ) ) {
			$s = '[';
			foreach ( $value as $elt ) {
				if ( $s != '[' ) {
					$s .= ', ';
				}
				$s .= self::encodeJsVar( $elt );
			}
			$s .= ']';
		} elseif ( is_object( $value ) ) {
			$s = '{';
			foreach ( (array)$value as $name => $elt ) {
				if ( $s != '{' ) {
					$s .= ', ';
				}
				$s .= '"' . self::escapeJsString( $name ) . '": ' . 
					self::encodeJsVar( $elt );
			}
			$s .= '}';
		} else {
			$s = '"' . self::escapeJsString( $value ) . '"';
		}
		return $s;
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
			//$err = xml_error_string( xml_get_error_code( $parser ) );
			//$position = xml_get_current_byte_index( $parser );
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

	/**
	 * Replace " > and < with their respective HTML entities ( &quot;,
	 * &gt;, &lt;)
	 *
	 * @param $in String: text that might contain HTML tags.
	 * @return string Escaped string
	 */
	public static function escapeTagsOnly( $in ) {
		return str_replace(
			array( '"', '>', '<' ),
			array( '&quot;', '&gt;', '&lt;' ),
			$in );
	}
}

