<?php
/**
 * Copyright (C) 2017 Kunal Mehta <legoktm@debian.org>
 *
 * @license GPL-2.0-or-later
 */

use PHPUnit\Framework\CodeCoverageException;
use PHPUnit\Util\Test;

/**
 * Check that `@covers` tags are valid. PHPUnit only does this when generating
 * code coverage reports, which is slow and we generally don't do that when
 * running tests during development and pre-merge in CI.
 *
 * @since 1.31
 */
trait MediaWikiCoversValidator {

	/**
	 * Assert that all "test*" methods in the host class have valid `@covers` tags.
	 *
	 * Don't use a data provider here because this assertion will be run many
	 * thousands of times in CI, and the implicit overhead from PHPUnit with
	 * generating and executing a test case around each becomes rather significant
	 * at that scale. Also, when using a data provider, the setUp() and tearDown()
	 * of the host class would be re-run for every check, which becomes very
	 * expensive for integration tests that involve databases.
	 * @coversNothing
	 */
	public function testValidCovers() {
		$class = static::class;
		foreach ( get_class_methods( $this ) as $method ) {
			if ( str_starts_with( $method, 'test' ) ) {
				try {
					Test::getLinesToBeCovered( $class, $method );
				} catch ( CodeCoverageException $ex ) {
					$this->fail( "$class::$method: " . $ex->getMessage() );
				}
			}
		}

		$this->addToAssertionCount( 1 );
	}
}
