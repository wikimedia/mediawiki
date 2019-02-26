<?php

namespace MediaWiki\Tests\Block\Restriction;

use MediaWiki\Block\Restriction\NamespaceRestriction;

/**
 * @group Database
 * @group Blocking
 * @covers \MediaWiki\Block\Restriction\AbstractRestriction
 * @covers \MediaWiki\Block\Restriction\NamespaceRestriction
 */
class NamespaceRestrictionTest extends RestrictionTestCase {

	public function testMatches() {
		$class = $this->getClass();
		$page = $this->getExistingTestPage( 'Saturn' );
		$restriction = new $class( 1, NS_MAIN );
		$this->assertTrue( $restriction->matches( $page->getTitle() ) );

		$page = $this->getExistingTestPage( 'Talk:Saturn' );
		$this->assertFalse( $restriction->matches( $page->getTitle() ) );
	}

	public function testGetType() {
		$class = $this->getClass();
		$restriction = new $class( 1, 2 );
		$this->assertEquals( 'ns', $restriction->getType() );
	}

	/**
	 * @inheritDoc
	 */
	protected function getClass() {
		return NamespaceRestriction::class;
	}
}
