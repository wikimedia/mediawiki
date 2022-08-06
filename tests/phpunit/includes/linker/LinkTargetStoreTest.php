<?php

use MediaWiki\MediaWikiServices;

/**
 * @group Database
 * @covers MediaWiki\Linker\LinkTargetStore
 */
class LinkTargetStoreTest extends MediaWikiIntegrationTestCase {
	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'linktarget';
	}

	public function provideLinkTargets() {
		yield [ new TitleValue( NS_SPECIAL, 'BlankPage' ) ];
		yield [ new TitleValue( NS_MAIN, 'Foobar' ) ];
		yield [ new TitleValue( NS_USER, 'Someuser' ) ];
	}

	/**
	 * @dataProvider provideLinkTargets
	 * @covers \MediaWiki\Linker\LinkTargetStore::acquireLinkTargetId
	 */
	public function testAcquireLinkTargetId( $target ) {
		$linkTargetStore = $this->getServiceContainer()->getLinkTargetLookup();
		$db = $this->getServiceContainer()->getDBLoadBalancer()->getConnection( DB_PRIMARY );
		$id = $linkTargetStore->acquireLinkTargetId( $target, $db );
		$row = $db->selectRow(
			'linktarget',
			[ 'lt_id', 'lt_namespace', 'lt_title' ],
			[
				'lt_namespace' => $target->getNamespace(),
				'lt_title' => $target->getDBkey()
			]
		);
		$this->assertSame( (int)$row->lt_id, $id );
	}

	/**
	 * @dataProvider provideLinkTargets
	 * @covers \MediaWiki\Linker\LinkTargetStore::acquireLinkTargetId
	 * @covers \MediaWiki\Linker\LinkTargetStore::getLinkTargetById
	 */
	public function testGetLinkTargetById( $target ) {
		$linkTargetStore = MediaWikiServices::getInstance()->getLinkTargetLookup();
		$db = MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection( DB_PRIMARY );
		$id = $linkTargetStore->acquireLinkTargetId( $target, $db );
		$actualLinkTarget = $linkTargetStore->getLinkTargetById( $id, $db );
		$this->assertEquals( $target, $actualLinkTarget );
	}

	/**
	 * @dataProvider provideLinkTargets
	 * @covers \MediaWiki\Linker\LinkTargetStore::acquireLinkTargetId
	 * @covers \MediaWiki\Linker\LinkTargetStore::getLinkTargetById
	 */
	public function testGetLinkTargetByIdWithoutCache( $target ) {
		$linkTargetStore = MediaWikiServices::getInstance()->getLinkTargetLookup();
		$db = MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection( DB_PRIMARY );
		$id = $linkTargetStore->acquireLinkTargetId( $target, $db );
		$linkTargetStore->clearClassCache();
		$actualLinkTarget = $linkTargetStore->getLinkTargetById( $id, $db );
		$this->assertEquals( $target, $actualLinkTarget );
	}

}
