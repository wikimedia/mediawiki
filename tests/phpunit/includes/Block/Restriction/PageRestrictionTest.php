<?php

namespace MediaWiki\Tests\Block\Restriction;

use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Title\Title;

/**
 * @group Database
 * @group Blocking
 * @covers \MediaWiki\Block\Restriction\AbstractRestriction
 * @covers \MediaWiki\Block\Restriction\PageRestriction
 */
class PageRestrictionTest extends RestrictionTestCase {

	public function testMatches() {
		$class = $this->getClass();
		$page = $this->getExistingTestPage( 'Saturn' );
		$restriction = new $class( 1, $page->getId() );
		$this->assertTrue( $restriction->matches( $page->getTitle() ) );

		$page = $this->getExistingTestPage( 'Mars' );
		$this->assertFalse( $restriction->matches( $page->getTitle() ) );

		// Deleted page.
		$restriction = new $class( 2, 99999 );
		$page = $this->getExistingTestPage( 'Saturn' );
		$this->assertFalse( $restriction->matches( $page->getTitle() ) );
	}

	public function testGetType() {
		$class = $this->getClass();
		$restriction = new $class( 1, 2 );
		$this->assertEquals( 'page', $restriction->getType() );
	}

	public function testGetTitle() {
		$class = $this->getClass();
		$restriction = new $class( 1, 2 );
		$title = Title::makeTitle( NS_MAIN, 'Pluto' );
		$title->mArticleID = 2;
		$restriction->setTitle( $title );
		$this->assertSame( $title, $restriction->getTitle() );
	}

	public function testNewFromRow() {
		$class = $this->getClass();
		$restriction = $class::newFromRow( (object)[
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
		$class = $this->getClass();
		$title = Title::makeTitle( NS_MAIN, 'Pluto' );
		$restriction = $class::newFromTitle( 'Mars' );
		$restriction2 = $class::newFromTitle( $title );

		$this->assertSame( 0, $restriction->getBlockId() );
		$this->assertSame( 'Mars', $restriction->getTitle()->getText() );
		$this->assertSame( $title->getArticleID(), $restriction2->getValue() );
	}

	/**
	 * @inheritDoc
	 */
	protected function getClass() {
		return PageRestriction::class;
	}
}
