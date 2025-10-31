<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Block\BlockUser;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\Api\ApiQueryLogEvents
 * @group API
 * @group Database
 * @group medium
 */
class ApiQueryLogEventsTest extends ApiTestCase {
	use TempUserTestTrait;

	public function testExecute() {
		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'logevents',
		] );
		$this->assertEquals( [ 'batchcomplete' => true, 'query' => [ 'logevents' => [] ] ], $data );
	}

	/**
	 * @group Database
	 */
	public function testLogEventByTempUser() {
		$this->enableAutoCreateTempUser();
		$tempUser = new UserIdentityValue( 1236764321, '~1' );
		$title = $this->getNonexistingTestPage( 'TestPage1' )->getTitle();
		$this->editPage(
			$title,
			'Some Content',
			'Create Page',
			NS_MAIN,
			new UltimateAuthority( $tempUser )
		);

		[ $result ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'logevents',
		] );
		$this->assertArrayHasKey( 'temp', $result['query']['logevents'][0] );
		$this->assertTrue( $result['query']['logevents'][0]['temp'] );
	}

	public function testFilterByLogIds() {
		$this->overrideConfigValue( MainConfigNames::EnableMultiBlocks, true );
		$services = $this->getServiceContainer();
		$sysop = $this->getTestSysop()->getUser();
		$badActor = $this->getTestUser()->getUser();

		$services->getChangeTagsStore()->defineTag( 'test' );

		// Fabricate 3 block log entries and tag the second block,
		// to ensure that `leids` works independently of tag filters
		$blockUserFactory = $services->getBlockUserFactory();
		for ( $i = 1; $i <= 3; $i++ ) {
			$tags = $i === 2 ? [ 'test' ] : null;
			$blockStatus = $blockUserFactory->newBlockUser(
				$badActor->getName(),
				$sysop,
				'infinity',
				$i,
				[],
				[],
				$tags
			)->placeBlock( BlockUser::CONFLICT_NEW );
			$this->assertStatusGood( $blockStatus, "Block #$i was not placed" );
		}

		// Query without filtering
		[ $result1 ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'logevents'
		] );

		// Verify all 3 log entries exist without filtering
		$logIds = array_column( $result1['query']['logevents'], 'logid' );
		$this->assertCount(
			3,
			$logIds,
			'Expected 3 log entries but got ' . count( $logIds )
		);

		// Query with ID-only filtering
		[ $result ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'logevents',
			'leids' => implode( '|', $logIds )
		] );

		// Verify all 3 log entries exist when specified
		$logIds = array_column( $result['query']['logevents'], 'logid' );
		$this->assertCount(
			3,
			$logIds,
			'Expected 3 log entries but got ' . count( $logIds )
		);

		// Query with ID- and tag-based filtering
		[ $result2 ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'logevents',
			'leids' => implode( '|', $logIds ),
			'letag' => 'test'
		] );
		$result2_logevents = $result2['query']['logevents'];

		// Verify that only the tagged log entry is returned
		$this->assertCount(
			1,
			$result2_logevents,
			'Expected only the tagged log entry to be returned'
		);

		// Verify that the returned log entry is the second block
		$this->assertEquals(
			$logIds[1],
			$result2_logevents[0]['logid'],
			'Expected log ID of the tagged block to be returned'
		);
	}
}
