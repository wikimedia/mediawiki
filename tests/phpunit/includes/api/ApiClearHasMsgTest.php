<?php

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
		$talkPageNotificationManager = $this->getServiceContainer()
			->getTalkPageNotificationManager();
		$talkPageNotificationManager->setUserHasNewMessages( $user );
		$this->assertTrue( $talkPageNotificationManager->userHasNewMessages( $user ) );

		$data = $this->doApiRequest( [ 'action' => 'clearhasmsg' ], [] );

		$this->assertEquals( 'success', $data[0]['clearhasmsg'] );
		$this->assertFalse( $talkPageNotificationManager->userHasNewMessages( $user ) );
	}

}
