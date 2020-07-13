<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UnitTestsListHook {
	/**
	 * This hook is called when building a list of paths containing PHPUnit tests.
	 * Since 1.24, paths pointing to a directory will be recursively scanned for
	 * test case files matching the suffix "Test.php".
	 *
	 * @since 1.35
	 *
	 * @param array &$paths List of test cases and directories to search
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUnitTestsList( &$paths );
}
