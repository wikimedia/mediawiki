<?php

namespace MediaWiki\HTMLForm\Field;

/**
 * Add a submit button inline in the form (as opposed to
 * HTMLForm::addButton(), which will add it at the end).
 *
 * @stable to extend
 */
class HTMLSubmitField extends HTMLButtonField {
	/** @inheritDoc */
	protected $buttonType = 'submit';

	/** @inheritDoc */
	protected $mFlags = [ 'primary', 'progressive' ];

	/** @inheritDoc */
	public function skipLoadData( $request ) {
		return !$request->getCheck( $this->mName );
	}

	/** @inheritDoc */
	public function loadDataFromRequest( $request ) {
		return $request->getCheck( $this->mName );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLSubmitField::class, 'HTMLSubmitField' );
