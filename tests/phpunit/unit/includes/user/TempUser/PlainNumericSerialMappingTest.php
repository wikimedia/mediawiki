<?php

namespace MediaWiki\Tests\User\TempUser;

use MediaWiki\User\TempUser\PlainNumericSerialMapping;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\User\TempUser\PlainNumericSerialMapping
 */
class PlainNumericSerialMappingTest extends TestCase {
	public function testGetSerialIdForIndex() {
		$map = new PlainNumericSerialMapping( [] );
		$this->assertSame( '111', $map->getSerialIdForIndex( 111 ) );
	}
}
