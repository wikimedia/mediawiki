<?php

namespace MediaWiki\Tests\Specials;

use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\ChangeTags\RestrictedTagTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Wikimedia\Parsoid\Ext\DOMUtils;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialUndelete
 */
class SpecialUndeleteTest extends SpecialPageTestBase {
	use RestrictedTagTestTrait;

	/** @inheritDoc */
	protected function newSpecialPage(): SpecialPage {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Undelete' );
	}

	/** @dataProvider provideShowDiff */
	public function testShowDiff( array $authorityRights, bool $canSeeRestrictedTag ): void {
		// Grant the rights to the generic test user (to avoid needing to create a new test user just for this test)
		$this->setGroupPermissions( [ '*' => array_fill_keys( $authorityRights, true ) ] );
		$this->setRestrictedTags( [ 'mw-private-test' => 'patrol' ] );

		$title = $this->getNonexistingTestPage()->getTitle();
		$user = $this->getTestUser()->getUser();
		// Distinct timestamps so the revision can be addressed unambiguously and the
		// diff (previous revision) header is rendered.
		ConvertibleTimestamp::setFakeTime( '20240101000000' );
		$this->editPage( $title, 'first', '', NS_MAIN, $user );
		ConvertibleTimestamp::setFakeTime( '20240101000100' );
		$revRecord = $this->editPage( $title, 'second page content', '', NS_MAIN, $user )->getNewRevision();
		$timestamp = $revRecord->getTimestamp();

		$this->getServiceContainer()->getChangeTagsStore()->addTags( [ 'mw-private-test' ], null, $revRecord->getId() );

		$this->deletePage( $title );

		// diff=1 reaches showRevision()'s diff header, which renders the tag summary.
		$request = new FauxRequest( [
			'target' => $title->getPrefixedText(),
			'timestamp' => $timestamp,
			'diff' => '1',
		] );

		[ $html ] = $this->executeSpecialPage(
			'',
			$request,
			'qqx',
			$user
		);
		$htmlDoc = DOMUtils::parseHTML( $html );

		$this->assertStringContainsString( '(undelete-summary)', $html );

		$diffTableHtml = $this->assertSelectorMatchesOneElementInNode( $htmlDoc, 'table.diff', true );

		$undeleteRevisionWarningHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlDoc,
			'.mw-undelete-revision',
			true
		);
		$this->assertStringContainsString( '(undelete-revision', $undeleteRevisionWarningHtml );
		$this->assertStringContainsString(
			$user->getName(),
			$undeleteRevisionWarningHtml,
			'Missing username from deleted revision warning'
		);
		$this->assertStringContainsString(
			'1 (january) 2024, 00:01',
			$undeleteRevisionWarningHtml,
			'Missing revision timestamp from deleted revision warning'
		);

