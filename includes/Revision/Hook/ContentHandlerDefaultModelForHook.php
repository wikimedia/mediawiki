<?php

namespace MediaWiki\Revision\Hook;

use Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ContentHandlerDefaultModelFor" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ContentHandlerDefaultModelForHook {
	/**
	 * This hook is called when the default content model is determined for a
	 * given title. Use this hook to assign a different model for that title.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title in question
	 * @param string &$model Model name. Use with CONTENT_MODEL_XXX constants.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentHandlerDefaultModelFor( $title, &$model );
}
