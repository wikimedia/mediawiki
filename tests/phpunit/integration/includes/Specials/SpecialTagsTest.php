<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\ChangeTags\RestrictedTagTestTrait;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use Wikimedia\Parsoid\Core\DOMCompat;
use Wikimedia\Parsoid\Ext\DOMUtils;

/**
 * @covers \MediaWiki\Specials\SpecialTags
 * @group Database
 */
class SpecialTagsTest extends SpecialPageTestBase {
	use MockAuthorityTrait;
	use RestrictedTagTestTrait;

	/** @dataProvider provideViewTagsList */
	public function testViewTagsList( array $authorityRights, array $expectedTags, array $unexpectedTags ): void {
		$this->setTemporaryHook(
			'ListDefinedTags',
			static function ( array &$tags ) {
				$tags[] = 'mw-private-test';
			}
		);
		$this->setRestrictedTags( [ 'mw-private-test' => 'patrol' ] );

		$editStatus = $this->editPage( $this->getNonexistingTestPage(), 'Test' );
		$this->assertStatusGood( $editStatus );

		$rcId = null;
		$revId = $editStatus->getNewRevision()->getId();
		$this->getServiceContainer()->getChangeTagsStore()
			->updateTags( [ 'mw-reverted' ], [], $rcId, $revId );

		[ $html ] = $this->executeSpecialPage(
			'',
			null,
			null,
			$this->mockRegisteredAuthorityWithPermissions( $authorityRights )
		);

		$tagsIntroHtml = $this->assertSelectorMatchesOneElement( $html, '.mw-tags-intro' );
		$this->assertStringContainsString( '(tags-intro)', $tagsIntroHtml );

		$tagsTable = $this->assertSelectorMatchesOneElement( $html, '.mw-tags-table' );

		$tagsTableHeader = DOMCompat::getOuterHTML(
			DOMCompat::querySelector( DOMUtils::parseHTML( $tagsTable ), 'thead' )
		);

		$this->assertStringContainsString( '(tags-tag)', $tagsTableHeader );
		$this->assertStringContainsString( '(tags-display-header)', $tagsTableHeader );
		$this->assertStringContainsString( '(tags-description-header)', $tagsTableHeader );
		$this->assertStringContainsString( '(tags-source-header)', $tagsTableHeader );
		$this->assertStringContainsString( '(tags-active-header)', $tagsTableHeader );
		$this->assertStringContainsString( '(tags-hitcount-header)', $tagsTableHeader );

		// mw-reverted has one use we added above, so should be at the top of the table
		$firstTableRow = DOMCompat::getOuterHTML(
			DOMCompat::querySelector( DOMUtils::parseHTML( $tagsTable ), 'tbody > tr' )
		);
		$this->assertStringContainsString( '(tag-mw-reverted)', $firstTableRow );
		$this->assertStringContainsString( '(tag-mw-reverted-description)', $firstTableRow );
		$this->assertStringContainsString( '(tags-source-extension)', $firstTableRow );
		$this->assertStringContainsString( '(tags-active-yes)', $firstTableRow );
		$this->assertStringContainsString( '(tags-hitcount: 1)', $firstTableRow );

		// All other expected tags should be present too
		foreach ( $expectedTags as $expectedTag ) {
			$this->assertStringContainsString( "(tag-$expectedTag)", $tagsTable );
		}
		foreach ( $unexpectedTags as $unexpectedTag ) {
			$this->assertStringNotContainsString( $unexpectedTag, $tagsTable );
		}
	}

	public static function provideViewTagsList(): array {
		return [
			'User cannot see private tag' => [
				'authorityRights' => [],
				'expectedTags' => [ 'mw-reverted' ],
				'unexpectedTags' => [ 'mw-private-test' ],
			],
			'User can see the private tag' => [
				'authorityRights' => [ 'patrol' ],
				'expectedTags' => [ 'mw-reverted', 'mw-private-test' ],
				'unexpectedTags' => [],
			],
		];
	}

