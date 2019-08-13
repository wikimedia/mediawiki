<?php

/**
 * @group API
 * @group medium
 *
 * @covers ApiFeedContributions
 */
class ApiFeedContributionsTest extends ApiTestCase {

	public function testInvalidExternalUser() {
		$this->setExpectedException( ApiUsageException::class,
			'Invalid value ">" for user parameter "user"' );
		$this->doApiRequest( [
			'action' => 'feedcontributions',
			'user' => '>'
		] );
	}
}
