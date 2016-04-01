<?php

/**
 * Add a submit button inline in the form (as opposed to
 * HTMLForm::addButton(), which will add it at the end).
 */
class HTMLSubmitField extends HTMLButtonField {
	protected $buttonType = 'submit';

	protected $mFlags = [ 'primary', 'constructive' ];

	public function skipLoadData( $request ) {
		return !$request->getCheck( $this->mName );
	}

	public function loadDataFromRequest( $request ) {
		return $request->getCheck( $this->mName );
	}
}
