<?php
/**
 * Wrapper for ChangeTags::buildTagFilterSelector to use in HTMLForm
 */
class HTMLTagFilter extends HTMLFormField {
	protected $tagFilter;

	public function getTableRow( $value ) {
		$this->tagFilter = ChangeTags::buildTagFilterSelector(
			$value, false, $this->mParent->getContext() );
		if ( $this->tagFilter ) {
			return parent::getTableRow( $value );
		}
		return '';
	}

	public function getDiv( $value ) {
		$this->tagFilter = ChangeTags::buildTagFilterSelector(
			$value, false, $this->mParent->getContext() );
		if ( $this->tagFilter ) {
			return parent::getDiv( $value );
		}
		return '';
	}

	public function getInputHTML( $value ) {
		if ( $this->tagFilter ) {
			// we only need the select field, HTMLForm should handle the label
			return $this->tagFilter[1];
		}
		return '';
	}
}
