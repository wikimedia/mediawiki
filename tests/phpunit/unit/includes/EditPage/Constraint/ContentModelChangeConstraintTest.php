<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\EditPage\Constraint\ContentModelChangeConstraint;
use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWikiUnitTestCase;
use MockTitleTrait;
use Wikimedia\TestingAccessWrapper;

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

	public static function provideTrueFalse() {
		return [ [ true ], [ false ] ];
	}

	/** @dataProvider provideTrueFalse */
	public function testPass( $exists ) {
		$newContentModel = 'FooBarBaz';

		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getContentModel', 'setContentModel', 'exists' ] )
			->getMock();
		$title->expects( $this->once() )
			->method( 'getContentModel' )
			->willReturn( 'differentStartingContentModel' );
		$title->expects( $this->once() )
			->method( 'setContentModel' )
			->with( $newContentModel );
		$title->expects( $this->once() )
			->method( 'exists' )
			->willReturn( $exists );

		$perm = $exists ? 'editcontentmodel' : 'createwithcontentmodel';
		$performer = $this->mockRegisteredAuthorityWithPermissions( [ 'edit', $perm ] );
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

	/** @dataProvider provideTrueFalse */
	public function testFailure( $exists ) {
		$newContentModel = 'FooBarBaz';

		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getContentModel', 'setContentModel', 'exists' ] )
			->getMock();
		$title->expects( $this->once() )
			->method( 'getContentModel' )
			->willReturn( 'differentStartingContentModel' );
		$title->expects( $this->once() )
			->method( 'setContentModel' )
			->with( $newContentModel );
		$title->expects( $this->once() )
			->method( 'exists' )
			->willReturn( $exists );

		$expectedPerm = $exists ? 'editcontentmodel' : 'createwithcontentmodel';

		$performer = $this->mockRegisteredAuthority( function (
			string $permission,
			?PageIdentity $page = null
		) use ( $title, $expectedPerm ) {
			if ( $permission === $expectedPerm ) {
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
		} );

		$constraint = new ContentModelChangeConstraint(
			$performer,
			$title,
			$newContentModel
		);
		$status = $this->assertConstraintFailed(
			$constraint,
			EditConstraint::AS_NO_CHANGE_CONTENT_MODEL
		);

		$this->assertNotNull( TestingAccessWrapper::newFromObject( $status )->errorFunction );
	}

	public function testFailure_quick() {
		$title = $this->makeMockTitle( __METHOD__, [
			'contentModel' => 'differentStartingContentModel',
			'exists' => true,
		] );

		$constraint = new ContentModelChangeConstraint(
			$this->mockRegisteredAuthorityWithoutPermissions( [ 'editcontentmodel' ] ),
			$title,
			'FooBarBaz'
		);
		$this->assertConstraintFailed(
			$constraint,
			EditConstraint::AS_NO_CHANGE_CONTENT_MODEL
		);
	}
}
