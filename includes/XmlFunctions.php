<?php
/**
 * Aliases for functions in the Xml module
 * Look at the Xml class (Xml.php) for the implementations.
 */
function wfElement( $element, $attribs = null, $contents = '') {
	wfDeprecated(__FUNCTION__);
	return Xml::element( $element, $attribs, $contents );
}
function wfElementClean( $element, $attribs = array(), $contents = '') {
	wfDeprecated(__FUNCTION__);
	return Xml::elementClean( $element, $attribs, $contents );
}
function wfOpenElement( $element, $attribs = null ) {
	wfDeprecated(__FUNCTION__);
	return Xml::openElement( $element, $attribs );
}
function wfCloseElement( $element ) {
	wfDeprecated(__FUNCTION__);
	return "</$element>";
}
function HTMLnamespaceselector($selected = '', $allnamespaces = null ) {
	wfDeprecated(__FUNCTION__);
	return Xml::namespaceSelector( $selected, $allnamespaces );
}
function wfSpan( $text, $class, $attribs=array() ) {
	wfDeprecated(__FUNCTION__);
	return Xml::span( $text, $class, $attribs );
}
function wfInput( $name, $size=false, $value=false, $attribs=array() ) {
	wfDeprecated(__FUNCTION__);
	return Xml::input( $name, $size, $value, $attribs );
}
function wfAttrib( $name, $present = true ) {
	wfDeprecated(__FUNCTION__);
	return Xml::attrib( $name, $present );
}
function wfCheck( $name, $checked=false, $attribs=array() ) {
	wfDeprecated(__FUNCTION__);
	return Xml::check( $name, $checked, $attribs );
}
function wfRadio( $name, $value, $checked=false, $attribs=array() ) {
	wfDeprecated(__FUNCTION__);
	return Xml::radio( $name, $value, $checked, $attribs );
}
function wfLabel( $label, $id ) {
	wfDeprecated(__FUNCTION__);
	return Xml::label( $label, $id );
}
function wfInputLabel( $label, $name, $id, $size=false, $value=false, $attribs=array() ) {
	wfDeprecated(__FUNCTION__);
	return Xml::inputLabel( $label, $name, $id, $size, $value, $attribs );
}
function wfCheckLabel( $label, $name, $id, $checked=false, $attribs=array() ) {
	wfDeprecated(__FUNCTION__);
	return Xml::checkLabel( $label, $name, $id, $checked, $attribs );
}
function wfRadioLabel( $label, $name, $value, $id, $checked=false, $attribs=array() ) {
	wfDeprecated(__FUNCTION__);
	return Xml::radioLabel( $label, $name, $value, $id, $checked, $attribs );
}
function wfSubmitButton( $value, $attribs=array() ) {
	wfDeprecated(__FUNCTION__);
	return Xml::submitButton( $value, $attribs );
}
function wfHidden( $name, $value, $attribs=array() ) {
	wfDeprecated(__FUNCTION__);
	return Xml::hidden( $name, $value, $attribs );
}
function wfEscapeJsString( $string ) {
	wfDeprecated(__FUNCTION__);
	return Xml::escapeJsString( $string );
}
function wfIsWellFormedXml( $text ) {
	wfDeprecated(__FUNCTION__);
	return Xml::isWellFormed( $text );
}
function wfIsWellFormedXmlFragment( $text ) {
	wfDeprecated(__FUNCTION__);
	return Xml::isWellFormedXmlFragment( $text );
}

function wfBuildForm( $fields, $submitLabel ) {
	wfDeprecated(__FUNCTION__);
	return Xml::buildForm( $fields, $submitLabel );
}
