<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MimeMagicImproveFromExtensionHook {
	/**
	 * Allows MW extensions to further improve the
	 * MIME type detected by considering the file extension.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $mimeMagic Instance of MimeAnalyzer.
	 * @param ?mixed $ext File extension.
	 * @param ?mixed &$mime MIME type (in/out).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMimeMagicImproveFromExtension( $mimeMagic, $ext, &$mime );
}
