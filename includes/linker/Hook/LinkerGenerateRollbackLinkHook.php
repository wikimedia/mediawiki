<?php

namespace MediaWiki\Linker\Hook;

use MediaWiki\Context\IContextSource;
use MediaWiki\Linker\Linker;
use MediaWiki\Revision\RevisionRecord;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface LinkerGenerateRollbackLinkHook {
	/**
	 * This hook is called before a rollback link is displayed to allow for customizing the
	 * appearance of the link or substituting it with something entirely different.
	 *
	 * The call to this hook is made after all checks, so the rollback should be valid.
	 *
	 * @see Linker::generateRollback()
	 * @since 1.36
	 *
	 * @param RevisionRecord $revRecord The top RevisionRecord that is being rolled back
	 * @param IContextSource $context The context source provided to the method
	 * @param array $options Array of options for the Linker::generateRollback() method
	 * @param string &$inner HTML of the rollback link
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinkerGenerateRollbackLink( $revRecord, $context, $options, &$inner );
}
