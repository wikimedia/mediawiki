<?php

namespace MediaWiki\Tests\Block\Restriction;

use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * See also \MediaWiki\Tests\Unit\Block\Restriction\PageRestrictionTest unit tests
 *
 * @group Database
 * @group Blocking
 * @covers \MediaWiki\Block\Restriction\AbstractRestriction
 * @covers \MediaWiki\Block\Restriction\PageRestriction
 */
class PageRestrictionTest extends MediaWikiIntegrationTestCase {

	public function testMatches() {
		$page = $this->getExistingTestPage( 'Saturn' );
		$restriction = new PageRestriction( 1, $page->getId() );
		$this->assertTrue( $restriction->matches( $page->getTitle() ) );

		$page = $this->getExistingTestPage( 'Mars' );
		$this->assertFalse( $restriction->matches( $page->getTitle() ) );

		// Deleted page.
		$restriction = new PageRestriction( 2, 99999 );
		$page = $this->getExistingTestPage( 'Saturn' );
		$this->assertFalse( $restriction->matches( $page->getTitle() ) );
	}

	public function testGetTitle() {
		$restriction = new PageRestriction( 1, 2 );
		$title = Title::makeTitle( NS_MAIN, 'Pluto' );
		$title->mArticleID = 2;
		$restriction->setTitle( $title );
		$this->assertSame( $title, $restriction->getTitle() );
	}

	public function testNewFromRow() {
		$restriction = PageRestriction::newFromRow( (object)[
			'ir_ipb_id' => 1,
			'ir_value' => 2,
			'page_namespace' => 0,
			'page_title' => 'Saturn',
		] );

		$this->assertSame( 1, $restriction->getBlockId() );
		$this->assertSame( 2, $restriction->getValue() );
		$this->assertSame( 'Saturn', $restriction->getTitle()->getText() );
	}

	public function testNewFromTitle() {
		$title = Title::makeTitle( NS_MAIN, 'Pluto' );
		$restriction = PageRestriction::newFromTitle( 'Mars' );
		$restriction2 = PageRestriction::newFromTitle( $title );

		$this->assertSame( 0, $restriction->getBlockId() );
		$this->assertSame( 'Mars', $restriction->getTitle()->getText() );
		$this->assertSame( $title->getArticleID(), $restriction2->getValue() );
	}
}
