<?php

namespace MediaWiki\Tests\Block\Restriction;

use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Title\Title;

/**
 * @group Blocking
 * @covers \MediaWiki\Block\Restriction\AbstractRestriction
 * @covers \MediaWiki\Block\Restriction\ActionRestriction
 */
class ActionRestrictionTest extends RestrictionTestCase {

	public function testMatches() {
		$class = $this->getClass();
		$restriction = new $class( 1, 2 );
		$this->assertFalse( $restriction->matches(
			$this->createMock( Title::class )
		) );
	}

	public function testGetType() {
		$class = $this->getClass();
		$restriction = new $class( 1, 2 );
		$this->assertEquals( 'action', $restriction->getType() );
	}

	/**
	 * @inheritDoc
	 */
	protected function getClass() {
		return ActionRestriction::class;
	}
}
