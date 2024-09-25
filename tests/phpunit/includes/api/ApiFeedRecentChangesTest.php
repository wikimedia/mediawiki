<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiFeedRecentChanges;
use MediaWiki\Api\ApiMain;
use MediaWiki\Context\RequestContext;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWikiIntegrationTestCase;

/**
 * @group API
 * @covers \MediaWiki\Api\ApiFeedRecentChanges
 */
class ApiFeedRecentChangesTest extends MediaWikiIntegrationTestCase {

	use TempUserTestTrait;

	private function commonTestGetAllowedParamsForHideAnons( $expectedMessageKey ) {
		$ctx = new RequestContext();
		$apiMain = new ApiMain( $ctx );
		$api = new ApiFeedRecentChanges(
			$apiMain,
			'feedrecentchanges',
			$this->getServiceContainer()->getSpecialPageFactory(),
			$this->getServiceContainer()->getTempUserConfig()
		);
		$params = $api->getAllowedParams();
		$this->assertArrayHasKey( 'hideanons', $params );
		$this->assertSame(
			$expectedMessageKey,
			$params['hideanons'][ApiBase::PARAM_HELP_MSG]
		);
	}

	public function testGetAllowedParamsWhenTemporaryAccountsAreEnabled() {
		$this->enableAutoCreateTempUser();
		$this->commonTestGetAllowedParamsForHideAnons(
			'apihelp-feedrecentchanges-param-hideanons-temp'
		);
	}

	public function testGetAllowedParamsWhenTemporaryAccountsAreNotEnabled() {
		$this->disableAutoCreateTempUser();
		$this->commonTestGetAllowedParamsForHideAnons(
			'apihelp-feedrecentchanges-param-hideanons'
		);
	}
}
