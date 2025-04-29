<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Block\AnonIpBlockTarget;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\RangeBlockTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers CleanupBlocks
 * @group Database
 */
class CleanupBlocksTest extends MaintenanceBaseTestCase {
	public function assertPreConditions(): void {
		if ( $this->getDb()->getType() === 'postgres' ) {
			$this->markTestSkipped( 'cleanupBlocks does not support postgres' );
		}
	}

	protected function getMaintenanceClass() {
		return \CleanupBlocks::class;
	}

	private function insertBlock( $options = [] ) {
		$options += [
			'by' => new UserIdentityValue( 100, 'Admin' ),
			'address' => '127.0.0.1',
		];
		if ( isset( $options['target'] ) ) {
			unset( $options['address'] );
		}
		return $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( $options );
	}

	private function getBlockTargetId( DatabaseBlock $block ) {
		return (int)$this->getDb()->newSelectQueryBuilder()
			->select( 'bl_target' )
			->from( 'block' )
			->where( [ 'bl_id' => $block->getId() ] )
			->fetchField();
	}

	public function testDeleteOrphanBlockTargets() {
		$block1 = $this->insertBlock();
		$bt1 = $this->getBlockTargetId( $block1 );
		$block2 = $this->insertBlock( [ 'address' => '127.0.0.2' ] );
		$this->getDb()->newDeleteQueryBuilder()
			->deleteFrom( 'block' )
			->where( [ 'bl_id' => $block2->getId() ] )
			->caller( __METHOD__ )->execute();

		$this->maintenance->execute();
		$this->newSelectQueryBuilder()
			->select( 'bt_id' )
			->from( 'block_target' )
			->assertFieldValues( [ (string)$bt1 ] );
	}

	public function testDeleteTargetlessBlocks() {
		$block1 = $this->insertBlock();
		$this->insertBlock( [ 'address' => '127.0.0.2' ] );
		$this->getDb()->newDeleteQueryBuilder()
			->deleteFrom( 'block_target' )
			->where( [ 'bt_address' => '127.0.0.2' ] )
			->execute();

		$this->maintenance->execute();
		$this->newSelectQueryBuilder()
			->select( 'bl_id' )
			->from( 'block' )
			->assertFieldValues( [ (string)$block1->getId() ] );
	}

	public function testNormalizeAddresses() {
		if ( $this->getDb()->getType() !== 'mysql' ) {
			$this->markTestSkipped( 'not implemented on this DBMS' );
		}
		$this->insertBlock();
		$this->insertBlock( [ 'target' => new AnonIpBlockTarget( '1.1.1.001' ) ] );
		$this->insertBlock( [ 'target' => new AnonIpBlockTarget( '300e:0:0:0:0:0:0:0' ) ] );
		$this->insertBlock( [
			'target' => new RangeBlockTarget(
				'2.1.1.111/24',
				$this->getConfVar( MainConfigNames::BlockCIDRLimit )
			)
		] );

		$this->newSelectQueryBuilder()
			->select( 'bt_address' )
			->from( 'block_target' )
			->assertFieldValues( [
				'1.1.1.001',
				'127.0.0.1',
				'2.1.1.111/24',
				'300e:0:0:0:0:0:0:0',
			] );

		$this->maintenance->execute();
		$this->newSelectQueryBuilder()
			->select( 'bt_address' )
			->from( 'block_target' )
			->assertFieldValues( [
				'1.1.1.1',
				'127.0.0.1',
				'2.1.1.0/24',
				'300E:0:0:0:0:0:0:0',
			] );
	}

	public function testMergeDuplicateBlockTargets() {
		$block1 = $this->insertBlock();
		$b1 = $block1->getId();
		$bt1 = $this->getBlockTargetId( $block1 );
		$dbw = $this->getDb();
		$dbw->insertSelect(
			'block_target', 'block_target',
			[
				'bt_address' => 'bt_address',
				'bt_user' => 'bt_user',
				'bt_user_text' => 'bt_user_text',
				'bt_auto' => 'bt_auto',
				'bt_range_end' => 'bt_range_end',
				'bt_ip_hex' => 'bt_ip_hex',
				'bt_count' => 'bt_count'
			],
			[ 'bt_id' => $bt1 ]
		);
		$bt2 = $dbw->insertId();
		$dbw->insertSelect(
			'block', 'block',
			[
				'bl_target' => $dbw->addQuotes( $bt2 ),
				'bl_by_actor' => 'bl_by_actor',
				'bl_reason_id' => 'bl_reason_id',
				'bl_timestamp' => 'bl_timestamp',
				'bl_anon_only' => 'bl_anon_only',
				'bl_create_account' => 'bl_create_account',
				'bl_enable_autoblock' => '0',
				'bl_expiry' => 'bl_expiry',
				'bl_deleted' => 'bl_deleted',
			],
			[ 'bl_id' => $block1->getId() ]
		);
		$b2 = $dbw->insertId();

		$this->maintenance->execute();

		$this->newSelectQueryBuilder()
			->select( [ 'bt_id', 'bt_count' ] )
			->from( 'block_target' )
			->assertResultSet( [
				[ (string)$bt1, '2' ]
			] );

		$this->newSelectQueryBuilder()
			->select( [ 'bl_id', 'bl_target' ] )
			->from( 'block' )
			->assertResultSet( [
				[ (string)$b1, (string)$bt1 ],
				[ (string)$b2, (string)$bt1 ]
			] );
	}

	public function testFixTargetCounts() {
		$block1 = $this->insertBlock();
		$bt1 = $this->getBlockTargetId( $block1 );
		$block2 = $this->insertBlock( [ 'address' => '127.0.0.2' ] );
		$bt2 = $this->getBlockTargetId( $block2 );
		$dbw = $this->getDb();
		$dbw->newUpdateQueryBuilder()
			->update( 'block_target' )
			->set( [ 'bt_count' => 2 ] )
			->where( [ 'bt_id' => $bt1 ] )
			->execute();

		$this->maintenance->execute();

		$this->newSelectQueryBuilder()
			->select( 'bt_count' )
			->from( 'block_target' )
			->where( [ 'bt_id' => [ $bt1, $bt2 ] ] )
			->assertFieldValues( [ '1', '1' ] );
	}
}
