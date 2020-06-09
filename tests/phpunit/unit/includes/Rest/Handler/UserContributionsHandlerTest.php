<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler\UserContributionsHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Revision\ContributionsLookup;
use PHPUnit\Framework\MockObject\MockObject;
use RequestContext;
use User;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\Handler\UserContributionsHandler
 */
class UserContributionsHandlerTest extends \MediaWikiUnitTestCase {

	use HandlerTestTrait;

	private function newHandler() {
		/** @var MockObject|ContributionsLookup $mockContributionsLookup */
		$mockContributionsLookup = $this->createNoOpMock( ContributionsLookup::class );
		$handler = new UserContributionsHandler( $mockContributionsLookup );
		return $handler;
	}

	public function testThatAnonymousUserReturns401() {
		$handler = $this->newHandler();
		$request = new RequestData( [] );
		RequestContext::getMain()->setUser( new User() );

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-permission-denied-anon' ), 401 )
		);
		$response = $this->executeHandler( $handler, $request );
		$this->assertSame( 401, $response->getStatusCode() );
	}
}
