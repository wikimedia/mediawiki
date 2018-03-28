<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiUnblock
 */
class ApiUnblockTest extends ApiTestCase {
	/**
	 * @expectedException ApiUsageException
	 */
	public function testWithNoToken() {
		$this->doApiRequest(
			[
				'action' => 'unblock',
				'user' => 'UTApiBlockee',
				'reason' => 'Some reason',
			]
		);
	}
}
