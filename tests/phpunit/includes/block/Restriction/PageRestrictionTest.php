<?php

namespace MediaWiki\Tests\Block\Restriction;

use MediaWiki\Block\Restriction\PageRestriction;

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
		$title = \Title::newFromText( 'Pluto' );
		$title->mArticleID = 2;
		$restriction->setTitle( $title );
		$this->assertSame( $title, $restriction->getTitle() );

		$restriction = new $class( 1, 1 );
		$title = \Title::newFromId( 1 );
		$this->assertEquals( $title->getArticleID(), $restriction->getTitle()->getArticleID() );
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

	/**
	 * @inheritDoc
	 */
	protected function getClass() {
		return PageRestriction::class;
	}
}
