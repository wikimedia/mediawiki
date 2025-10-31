<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Logging\LogEntryBase;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\User;
use MediaWiki\User\UserRigorOptions;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiBlock
 */
class ApiBlockTest extends ApiTestCase {
	use MockAuthorityTrait;

	/** @var User|null */
	protected $mUser = null;
	/** @var DatabaseBlockStore */
	private $blockStore;
	/** @var DatabaseBlock|null */
	private $block;

	protected function setUp(): void {
		parent::setUp();

		$this->mUser = $this->getMutableTestUser()->getUser();
		$this->overrideConfigValue(
			MainConfigNames::BlockCIDRLimit,
			[
				'IPv4' => 16,
				'IPv6' => 19,
			]
		);
		$this->overrideConfigValue( MainConfigNames::EnableMultiBlocks, true );
		$this->blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
	}

	/**
	 * @param array $extraParams Extra API parameters to pass to doApiRequest
	 * @param Authority|null $blocker User to do the blocking, null to pick arbitrarily
	 * @return array result of doApiRequest
	 */
	private function doBlock( array $extraParams = [], ?Authority $blocker = null ) {
		$this->assertNotNull( $this->mUser );

		$params = $extraParams + [
			'action' => 'block',
			'user' => $this->mUser->getName(),
			'reason' => 'Some reason',
		];
		if ( array_key_exists( 'userid', $extraParams ) ) {
			// Make sure we don't have both user and userid
			unset( $params['user'] );
		}
		$ret = $this->doApiRequestWithToken( $params, null, $blocker );

		$this->block = $this->blockStore->newFromId( $ret[0]['block']['id'] );

		$this->assertInstanceOf( DatabaseBlock::class, $this->block, 'Block is valid' );

		$this->assertSame( $params['user'] ?? $this->mUser->getName(), $this->block->getTargetName() );
		$this->assertSame( 'Some reason', $this->block->getReasonComment()->text );

		return $ret;
	}

	/**
	 * Block by username
	 */
	public function testNormalBlock() {
		$this->doBlock();
	}

	/**
	 * Block by user ID
	 */
	public function testBlockById() {
		$this->doBlock( [ 'userid' => $this->mUser->getId() ] );
	}

