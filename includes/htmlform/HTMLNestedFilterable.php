<?php

namespace MediaWiki\HTMLForm;

interface HTMLNestedFilterable {
	/**
	 * Support for separating multi-option preferences into multiple preferences
	 * Due to lack of array support.
	 *
	 * @param array $data
	 */
	public function filterDataForSubmit( $data );
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLNestedFilterable::class, 'HTMLNestedFilterable' );
