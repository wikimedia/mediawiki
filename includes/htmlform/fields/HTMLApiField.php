<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\HTMLForm\HTMLFormField;

/**
 * @stable to extend
 */
class HTMLApiField extends HTMLFormField {
	public function getTableRow( $value ) {
		return '';
	}

	public function getDiv( $value ) {
		return $this->getTableRow( $value );
	}

	public function getRaw( $value ) {
		return $this->getTableRow( $value );
	}

	public function getInputHTML( $value ) {
		return '';
	}

	public function hasVisibleOutput() {
		return false;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLApiField::class, 'HTMLApiField' );
