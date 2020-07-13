<?php

namespace MediaWiki\Hook;

use Article;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ActionModifyFormFieldsHook {
	/**
	 * This hook is called before creating an HTMLForm object for a page action.
	 * Use this hook to change the fields on the form that will be generated.
	 *
	 * @since 1.35
	 *
	 * @param string $name Name of the action
	 * @param array &$fields HTMLForm descriptor array
	 * @param Article $article
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onActionModifyFormFields( $name, &$fields, $article );
}
