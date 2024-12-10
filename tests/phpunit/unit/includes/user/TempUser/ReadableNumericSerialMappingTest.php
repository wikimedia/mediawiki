<?php

namespace MediaWiki\Tests\User\TempUser;

use MediaWiki\User\TempUser\ReadableNumericSerialMapping;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\TempUser\ReadableNumericSerialMapping
 */
class ReadableNumericSerialMappingTest extends MediaWikiUnitTestCase {

	public function testGetSerialIdForIndex() {
		$map = new ReadableNumericSerialMapping( [] );
		$this->assertSame( '111', $map->getSerialIdForIndex( 111 ) );
	}

	public function testGetSerialIdForIndexWithOffsetAndHyphens() {
		$map = new ReadableNumericSerialMapping( [ 'offset' => 111111111111 ] );
		$this->assertSame( '11111-11112-22', $map->getSerialIdForIndex( 111 ) );
	}
}
