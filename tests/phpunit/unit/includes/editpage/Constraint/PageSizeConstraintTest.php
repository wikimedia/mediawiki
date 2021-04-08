<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\PageSizeConstraint;

/**
 * Tests the PageSizeConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\PageSizeConstraint
 */
class PageSizeConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	/**
	 * @dataProvider provideCodes
	 */
	public function testPass( string $type, int $errorCode ) {
		// 1023 < ( 1 * 1024 )
		$constraint = new PageSizeConstraint(
			1,
			1023,
			$type
		);
		$this->assertConstraintPassed( $constraint );
	}

	/**
	 * @dataProvider provideCodes
	 */
	public function testFailure( string $type, int $errorCode ) {
		// 1025 > ( 1 * 1024 )
		$constraint = new PageSizeConstraint(
			1,
			1025,
			$type
		);
		$this->assertConstraintFailed( $constraint, $errorCode );
	}

	public function provideCodes() {
		return [
			'Before merge - CONTENT_TOO_BIG' => [
				PageSizeConstraint::BEFORE_MERGE,
				IEditConstraint::AS_CONTENT_TOO_BIG
			],
			'After merge - MAX_ARTICLE_SIZE' => [
				PageSizeConstraint::AFTER_MERGE,
				IEditConstraint::AS_MAX_ARTICLE_SIZE_EXCEEDED
			]
		];
	}

	public function testInvalidType() {
		$this->expectException( InvalidArgumentException::class );
		new PageSizeConstraint( 1, 1023, 'FooBar' );
	}

}
