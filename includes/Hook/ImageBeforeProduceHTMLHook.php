<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImageBeforeProduceHTMLHook {
	/**
	 * Called before producing the HTML created by a wiki
	 * image insertion. You can skip the default logic entirely by returning false, or
	 * just modify a few things using call-by-reference.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $skin Skin object
	 * @param ?mixed &$title Title object of the image
	 * @param ?mixed &$file File object, or false if it doesn't exist
	 * @param ?mixed &$frameParams Various parameters with special meanings; see documentation in
	 *   includes/Linker.php for Linker::makeImageLink
	 * @param ?mixed &$handlerParams Various parameters with special meanings; see documentation in
	 *   includes/Linker.php for Linker::makeImageLink
	 * @param ?mixed &$time Timestamp of file in 'YYYYMMDDHHIISS' string form, or false for current
	 * @param ?mixed &$res Final HTML output, used if you return false
	 * @param ?mixed $parser Parser instance
	 * @param ?mixed &$query Query params for desc URL
	 * @param ?mixed &$widthOption Used by the parser to remember the user preference thumbnailsize
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImageBeforeProduceHTML( $skin, &$title, &$file,
		&$frameParams, &$handlerParams, &$time, &$res, $parser, &$query, &$widthOption
	);
}
