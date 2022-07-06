<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiBlock
 */
class ApiBlockTest extends ApiTestCase {
	use MockAuthorityTrait;

	protected $mUser = null;

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'ipblocks', 'ipblocks_restrictions', 'change_tag', 'change_tag_def', 'logging' ]
		);

		$this->mUser = $this->getMutableTestUser()->getUser();
		$this->overrideConfigValue(
			MainConfigNames::BlockCIDRLimit,
			[
				'IPv4' => 16,
				'IPv6' => 19,
			]
		);
	}

	/**
	 * @param array $extraParams Extra API parameters to pass to doApiRequest
	 * @param User|null $blocker User to do the blocking, null to pick arbitrarily
	 * @return array result of doApiRequest
	 */
	private function doBlock( array $extraParams = [], User $blocker = null ) {
		$this->assertNotNull( $this->mUser );

		$params = [
			'action' => 'block',
			'user' => $this->mUser->getName(),
			'reason' => 'Some reason',
		];
		if ( array_key_exists( 'userid', $extraParams ) ) {
			// Make sure we don't have both user and userid
			unset( $params['user'] );
		}
		$ret = $this->doApiRequestWithToken( array_merge( $params, $extraParams ), null, $blocker );

		$block = DatabaseBlock::newFromTarget( $this->mUser->getName() );

		$this->assertTrue( $block !== null, 'Block is valid' );

		$this->assertSame( $this->mUser->getName(), $block->getTargetName() );
		$this->assertSame( 'Some reason', $block->getReasonComment()->text );

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
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage(
			'You cannot block or unblock other users because you are yourself blocked.'
		);

		$blocked = $this->getMutableTestUser( [ 'sysop' ] )->getUser();
		$block = new DatabaseBlock( [
			'address' => $blocked->getName(),
			'by' => self::$users['sysop']->getUser(),
			'reason' => 'Capriciousness',
			'timestamp' => '19370101000000',
			'expiry' => 'infinity',
		] );
		$this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );

		$this->doBlock( [], $blocked );
	}

	public function testBlockOfNonexistentUser() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage(
			'There is no user by the name "Nonexistent". Check your spelling.'
		);

		$this->doBlock( [ 'user' => 'Nonexistent' ] );
	}

	public function testBlockOfNonexistentUserId() {
		$id = 948206325;
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( "There is no user with ID $id." );

		$this->assertFalse( User::whoIs( $id ) );

		$this->doBlock( [ 'userid' => $id ] );
	}

	public function testBlockWithTag() {
		ChangeTags::defineTag( 'custom tag' );

		$this->doBlock( [ 'tags' => 'custom tag' ] );

		$dbw = wfGetDB( DB_PRIMARY );
		$this->assertSame( 1, (int)$dbw->selectField(
			[ 'change_tag', 'logging', 'change_tag_def' ],
			'COUNT(*)',
			[ 'log_type' => 'block', 'ctd_name' => 'custom tag' ],
			__METHOD__,
			[],
			[
				'change_tag' => [ 'JOIN', 'ct_log_id = log_id' ],
				'change_tag_def' => [ 'JOIN', 'ctd_id = ct_tag_id' ],
			]
		) );
	}

	public function testBlockWithProhibitedTag() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage(
			'You do not have permission to apply change tags along with your changes.'
		);

		ChangeTags::defineTag( 'custom tag' );

		$this->overrideConfigValue(
			MainConfigNames::RevokePermissions,
			[ 'user' => [ 'applychangetags' => true ] ]
		);

		$this->doBlock( [ 'tags' => 'custom tag' ] );
	}

	public function testBlockWithHide() {
		global $wgGroupPermissions;
		$newPermissions = $wgGroupPermissions['sysop'];
		$newPermissions['hideuser'] = true;
		$this->mergeMwGlobalArrayValue( 'wgGroupPermissions',
			[ 'sysop' => $newPermissions ] );

		$res = $this->doBlock(
			[ 'hidename' => '' ],
			self::$users['sysop']->getUser()
		);

		$this->assertSame( '1', $this->db->selectField(
			'ipblocks',
			'ipb_deleted',
			[ 'ipb_id' => $res[0]['block']['id'] ],
			__METHOD__
		) );
	}

	public function testBlockWithProhibitedHide() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage(
			"You are not allowed to execute the action you have requested."
		);

		$this->doBlock( [ 'hidename' => '' ] );
	}

	public function testBlockWithEmailBlock() {
		$this->overrideConfigValues( [
			MainConfigNames::EnableEmail => true,
			MainConfigNames::EnableUserEmail => true,
		] );

		$res = $this->doBlock( [ 'noemail' => '' ] );

		$dbw = wfGetDB( DB_PRIMARY );
		$this->assertSame( '1', $dbw->selectField(
			'ipblocks',
			'ipb_block_email',
			[ 'ipb_id' => $res[0]['block']['id'] ],
			__METHOD__
		) );
	}

	public function testBlockWithProhibitedEmailBlock() {
		$this->overrideConfigValues( [
			MainConfigNames::EnableEmail => true,
			MainConfigNames::EnableUserEmail => true,
		] );

		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage(
			"You don't have permission to block users from sending email through the wiki."
		);

		$this->overrideConfigValue(
			MainConfigNames::RevokePermissions,
			[ 'sysop' => [ 'blockemail' => true ] ]
		);

		$this->doBlock( [ 'noemail' => '' ] );
	}

	public function testBlockWithExpiry() {
		$fakeTime = 1616432035;
		MWTimestamp::setFakeTime( $fakeTime );
		$res = $this->doBlock( [ 'expiry' => '1 day' ] );

		$dbw = wfGetDB( DB_PRIMARY );
		$expiry = $dbw->selectField(
			'ipblocks',
			'ipb_expiry',
			[ 'ipb_id' => $res[0]['block']['id'] ],
			__METHOD__
		);

		$this->assertSame( (int)wfTimestamp( TS_UNIX, $expiry ), $fakeTime + 86400 );
	}

	public function testBlockWithInvalidExpiry() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( "Expiry time invalid." );

		$this->doBlock( [ 'expiry' => '' ] );
	}

	public function testBlockWithoutRestrictions() {
		$this->doBlock();

		$block = DatabaseBlock::newFromTarget( $this->mUser->getName() );

		$this->assertTrue( $block->isSitewide() );
		$this->assertSame( [], $block->getRestrictions() );
	}

	public function testBlockWithRestrictionsPage() {
		$title = 'Foo';
		$page = $this->getExistingTestPage( $title );

		$this->doBlock( [
			'partial' => true,
			'pagerestrictions' => $title,
			'allowusertalk' => true,
		] );

		$block = DatabaseBlock::newFromTarget( $this->mUser->getName() );

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

		$block = DatabaseBlock::newFromTarget( $this->mUser->getName() );

		$this->assertInstanceOf( NamespaceRestriction::class, $block->getRestrictions()[0] );
		$this->assertEquals( $namespace, $block->getRestrictions()[0]->getValue() );
	}

	public function testBlockWithRestrictionsAction() {
		$this->overrideConfigValue(
			MainConfigNames::EnablePartialActionBlocks,
			true
		);

		$blockActionInfo = $this->getServiceContainer()->getBlockActionInfo();
		$action = 'upload';

		$this->doBlock( [
			'partial' => true,
			'actionrestrictions' => $action,
			'allowusertalk' => true,
		] );

		$block = DatabaseBlock::newFromTarget( $this->mUser->getName() );

		$this->assertInstanceOf( ActionRestriction::class, $block->getRestrictions()[0] );
		$this->assertEquals( $action, $blockActionInfo->getActionFromId( $block->getRestrictions()[0]->getValue() ) );
	}

	public function testBlockingActionWithNoToken() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( 'The "token" parameter must be set' );
		$this->doApiRequest(
			[
				'action' => 'block',
				'user' => $this->mUser->getName(),
				'reason' => 'Some reason',
			],
			null,
			false,
			self::$users['sysop']->getUser()
		);
	}

	public function testBlockWithLargeRange() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( 'Invalid value "127.0.0.1/64" for user parameter "user".' );
		$this->doApiRequestWithToken(
			[
				'action' => 'block',
				'user' => '127.0.0.1/64',
				'reason' => 'Some reason',
			],
			null,
			self::$users['sysop']->getUser()
		);
	}

	public function testBlockingTooManyPageRestrictions() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage(
			"Too many values supplied for parameter \"pagerestrictions\". The limit is 10." );
		$this->doApiRequestWithToken(
			[
				'action' => 'block',
				'user' => $this->mUser->getName(),
				'reason' => 'Some reason',
				'partial' => true,
				'pagerestrictions' => 'One|Two|Three|Four|Five|Six|Seven|Eight|Nine|Ten|Eleven',
			],
			null,
			self::$users['sysop']->getUser()
		);
	}

	public function testRangeBlock() {
		$this->mUser = User::newFromName( '128.0.0.0/16', false );
		$this->doBlock();
	}

	public function testVeryLargeRangeBlock() {
		$this->mUser = User::newFromName( '128.0.0.0/1', false );
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( "Range blocks larger than /16 are not allowed." );
		$this->doBlock();
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
}
