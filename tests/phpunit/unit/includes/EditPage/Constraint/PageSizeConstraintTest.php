<?php
/**
 * @license GPL-2.0-or-later
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

	public static function provideCodes() {
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
