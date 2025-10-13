<?php

namespace MediaWiki\Tests\Logger;

use MediaWiki\Logger\LoggingContext;
use MediaWikiUnitTestCase;
use Wikimedia\ScopedCallback;

/**
 * @covers \MediaWiki\Logger\LoggingContext
 */
class LoggingContextTest extends MediaWikiUnitTestCase {

	public function testContext() {
		$loggingContext = new LoggingContext();
		$this->assertSame( [], $loggingContext->get() );
		$loggingContext->add( [ 'a' => 1, 'b' => 1, 'c' => 1, 'd' => 1 ] );
		$loggingContext->add( [ 'b' => 2, 'c' => 2, 'h' => 2 ] );
		$scope1 = $loggingContext->addScoped( [ 'c' => 3, 'd' => 3, 'i' => 3 ] );
		$scope2 = $loggingContext->addScoped( [ 'd' => 4, 'e' => 4 ] );
		$this->assertArrayEquals( [ 'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 4, 'h' => 2, 'i' => 3 ],
			$loggingContext->get(), false, true );
		ScopedCallback::consume( $scope2 );
		$this->assertArrayEquals( [ 'a' => 1, 'b' => 2, 'c' => 3, 'd' => 3, 'h' => 2, 'i' => 3 ],
			$loggingContext->get(), false, true );
		ScopedCallback::consume( $scope1 );
		$this->assertArrayEquals( [ 'a' => 1, 'b' => 2, 'c' => 2, 'd' => 1, 'h' => 2 ],
			$loggingContext->get(), false, true );
	}

}
