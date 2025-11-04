<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Content\Content;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\ImageRedirectConstraint;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;

/**
 * Tests the ImageRedirectConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\ImageRedirectConstraint
 */
class ImageRedirectConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;
	use MockAuthorityTrait;

	/**
	 * @param Authority $performer
	 * @return ImageRedirectConstraint
	 */
	private function getConstraint( Authority $performer ) {
		$content = $this->createMock( Content::class );
		$content->method( 'isRedirect' )->willReturn( true );

		$title = $this->createMock( Title::class );
		$title->method( 'getNamespace' )->willReturn( NS_FILE );

		return new ImageRedirectConstraint(
			$content,
			$title,
			$performer
		);
	}

	public function testPass() {
		$constraint = $this->getConstraint( $this->mockRegisteredUltimateAuthority() );
		$this->assertConstraintPassed( $constraint );
	}

	/**
	 * @dataProvider provideTestFailure
	 */
	public function testFailure( string $performerSpec, int $expectedValue ) {
		$performer = $performerSpec === 'anon'
			? $this->mockAnonAuthorityWithoutPermissions( [ 'upload' ] )
			: $this->mockRegisteredAuthorityWithoutPermissions( [ 'upload' ] );
		$constraint = $this->getConstraint( $performer );
		$this->assertConstraintFailed( $constraint, $expectedValue );
	}

	public static function provideTestFailure() {
		yield 'Anonymous user' => [
			'performerSpec' => 'anon',
			'expectedValue' => IEditConstraint::AS_IMAGE_REDIRECT_ANON
		];
		yield 'Registered user' => [
			'performerSpec' => 'registered',
			'expectedValue' => IEditConstraint::AS_IMAGE_REDIRECT_LOGGED
		];
	}

}
