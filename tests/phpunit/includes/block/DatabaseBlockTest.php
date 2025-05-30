<?php

use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\UserBlockTarget;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserNameUtils;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group Database
 * @group Blocking
 * @coversDefaultClass \MediaWiki\Block\DatabaseBlock
 */
class DatabaseBlockTest extends MediaWikiLangTestCase {
	use TempUserTestTrait;

	/**
	 * @return UserIdentity
	 */
	private function getUserForBlocking() {
		return $this->getTestUser()->getUserIdentity();
	}

	/**
	 * @param UserIdentity $user
	 *
	 * @return DatabaseBlock
	 */
	private function addBlockForUser( UserIdentity $user ) {
		$block = $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $user,
				'by' => $this->getTestSysop()->getUser(),
				'reason' => 'Parce que',
				'expiry' => time() + 100500,
			] );
		if ( !$block->getId() ) {
			throw new RuntimeException( "Failed to insert block for BlockTest; old leftover block remaining?" );
		}

		return $block;
	}

	/**
	 * @covers ::newFromTarget
	 */
	public function testINewFromTargetReturnsCorrectBlock() {
		$this->hideDeprecated( DatabaseBlock::class . '::newFromTarget' );
		$user = $this->getUserForBlocking();
		$block = $this->addBlockForUser( $user );

		$this->assertTrue(
			$block->equals( DatabaseBlock::newFromTarget( $user->getName() ) ),
			"newFromTarget() returns the same block as the one that was made"
		);
	}

	/**
	 * @covers ::newFromID
	 */
	public function testINewFromIDReturnsCorrectBlock() {
		$this->hideDeprecated( DatabaseBlock::class . '::newFromID' );
		$user = $this->getUserForBlocking();
		$block = $this->addBlockForUser( $user );

		$this->assertTrue(
			$block->equals( DatabaseBlock::newFromID( $block->getId() ) ),
			"newFromID() returns the same block as the one that was made"
		);
	}

	/**
	 * per T28425
	 * @covers ::__construct
	 */
	public function testT28425BlockTimestampDefaultsToTime() {
		$madeAt = wfTimestamp( TS_MW );
		ConvertibleTimestamp::setFakeTime( $madeAt );

		$user = $this->getUserForBlocking();
		$block = $this->addBlockForUser( $user );

		$this->assertSame( $madeAt, $block->getTimestamp() );
	}

	/**
	 * @covers ::getTargetName()
	 * @covers ::getTargetUserIdentity()
	 * @covers ::isBlocking()
	 * @covers ::getBlocker()
	 * @covers ::getByName()
	 */
	public function testCrossWikiBlocking() {
		$this->overrideConfigValue( MainConfigNames::LocalDatabases, [ 'm' ] );
		$dbMock = $this->createMock( IDatabase::class );
		$dbMock->method( 'decodeExpiry' )->willReturn( 'infinity' );
		$lbMock = $this->createMock( ILoadBalancer::class );
		$lbMock->method( 'getConnection' )
			->with( DB_REPLICA, [], 'm' )
			->willReturn( $dbMock );
		$lbFactoryMock = $this->createMock( LBFactory::class );
		$lbFactoryMock
			->method( 'getMainLB' )
			->with( 'm' )
			->willReturn( $lbMock );
		$this->setService( 'DBLoadBalancerFactory', $lbFactoryMock );

		$target = new UserBlockTarget(
			UserIdentityValue::newExternal( 'm', 'UserOnForeignWiki', 'm' )
		);

		$blocker = UserIdentityValue::newExternal( 'm', 'MetaWikiUser', 'm' );

		$userNameUtilsMock = $this->createMock( UserNameUtils::class );
		$userNameUtilsMock
			->method( 'isUsable' )
			->with( $blocker->getName() )
			->willReturn( false );
		$this->setService( 'UserNameUtils', $userNameUtilsMock );

		$blockOptions = [
			'target' => $target,
			'wiki' => 'm',
			'reason' => 'testing crosswiki blocking',
			'timestamp' => wfTimestampNow(),
			'createAccount' => true,
			'enableAutoblock' => true,
			'hideName' => true,
			'blockEmail' => true,
			'by' => $blocker,
		];
		$block = new DatabaseBlock( $blockOptions );

		$this->assertEquals(
			'm>UserOnForeignWiki',
			$block->getTargetName(),
			'Correct blockee name'
		);
		$this->assertEquals(
			'm>UserOnForeignWiki',
			$block->getTargetUserIdentity()->getName(),
			'Correct blockee name'
		);
		$this->assertTrue( $block->isBlocking( 'm>UserOnForeignWiki' ), 'Is blocking blockee' );
		$this->assertEquals(
			'm>MetaWikiUser',
			$block->getBlocker()->getName(),
			'Correct blocker name'
		);
		$this->assertEquals( 'm>MetaWikiUser', $block->getByName(), 'Correct blocker name' );
	}

	/**
	 * @covers ::equals
	 */
	public function testEquals() {
		$block = new DatabaseBlock();

		$this->assertTrue( $block->equals( $block ) );

		$partial = new DatabaseBlock( [
			'sitewide' => false,
		] );
		$this->assertFalse( $block->equals( $partial ) );
	}

	/**
	 * @covers ::getWikiId
	 */
	public function testGetWikiId() {
		$this->overrideConfigValue( MainConfigNames::LocalDatabases, [ 'foo' ] );
		$dbMock = $this->createMock( IDatabase::class );
		$dbMock->method( 'decodeExpiry' )->willReturn( 'infinity' );
		$lbMock = $this->createMock( ILoadBalancer::class );
		$lbMock->method( 'getConnection' )->willReturn( $dbMock );
		$lbFactoryMock = $this->createMock( LBFactory::class );
		$lbFactoryMock->method( 'getMainLB' )->willReturn( $lbMock );
		$this->setService( 'DBLoadBalancerFactory', $lbFactoryMock );

		$block = new DatabaseBlock( [ 'wiki' => 'foo' ] );
		$this->assertSame( 'foo', $block->getWikiId() );

		$this->resetServices();

		$localBlock = new DatabaseBlock();
		$this->assertSame( WikiAwareEntity::LOCAL, $localBlock->getWikiId() );
	}

	/**
	 * @covers ::isSitewide
	 */
	public function testIsSitewide() {
		$block = new DatabaseBlock();
		$this->assertTrue( $block->isSitewide() );

		$block = new DatabaseBlock( [
			'sitewide' => true,
		] );
		$this->assertTrue( $block->isSitewide() );

		$block = new DatabaseBlock( [
			'sitewide' => false,
		] );
		$this->assertFalse( $block->isSitewide() );

		$block = new DatabaseBlock( [
			'sitewide' => false,
		] );
		$block->isSitewide( true );
		$this->assertTrue( $block->isSitewide() );
	}

	/**
	 * @covers ::getRestrictions
	 * @covers ::setRestrictions
	 */
	public function testRestrictions() {
		$block = new DatabaseBlock();
		$restrictions = [
			new PageRestriction( 0, 1 ),
		];
		$block->setRestrictions( $restrictions );

		$this->assertSame( $restrictions, $block->getRestrictions() );
	}

	/**
	 * @covers ::getRestrictions
	 */
	public function testRestrictionsFromDatabase() {
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$targetFactory = $this->getServiceContainer()->getBlockTargetFactory();
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = new DatabaseBlock( [
			'target' => $targetFactory->newUserBlockTarget( $badActor ),
			'by' => $sysop,
			'expiry' => 'infinity',
		] );
		$page = $this->getExistingTestPage( 'Foo' );
		$restriction = new PageRestriction( 0, $page->getId() );
		$block->setRestrictions( [ $restriction ] );
		$blockStore->insertBlock( $block );

		// Refresh the block from the database.
		$block = $blockStore->newFromID( $block->getId() );
		$restrictions = $block->getRestrictions();
		$this->assertCount( 1, $restrictions );
		$this->assertTrue( $restriction->equals( $restrictions[0] ) );
	}

	/**
	 * @covers ::appliesToTitle
	 */
	public function testAppliesToTitleReturnsTrueOnSitewideBlock() {
		$this->overrideConfigValue( MainConfigNames::BlockDisablesLogin, false );
		$user = $this->getTestUser()->getUser();
		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
			'allowUsertalk' => true,
			'sitewide' => true,
		] );

		$block->setTarget(
			new UserBlockTarget( new UserIdentityValue( $user->getId(), $user->getName() ) ) );
		$block->setBlocker( $this->getTestSysop()->getUser() );

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		$title = $this->getExistingTestPage( 'Foo' )->getTitle();

		$this->assertTrue( $block->appliesToTitle( $title ) );

		// appliesToTitle() ignores allowUsertalk
		$title = $user->getTalkPage();
		$this->assertTrue( $block->appliesToTitle( $title ) );
	}

	/**
	 * @covers ::appliesToTitle
	 */
	public function testAppliesToTitleOnPartialBlock() {
		$this->overrideConfigValue( MainConfigNames::BlockDisablesLogin, false );
		$user = $this->getTestUser()->getUser();
		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
			'allowUsertalk' => true,
			'sitewide' => false,
		] );

		$block->setTarget( new UserBlockTarget( $user ) );
		$block->setBlocker( $this->getTestSysop()->getUser() );

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		$pageFoo = $this->getExistingTestPage( 'Foo' );
		$pageBar = $this->getExistingTestPage( 'Bar' );
		$pageJohn = $this->getExistingTestPage( 'User:John' );

		$pageRestriction = new PageRestriction( $block->getId(), $pageFoo->getId() );
		$namespaceRestriction = new NamespaceRestriction( $block->getId(), NS_USER );
		$this->getBlockRestrictionStore()->insert( [ $pageRestriction, $namespaceRestriction ] );

		$this->assertTrue( $block->appliesToTitle( $pageFoo->getTitle() ) );
		$this->assertFalse( $block->appliesToTitle( $pageBar->getTitle() ) );
		$this->assertTrue( $block->appliesToTitle( $pageJohn->getTitle() ) );
	}

	/**
	 * @covers ::appliesToNamespace
	 * @covers ::appliesToPage
	 */
	public function testAppliesToReturnsTrueOnSitewideBlock() {
		$this->overrideConfigValue( MainConfigNames::BlockDisablesLogin, false );
		$user = $this->getTestUser()->getUser();
		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
			'allowUsertalk' => true,
			'sitewide' => true,
		] );

		$block->setTarget( new UserBlockTarget( $user ) );
		$block->setBlocker( $this->getTestSysop()->getUser() );

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		$title = $this->getExistingTestPage()->getTitle();

		$this->assertTrue( $block->appliesToPage( $title->getArticleID() ) );
		$this->assertTrue( $block->appliesToNamespace( NS_MAIN ) );
		$this->assertTrue( $block->appliesToNamespace( NS_USER_TALK ) );
	}

	/**
	 * @covers ::appliesToPage
	 */
	public function testAppliesToPageOnPartialPageBlock() {
		$this->overrideConfigValue( MainConfigNames::BlockDisablesLogin, false );
		$user = $this->getTestUser()->getUser();
		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
			'allowUsertalk' => true,
			'sitewide' => false,
		] );

		$block->setTarget( new UserBlockTarget( $user ) );
		$block->setBlocker( $this->getTestSysop()->getUser() );

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		$title = $this->getExistingTestPage()->getTitle();

		$pageRestriction = new PageRestriction(
			$block->getId(),
			$title->getArticleID()
		);
		$this->getBlockRestrictionStore()->insert( [ $pageRestriction ] );

		$this->assertTrue( $block->appliesToPage( $title->getArticleID() ) );
	}

	/**
	 * @covers ::appliesToNamespace
	 */
	public function testAppliesToNamespaceOnPartialNamespaceBlock() {
		$this->overrideConfigValue( MainConfigNames::BlockDisablesLogin, false );
		$user = $this->getTestUser()->getUser();
		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
			'allowUsertalk' => true,
			'sitewide' => false,
		] );

		$block->setTarget( new UserBlockTarget( $user ) );
		$block->setBlocker( $this->getTestSysop()->getUser() );

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		$namespaceRestriction = new NamespaceRestriction( $block->getId(), NS_MAIN );
		$this->getBlockRestrictionStore()->insert( [ $namespaceRestriction ] );

		$this->assertTrue( $block->appliesToNamespace( NS_MAIN ) );
		$this->assertFalse( $block->appliesToNamespace( NS_USER ) );
	}

	/**
	 * @covers ::appliesToRight
	 */
	public function testBlockAllowsRead() {
		$this->overrideConfigValue( MainConfigNames::BlockDisablesLogin, false );
		$block = new DatabaseBlock();
		$this->assertFalse( $block->appliesToRight( 'read' ) );
	}

	/**
	 * @covers ::isIndefinite
	 */
	public function testIsIndefinite() {
		$block = new DatabaseBlock( [ 'expiry' => '20250301000000' ] );
		$this->assertFalse( $block->isIndefinite() );

		$block = new DatabaseBlock( [ 'expiry' => 'infinity' ] );
		$this->assertTrue( $block->isIndefinite() );
	}

	protected function getBlockRestrictionStore(): BlockRestrictionStore {
		return $this->getServiceContainer()->getBlockRestrictionStore();
	}
}
