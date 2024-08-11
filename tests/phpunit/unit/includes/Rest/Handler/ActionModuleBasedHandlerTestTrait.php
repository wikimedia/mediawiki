<?php

namespace MediaWiki\Tests\Rest\Handler;

use ApiBase;
use ApiMain;
use Exception;
use MediaWiki\Context\RequestContext;
use MediaWiki\Language\Language;
use MediaWiki\Request\FauxRequest;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * A trait providing utility functions for testing Handler classes
 * derived from ActionModuleBasedHandler.
 * This trait is intended to be used on subclasses of MediaWikiUnitTestCase
 * or MediaWikiIntegrationTestCase.
 *
 * @method MockBuilder getMockBuilder(string $className)
 */
trait ActionModuleBasedHandlerTestTrait {

	use HandlerTestTrait;

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
				static function () use ( $module, $resultData, $throwException ) {
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
	 * @param bool $csrfSafe
	 * @return ApiMain
	 */
	private function getApiMain( $csrfSafe = false ) {
		$session = $this->getSession( $csrfSafe );

		// NOTE: This being a MediaWiki\Request\FauxRequest instance triggers special case behavior
		// in ApiMain, causing ApiMain::isInternalMode() to return true. Among other things,
		// this causes ApiMain to throw errors rather than encode them in the result data.
		/** @var MockObject|FauxRequest $fauxRequest */
		$fauxRequest = $this->getMockBuilder( FauxRequest::class )
			->onlyMethods( [ 'getSession', 'getSessionId' ] )
			->getMock();
		$fauxRequest->method( 'getSession' )->willReturn( $session );
		$fauxRequest->method( 'getSessionId' )->willReturn( $session->getSessionId() );

		/** @var Language|MockObject $language */
		$language = $this->createNoOpMock( Language::class );
		$testContext = RequestContext::getMain();

		$fauxContext = new RequestContext();
		$fauxContext->setRequest( $fauxRequest );
		$fauxContext->setUser( $testContext->getUser() );
		$fauxContext->setLanguage( $language );

		return new ApiMain( $fauxContext, true );
	}

}
