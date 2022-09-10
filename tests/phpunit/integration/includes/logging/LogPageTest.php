<?php

namespace MediaWiki\Tests\Log;

use DatabaseLogEntry;
use LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\User\UserIdentityValue;
use MockTitleTrait;

/**
 * @group Database
 * @coversDefaultClass LogPage
 * @package MediaWiki\Tests\Log
 */
class LogPageTest extends \MediaWikiIntegrationTestCase {
	use MockTitleTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::LogNames => [
				'test_test' => 'testing-log-message'
			],
			MainConfigNames::LogHeaders => [
				'test_test' => 'testing-log-header'
			],
			MainConfigNames::LogRestrictions => [
				'test_test' => 'testing-log-restriction'
			]
		] );
		$this->tablesUsed[] = 'logging';
	}

	/**
	 * @covers ::__construct
	 * @covers ::getName
	 * @covers ::getDescription
	 * @covers ::getRestriction
	 * @covers ::isRestricted
	 */
	public function testConstruct() {
		$logPage = new LogPage( 'test_test' );
		$this->assertSame( 'testing-log-message', $logPage->getName()->getKey() );
		$this->assertSame( 'testing-log-header', $logPage->getDescription()->getKey() );
		$this->assertSame( 'testing-log-restriction', $logPage->getRestriction() );
		$this->assertTrue( $logPage->isRestricted() );
	}

	/**
	 * @covers ::addEntry
	 * @covers ::getComment
	 * @covers ::getRcComment
	 * @covers ::getRcCommentIRC
	 */
	public function testAddEntrySetsProperties() {
		$logPage = new LogPage( 'test_test' );
		$user = new UserIdentityValue( 0, '127.0.0.1' );
		$logPage->addEntry(
			'test_action',
			$this->makeMockTitle( __METHOD__ ),
			'testing_comment',
			[ 'param_one', 'param_two' ],
			$user
		);
		$this->assertSame( 'testing_comment', $logPage->getComment() );
		$this->assertStringContainsString( 'testing_comment', $logPage->getRcComment() );
		$this->assertStringContainsString( 'testing_comment', $logPage->getRcCommentIRC() );
	}

	/**
	 * @covers ::addEntry
	 */
	public function testAddEntrySave() {
		$logPage = new LogPage( 'test_test' );
		$user = new UserIdentityValue( 0, '127.0.0.1' );
		$title = $this->makeMockTitle( __METHOD__ );
		$id = $logPage->addEntry(
			'test_action',
			$title,
			'testing_comment',
			[ 'param_one', 'param_two' ],
			$user
		);

		$savedLogEntry = DatabaseLogEntry::newFromId( $id, $this->db );
		$this->assertNotNull( $savedLogEntry );
		$this->assertSame( 'test_test', $savedLogEntry->getType() );
		$this->assertSame( 'test_action', $savedLogEntry->getSubtype() );
		$this->assertSame( 'testing_comment', $savedLogEntry->getComment() );
		$this->assertArrayEquals( [ 'param_one', 'param_two' ], $savedLogEntry->getParameters() );
		$this->assertTrue( $title->equals( $savedLogEntry->getTarget() ) );
		$this->assertTrue( $user->equals( $savedLogEntry->getPerformerIdentity() ) );
	}
}
