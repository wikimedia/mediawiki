<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Utils\MWTimestamp;

/**
 * @group API
 * @group Database
 * @group medium
 * @covers \MediaWiki\Api\ApiQueryUserInfo
 */
class ApiQueryUserInfoTest extends ApiTestCase {

	use TempUserTestTrait;
	use MockAuthorityTrait;

	/**
	 * @covers \MediaWiki\Api\ApiQueryUserInfo::getLatestContributionTime
	 */
	public function testTimestamp() {
		$clock = MWTimestamp::convert( TS_UNIX, '20100101000000' );
		MWTimestamp::setFakeTime( static function () use ( &$clock ) {
			return $clock += 1000;
		} );

		$params = [
			'action' => 'query',
			'meta' => 'userinfo',
			'uiprop' => 'latestcontrib',
		];

		$page = $this->getNonexistingTestPage();
		$performer = $this->getTestUser()->getAuthority();

		$apiResult = $this->doApiRequest( $params, null, false, $performer );
		$this->assertArrayNotHasKey( 'continue', $apiResult[0] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'userinfo', $apiResult[0]['query'] );
		$this->assertArrayNotHasKey( 'latestcontrib', $apiResult[0]['query']['userinfo'] );

		$status = $this->editPage( $page, 'one' );
		$this->assertStatusOK( $status );
		$status = $this->editPage( $page, 'two' );
		$this->assertStatusOK( $status );

		$revisionTimestamp = MWTimestamp::convert( TS_ISO_8601, $page->getTimestamp() );

		$apiResult = $this->doApiRequest( $params, null, false, $performer );
		$this->assertArrayNotHasKey( 'continue', $apiResult[0] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'userinfo', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 'latestcontrib', $apiResult[0]['query']['userinfo'] );
		$queryTimestamp = $apiResult[0]['query']['userinfo']['latestcontrib'];
		$this->assertSame( $revisionTimestamp, $queryTimestamp );
	}

	public function testCanCreateAccount() {
		$params = [
			'action' => 'query',
			'meta' => 'userinfo',
			'uiprop' => 'cancreateaccount',
		];
		$user = $this->getTestUser()->getUser();
		$apiResult = $this->doApiRequest( $params, null, false, $user );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'userinfo', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 'cancreateaccount', $apiResult[0]['query']['userinfo'] );
		$this->assertTrue( $apiResult[0]['query']['userinfo']['cancreateaccount'] );
		$this->assertArrayNotHasKey( 'cancreateaccounterror', $apiResult[0]['query']['userinfo'] );

		$user = $this->getMutableTestUser()->getUser();
		$status = $this->getServiceContainer()->getBlockUserFactory()->newBlockUser(
			$user,
			$this->getTestSysop()->getUser(),
			'infinity',
			'',
			[ 'isCreateAccountBlocked' => true ]
		)->placeBlock();
		if ( !$status->isGood() ) {
			$this->fail( $status->getWikiText( false, false, 'en' ) );
		}
		$apiResult = $this->doApiRequest( $params, null, false, $user );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'userinfo', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 'cancreateaccount', $apiResult[0]['query']['userinfo'] );
		$this->assertFalse( $apiResult[0]['query']['userinfo']['cancreateaccount'] );
		$this->assertArrayHasKey( 'cancreateaccounterror', $apiResult[0]['query']['userinfo'] );
	}

	public function testTempFlag() {
		$this->enableAutoCreateTempUser();
		$params = [
			'action' => 'query',
			'meta' => 'userinfo',
		];
		$user = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();
		$apiResult = $this->doApiRequest( $params, null, false, $user );

		// Verify that the temp flag is set.
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'userinfo', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 'temp', $apiResult[0]['query']['userinfo'] );
		$this->assertTrue( $apiResult[0]['query']['userinfo']['temp'] );

		// Verify that the name is correct
		$this->assertArrayHasKey( 'name', $apiResult[0]['query']['userinfo'] );
		$this->assertSame( $user->getName(), $apiResult[0]['query']['userinfo']['name'] );

		// Verify that the user ID is correct
		$this->assertArrayHasKey( 'id', $apiResult[0]['query']['userinfo'] );
		$this->assertSame( $user->getId(), $apiResult[0]['query']['userinfo']['id'] );
	}

	public function testAnonFlag() {
		$this->disableAutoCreateTempUser();
		$params = [
			'action' => 'query',
			'meta' => 'userinfo',
		];
		$user = $this->mockAnonUltimateAuthority();
		$apiResult = $this->doApiRequest( $params, null, false, $user );

		// Verify that the temp flag is not set.
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'userinfo', $apiResult[0]['query'] );
		$this->assertArrayNotHasKey( 'temp', $apiResult[0]['query']['userinfo'] );

		// Verify that the anon flag is set.
		$this->assertArrayHasKey( 'anon', $apiResult[0]['query']['userinfo'] );
		$this->assertTrue( $apiResult[0]['query']['userinfo']['anon'] );
	}
}
