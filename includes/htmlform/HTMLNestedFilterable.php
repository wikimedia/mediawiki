<?php

interface HTMLNestedFilterable {
	/**
	 * Support for seperating multi-option preferences into multiple preferences
	 * Due to lack of array support.
	 *
	 * @param $data array
	 */
	function filterDataForSubmit( $data );
}
