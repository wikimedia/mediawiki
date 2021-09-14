<?php

namespace MediaWiki\Tests\Unit\Block;

use MediaWiki\Block\AbstractBlock;
use MediaWikiUnitTestCase;

/**
 * @group Blocking
 * @coversDefaultClass MediaWiki\Block\AbstractBlock
 */
class AbstractBlockTest extends MediaWikiUnitTestCase {

	/**
	 * @covers ::getTarget
	 */
	public function testGetTarget_deprecated() {
		$this->expectDeprecation();
		$block = $this->createNoOpAbstractMock(
			AbstractBlock::class,
			[ 'getTarget' ]
		);
		$block->getTarget();
	}

	/**
	 * @covers ::getTargetAndType
	 */
	public function testGetTargetAndType_deprecated() {
		$this->expectDeprecation();
		$this->expectDeprecationMessageMatches( '/AbstractBlock::getTargetAndType was deprecated in MediaWiki 1.37/' );
		$block = $this->createNoOpAbstractMock(
			AbstractBlock::class,
			[ 'getTargetAndType' ]
		);
		$block->getTargetAndType();
	}
}
