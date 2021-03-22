<?php

namespace MediaWiki\Tests\Unit\Block\Restriction;

use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Title\Title;

/**
 * @group Blocking
 * @covers \MediaWiki\Block\Restriction\AbstractRestriction
 * @covers \MediaWiki\Block\Restriction\NamespaceRestriction
 */
class NamespaceRestrictionTest extends RestrictionTestCase {

	public function testMatches() {
		$class = $this->getClass();
		$restriction = new $class( 1, NS_MAIN );

		$title = $this->createMock( Title::class );
		$title->method( 'getNamespace' )->willReturn( NS_MAIN );
		$this->assertTrue( $restriction->matches( $title ) );

		$title = $this->createMock( Title::class );
		$title->method( 'getNamespace' )->willReturn( NS_TALK );
		$this->assertFalse( $restriction->matches( $title ) );
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
