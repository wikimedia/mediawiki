<?php

namespace MediaWiki\Tests\Integration\Block;

use MediaWiki\Block\HideUserUtils;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * @group Database
 * @covers \MediaWiki\Block\HideUserUtils
 */
class HideUserUtilsTest extends MediaWikiIntegrationTestCase {
	/** @var HideUserUtils */
	private $hideUserUtils;

	public function setUp(): void {
		$this->hideUserUtils = $this->getServiceContainer()->getHideUserUtils();
	}

	public function addDBDataOnce() {
		$admin = User::createNew( 'Admin' );
		User::createNew( 'Normal' );
		User::createNew( 'Blocked' );
		User::createNew( 'Deleted' );
		User::createNew( 'Deleted twice' );

		$blocks = [
			[
				'address' => 'Blocked',
				'by' => $admin,
			],
			[
				'address' => 'Deleted',
				'by' => $admin,
				'hideName' => true,
			],
			[
				'address' => 'Deleted twice',
				'by' => $admin,
				'hideName' => true
			],
			[
				'address' => 'Deleted twice',
				'by' => $admin,
				'hideName' => true
			],
		];

		$dbs = $this->getServiceContainer()->getDatabaseBlockStore();
		foreach ( $blocks as $params ) {
			$dbs->insertBlockWithParams( $params );
		}
	}

	public static function provideGetExpression() {
		return [
			[ 'Nonexistent', HideUserUtils::SHOWN_USERS, false ],
			[ 'Nonexistent', HideUserUtils::HIDDEN_USERS, false ],
			[ 'Normal', HideUserUtils::SHOWN_USERS, true ],
			[ 'Normal', HideUserUtils::HIDDEN_USERS, false ],
			[ 'Deleted', HideUserUtils::SHOWN_USERS, false ],
			[ 'Deleted', HideUserUtils::HIDDEN_USERS, true ],
			[ 'Deleted twice', HideUserUtils::SHOWN_USERS, false ],
			[ 'Deleted twice', HideUserUtils::HIDDEN_USERS, true ],
		];
	}

	/**
	 * @dataProvider provideGetExpression
	 * @param string $name
	 * @param int $status
	 * @param bool $expected
	 */
	public function testGetExpression( $name, $status, $expected ) {
		$qb = $this->newSelectQueryBuilder()
			->select( 'user_name' )
			->from( 'user' )
			->where( [
				'user_name' => $name,
				$this->hideUserUtils->getExpression( $this->getDb(), 'user_id', $status )
			] )
			->caller( __METHOD__ );
		if ( $expected ) {
			$qb->assertFieldValue( $name );
		} else {
			$qb->assertEmptyResult();
		}
	}

	public function testAddFieldToBuilder() {
		$qb = $this->newSelectQueryBuilder()
			->select( [ 'user_id', 'user_name' ] )
			->from( 'user' )
			->caller( __METHOD__ );
		$this->hideUserUtils->addFieldToBuilder( $qb );
		$qb->assertResultSet( [
			[ 1, 'Admin', 0 ],
			[ 2, 'Normal', 0 ],
			[ 3, 'Blocked', 0 ],
			[ 4, 'Deleted', 1 ],
			[ 5, 'Deleted twice', 1 ],
		] );
	}
}
