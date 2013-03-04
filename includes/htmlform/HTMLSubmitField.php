<?php

/**
 * Add a submit button inline in the form (as opposed to
 * HTMLForm::addButton(), which will add it at the end).
 */
class HTMLSubmitField extends HTMLButtonField {
	protected $buttonType = 'submit';

	// TODO Needs a constructor to add $this->mClass .= ' mw-ui-primary' (or other suitable class name).
}
