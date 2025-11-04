<?php

use MediaWiki\Block\Block;
use MediaWiki\Block\UserBlockTarget;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\Block\UserBlockTarget
 */
class UserBlockTargetTest extends MediaWikiUnitTestCase {
	private function getTarget() {
		return new UserBlockTarget(
			new UserIdentityValue( 5, 'Alice', 'enwiki' )
		);
	}

	public function testToString() {
		$this->assertSame( 'Alice', $this->getTarget()->toString() );
	}

	public function testGetType() {
		$this->assertSame( Block::TYPE_USER, $this->getTarget()->getType() );
	}

	public function testGetSpecificity() {
		$this->assertSame( 1, $this->getTarget()->getSpecificity() );
	}

	public function testGetLogPage() {
		$page = $this->getTarget()->getLogPage();
		$this->assertSame( NS_USER, $page->getNamespace() );
		$this->assertSame( 'Alice', $page->getDBkey() );
		$this->assertSame( 'enwiki', $page->getWikiId() );
	}

	public function testGetUserPage() {
		$page = $this->getTarget()->getUserPage();
		$this->assertSame( NS_USER, $page->getNamespace() );
		$this->assertSame( 'Alice', $page->getDBkey() );
		$this->assertSame( 'enwiki', $page->getWikiId() );
	}

	public function testGetUserIdentity() {
		$user = $this->getTarget()->getUserIdentity();
		$this->assertSame( 'Alice', $user->getName() );
		$this->assertSame( 5, $user->getId( 'enwiki' ) );
		$this->assertSame( 'enwiki', $user->getWikiId() );
	}

	public function testValidateForCreation() {
		$this->assertStatusGood( $this->getTarget()->validateForCreation() );
		// The failure case is covered by BlockTargetFactoryTest since
		// wfEscapeWikiText() needs config
	}

	public function testGetLegacyTuple() {
		$user = new UserIdentityValue( 5, 'Alice', 'enwiki' );
		$target = new UserBlockTarget( $user );
		[ $resTarget, $resType ] = $target->getLegacyTuple();
		$this->assertSame( $user, $resTarget );
		$this->assertSame( Block::TYPE_USER, $resType );
	}

	public function testGetWikiId() {
		$this->assertSame( 'enwiki', $this->getTarget()->getWikiId() );
	}
}
