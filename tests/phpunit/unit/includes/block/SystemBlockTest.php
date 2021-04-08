<?php

use MediaWiki\Block\SystemBlock;

/**
 * @group Blocking
 * @coversDefaultClass \MediaWiki\Block\SystemBlock
 */
class SystemBlockTest extends MediaWikiUnitTestCase {
	/**
	 * @covers ::getSystemBlockType
	 */
	public function testSystemBlockType() {
		$block = new SystemBlock( [
			'systemBlock' => 'proxy',
		] );

		$this->assertSame( 'proxy', $block->getSystemBlockType() );
	}

}
