<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\User\UserIdentityValue;

/**
 * @group API
 * @group medium
 * @group Database
 * @covers \MediaWiki\Api\ApiClearHasMsg
 */
class ApiClearHasMsgTest extends ApiTestCase {

	/**
	 * Test clearing hasmsg flag for current user
	 */
	public function testClearFlag() {
		$user = new UserIdentityValue( 42, __METHOD__ );
		$talkPageNotificationManager = $this->getServiceContainer()
			->getTalkPageNotificationManager();
		$talkPageNotificationManager->setUserHasNewMessages( $user );
		$this->assertTrue( $talkPageNotificationManager->userHasNewMessages( $user ) );

		$data = $this->doApiRequest(
			[ 'action' => 'clearhasmsg' ],
			[],
			false,
			new UltimateAuthority( $user )
		);

		$this->assertEquals( 'success', $data[0]['clearhasmsg'] );
		$this->assertFalse( $talkPageNotificationManager->userHasNewMessages( $user ) );
	}

}
