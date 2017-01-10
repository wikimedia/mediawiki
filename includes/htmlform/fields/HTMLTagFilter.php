<?php
/**
 * Wrapper for ChangeTags::buildTagFilterSelector to use in HTMLForm
 */
class HTMLTagFilter extends HTMLMultiSelectField {
	public function __construct( array $params ) {
		$params['flatlist'] = true;
		$params['dropdown'] = true;
		$params['options'] = array_flip( ChangeTags::listDefinedTags() );
		parent::__construct( $params );
	}
}
