<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiQueryUserInfo
 */
class ApiQueryUserInfoTest extends ApiTestCase {

	/**
	 * @throws MWContentSerializationException
	 * @throws MWException
	 * @covers ApiQueryUserInfo::getLatestContributionTime
	 */
	public function testTimestamp() {
		$clock = MWTimestamp::convert( TS_UNIX, '20100101000000' );
		MWTimestamp::setFakeTime( function () use ( &$clock ) {
			return $clock += 1000;
		} );

		$page = $this->getNonexistingTestPage();
		$user = $this->getTestUser()->getUser();
		$status = $this->editPage( $page, 'one' );
		$this->assertTrue( $status->isOK() );
		$status = $this->editPage( $page, 'two' );
		$this->assertTrue( $status->isOK() );
		$revisionTimestamp = wfTimestamp( TS_ISO_8601, $page->getTimestamp() );

		$params = [
			'action' => 'query',
			'meta' => 'userinfo',
			'uiprop' => 'latestcontrib',
		];

		$apiResult = $this->doApiRequest( $params, null, false, $user );
		$this->assertArrayNotHasKey( 'continue', $apiResult[0] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'userinfo', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 'latestcontrib', $apiResult[0]['query']['userinfo'] );
		$queryTimestamp = $apiResult[0]['query']['userinfo']['latestcontrib'];
		$this->assertSame( $revisionTimestamp, $queryTimestamp );
	}

	public function tearDown(): void {
		parent::tearDown();
		MWTimestamp::setFakeTime( false );
	}
}
