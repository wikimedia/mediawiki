<?php

namespace MediaWiki\Tests\Language;

use MessageCache;

/**
 * Trait providing test spies for asserting that listeners in
 * LanguageEventIngress do or do not get called during
 * certain actions.
 */
trait LanguageEventIngressSpyTrait {
	private function installLanguageEventIngressSpys( int $messageOverrides ) {
		// Make sure LanguageEventIngress is triggered and updates the message
		$messageCache = $this->createNoOpMock(
			MessageCache::class,
			[
				'updateMessageOverride',
				'figureMessage',
				'get',
				'transform',
				'getMsgFromNamespace',
			]
		);

		// this is the relevant assertion:
		$messageCache->expects( $this->exactly( $messageOverrides ) )
			->method( 'updateMessageOverride' );

		$messageCache->method( 'figureMessage' )
			->willReturn( [ 'xxx', 'en' ] );

		$messageCache->method( 'get' )
			->willReturn( 'dummy test' );

		$messageCache->method( 'transform' )
			->willReturn( 'dummy test' );

		$messageCache->method( 'getMsgFromNamespace' )
			->willReturn( false );

		$this->setService( 'MessageCache', $messageCache );
	}

	private function installLanguageEventIngressSpyForEdit() {
		$this->installLanguageEventIngressSpys( 1 );
	}

	private function installLanguageEventIngressSpyForPageMove() {
		$this->installLanguageEventIngressSpys( 2 );
	}

	private function installLanguageEventIngressSpyForUndeletion() {
		$this->installLanguageEventIngressSpys( 1 );
	}

	private function installLanguageEventIngressSpyForImport() {
		$this->installLanguageEventIngressSpys( 1 );
	}

}
