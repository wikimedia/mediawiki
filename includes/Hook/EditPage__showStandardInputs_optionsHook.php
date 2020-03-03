<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPage__showStandardInputs_optionsHook {
	/**
	 * allows injection of form fields into
	 * the editOptions area
	 * Return value is ignored (should always be true)
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editor EditPage instance (object)
	 * @param ?mixed $out an OutputPage instance to write to
	 * @param ?mixed &$tabindex HTML tabindex of the last edit check/button
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPage__showStandardInputs_options( $editor, $out,
		&$tabindex
	);
}
