<?php
/**
 * Adds raw HTML into the HTML Form
 */
class HTMLFormRawElementField extends HTMLFormField {
	function getLabelHtml( $cellAttributes = [] ) {
		return '';
	}
	function getInputHTML( $value ) {
		return $value;
	}
	function needsLabel() {
		return false;
	}
}