	public function testViewTagsListForNoDefinedTags(): void {
		$this->overrideConfigValue( MainConfigNames::SoftwareTags, [] );
		$this->clearHook( 'ListDefinedTags' );

		[ $html ] = $this->executeSpecialPage( '', null, null, $this->mockAnonNullAuthority() );

		$tagsIntroHtml = $this->assertSelectorMatchesOneElement( $html, '.mw-tags-intro' );
		$this->assertStringContainsString( '(tags-intro)', $tagsIntroHtml );

		$this->assertStringNotContainsString( 'mw-tags-table', $html );
	}

	public function testViewActivateFormWithNoTagSpecified(): void {
		[ $html ] = $this->executeSpecialPage(
			'activate',
			null,
			null,
			$this->mockRegisteredAuthorityWithPermissions( [ 'managechangetags' ] )
		);

		$this->assertStringContainsString( '(tags-deactivate-or-activate-not-specified)', $html );
		$this->assertStringNotContainsString( 'tags-activate-question', $html );
	}

	public function testViewActivateFormForTagUserCannotSee(): void {
		$this->setRestrictedTags( [ 'mw-private-test' => 'patrol' ] );

		[ $html ] = $this->executeSpecialPage(
			'activate',
			new FauxRequest( [ 'tag' => 'mw-private-test' ] ),
			null,
			$this->mockRegisteredAuthorityWithPermissions( [ 'managechangetags' ] )
		);

		$this->assertStringContainsString( '(tags-activate-not-found: mw-private-test)', $html );
		$this->assertStringNotContainsString( 'tags-activate-question', $html );
	}

	public function testViewActivateFormForTagUserCanSee(): void {
		$this->setTemporaryHook(
			'ListDefinedTags',
			static function ( array &$tags ) {
				$tags[] = 'mw-private-test';
			}
		);
		$this->setRestrictedTags( [ 'mw-private-test' => 'patrol' ] );

		[ $html ] = $this->executeSpecialPage(
			'activate',
			new FauxRequest( [ 'tag' => 'mw-private-test' ] ),
			null,
			$this->mockRegisteredAuthorityWithPermissions( [ 'managechangetags', 'patrol' ] )
		);

		// Software defined tags cannot be activiated, so even though the user can see the tag they
		// just get a different error message
		$this->assertStringContainsString( '(tags-activate-not-allowed: mw-private-test)', $html );
		$this->assertStringNotContainsString( 'tags-activate-question', $html );
	}

	public function testViewDeleteFormWithNoTagSpecified(): void {
		[ $html ] = $this->executeSpecialPage(
			'delete',
			null,
			null,
			$this->mockRegisteredAuthorityWithPermissions( [ 'deletechangetags' ] )
		);

		$this->assertStringContainsString( '(tags-delete-not-specified)', $html );
		$this->assertStringNotContainsString( 'tags-delete-explanation-initial', $html );
	}

	public function testViewDeleteFormForTagUserCannotSee(): void {
		$this->setRestrictedTags( [ 'mw-private-test' => 'patrol' ] );

		[ $html ] = $this->executeSpecialPage(
			'delete',
			new FauxRequest( [ 'tag' => 'mw-private-test' ] ),
			null,
			$this->mockRegisteredAuthorityWithPermissions( [ 'deletechangetags' ] )
		);

		$this->assertStringContainsString( '(tags-delete-not-found: mw-private-test)', $html );
		$this->assertStringNotContainsString( 'tags-delete-explanation-initial', $html );
	}

	public function testViewDeleteFormForTagUserCanSee(): void {
		$this->setTemporaryHook(
			'ListDefinedTags',
			static function ( array &$tags ) {
				$tags[] = 'mw-private-test';
			}
		);
		$this->setRestrictedTags( [ 'mw-private-test' => 'patrol' ] );

		[ $html ] = $this->executeSpecialPage(
			'delete',
			new FauxRequest( [ 'tag' => 'mw-private-test' ] ),
			null,
			$this->mockRegisteredAuthorityWithPermissions( [ 'deletechangetags', 'patrol' ] )
		);

		// Software defined tags cannot be deleted, so even though the user can see the tag they
		// just get a different error message
		$this->assertStringContainsString( '(tags-delete-not-allowed)', $html );
		$this->assertStringNotContainsString( 'tags-delete-explanation-initial', $html );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Tags' );
	}
}
