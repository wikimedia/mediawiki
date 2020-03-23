<?php

namespace MediaWiki\Tests\Rest\Handler;

use ApiBase;
use ApiMain;
use Exception;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;

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

}
