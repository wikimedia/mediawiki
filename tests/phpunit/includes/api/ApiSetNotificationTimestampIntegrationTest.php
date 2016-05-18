<?php
use MediaWiki\MediaWikiServices;

/**
 * @author Addshore
 * @covers ApiSetNotificationTimestamp
 * @group API
 * @group medium
 * @group Database
 */
class ApiSetNotificationTimestampIntegrationTest extends ApiTestCase {

	protected function setUp() {
		parent::setUp();
		self::$users[__CLASS__] = new TestUser( __CLASS__ );
		$this->doLogin( __CLASS__ );
	}

	public function testStuff() {
		$user = self::$users[__CLASS__]->getUser();
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );

		$user->addWatch( $page->getTitle() );

		$result = $this->doApiRequestWithToken(
			[
				'action' => 'setnotificationtimestamp',
				'timestamp' => '20160101020202',
				'pageids' => $page->getId(),
			],
			null,
			$user
		);

		$this->assertEquals(
			[
				'batchcomplete' => true,
				'setnotificationtimestamp' => [
					[ 'ns' => 0, 'title' => 'UTPage', 'notificationtimestamp' => '2016-01-01T02:02:02Z' ]
				],
			],
			$result[0]
		);

		$watchedItemStore = MediaWikiServices::getInstance()->getWatchedItemStore();
		$this->assertEquals(
			$watchedItemStore->getNotificationTimestampsBatch( $user, [ $page->getTitle() ] ),
			[ [ 'UTPage' => '20160101020202' ] ]
		);
	}

}
