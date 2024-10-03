<?php

namespace MediaWiki\Hook;

use Wikimedia\Mime\MimeAnalyzer;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MimeMagicImproveFromExtension" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MimeMagicImproveFromExtensionHook {
	/**
	 * Use this hook to further improve the MIME type detected by considering the file extension.
	 *
	 * @since 1.35
	 *
	 * @param MimeAnalyzer $mimeMagic
	 * @param string $ext File extension
	 * @param string &$mime MIME type (in/out)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMimeMagicImproveFromExtension( $mimeMagic, $ext, &$mime );
}
