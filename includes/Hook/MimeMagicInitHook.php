<?php

namespace MediaWiki\Hook;

use MimeAnalyzer;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface MimeMagicInitHook {
	/**
	 * This hook is called before processing the list mapping MIME types to media types
	 * and the list mapping MIME types to file extensions.
	 * As an extension author, you are encouraged to submit patches to MediaWiki's
	 * core to add new MIME types to mime.types.
	 *
	 * @since 1.35
	 *
	 * @param MimeAnalyzer $mimeMagic
	 *   - Use $mimeMagic->addExtraInfo( $stringOfInfo ); for adding new MIME info to the list.
	 *   - Use $mimeMagic->addExtraTypes( $stringOfTypes ); for adding new MIME types to the list.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMimeMagicInit( $mimeMagic );
}
