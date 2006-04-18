<?php

/**
 * Format an XML element with given attributes and, optionally, text content.
 * Element and attribute names are assumed to be ready for literal inclusion.
 * Strings are assumed to not contain XML-illegal characters; special
 * characters (<, >, &) are escaped but illegals are not touched.
 *
 * @param string $element
 * @param array $attribs Name=>value pairs. Values will be escaped.
 * @param string $contents NULL to make an open tag only; '' for a contentless closed tag (default)
 * @return string
 */
function wfElement( $element, $attribs = null, $contents = '') {
	$out = '<' . $element;
	if( !is_null( $attribs ) ) {
		foreach( $attribs as $name => $val ) {
			$out .= ' ' . $name . '="' . htmlspecialchars( $val ) . '"';
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
 * Format an XML element as with wfElement(), but run text through the
 * UtfNormal::cleanUp() validator first to ensure that no invalid UTF-8
 * is passed.
 *
 * @param string $element
 * @param array $attribs Name=>value pairs. Values will be escaped.
 * @param string $contents NULL to make an open tag only; '' for a contentless closed tag (default)
 * @return string
 */
function wfElementClean( $element, $attribs = array(), $contents = '') {
	if( $attribs ) {
		$attribs = array_map( array( 'UtfNormal', 'cleanUp' ), $attribs );
	}
	if( $contents ) {
		$contents = UtfNormal::cleanUp( $contents );
	}
	return wfElement( $element, $attribs, $contents );
}

// Shortcuts
function wfOpenElement( $element, $attribs = null ) { return wfElement( $element, $attribs, null ); }
function wfCloseElement( $element ) { return "</$element>"; }

/**
 * Create a namespace selector
 *
 * @param mixed $selected The namespace which should be selected, default ''
 * @param string $allnamespaces Value of a special item denoting all namespaces. Null to not include (default)
 * @param bool $includehidden Include hidden namespaces?
 * @return Html string containing the namespace selector
 */
function &HTMLnamespaceselector($selected = '', $allnamespaces = null, $includehidden=false) {
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
	$s = "<select id='namespace' name='namespace' class='namespaceselector'>\n\t";
	$arr = $wgContLang->getFormattedNamespaces();
	if( !is_null($allnamespaces) ) {
		$arr = array($allnamespaces => wfMsgHtml('namespacesall')) + $arr;
	}
	foreach ($arr as $index => $name) {
		if ($index < NS_MAIN) continue;

		$name = $index !== 0 ? $name : wfMsgHtml('blanknamespace');

		if ($index === $selected) {
			$s .= wfElement("option",
					array("value" => $index, "selected" => "selected"),
					$name);
		} else {
			$s .= wfElement("option", array("value" => $index), $name);
		}
	}
	$s .= "\n</select>\n";
	return $s;
}

function wfSpan( $text, $class, $attribs=array() ) {
	return wfElement( 'span', array( 'class' => $class ) + $attribs, $text );
}

/**
 * Convenience function to build an HTML text input field
 * @return string HTML
 */
function wfInput( $name, $size=false, $value=false, $attribs=array() ) {
	return wfElement( 'input', array(
		'name' => $name,
		'size' => $size,
		'value' => $value ) + $attribs );
}

/**
 * Internal function for use in checkboxes and radio buttons and such.
 * @return array
 */
function wfAttrib( $name, $present = true ) {
	return $present ? array( $name => $name ) : array();
}

/**
 * Convenience function to build an HTML checkbox
 * @return string HTML
 */
function wfCheck( $name, $checked=false, $attribs=array() ) {
	return wfElement( 'input', array(
		'name' => $name,
		'type' => 'checkbox',
		'value' => 1 ) + wfAttrib( 'checked', $checked ) +  $attribs );
}

/**
 * Convenience function to build an HTML radio button
 * @return string HTML
 */
function wfRadio( $name, $value, $checked=false, $attribs=array() ) {
	return wfElement( 'input', array(
		'name' => $name,
		'type' => 'radio',
		'value' => $value ) + wfAttrib( 'checked', $checked ) + $attribs );
}

/**
 * Convenience function to build an HTML form label
 * @return string HTML
 */
function wfLabel( $label, $id ) {
	return wfElement( 'label', array( 'for' => $id ), $label );
}

/**
 * Convenience function to build an HTML text input field with a label
 * @return string HTML
 */
function wfInputLabel( $label, $name, $id, $size=false, $value=false, $attribs=array() ) {
	return wfLabel( $label, $id ) .
		'&nbsp;' .
		wfInput( $name, $size, $value, array( 'id' => $id ) + $attribs );
}

/**
 * Convenience function to build an HTML checkbox with a label
 * @return string HTML
 */
function wfCheckLabel( $label, $name, $id, $checked=false, $attribs=array() ) {
	return wfCheck( $name, $checked, array( 'id' => $id ) + $attribs ) .
		'&nbsp;' .
		wfLabel( $label, $id );
}

/**
 * Convenience function to build an HTML radio button with a label
 * @return string HTML
 */
function wfRadioLabel( $label, $name, $value, $id, $checked=false, $attribs=array() ) {
	return wfRadio( $name, $checked, $value, array( 'id' => $id ) + $attribs ) .
		'&nbsp;' .
		wfLabel( $label, $id );
}

/**
 * Convenience function to build an HTML submit button
 * @param string $value Label text for the button
 * @param array $attribs optional custom attributes
 * @return string HTML
 */
function wfSubmitButton( $value, $attribs=array() ) {
	return wfElement( 'input', array( 'type' => 'submit', 'value' => $value ) + $attribs );
}

/**
 * Convenience function to build an HTML hidden form field
 * @param string $value Label text for the button
 * @param array $attribs optional custom attributes
 * @return string HTML
 */
function wfHidden( $name, $value, $attribs=array() ) {
	return wfElement( 'input', array(
		'name' => $name,
		'type' => 'hidden',
		'value' => $value ) + $attribs );
}

/**
 * Returns an escaped string suitable for inclusion in a string literal
 * for JavaScript source code.
 * Illegal control characters are assumed not to be present.
 *
 * @param string $string
 * @return string
 */
function wfEscapeJsString( $string ) {
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
	);
	return strtr( $string, $pairs );
}

/**
 * Check if a string is well-formed XML.
 * Must include the surrounding tag.
 *
 * @param string $text
 * @return bool
 *
 * @todo Error position reporting return
 */
function wfIsWellFormedXml( $text ) {
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
 * Wraps fragment in an <html> bit and doctype, so it can be a fragment
 * and can use HTML named entities.
 *
 * @param string $text
 * @return bool
 */
function wfIsWellFormedXmlFragment( $text ) {
	$html =
		Sanitizer::hackDocType() .
		'<html>' .
		$text .
		'</html>';
	return wfIsWellFormedXml( $html );
}


?>