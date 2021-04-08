<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UnitTestsBeforeDatabaseTeardown" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UnitTestsBeforeDatabaseTeardownHook {
	/**
	 * This hook is called right before MediaWiki tears down its
	 * database infrastructure used for unit tests.
	 *
	 * @since 1.35
	 *
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUnitTestsBeforeDatabaseTeardown();
}
