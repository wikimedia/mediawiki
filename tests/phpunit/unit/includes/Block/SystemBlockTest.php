<?php

use MediaWiki\Block\SystemBlock;

/**
 * @group Blocking
 * @covers \MediaWiki\Block\SystemBlock
 * @covers \MediaWiki\Block\AbstractBlock
 */
class SystemBlockTest extends MediaWikiUnitTestCase {

	public function testSystemBlockType() {
		$block = new SystemBlock( [
			'systemBlock' => 'proxy',
		] );

		$this->assertSame( 'proxy', $block->getSystemBlockType() );
	}

}
