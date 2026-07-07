<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\MainConfigNames;
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

	public function testViewTagsList(): void {
		$editStatus = $this->editPage( $this->getNonexistingTestPage(), 'Test' );
		$this->assertStatusGood( $editStatus );

		$rcId = null;
		$revId = $editStatus->getNewRevision()->getId();
		$this->getServiceContainer()->getChangeTagsStore()
			->updateTags( [ 'mw-reverted' ], [], $rcId, $revId );

		[ $html ] = $this->executeSpecialPage( '', null, null, $this->mockAnonNullAuthority() );

		$tagsIntroHtml = $this->assertAndGetByElementClass( $html, 'mw-tags-intro' );
		$this->assertStringContainsString( '(tags-intro)', $tagsIntroHtml );

		$tagsTable = $this->assertAndGetByElementClass( $html, 'mw-tags-table' );

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
	}

	public function testViewTagsListForNoDefinedTags(): void {
		$this->overrideConfigValue( MainConfigNames::SoftwareTags, [] );
		$this->clearHook( 'ListDefinedTags' );

		[ $html ] = $this->executeSpecialPage( '', null, null, $this->mockAnonNullAuthority() );

		$tagsIntroHtml = $this->assertAndGetByElementClass( $html, 'mw-tags-intro' );
		$this->assertStringContainsString( '(tags-intro)', $tagsIntroHtml );

		$this->assertStringNotContainsString( 'mw-tags-table', $html );
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

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Tags' );
	}
}
