<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserMakeImageParamsHook {
	/**
	 * Called before the parser make an image link, use this
	 * to modify the parameters of the image.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title title object representing the file
	 * @param ?mixed $file file object that will be used to create the image
	 * @param ?mixed &$params 2-D array of parameters
	 * @param ?mixed $parser Parser object that called the hook
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserMakeImageParams( $title, $file, &$params, $parser );
}
