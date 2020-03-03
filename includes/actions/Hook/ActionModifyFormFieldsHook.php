<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ActionModifyFormFieldsHook {
	/**
	 * Before creating an HTMLForm object for a page action;
	 * Allows to change the fields on the form that will be generated.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $name name of the action
	 * @param ?mixed &$fields HTMLForm descriptor array
	 * @param ?mixed $article Article object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onActionModifyFormFields( $name, &$fields, $article );
}
