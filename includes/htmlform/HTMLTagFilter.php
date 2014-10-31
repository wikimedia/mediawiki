<?php
/**
 * Wrapper for ChgangeTags::buildTagFilterSelector to use in HTMLForm
 */
class HTMLTagFilter extends HTMLFormField {
	function getInputHTML( $value ) {
		$tagFilter = ChangeTags::buildTagFilterSelector( $value );
		if ( $tagFilter ) {
			list( $tagFilterLabel, $tagFilterSelector ) = $tagFilter;
			// we only need the select field, HTMLForm should handle the label
			return $tagFilterSelector;
		}
		return '';
	}
}
