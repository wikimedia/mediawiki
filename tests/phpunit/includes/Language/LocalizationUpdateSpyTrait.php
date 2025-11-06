<?php

namespace MediaWiki\Tests\Language;

use MessageCache;

/**
 * Trait for asserting that the localization component is getting notified
 * about changes as expected.
 */
trait LocalizationUpdateSpyTrait {

	/**
	 * Register expectations about updates that should get triggered.
	 * The parameters of this method represent known kinds of updates.
	 * If a parameter is added, tests calling this method should be forced
	 * to specify their expectations with respect to that kind of update.
	 * For this reason, this method should not be split, and all parameters
	 * should be required.
	 */
	private function expectLocalizationUpdate( int $messageOverrides ) {
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

		$messageCache->method( 'getMsgFromNamespace' )
			->willReturn( false );

		$this->setService( 'MessageCache', $messageCache );
	}

}
