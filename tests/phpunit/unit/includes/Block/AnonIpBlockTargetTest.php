<?php

use MediaWiki\Block\AnonIpBlockTarget;
use MediaWiki\Block\Block;
use MediaWiki\User\UserIdentity;

/**
 * @covers \MediaWiki\Block\AnonIpBlockTarget
 */
class AnonIpBlockTargetTest extends MediaWikiUnitTestCase {
	public function testToString() {
		$target = new AnonIpBlockTarget( '1.2.3.4', false );
		$this->assertSame( '1.2.3.4', $target->toString() );
	}

	public function testGetType() {
		$target = new AnonIpBlockTarget( '1.2.3.4', false );
		$this->assertSame( Block::TYPE_IP, $target->getType() );
	}

	public function testGetSpecificity() {
		$target = new AnonIpBlockTarget( '1.2.3.4', false );
		$this->assertSame( 2, $target->getSpecificity() );
	}

	public function testGetLogPage() {
		$target = new AnonIpBlockTarget( '1.2.3.4', false );
		$logPage = $target->getLogPage();
		$this->assertSame( NS_USER, $logPage->getNamespace() );
		$this->assertSame( '1.2.3.4', $logPage->getDBkey() );
	}

	public function testGetUserPage() {
		$target = new AnonIpBlockTarget( '1.2.3.4', false );
		$page = $target->getUserPage();
		$this->assertSame( NS_USER, $page->getNamespace() );
		$this->assertSame( '1.2.3.4', $page->getDBkey() );
	}

	public function testGetUserIdentity() {
		$target = new AnonIpBlockTarget( '1.2.3.4', 'enwiki' );
		$user = $target->getUserIdentity();
		$this->assertSame( 'enwiki', $user->getWikiId() );
		$this->assertSame( '1.2.3.4', $user->getName() );
		$this->assertSame( 0, $user->getId( 'enwiki' ) );
	}

	public function testValidateForCreation() {
		$target = new AnonIpBlockTarget( '1.2.3.4', 'enwiki' );
		$this->assertStatusGood( $target->validateForCreation() );
	}

	public function testToHex() {
		$target = new AnonIpBlockTarget( '1.2.3.4', 'enwiki' );
		$this->assertSame( '01020304', $target->toHex() );
	}

	public function testToHexRange() {
		$target = new AnonIpBlockTarget( '1.2.3.4', 'enwiki' );
		$this->assertSame( [ '01020304', '01020304' ], $target->toHexRange() );
	}

	public function testGetLegacyTuple() {
		$target = new AnonIpBlockTarget( '1.2.3.4', 'enwiki' );
		[ $resTarget, $resType ] = $target->getLegacyTuple();
		$this->assertSame( Block::TYPE_IP, $resType );
		$this->assertInstanceOf( UserIdentity::class, $resTarget );
		$this->assertSame( 0, $resTarget->getId( 'enwiki' ) );
		$this->assertSame( '1.2.3.4', $resTarget->getName() );
	}

	public function testGetWikiId() {
		$target = new AnonIpBlockTarget( '1.2.3.4', 'enwiki' );
		$this->assertSame( 'enwiki', $target->getWikiId() );
	}
}
