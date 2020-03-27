<?php

namespace MediaWiki\Tests\Rest\Handler;

use ApiBase;
use ApiMain;
use Exception;
use FauxRequest;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionId;
use MediaWiki\Session\SessionProviderInterface;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use RequestContext;

/**
 * A trait providing utility functions for testing Handler classes
 * derived from ActionModuleBasedHandler.
 * This trait is intended to be used on subclasses of MediaWikiUnitTestCase
 * or MediaWikiIntegrationTestCase.
 *
 * @package MediaWiki\Tests\Rest\Handler
 */
trait ActionModuleBasedHandlerTestTrait {

	use HandlerTestTrait;

	/**
	 * Expected to be provided by the class, probably inherited from TestCase.
	 *
	 * @param string $className
	 *
	 * @return MockBuilder
	 */
	abstract protected function getMockBuilder( $className ): MockBuilder;

	/**
	 * @param ApiMain $main
	 * @param string $name
	 * @param array $resultData
	 * @param Exception|null $throwException
	 *
	 * @return ApiBase|MockObject
	 */
	private function getDummyApiModule(
		ApiMain $main,
		$name,
		$resultData,
		Exception $throwException = null
	) {
		/** @var ApiBase|MockObject $module */
		$module = $this->getMockBuilder( ApiBase::class )
			->setConstructorArgs( [ $main, $name ] )
			->onlyMethods( [ 'execute' ] )
			->getMock();

		$module->method( 'execute' )
			->willReturnCallback(
				function () use ( $module, $resultData, $throwException ) {
					if ( $throwException ) {
						throw $throwException;
					}

					$res = $module->getResult();
					foreach ( $resultData as $key => $value ) {
						$res->addValue( null, $key, $value );
					}
				}
			);

		return $module;
	}

	/**
	 * @return ApiMain
	 */
	private function getApiMain( $csrfSafe = false ) {
		/** @var SessionProviderInterface|MockObject $session */
		$sessionProvider =
			$this->createNoOpMock( SessionProviderInterface::class, [ 'safeAgainstCsrf' ] );
		$sessionProvider->method( 'safeAgainstCsrf' )->willReturn( $csrfSafe );

		/** @var Session|MockObject $session */
		$session = $this->createNoOpMock( Session::class, [ 'getSessionId', 'getProvider' ] );
		$session->method( 'getSessionId' )->willReturn( new SessionId( 'test' ) );
		$session->method( 'getProvider' )->willReturn( $sessionProvider );

		// NOTE: This being a FauxRequest instance triggers special case behavior
		// in ApiMain, causing ApiMain::isInternalMode() to return true. Among other things,
		// this causes ApiMain to throw errors rather than encode them in the result data.
		/** @var MockObject|FauxRequest $fauxRequest */
		$fauxRequest = $this->getMockBuilder( FauxRequest::class )
			->onlyMethods( [ 'getSession', 'getSessionId' ] )
			->getMock();
		$fauxRequest->method( 'getSession' )->willReturn( $session );
		$fauxRequest->method( 'getSessionId' )->willReturn( $session->getSessionId() );

		$testContext = RequestContext::getMain();

		$fauxContext = new RequestContext();
		$fauxContext->setRequest( $fauxRequest );
		$fauxContext->setUser( $testContext->getUser() );
		$fauxContext->setLanguage( $testContext->getLanguage() );

		$apiMain = new ApiMain( $fauxContext, true );
		return $apiMain;
	}

}
