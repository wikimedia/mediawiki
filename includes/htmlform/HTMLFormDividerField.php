<?php
/**
 * Adds a divider separating form elements
 */
class HTMLFormDividerField extends HTMLFormField {
	function getLabelHtml( $cellAttributes = [] ) {
		return '';
	}
	function getInputHTML( $value ) {
		return '<hr class="mw-htmlform-divider"/>';
	}
	function needsLabel() {
		return false;
	}
}
