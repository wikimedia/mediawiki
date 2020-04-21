<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPageGetCheckboxesDefinitionHook {
	/**
	 * Allows modifying the edit checkboxes
	 * below the textarea in the edit form.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editpage The current EditPage object
	 * @param ?mixed &$checkboxes Array of checkbox definitions. See
	 *   EditPage::getCheckboxesDefinition() for the format.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageGetCheckboxesDefinition( $editpage, &$checkboxes );
}