	/**
	 * A blocked user can't block
	 */
	public function testBlockByBlockedUser() {
		$this->expectApiErrorCode( 'ipbblocked' );

		$blocked = $this->getMutableTestUser( [ 'sysop' ] )->getUser();
		$this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'address' => $blocked->getName(),
				'by' => $this->getTestSysop()->getUser(),
				'reason' => 'Capriciousness',
				'timestamp' => '19370101000000',
				'expiry' => 'infinity',
			] );

		$this->doBlock( [], $blocked );
	}

	public function testBlockOfNonexistentUser() {
		$this->expectApiErrorCode( 'nosuchuser' );

		$this->doBlock( [ 'user' => 'Nonexistent' ] );
	}

	public function testBlockOfNonexistentUserId() {
		$id = 948206325;
		$this->expectApiErrorCode( 'nosuchuserid' );

		$this->assertNull( $this->getServiceContainer()->getUserIdentityLookup()->getUserIdentityByUserId( $id ) );

		$this->doBlock( [ 'userid' => $id ] );
	}

	public function testBlockWithTag() {
		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'custom tag' );

		$this->doBlock( [ 'tags' => 'custom tag' ] );

		$this->assertSame( 1, (int)$this->getDb()->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'logging' )
			->join( 'change_tag', null, 'ct_log_id = log_id' )
			->join( 'change_tag_def', null, 'ctd_id = ct_tag_id' )
			->where( [ 'log_type' => 'block', 'ctd_name' => 'custom tag' ] )
			->caller( __METHOD__ )->fetchField() );
	}

	public function testBlockWithProhibitedTag() {
		$this->expectApiErrorCode( 'tags-apply-no-permission' );

		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'custom tag' );

		$this->overrideConfigValue(
			MainConfigNames::RevokePermissions,
			[ 'user' => [ 'applychangetags' => true ] ]
		);

		$this->doBlock( [ 'tags' => 'custom tag' ] );
	}

	public function testBlockWithHide() {
		$res = $this->doBlock(
			[ 'hidename' => '' ],
			new UltimateAuthority( $this->getTestSysop()->getUser() )
		);

		$this->assertSame( '1', $this->getDb()->newSelectQueryBuilder()
			->select( 'bl_deleted' )
			->from( 'block' )
			->where( [ 'bl_id' => $res[0]['block']['id'] ] )
			->caller( __METHOD__ )->fetchField() );
	}

	public function testBlockWithProhibitedHide() {
		$performer = $this->mockUserAuthorityWithoutPermissions(
			$this->getTestUser()->getUser(),
			[ 'hideuser' ]
		);
		$this->expectApiErrorCode( 'permissiondenied' );

		$this->doBlock( [ 'hidename' => '' ], $performer );
	}

	public function testBlockWithEmailBlock() {
		$this->overrideConfigValues( [
			MainConfigNames::EnableEmail => true,
			MainConfigNames::EnableUserEmail => true,
		] );

		$res = $this->doBlock( [ 'noemail' => '' ] );
		$this->assertSame( '1', $this->getDb()->newSelectQueryBuilder()
			->select( 'bl_block_email' )
			->from( 'block' )
			->where( [ 'bl_id' => $res[0]['block']['id'] ] )
			->caller( __METHOD__ )->fetchField() );
	}

	public function testBlockWithProhibitedEmailBlock() {
		$this->overrideConfigValues( [
			MainConfigNames::EnableEmail => true,
			MainConfigNames::EnableUserEmail => true,
			MainConfigNames::RevokePermissions => [ 'sysop' => [ 'blockemail' => true ] ],
		] );

		$this->expectApiErrorCode( 'cantblock-email' );
		$this->doBlock( [ 'noemail' => '' ] );
	}

	public function testBlockWithExpiry() {
		$fakeTime = 1616432035;
		MWTimestamp::setFakeTime( $fakeTime );
		$res = $this->doBlock( [ 'expiry' => '1 day' ] );
		$expiry = $this->getDb()->newSelectQueryBuilder()
			->select( 'bl_expiry' )
			->from( 'block' )
			->where( [ 'bl_id' => $res[0]['block']['id'] ] )
			->caller( __METHOD__ )->fetchField();
		$this->assertSame( (int)wfTimestamp( TS_UNIX, $expiry ), $fakeTime + 86400 );

		// Check log format (T248196)
		$blob = $this->newSelectQueryBuilder()
			->select( 'log_params' )
			->from( 'logging' )
			->where( [
				'log_action' => 'block',
				'log_type' => 'block'
			] )
			->orderBy( 'log_timestamp', SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )
			->fetchField();
		$params = LogEntryBase::extractParams( $blob );
		$this->assertSame( '1 day', $params['5::duration'] );
	}

	public function testBlockWithInvalidExpiry() {
		$this->expectApiErrorCode( 'invalidexpiry' );

		$this->doBlock( [ 'expiry' => '' ] );
	}

	public function testBlockWithoutRestrictions() {
		$this->doBlock();

		$block = $this->blockStore->newFromTarget( $this->mUser->getName() );

		$this->assertTrue( $block->isSitewide() );
		$this->assertSame( [], $block->getRestrictions() );
	}

	public function testBlockWithRestrictionsPage() {
		$title = 'Foo';
		$this->getExistingTestPage( $title );

		$this->doBlock( [
			'partial' => true,
			'pagerestrictions' => $title,
			'allowusertalk' => true,
		] );

		$block = $this->blockStore->newFromTarget( $this->mUser->getName() );

		$this->assertFalse( $block->isSitewide() );
		$this->assertInstanceOf( PageRestriction::class, $block->getRestrictions()[0] );
		$this->assertEquals( $title, $block->getRestrictions()[0]->getTitle()->getText() );
	}

	public function testBlockWithRestrictionsNamespace() {
		$namespace = NS_TALK;

		$this->doBlock( [
			'partial' => true,
			'namespacerestrictions' => $namespace,
			'allowusertalk' => true,
		] );

		$block = $this->blockStore->newFromTarget( $this->mUser->getName() );

		$this->assertInstanceOf( NamespaceRestriction::class, $block->getRestrictions()[0] );
		$this->assertEquals( $namespace, $block->getRestrictions()[0]->getValue() );
	}

	public function testBlockWithRestrictionsAction() {
		$blockActionInfo = $this->getServiceContainer()->getBlockActionInfo();
		$action = 'upload';

		$this->doBlock( [
			'partial' => true,
			'actionrestrictions' => $action,
			'allowusertalk' => true,
		] );

		$block = $this->blockStore->newFromTarget( $this->mUser->getName() );

		$this->assertInstanceOf( ActionRestriction::class, $block->getRestrictions()[0] );
		$this->assertEquals( $action, $blockActionInfo->getActionFromId( $block->getRestrictions()[0]->getValue() ) );
	}

	public function testBlockingActionWithNoToken() {
		$this->expectApiErrorCode( 'missingparam' );
		$this->doApiRequest(
			[
				'action' => 'block',
				'user' => $this->mUser->getName(),
				'reason' => 'Some reason',
			],
			null,
			false,
			$this->getTestSysop()->getUser()
		);
	}

	public function testBlockWithLargeRange() {
		$this->expectApiErrorCode( 'baduser' );
		$this->doApiRequestWithToken(
			[
				'action' => 'block',
				'user' => '127.0.0.1/64',
				'reason' => 'Some reason',
			],
			null,
			$this->getTestSysop()->getUser()
		);
	}

	public function testBlockingTooManyPageRestrictions() {
		$this->expectApiErrorCode( 'toomanyvalues' );
		$this->doApiRequestWithToken(
			[
				'action' => 'block',
				'user' => $this->mUser->getName(),
				'reason' => 'Some reason',
				'partial' => true,
				'pagerestrictions' => implode( '|', range( 1, 55 ) ),
			],
			null,
			$this->getTestSysop()->getUser()
		);
	}

	public function testRangeBlock() {
		$this->mUser = $this->getServiceContainer()->getUserFactory()->newFromName( '128.0.0.0/16', UserRigorOptions::RIGOR_NONE );
		$this->doBlock();
	}

	public function testVeryLargeRangeBlock() {
		$this->mUser = $this->getServiceContainer()->getUserFactory()->newFromName( '128.0.0.0/1', UserRigorOptions::RIGOR_NONE );
		$this->expectApiErrorCode( 'ip_range_toolarge' );
		$this->doBlock();
	}

	public function testNonNormalizedRangeBlock() {
		$params = [
			'action' => 'block',
			'user' => '128.0.0.1/16',
			'reason' => 'Some reason',
		];
		$res = $this->doApiRequestWithToken( $params );
		$this->assertSame( '128.0.0.0/16', $res[0]['block']['user'] );
		$this->newSelectQueryBuilder()
			->select( 'bt_address' )
			->from( 'block_target' )
			->join( 'block', null, 'bl_target=bt_id' )
			->where( [ 'bl_id' => $res[0]['block']['id'] ] )
			->assertFieldValue( '128.0.0.0/16' );
	}

	public function testBlockByIdReturns() {
		// See T189073 and Ifdced735b694b85116cb0e43dadbfa8e4cdb8cab for context
		$userId = $this->mUser->getId();

		$res = $this->doBlock(
			[ 'userid' => $userId ]
		);

		$blockResult = $res[0]['block'];

		$this->assertArrayHasKey( 'user', $blockResult );
		$this->assertSame( $this->mUser->getName(), $blockResult['user'] );

		$this->assertArrayHasKey( 'userID', $blockResult );
		$this->assertSame( $userId, $blockResult['userID'] );
	}

	public function testConflict() {
		$this->doBlock();
		$this->expectApiErrorCode( 'alreadyblocked' );
		$this->doBlock( [ 'noemail' => '' ] );
	}

	public function testReblock() {
		$this->doBlock();
		$this->assertFalse( $this->block->isEmailBlocked() );
		$this->doBlock( [ 'noemail' => '', 'reblock' => true ] );
		$this->assertTrue( $this->block->isEmailBlocked() );
	}

	public function testMultiBlocks() {
		$this->doBlock();
		$this->doBlock( [ 'noemail' => '', 'newblock' => '' ] );
		$this->assertTrue( $this->block->isEmailBlocked() );
		$this->assertCount( 2, $this->blockStore->newListFromTarget( $this->mUser ) );
	}

	public function testMultiRedundant() {
		$this->expectApiErrorCode( 'alreadyblocked' );
		$this->doBlock();
		$this->doBlock( [ 'newblock' => '' ] );
	}

	public function testReblockMulti() {
		$this->doBlock();
		$this->doBlock( [ 'noemail' => '', 'newblock' => '' ] );
		$this->expectApiErrorCode( 'ambiguous-block' );
		$this->doBlock( [ 'reblock' => true ] );
	}

	public function testId() {
		$this->doBlock();
		$this->assertFalse( $this->block->isEmailBlocked() );
		$this->doBlock( [ 'noemail' => '', 'id' => $this->block->getId(), 'user' => null ] );
		$this->assertTrue( $this->block->isEmailBlocked() );
	}

	public function testIdConflictsWithUser() {
		$this->expectApiErrorCode( 'invalidparammix' );
		$this->doBlock( [ 'noemail' => '', 'id' => '1' ] );
	}

	public function testIdConflictsWithNewblock() {
		$this->expectApiErrorCode( 'invalidparammix' );
		$this->doBlock( [ 'newblock' => '', 'id' => '1' ] );
	}

	public function testIdConflictsWithReblock() {
		$this->expectApiErrorCode( 'invalidparammix' );
		$this->doBlock( [ 'reblock' => '', 'id' => '1' ] );
	}

	public function testNewblockConflictsWithReblock() {
		$this->expectApiErrorCode( 'invalidparammix' );
		$this->doBlock( [ 'newblock' => '', 'reblock' => '' ] );
	}

	public function testIdMulti() {
		$this->doBlock();
		$block1 = $this->block->getId();
		$this->doBlock( [ 'allowusertalk' => '', 'newblock' => '' ] );
		$block2 = $this->block->getId();
		$this->assertFalse( $this->blockStore->newFromId( $block2 )->isEmailBlocked() );

		$this->doBlock( [ 'id' => $block2, 'user' => null, 'noemail' => '' ] );
		$this->assertFalse( $this->blockStore->newFromId( $block1 )->isEmailBlocked() );
		$this->assertTrue( $this->blockStore->newFromId( $block2 )->isEmailBlocked() );
	}

	public function testNoSuchBlockId() {
		$this->expectApiErrorCode( 'nosuchblockid' );
		$this->doBlock( [ 'id' => '1', 'user' => null ] );
	}

	public function testModifyAutoblock() {
		$this->doBlock( [ 'autoblock' => '' ] );
		$autoId = $this->blockStore->doAutoblock( $this->block, '127.0.0.1' );
		$this->expectApiErrorCode( 'modify-autoblock' );
		$this->doBlock( [ 'id' => $autoId, 'user' => null, 'noemail' => '' ] );
	}

	public function testNoOpBlockUpdate() {
		$this->doBlock();
		$this->expectApiErrorCode( 'alreadyblocked' );
		$this->doBlock( [ 'id' => $this->block->getId(), 'user' => null ] );
	}

	/**
	 * Regression test for T389452
	 */
	public function testReblockAutoblockedIp() {
		$ip = '127.0.0.1';
		$this->doBlock( [ 'autoblock' => '' ] );
		$autoId = $this->blockStore->doAutoblock( $this->block, $ip );
		$this->doBlock( [ 'user' => $ip, 'expiry' => '1 year', 'reblock' => true ] );
		$blocks = $this->blockStore->newListFromTarget( $ip );
		$this->assertCount( 2, $blocks );
		usort( $blocks, static fn ( $a, $b ) => $a->getId() <=> $b->getId() );
		$this->assertSame( AbstractBlock::TYPE_AUTO, $blocks[0]->getType() );
		$this->assertSame( $autoId, $blocks[0]->getId() );
		$this->assertSame( AbstractBlock::TYPE_IP, $blocks[1]->getType() );
	}
}
