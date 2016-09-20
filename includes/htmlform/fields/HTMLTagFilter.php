<?php
/**
 * Wrapper for ChangeTags::buildTagFilterSelector to use in HTMLForm
 */
class HTMLTagFilter extends HTMLFormField {
	protected $tagFilter;

	public function hasVisibleOutput() {
		return (bool) $this->getTagFilter();
	}

	/**
	 * Returns the HTML of the tag filter, if there's one. If $v is null, the tag filter isn't
	 * cached for a second call, otherwise calling this function again will return the previously
	 * created tag filter (even if the second call's $value is different or null).
	 *
	 * @param null $v The pre-selected value
	 * @param bool $ooui See ChangeTags::buildTagFilterSelector() $ooui
	 * @return array
	 */
	protected function getTagFilter( $v = null, $ooui = false ) {
		if ( $this->tagFilter !==  null && $v === null ) {
			return ChangeTags::buildTagFilterSelector();
		} elseif ( $this->tagFilter === null ) {
			$this->tagFilter = ChangeTags::buildTagFilterSelector( $v, false, null, $ooui );
		}
		return $this->tagFilter;
	}

	public function getInputHTML( $value ) {
		if ( $this->getTagFilter( $value, false ) ) {
			// we only need the select field, HTMLForm should handle the label
			return $this->getTagFilter( $value )[1];
		}
	}

	public function getInputOOUI( $value ) {
		if ( $this->getTagFilter( $value, true ) ) {
			// we only need the select field, HTMLForm should handle the label
			return $this->getTagFilter( $value )[1];
		}
	}
}
