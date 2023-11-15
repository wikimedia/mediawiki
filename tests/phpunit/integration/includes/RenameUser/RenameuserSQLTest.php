<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\RenameUser\RenameuserSQL;

/**
 * @group Database
 * @covers \MediaWiki\RenameUser\RenameuserSQL
 */
class RenameuserSQLTest extends MediaWikiIntegrationTestCase {
	public function testRename() {
		$oldUser = $this->getMutableTestUser()->getUser();
		$admin = $this->getTestSysop()->getUser();
		$oldName = $oldUser->getName();
		$newName = 'RenameuserSQL.new';
		$userId = $oldUser->getId();
		$adminActor = $admin->getActorId();

		$this->editPage( __CLASS__, 'test' );

		$blockStatus = $this->getServiceContainer()->getBlockUserFactory()
			->newBlockUser(
				$oldUser,
				$admin,
				'infinity',
				'reason'
			)
			->placeBlock();
		$this->assertStatusGood( $blockStatus );
		/** @var DatabaseBlock $block */
		$block = $blockStatus->getValue();
		$blockId = $block->getId();

		$renamer = new RenameuserSQL( $oldName, $newName, $userId, $admin );
		$this->assertTrue( $renamer->rename() );

		$this->newSelectQueryBuilder()
			->select( 'user_name' )
			->from( 'user' )
			->where( [ 'user_id' => $userId ] )
			->assertFieldValue( $newName );

		$this->newSelectQueryBuilder()
			->select( 'actor_name' )
			->from( 'actor' )
			->where( [ 'actor_user' => $userId ] )
			->assertFieldValue( $newName );

		$this->newSelectQueryBuilder()
			->select( 'log_title' )
			->from( 'logging' )
			->where( [
				'log_type' => 'block',
				'log_actor' => $adminActor
			] )
			->assertFieldValue( $newName );

		$this->newSelectQueryBuilder()
			->select( 'rc_title' )
			->from( 'recentchanges' )
			->where( [
				'rc_actor' => $adminActor,
				'rc_log_type' => 'block'
			] )
			->assertFieldValue( $newName );

		$block = $this->getServiceContainer()->getDatabaseBlockStore()
			->newFromTarget( "#$blockId" );
		$this->assertSame( $newName, $block->getTargetName() );
	}
}
