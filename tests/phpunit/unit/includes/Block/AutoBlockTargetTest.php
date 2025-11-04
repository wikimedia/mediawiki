<?php

use MediaWiki\Block\AutoBlockTarget;
use MediaWiki\Block\Block;

/**
 * @covers \MediaWiki\Block\AutoBlockTarget
 */
class AutoBlockTargetTest extends MediaWikiUnitTestCase {
	public function testToString() {
		$target = new AutoBlockTarget( 1234, false );
		$this->assertSame( '#1234', $target->toString() );
	}

	public function testGetType() {
		$target = new AutoBlockTarget( 1234, false );
		$this->assertSame( Block::TYPE_AUTO, $target->getType() );
	}

	public function testGetLogPage() {
		$target = new AutoBlockTarget( 1234, false );
		$page = $target->getLogPage();
		$this->assertSame( NS_USER, $page->getNamespace() );
		$this->assertSame( '#1234', $page->getDBkey() );
	}

	public function testGetSpecificity() {
		$target = new AutoBlockTarget( 1234, false );
		$this->assertSame( 2, $target->getSpecificity() );
	}

	public function testValidateForCreation() {
		$target = new AutoBlockTarget( 1234, false );
		$this->assertStatusNotOK( $target->validateForCreation() );
	}

	public function testGetId() {
		$target = new AutoBlockTarget( 1234, false );
		$this->assertSame( 1234, $target->getId() );
	}

	public function testGetLegacyTuple() {
		$target = new AutoBlockTarget( 1234, false );
		[ $id, $type ] = $target->getLegacyTuple();
		$this->assertSame( '1234', $id );
		$this->assertSame( Block::TYPE_AUTO, $type );
	}

	public function testGetWikiId() {
		$target = new AutoBlockTarget( 1234, 'enwiki' );
		$this->assertSame( 'enwiki', $target->getWikiId() );
	}
}
