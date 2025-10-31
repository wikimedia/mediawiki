<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\HTMLForm\HTMLFormField;

/**
 * @stable to extend
 */
class HTMLApiField extends HTMLFormField {
	/** @inheritDoc */
	public function getTableRow( $value ) {
		return '';
	}

	/** @inheritDoc */
	public function getDiv( $value ) {
		return $this->getTableRow( $value );
	}

	/** @inheritDoc */
	public function getRaw( $value ) {
		return $this->getTableRow( $value );
	}

	/** @inheritDoc */
	public function getInputHTML( $value ) {
		return '';
	}

	/** @inheritDoc */
	public function hasVisibleOutput() {
		return false;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLApiField::class, 'HTMLApiField' );