		$undeleteTextAreaHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlDoc,
			'.mw-undelete-textarea',
			true
		);
		$this->assertStringContainsString( 'second page content', $undeleteTextAreaHtml );

		// Verify the restricted change tag can only be seen if the user can see it
		if ( $canSeeRestrictedTag ) {
			$this->assertStringContainsString( 'mw-tag-marker-mw-private-test', $diffTableHtml );
		} else {
			$this->assertStringNotContainsString( 'mw-private-test', $diffTableHtml );
		}
	}

	public static function provideShowDiff(): array {
		return [
			'Authority can see deleted text but not the rights-restricted tag' => [
				'authorityRights' => [ 'read', 'deletedtext', 'deletedhistory' ],
				'canSeeRestrictedTag' => false,
			],
			'Authority can see deleted text and the rights-restricted tag' => [
				'authorityRights' => [ 'read', 'deletedtext', 'deletedhistory', 'patrol' ],
				'canSeeRestrictedTag' => true,
			],
		];
	}

	public function testPermissionErrorOnUnprivilegedUser(): void {
		$this->expectException( PermissionsError::class );
		$this->executeSpecialPage( '', null, 'qqx', $this->getTestUser()->getAuthority() );
	}

	private function newSpecialPageForUser( User $user ): SpecialPage {
		$page = $this->newSpecialPage();
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $user );
		$context->setLanguage( 'qqx' );
		$context->setRequest( new FauxRequest() );
		$page->setContext( $context );
		return $page;
	}

	public function testHandleRetrievedDataPopulatesFormState(): void {
		$page = $this->newSpecialPageForUser( $this->getTestUser()->getUser() );
		$wrapper = TestingAccessWrapper::newFromObject( $page );
		$wrapper->mAllowed = true;

		$wrapper->handleRetrievedData( [
			'wpCommentList' => 'other',
			'wpComment' => 'reason text',
			'undeletetalk' => '1',
			'ts20260101000000' => '1',
			'ts20260102000000' => '1',
			'fileid42' => '1',
			'fileid7' => '1',
		] );

		$this->assertSame( 'reason text', $wrapper->mComment );
		$this->assertTrue( $wrapper->mUndeleteTalk );
		$this->assertSame( 'submit', $wrapper->mAction );
		$this->assertTrue( $wrapper->mRestore );
		// Timestamps rsorted (newest first)
		$this->assertSame(
			[ '20260102000000', '20260101000000' ],
			$wrapper->mTargetTimestamp
		);
		// File ids parsed as ints
		$this->assertEqualsCanonicalizing( [ 42, 7 ], $wrapper->mFileVersions );
	}

	public function testHandleRetrievedDataDoesNotForceSubmitWhenNotAllowed(): void {
		$page = $this->newSpecialPageForUser( $this->getTestUser()->getUser() );
		$wrapper = TestingAccessWrapper::newFromObject( $page );
		$wrapper->mAllowed = false;
		$wrapper->mAction = null;
		$wrapper->mRestore = false;

		$wrapper->handleRetrievedData( [
			'wpComment' => 'reason text',
			'ts20260101000000' => '1',
		] );

		// Fields still parsed for consistency
		$this->assertSame( 'reason text', $wrapper->mComment );
		$this->assertSame( [ '20260101000000' ], $wrapper->mTargetTimestamp );
		// But submit dispatch is NOT forced — user lacks the right
		$this->assertNull( $wrapper->mAction );
		$this->assertFalse( $wrapper->mRestore );
	}

	public function testHandleRetrievedDataCombinesReasonListAndFreeForm(): void {
		$page = $this->newSpecialPageForUser( $this->getTestUser()->getUser() );
		$wrapper = TestingAccessWrapper::newFromObject( $page );

		$wrapper->handleRetrievedData( [
			'wpCommentList' => 'vandalism',
			'wpComment' => 'reverted',
		] );

		$this->assertStringStartsWith( 'vandalism', $wrapper->mComment );
		$this->assertStringContainsString( 'reverted', $wrapper->mComment );
	}

	public function testHandleRetrievedDataUnsuppressIgnoredWithoutRight(): void {
		$page = $this->newSpecialPageForUser( $this->getTestUser()->getUser() );
		$wrapper = TestingAccessWrapper::newFromObject( $page );

		$wrapper->handleRetrievedData( [ 'wpUnsuppress' => '1' ] );

		$this->assertFalse( $wrapper->mUnsuppress );
	}

	public function testHandleRetrievedDataUnsuppressHonouredWithRight(): void {
		$this->setGroupPermissions( [ '*' => [ 'suppressrevision' => true ] ] );
		$page = $this->newSpecialPageForUser( $this->getTestUser()->getUser() );
		$wrapper = TestingAccessWrapper::newFromObject( $page );

		$wrapper->handleRetrievedData( [ 'wpUnsuppress' => '1' ] );

		$this->assertTrue( $wrapper->mUnsuppress );
	}

	public function testGetTitleWithoutTargetReturnsBareSpecialPage(): void {
		$page = $this->newSpecialPageForUser( $this->getTestUser()->getUser() );

		$this->assertSame( 'Special:Undelete', $page->getTitle()->getPrefixedText() );
	}

	public function testGetTitleWithTargetIncludesSubpage(): void {
		$page = $this->newSpecialPageForUser( $this->getTestUser()->getUser() );
		$wrapper = TestingAccessWrapper::newFromObject( $page );
		$wrapper->mTargetObj = Title::makeTitle( NS_MEDIAWIKI, 'CTest.js' );

		$this->assertSame(
			'Special:Undelete/MediaWiki:CTest.js',
			$page->getTitle()->getPrefixedText()
		);
	}
}
