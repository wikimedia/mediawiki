<?php

namespace MediaWiki\Hook;

use File;
use Parser;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserMakeImageParamsHook {
	/**
	 * This hook is called before the parser generates an image link, use this
	 * to modify the parameters of the image.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title object representing the file
	 * @param File $file File object that will be used to create the image
	 * @param array &$params Two-dimensional array of parameters
	 * @param Parser $parser Parser object that called the hook
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserMakeImageParams( $title, $file, &$params, $parser );
}
