<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Content\Content;
use MediaWiki\Context\RequestContext;
use MediaWiki\EditPage\Constraint\EditFilterMergedContentHookConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Language\Language;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use Wikimedia\TestingAccessWrapper;

/**
 * Tests the EditFilterMergedContentHookConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\EditFilterMergedContentHookConstraint
 * @todo Make this a unit test when Message will no longer use the global state.
 */
class EditFilterMergedContentHookConstraintTest extends MediaWikiIntegrationTestCase {
	use EditConstraintTestTrait;

	private function getConstraint( $hookResult ) {
		$hookContainer = $this->createMock( HookContainer::class );
		$hookContainer->expects( $this->once() )
			->method( 'run' )
			->with(
				'EditFilterMergedContent',
				$this->anything() // Not worrying about the hook call here
			)
			->willReturn( $hookResult );
		$language = $this->createMock( Language::class );
		$language->method( 'getCode' )
			->willReturn( 'en' );
		$constraint = new EditFilterMergedContentHookConstraint(
			$hookContainer,
			$this->getMockForAbstractClass( Content::class ),
			$this->createMock( RequestContext::class ),
			'EditSummaryGoesHere',
			true, // Minor edit
			$language,
			$this->createMock( User::class )
		);
		return $constraint;
	}

	public function testPass() {
		$constraint = $this->getConstraint( true );
		$this->assertConstraintPassed( $constraint );
		$this->assertSame( '', $constraint->getHookError() );
	}

	public function testFailure_goodStatus() {
		// Code path 1: Hook returns false, but status is still good
		// Status has no value set, falls back to AS_HOOK_ERROR_EXPECTED
		$constraint = $this->getConstraint( false );
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_HOOK_ERROR_EXPECTED );
	}

	public function testFailure_badStatus() {
		// Code path 2: Hook returns false, status is bad
		// To avoid using the real Status::getWikiText, which can use global state, etc.,
		// replace the status object with a mock
		$constraint = $this->getConstraint( false );
		$mockStatus = $this->getMockBuilder( Status::class )
			->onlyMethods( [ 'isGood', 'getWikiText' ] )
			->getMock();
		$mockStatus->method( 'isGood' )->willReturn( false );
		$mockStatus->method( 'getWikiText' )->willReturn( 'WIKITEXT' );
		$mockStatus->value = 12345;
		TestingAccessWrapper::newFromObject( $constraint )->status = $mockStatus;

		$this->assertConstraintFailed(
			$constraint,
			12345 // Value is set in hook (or in this case in the mock)
		);
	}

	public function testFailure_notOKStatus() {
		// Code path 3: Hook returns true, but status is not okay
		$constraint = $this->getConstraint( true );
		$status = Status::newGood();
		$status->setOK( false );
		TestingAccessWrapper::newFromObject( $constraint )->status = $status;

		$this->assertConstraintFailed(
			$constraint,
			IEditConstraint::AS_HOOK_ERROR_EXPECTED
		);
	}

}
