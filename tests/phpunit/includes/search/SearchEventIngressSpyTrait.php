<?php

namespace MediaWiki\Tests\Search;

use SearchEngine;
use SearchEngineFactory;

/**
 * Trait providing test spies for asserting that listeners in
 * SearchEventIngress do or do not get called during
 * certain actions.
 */
trait SearchEventIngressSpyTrait {
	private function installSearchEventIngressSpys( int $searchEngineCreate ) {
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

	private function installSearchEventIngressSpyForEdit() {
		$this->installSearchEventIngressSpys( 1 );
	}

	private function installSearchEventIngressSpyForDerived() {
		$this->installSearchEventIngressSpys( 1 );
	}

	private function installSearchEventIngressSpyForPageMove() {
		$this->installSearchEventIngressSpys( 2 );
	}

	private function installSearchEventIngressSpyForUndeletion() {
		$this->installSearchEventIngressSpys( 1 );
	}

	private function installSearchEventIngressSpyForImport() {
		$this->installSearchEventIngressSpys( 1 );
	}

}
