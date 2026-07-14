<?php

namespace MediaWiki\Tests\Specials;

use MediaWiki\Exception\PermissionsError;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\ChangeTags\RestrictedTagTestTrait;
use Wikimedia\Parsoid\Core\DOMCompat;
use Wikimedia\Parsoid\Ext\DOMUtils;
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

		$this->assertStringContainsString( '(undelete-summary)', $html );

		$diffTableElements = DOMCompat::querySelectorAll( DOMUtils::parseHTML( $html ), 'table.diff' );
		$this->assertCount( 1, $diffTableElements );
		$diffTableHtml = DOMCompat::getOuterHTML( $diffTableElements[0] );

		$undeleteRevisionWarningHtml = $this->assertAndGetByElementClass( $html, 'mw-undelete-revision' );
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

		$undeleteTextAreaHtml = $this->assertAndGetByElementClass( $html, 'mw-undelete-textarea' );
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

	/**
	 * Expects that one element exists with the given class inside the provided HTML and then returns
	 * the HTML inside that element
	 *
	 * @param string $html The HTML to search through
	 * @param string $class The CSS class to search for, excluding the "." character
	 * @return string
	 */
	private function assertAndGetByElementClass( string $html, string $class ): string {
		$specialPageDocument = DOMUtils::parseHTML( $html );
		$element = DOMCompat::querySelectorAll( $specialPageDocument, '.' . $class );
		$this->assertCount( 1, $element, "Could not find only one element with CSS class $class in $html" );
		return DOMCompat::getOuterHTML( $element[0] );
	}

	public function testPermissionErrorOnUnprivilegedUser(): void {
		$this->expectException( PermissionsError::class );
		$this->executeSpecialPage( '', null, 'qqx', $this->getTestUser()->getAuthority() );
	}
}
