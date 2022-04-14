<?php

namespace MediaWiki\Tests\User\TempUser;

use MediaWiki\User\TempUser\LocalizedNumericSerialMapping;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\User\TempUser\LocalizedNumericSerialMapping
 */
class LocalizedNumericSerialMappingTest extends MediaWikiIntegrationTestCase {
	public function testGetSerialIdForIndex() {
		$map = new LocalizedNumericSerialMapping(
			[ 'language' => 'ar' ],
			$this->getServiceContainer()->getLanguageFactory()
		);
		$this->assertSame( '١٠', $map->getSerialIdForIndex( 10 ) );
	}
}
