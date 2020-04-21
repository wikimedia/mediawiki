<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BaseTemplateToolboxHook {
	/**
	 * Called by BaseTemplate when building the $toolbox array
	 * and returning it for the skin to output. You can add items to the toolbox while
	 * still letting the skin make final decisions on skin-specific markup conventions
	 * using this hook.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $sk The BaseTemplate base skin template
	 * @param ?mixed &$toolbox An array of toolbox items, see BaseTemplate::getToolbox and
	 *   BaseTemplate::makeListItem for details on the format of individual items
	 *   inside of this array.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBaseTemplateToolbox( $sk, &$toolbox );
}
