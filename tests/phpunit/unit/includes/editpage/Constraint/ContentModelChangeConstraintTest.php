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

use MediaWiki\EditPage\Constraint\ContentModelChangeConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;

/**
 * Tests the ContentModelChangeConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\ContentModelChangeConstraint
 */
class ContentModelChangeConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;
	use MockAuthorityTrait;
	use MockTitleTrait;

	public function testPass() {
		$newContentModel = 'FooBarBaz';

		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getContentModel', 'setContentModel' ] )
			->getMock();
		$title->expects( $this->once() )
			->method( 'getContentModel' )
			->willReturn( 'differentStartingContentModel' );
		$title->expects( $this->once() )
			->method( 'setContentModel' )
			->with( $newContentModel );

		$performer = $this->mockRegisteredAuthorityWithPermissions( [ 'edit', 'editcontentmodel' ] );
		$constraint = new ContentModelChangeConstraint(
			$performer,
			$title,
			$newContentModel
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testNoChange() {
		$unchangingContentModel = 'FooBarBaz';
		$title = $this->makeMockTitle( __METHOD__, [
			'contentModel' => $unchangingContentModel,
		] );
		$constraint = new ContentModelChangeConstraint(
			$this->mockRegisteredUltimateAuthority(),
			$title,
			$unchangingContentModel
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$newContentModel = 'FooBarBaz';

		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getContentModel', 'setContentModel' ] )
			->getMock();
		$title->expects( $this->once() )
			->method( 'getContentModel' )
			->willReturn( 'differentStartingContentModel' );
		$title->expects( $this->once() )
			->method( 'setContentModel' )
			->with( $newContentModel );

		$performer = $this->mockRegisteredAuthority( function (
			string $permission,
			PageIdentity $page = null
		) use ( $title ) {
			if ( $permission === 'editcontentmodel' ) {
				if ( $page ) {
					$this->assertEquals( $title, $page );
				}
				return true;
			}
			if ( $permission === 'edit' ) {
				$this->assertEquals( $title, $page );
				return false;
			}
			$this->fail( "Unexpected permission check $permission" );
			return false;
		} );

		$constraint = new ContentModelChangeConstraint(
			$performer,
			$title,
			$newContentModel
		);
		$this->assertConstraintFailed(
			$constraint,
			IEditConstraint::AS_NO_CHANGE_CONTENT_MODEL
		);
	}

	public function testFailure_quick() {
		$title = $this->makeMockTitle( __METHOD__, [
			'contentModel' => 'differentStartingContentModel',
		] );

		$constraint = new ContentModelChangeConstraint(
			$this->mockRegisteredAuthorityWithoutPermissions( [ 'editcontentmodel' ] ),
			$title,
			'FooBarBaz'
		);
		$this->assertConstraintFailed(
			$constraint,
			IEditConstraint::AS_NO_CHANGE_CONTENT_MODEL
		);
	}
}
