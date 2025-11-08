<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Exception\UserNotLoggedIn
 * @author Addshore
 * @author Dreamy Jazz
 * @group Database
 */
class UserNotLoggedInTest extends MediaWikiIntegrationTestCase {

	use TempUserTestTrait;

	/** @dataProvider provideConstruction */
	public function testConstruction( $userIsTemp, $expectedReasonMsgKey ) {
		if ( $userIsTemp ) {
			$this->enableAutoCreateTempUser();
			$user = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();
			RequestContext::getMain()->setUser( $user );
		}
		$e = new UserNotLoggedIn();
		$this->assertEquals( 'exception-nologin', $e->title );
		$this->assertEquals( $expectedReasonMsgKey, $e->msg );
		$this->assertEquals( [], $e->params );
	}

	public static function provideConstruction() {
		return [
			'User is not a temporary account' => [ false, 'exception-nologin-text' ],
			'User is a temporary account' => [ true, 'exception-nologin-text-for-temp-user' ],
		];
	}

	public function testConstructionForTempAccountWithAlwaysRedirectToLoginPageSet() {
		$this->enableAutoCreateTempUser();
		$user = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();
		RequestContext::getMain()->setUser( $user );
		$e = new UserNotLoggedIn( 'exception-nologin-text', 'exception-nologin', [], true );
		$this->assertEquals( 'exception-nologin', $e->title );
		$this->assertEquals( 'exception-nologin-text', $e->msg );
		$this->assertEquals( [], $e->params );
	}

	public function testConstructionForReasonMsgWithoutTemporaryAccountEquivalent() {
		$this->enableAutoCreateTempUser();
		$user = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();
		RequestContext::getMain()->setUser( $user );
		$e = new UserNotLoggedIn( 'changeemail-no-info' );
		$this->assertEquals( 'changeemail-no-info', $e->msg );
	}

	/** @dataProvider provideTemporaryAccountsEnabled */
	public function testReportForRedirectToLoginPage( $temporaryAccountsEnabled ) {
		if ( $temporaryAccountsEnabled ) {
			$this->enableAutoCreateTempUser();
		} else {
			$this->disableAutoCreateTempUser();
		}
		RequestContext::getMain()->setTitle( Title::newFromText( 'Preferences', NS_SPECIAL ) );
		$e = new UserNotLoggedIn();
		$e->report();
		$redirectUrl = RequestContext::getMain()->getOutput()->getRedirect();
		$parsedUrlParts = $this->getServiceContainer()->getUrlUtils()->parse( $redirectUrl );
		$this->assertNotNull( $parsedUrlParts );
		$this->assertArrayEquals(
			[
				'title' => 'Special:UserLogin',
				'returntoquery' => '',
				'returnto' => 'Special:Preferences',
				'warning' => 'exception-nologin-text',
			],
			wfCgiToArray( $parsedUrlParts['query'] ),
			false,
			true
		);
	}

	public static function provideTemporaryAccountsEnabled() {
		return [
			'Temporary accounts disabled' => [ false ],
			'Temporary accounts enabled' => [ true ],
		];
	}

	public function testReportForRedirectToAccountCreationPage() {
		$this->enableAutoCreateTempUser();
		$user = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();
		RequestContext::getMain()->setUser( $user );
		RequestContext::getMain()->setTitle( Title::newFromText( 'Preferences', NS_SPECIAL ) );
		$e = new UserNotLoggedIn();
		$e->report();
		$redirectUrl = RequestContext::getMain()->getOutput()->getRedirect();
		$parsedUrlParts = $this->getServiceContainer()->getUrlUtils()->parse( $redirectUrl );
		$this->assertNotNull( $parsedUrlParts );
		$this->assertArrayEquals(
			[
				'title' => 'Special:CreateAccount',
				'returntoquery' => '',
				'returnto' => 'Special:Preferences',
				'warning' => 'exception-nologin-text-for-temp-user',
			],
			wfCgiToArray( $parsedUrlParts['query'] ),
			false,
			true
		);
	}
}
