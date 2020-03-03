<?php

namespace MediaWiki\Content\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ConvertContentHook {
	/**
	 * Called by AbstractContent::convert when a conversion to
	 * another content model is requested.
	 * Handler functions that modify $result should generally return false to disable
	 * further attempts at conversion.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $content The Content object to be converted.
	 * @param ?mixed $toModel The ID of the content model to convert to.
	 * @param ?mixed $lossy boolean indicating whether lossy conversion is allowed.
	 * @param ?mixed &$result Output parameter, in case the handler function wants to provide a
	 *   converted Content object. Note that $result->getContentModel() must return
	 *   $toModel.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onConvertContent( $content, $toModel, $lossy, &$result );
}
