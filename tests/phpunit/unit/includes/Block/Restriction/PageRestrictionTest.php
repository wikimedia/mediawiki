<?php

namespace MediaWiki\Tests\Unit\Block\Restriction;

use MediaWiki\Block\Restriction\PageRestriction;

/**
 * See also \MediaWiki\Tests\Block\Restriction\PageRestrictionTest integration tests
 *
 * @group Blocking
 * @covers \MediaWiki\Block\Restriction\AbstractRestriction
 * @covers \MediaWiki\Block\Restriction\PageRestriction
 */
class PageRestrictionTest extends RestrictionTestCase {

	public function testGetType() {
		$class = $this->getClass();
		$restriction = new $class( 1, 2 );
		$this->assertEquals( 'page', $restriction->getType() );
	}

	/**
	 * @inheritDoc
	 */
	protected function getClass() {
		return PageRestriction::class;
	}
}
