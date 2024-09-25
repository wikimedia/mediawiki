<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers MediaWiki\Api\ApiQueryLogEvents
 * @group API
 * @group Database
 * @group medium
 */
class ApiQueryLogEventsTest extends ApiTestCase {
	use TempUserTestTrait;

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

		[ $result, ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'logevents',
		] );
		$this->assertArrayHasKey( 'temp', $result[ 'query' ][ 'logevents' ][0] );
		$this->assertTrue( $result[ 'query' ][ 'logevents' ][0]['temp'] );
	}
}
