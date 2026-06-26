<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\Content\Content;
use MediaWiki\Content\ContentHandler;
use MediaWiki\EditPage\Constraint\RedirectConstraint;
use MediaWiki\EditPage\IEditObject;
use MediaWiki\Language\RawMessage;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\PageEdit\PageEditAttemptState;
use MediaWiki\Title\Title;
use MediaWikiUnitTestCase;

/**
 * Tests the RedirectConstraint
 *
 * @author SomeRandomDeveloper
 *
 * @covers \MediaWiki\EditPage\Constraint\RedirectConstraint
 */
class RedirectConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	private function getContent( ?Title $target = null ): Content {
		$content = $this->createMock( Content::class );
		$target ?? $this->createMock( Title::class );
		// No $this->once() since only called for the new content
		$content->method( 'isRedirect' )
			->willReturn( true );
		$content->expects( $this->atLeastOnce() )
			->method( 'getRedirectTarget' )
			->willReturn( $target );
		return $content;
	}

	private function createTargetTitle( bool $exists, bool $isRedirect, bool $isValidRedirectTarget = true ): Title {
		$target = $this->createMock( Title::class );
		$target->expects( $this->atLeastOnce() )
			->method( $this->logicalOr( $this->identicalTo( 'equals' ), $this->identicalTo( 'isSamePageAs' ) ) )
			->willReturnCallback( static fn ( $otherTitle ) => $otherTitle === $target );
		$target->method( 'isKnown' )->willReturn( $exists );
		$target->method( 'isRedirect' )->willReturn( $isRedirect );
		$target->method( 'isValidRedirectTarget' )->willReturn( $isValidRedirectTarget );
		return $target;
	}

	private function createRedirectConstraint(
		Content $originalContent,
		Content $newContent,
		PageEditAttemptState $editAttemptState = new PageEditAttemptState(),
		?Title $title = null,
		?Title $redirectLookupResult = null,
	): RedirectConstraint {
		$redirectLookup = $this->createMock( RedirectLookup::class );
		$redirectLookup->method( 'getRedirectTarget' )->willReturn( $redirectLookupResult );
		return new RedirectConstraint(
			$editAttemptState,
			$newContent,
			$originalContent,
			$title ?? $this->createMock( Title::class ),
			new RawMessage( '' ),
			null,
			$redirectLookup
		);
	}

	public function testNormalRedirectPass() {
		// old and new content is a normal redirect
		$constraint = $this->createRedirectConstraint(
			$this->getContent( $this->createTargetTitle( true, false ) ),
			$this->getContent( $this->createTargetTitle( true, false ) )
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testBrokenRedirectPass() {
		// both the old and the new content have a broken redirect pointing to the same title, so no warning
		$target = $this->createTargetTitle( false, false );
		$constraint = $this->createRedirectConstraint(
			$this->getContent( $target ),
			$this->getContent( $target )
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testBrokenRedirectFailure() {
		// new content is a broken redirect, but existing content is not
		$editAttemptState = new PageEditAttemptState();
		$targetTitle = $this->createTargetTitle( false, false );
		$constraint = $this->createRedirectConstraint(
			$this->getContent(),
			$this->getContent( $targetTitle ),
			editAttemptState: $editAttemptState,
		);
		$status = $this->assertConstraintFailed(
			$constraint,
			IEditObject::AS_BROKEN_REDIRECT
		);
		$this->assertEquals( $targetTitle, $editAttemptState->getAllowedProblematicRedirectTarget() );
		$this->assertTrue( $status->canResubmit() );
	}

	public function testDoubleRedirectPass() {
		// both the old and the new content have a double redirect pointing to the same redirect, so no warning
		$target = $this->createTargetTitle( true, true );
		$constraint = $this->createRedirectConstraint(
			$this->getContent( $target ),
			$this->getContent( $target )
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testDoubleRedirectFailure() {
		// new content is a double redirect, but existing content is not
		$targetTitle = $this->createTargetTitle( true, true );
		$newContent = $this->getContent( $targetTitle );

		$suggestedRedirectContent = $this->createMock( Content::class );
		$contentHandler = $this->createMock( ContentHandler::class );
		$contentHandler->method( 'makeRedirectContent' )->willReturn( $suggestedRedirectContent );
		$newContent->method( 'getContentHandler' )->willReturn( $contentHandler );

		// wfEscapeWikiText relies on this option
		global $wgEnableMagicLinks;
		$wgEnableMagicLinks = [];

		$editAttemptState = new PageEditAttemptState();
		$constraint = $this->createRedirectConstraint(
			$this->getContent(),
			$newContent,
			editAttemptState: $editAttemptState,
			redirectLookupResult: $this->createMock( Title::class ),
		);
		$status = $this->assertConstraintFailed(
			$constraint,
			IEditObject::AS_DOUBLE_REDIRECT
		);
		$this->assertEquals( $targetTitle, $editAttemptState->getAllowedProblematicRedirectTarget() );
		$this->assertTrue( $status->canResubmit() );
	}

	public function testSelfRedirectPass() {
		// both the old and the new content have a self redirect, so no warning
		$target = $this->createTargetTitle( true, true );
		$constraint = $this->createRedirectConstraint(
			$this->getContent( $target ),
			$this->getContent( $target ),
			title: $target
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testSelfRedirectFailure() {
		// new content is a self redirect, but existing content is not
		$target = $this->createTargetTitle( true, true );
		$editAttemptState = new PageEditAttemptState();
		$constraint = $this->createRedirectConstraint(
			$this->getContent(),
			$this->getContent( $target ),
			editAttemptState: $editAttemptState,
			title: $target
		);
		$status = $this->assertConstraintFailed(
			$constraint,
			IEditObject::AS_SELF_REDIRECT
		);
		$this->assertEquals( $target, $editAttemptState->getAllowedProblematicRedirectTarget() );
		$this->assertTrue( $status->canResubmit() );
	}

	public function testInvalidRedirectTargetPass() {
		// both the old and the new content have a redirect pointing to an invalid redirect target, so no warning
		$target = $this->createTargetTitle( true, false, false );
		$constraint = $this->createRedirectConstraint(
			$this->getContent( $target ),
			$this->getContent( $target )
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testInvalidRedirectTargetFailure() {
		// new content is a redirect pointing to an invalid redirect target, but existing content is not
		$target = $this->createTargetTitle( true, false, false );
		$editAttemptState = new PageEditAttemptState();
		$constraint = $this->createRedirectConstraint(
			$this->getContent(),
			$this->getContent( $target ),
			editAttemptState: $editAttemptState,
		);
		$status = $this->assertConstraintFailed(
			$constraint,
			IEditObject::AS_INVALID_REDIRECT_TARGET
		);
		$this->assertEquals( $target, $editAttemptState->getAllowedProblematicRedirectTarget() );
		$this->assertTrue( $status->canResubmit() );
	}

}
