<?php

namespace MediaWiki\Hook;

use BaseTemplate;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "BaseTemplateToolbox" to register handlers implementing this interface.
 *
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface BaseTemplateToolboxHook {
	/**
	 * This hook is called by BaseTemplate when building the $toolbox array
	 * and returning it for the skin to output. You can add items to the toolbox while
	 * still letting the skin make final decisions on skin-specific markup conventions
	 * using this hook.
	 *
	 * @since 1.35
	 *
	 * @param BaseTemplate $sk Base skin template
	 * @param array &$toolbox Array of toolbox items, see BaseTemplate::getToolbox and
	 *   BaseTemplate::makeListItem for details on the format of individual items
	 *   inside of this array
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBaseTemplateToolbox( $sk, &$toolbox );
}
