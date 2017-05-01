<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiUnblock
 */
class ApiUnblockTest extends ApiTestCase {
	protected function setUp() {
		parent::setUp();
		$this->doLogin();
	}

	/**
	 * @expectedException UsageException
	 */
	public function testWithNoToken() {
		$this->doApiRequest(
			[
				'action' => 'unblock',
				'user' => 'UTApiBlockee',
				'reason' => 'Some reason',
			],
			null,
			false,
			self::$users['sysop']->getUser()
		);
	}
}
