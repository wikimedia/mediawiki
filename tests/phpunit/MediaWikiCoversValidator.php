<?php
/**
 * Copyright (C) 2017 Kunal Mehta <legoktm@member.fsf.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
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
	 */
	public function testValidCovers() {
		$class = static::class;
		foreach ( get_class_methods( $this ) as $method ) {
			if ( strncmp( $method, 'test', 4 ) === 0 ) {
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
