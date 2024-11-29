<?php

namespace MediaWiki\Tests\Search;

use SearchEngine;
use SearchEngineFactory;

/**
 * Trait for asserting that the search component is getting notified
 * about changes as expected.
 */
trait SearchUpdateSpyTrait {

	/**
	 * Register expectations about updates that should get triggered.
	 * The parameters of this method represent known kinds of updates.
	 * If a parameter is added, tests calling this method should be forced
	 * to specify their expectations with respect to that kind of update.
	 * For this reason, this method should not be split, and all parameters
	 * should be required.
	 */
	private function expectSearchUpdates( int $searchEngineCreate ) {
		// Make sure SearchEventIngress is triggered and tries to re-index the page
		$searchEngine = $this->createNoOpMock(
			SearchEngine::class,
			[ 'supports' ]
		);

		$searchEngine->method( 'supports' )->willReturn( false );

		$searchEngineFactory = $this->createNoOpMock(
			SearchEngineFactory::class,
			[ 'create' ]
		);

		// this is the relevant assertion:
		$searchEngineFactory->expects( $this->exactly( $searchEngineCreate ) )
			->method( 'create' )->willReturn( $searchEngine );

		$this->setService( 'SearchEngineFactory', $searchEngineFactory );
	}

}
