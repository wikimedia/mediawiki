<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LinkerMakeMediaLinkFileHook {
	/**
	 * At the end of Linker::makeMediaLinkFile() just
	 * before the return.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title the Title object that the link is pointing to
	 * @param ?mixed $file the File object or false if broken link
	 * @param ?mixed &$html the link text
	 * @param ?mixed &$attribs the attributes to be applied
	 * @param ?mixed &$ret the value to return if your hook returns false
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinkerMakeMediaLinkFile( $title, $file, &$html, &$attribs,
		&$ret
	);
}
