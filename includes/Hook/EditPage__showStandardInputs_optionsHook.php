<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use EditPage;
use OutputPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPage__showStandardInputs_optionsHook {
	/**
	 * Use this hook to inject form fields into the editOptions area.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editor
	 * @param OutputPage $out OutputPage instance to write to
	 * @param int &$tabindex HTML tabindex of the last edit check/button
	 * @return bool|void Return value is ignored; this hook should always return true
	 */
	public function onEditPage__showStandardInputs_options( $editor, $out,
		&$tabindex
	);
}
