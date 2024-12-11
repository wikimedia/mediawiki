<?php

namespace MediaWiki\Tests\User\TempUser;

use MediaWiki\User\TempUser\PlainNumericSerialMapping;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\TempUser\PlainNumericSerialMapping
 */
class PlainNumericSerialMappingTest extends MediaWikiUnitTestCase {

	public function testGetSerialIdForIndex() {
		$map = new PlainNumericSerialMapping( [] );
		$this->assertSame( '111', $map->getSerialIdForIndex( 111 ) );
	}

	public function testGetSerialIdForIndexWithOffset() {
		$map = new PlainNumericSerialMapping( [ 'offset' => 111 ] );
		$this->assertSame( '222', $map->getSerialIdForIndex( 111 ) );
	}
}
