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
