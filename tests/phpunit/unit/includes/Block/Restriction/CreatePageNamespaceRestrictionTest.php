<?php

namespace MediaWiki\Tests\Unit\Block\Restriction;

use MediaWiki\Block\Restriction\CreatePageNamespaceRestriction;
use MediaWiki\Title\Title;

/**
 * @group Blocking
 * @covers \MediaWiki\Block\Restriction\AbstractRestriction
 * @covers \MediaWiki\Block\Restriction\CreatePageNamespaceRestriction
 */
class CreatePageNamespaceRestrictionTest extends RestrictionTestCase {

	public function testMatches() {
		$class = $this->getClass();
		$restriction = new $class( 1, NS_MAIN );

		$title = $this->createMock( Title::class );
		$title->method( 'getNamespace' )->willReturn( NS_MAIN );
		$this->assertFalse( $restriction->matches( $title ) );
	}

	public function testGetType() {
		$class = $this->getClass();
		$restriction = new $class( 1, 2 );
		$this->assertEquals( 'createpage-ns', $restriction->getType() );
	}

	/**
	 * @inheritDoc
	 */
	protected function getClass() {
		return CreatePageNamespaceRestriction::class;
	}
}
