<?php

use MediaWiki\MediaWikiServices;

/**
 * @group API
 * @group medium
 * @covers ApiClearHasMsg
 */
class ApiClearHasMsgTest extends ApiTestCase {

	/**
	 * Test clearing hasmsg flag for current user
	 */
	public function testClearFlag() {
		$user = self::$users['sysop']->getUser();
		$talkPageNotificationManager = MediaWikiServices::getInstance()
			->getTalkPageNotificationManager();
		$talkPageNotificationManager->setUserHasNewMessages( $user );
		$this->assertTrue( $talkPageNotificationManager->userHasNewMessages( $user ), 'sanity check' );

		$data = $this->doApiRequest( [ 'action' => 'clearhasmsg' ], [] );

		$this->assertEquals( 'success', $data[0]['clearhasmsg'] );
		$this->assertFalse( $talkPageNotificationManager->userHasNewMessages( $user ) );
	}

}
