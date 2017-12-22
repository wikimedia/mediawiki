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

/**
 * Trait that checks that covers tags are valid, since PHPUnit
 * won't do it unless you run it with coverage, which is super
 * slow.
 *
 * @since 1.31
 */
trait MediaWikiCoversValidator {

	/**
	 * Test that all methods in this class that begin
	 * with "test" have valid covers tags.
	 */
	public function testValidCovers() {
		$methods = get_class_methods( $this );
		$class = get_class( $this );
		foreach ( $methods as $method ) {
			if ( strpos( $method, 'test' ) === 0 ) {
				PHPUnit_Util_Test::getLinesToBeCovered( $class, $method );
			}
		}
		// If the above didn't throw an exception, make an assertion
		// so this isn't marked as risky
		$this->assertTrue( true );
	}
}
