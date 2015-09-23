<?php
/**
 * Wrapper for ChangeTags::buildTagFilterSelector to use in HTMLForm
 */
class HTMLTagFilter extends HTMLFormField {
	protected $tagFilter;

	function getTableRow( $value ) {
		$this->tagFilter = ChangeTags::buildTagFilterSelector( $value );
		if ( $this->tagFilter ) {
			return parent::getTableRow( $value );
		}
		return '';
	}

	function getDiv( $value ) {
		$this->tagFilter = ChangeTags::buildTagFilterSelector( $value );
		if ( $this->tagFilter ) {
			return parent::getDiv( $value );
		}
		return '';
	}

	function getInputHTML( $value ) {
		if ( $this->tagFilter ) {
			// we only need the select field, HTMLForm should handle the label
			return $this->tagFilter[1];
		}
		return '';
	}
}
