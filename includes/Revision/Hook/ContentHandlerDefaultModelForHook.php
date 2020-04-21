<?php

namespace MediaWiki\Revision\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ContentHandlerDefaultModelForHook {
	/**
	 * Called when the default content model is
	 * determined for a given title. May be used to assign a different model for that
	 * title.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title the Title in question
	 * @param ?mixed &$model the model name. Use with CONTENT_MODEL_XXX constants.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentHandlerDefaultModelFor( $title, &$model );
}
